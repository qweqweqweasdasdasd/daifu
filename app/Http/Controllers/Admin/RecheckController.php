<?php

namespace App\Http\Controllers\Admin;

use Google2FA;
use App\Order;
use App\Manager;
use App\Libs\ToolRedis;
use App\ErrDesc\ApiErrDesc;
use Illuminate\Http\Request;
use App\Server\Pay\ExtendPay;
use App\Server\Pay\HengxinPay;
use App\Resphonse\JsonResphonse;
use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Repositories\RecheckRepository;
use App\Repositories\MerchantRepository;

class RecheckController extends Controller
{
    /**
     * 下发订单前缀
     */
    protected $prefix = 'df';
    /**
     * 银行仓库
     */
    protected $recheck;

    /**
     * 下发订单仓库
     */
    protected $order;

    /**
     * 商户仓库
     */
    protected $merchant;

    /**
     * 订单重复提交问题开关
     */
    protected $ONOFF = false;
    /**
     * 初始化仓库
     */
    public function __construct(RecheckRepository $recheck,OrderRepository $order,MerchantRepository $merchant)
    {
        $this->recheck = $recheck;
        $this->order   = $order;
        $this->merchant = $merchant;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $whereData = [
            'start' => !empty($request->get('start'))? $request->get('start') :'',
            'end' => !empty($request->get('end'))? $request->get('end') :'',
            'recheck_status' => !empty($request->get('recheck_status'))? $request->get('recheck_status') :'',
            'merOrderNo' => !empty($request->get('merOrderNo'))? $request->get('merOrderNo') :'',
            'merchant_id' => !empty($request->get('merchant_id'))? $request->get('merchant_id') :'',
        ];

        $merchants = $this->merchant->GetMerchantNameId();
        $pathInfo = $this->recheck->getCurrentPathInfo();
        $recheck = $this->recheck->GetRecheck($whereData);
        $data = [];
        foreach($merchants->toArray() as $k => $v) {
            $data[$v['mer_id']] = $v['mer_name'];
        }

        foreach ($recheck as $k => $v) {
            $v->mer_name = @$data[$v->merchant_id];
        }
        //dump($recheck);
        return view('admin.recheck.index',compact('pathInfo','recheck','whereData','merchants'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = $this->order->CommonFirst($id);
        $bank_info = json_decode($order->bank_info,true);
        //dump($order);
        $order->bankCode = $bank_info['bankCode'];
        $order->bankAccountNo = $bank_info['bankAccountNo'];
        $order->bankAccountName = $bank_info['bankAccountName'];
        
        if($order->order_status == Order::XIAFA_FAIL[0] || $order->order_status == Order::XIAFA_SUCCESS[0] || $order->order_status == Order::ORDER_GUOQI[0]){
            return '订单号: ' . $order->merOrderNo . '-已经下发处理,如果没有到账查看状态!';
        }
        return view('admin.recheck.show',compact('order'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 订单重复提交问题 prefix
        if($this->ONOFF){
            $res = ToolRedis::RecheckOrderUniqu($this->prefix,$request->get('merOrderNo'));
            if($res){
                return JsonResphonse::JsonData(ApiErrDesc::RECHECK_UNIQU[0],ApiErrDesc::RECHECK_UNIQU[1]);
            }
        }
        
        // 二次验证
        $secretKey = Manager::find(1)->google_token;
        
        if(!$secretKey){
            return JsonResphonse::JsonData(ApiErrDesc::GOOLE_BINDING_NO[0],ApiErrDesc::GOOLE_BINDING_NO[1]);
        }
        $verify = Google2FA::verifyKey($secretKey, $request->input('gooleToken'));
        if(!$verify){
            return JsonResphonse::JsonData(ApiErrDesc::GOOLE_VERIFY_FAIL[0],ApiErrDesc::GOOLE_VERIFY_FAIL[1]);
        }
        
        // 下发提交金额是否小于第三方余额
        $res = (new ExtendPay($request->get('merchant_id')))->VerifyAmount($request->get('amount'));
        if(!$res['code']){
            return JsonResphonse::JsonData($res['code'],$res['msg']);
        }
        
        // 组装接口 && 请求代付接口(队列) 
        $d = [
            'merOrderNo' => $request->get('merOrderNo'),
            'amount' => $request->get('amount'),
            'notifyUrl' => config('order.notifyUrl'),
            'bankCode' => $request->get('bankCode'),
            'bankAccountNo' => $request->get('bankAccountNo'),
            'bankAccountName' => $request->get('bankAccountName'),
            'remarks' => $request->get('remarks')
        ];
        $res = (new ExtendPay($request->get('merchant_id')))->ExtendRemitSubmit($d);
        
        // $res->code = 200;
        // $res->msg = '1111';
        // 事务
        \DB::beginTransaction();
        try {
            $recheck = $this->recheck->Recheck($id);
            $order = $this->order->Order($id);
            // 审核表状态修改,备注信息(接口返回信息),操作者记录
            $recheck->recheck_status = 1;
            $recheck->re_operator = \Auth::guard('admin')->user()->mg_name;
            $recheck->desc = $res->message;
            // 下发订单表状态修改
            if($res->code != 200){
                $order->order_status = Order::CHECKING[0];          // 订单状态:接口维护中
                $recheck->recheck_status = 1;                       // 审核成功
            }else{
                $order->order_status = Order::XIAFA_IMG[0];         // 订单处理中
                                                                    // 记录金额
            }
            $recheck->save();
            $order->save();
            \DB::commit();
            return JsonResphonse::ResphonseSuccess();
        } catch (\Exception $th) {
            \DB::rollBack();
            return JsonResphonse::JsonData(ApiErrDesc::REQUEST_FAIL[0],ApiErrDesc::REQUEST_FAIL[1]);
        }
        
    }

    /**
     * 审核未处理提醒
     */
    public function notice()
    {
        $count = $this->recheck->CountRecheckNo();

        if(!$count){
            return JsonResphonse::JsonData('0',$count);
        }
        return JsonResphonse::ResphonseSuccess(['count'=>$count]);
    }
}
