<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\ErrDesc\ApiErrDesc;
use Illuminate\Http\Request;
use App\Resphonse\JsonResphonse;
use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Repositories\RecheckRepository;

class RecheckController extends Controller
{
    /**
     * 银行仓库
     */
    protected $recheck;

    /**
     * 下发订单仓库
     */
    protected $order;

    /**
     * 初始化仓库
     */
    public function __construct(RecheckRepository $recheck,OrderRepository $order)
    {
        $this->recheck = $recheck;
        $this->order   = $order;
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
            'merOrderNo' => !empty($request->get('merOrderNo'))? $request->get('merOrderNo') :''
        ];

        $pathInfo = $this->recheck->getCurrentPathInfo();
        $recheck = $this->recheck->GetRecheck($whereData);
        //dump($whereData);
        return view('admin.recheck.index',compact('pathInfo','recheck','whereData'));
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
        // 请求代付接口(队列)  ??
        $res['code'] = true;

        // 事务
        \DB::beginTransaction();
        try {
            $recheck = $this->recheck->Recheck($id);
            $order = $this->order->Order($id);
            // 审核表状态修改,备注信息(接口返回信息),操作者记录
            $recheck->recheck_status = 1;
            $recheck->re_operator = \Auth::guard('admin')->user()->mg_name;
            $recheck->desc = '接口返回的信息';
            // 下发订单表状态修改
            if(!$res['code']){
                $order->order_status = Order::XIAFA_FAIL[0];
            }
            $order->order_status = Order::XIAFA_SUCCESS[0];
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
