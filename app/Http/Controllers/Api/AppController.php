<?php

namespace App\Http\Controllers\Api;

use App\Order;
use Illuminate\Http\Request;
use App\Server\Pay\ExtendPay;
// use App\Server\Pay\HengxinPay;
use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Repositories\RecheckRepository;
use App\Repositories\MerchantRepository;

class AppController extends Controller
{
    /**
     * 审核仓库
     */
    protected $recheck;

    /**
     * 订单仓库
     */
    protected $order;

    /**
     * 商户仓库
     */
    protected $merchant;

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
     * 账户余额
     */
    public function BalanceQuery(Request $request,$merid)
    {
        
        try {
            $res = (new ExtendPay($merid))->ExtendCheckBalance();
        } catch (\Exception $e) {
            return ['code'=>0,'msg'=>$e->getMessage()];
        }

        if($res->code != 200){
            return ['code'=>0,'msg'=>$res->message];
        };
        
        return ['code'=>1,'msg'=>$res->message,'data'=>$res->data->usableAmount];
    }

    /**
     * 下发请求操作
     */
    public function remitSubmit($d)
    {
        $res = (new HengxinPay())->remitSubmit($d);
        
        if($res->code == 200){
            return ['code'=>$res->code,'msg'=>$res->message];
        }
        return ['code'=>$res->code,'msg'=>$res->message];
    }

    /**
     * 下发回调
     * 修改状态
     */
    public function notify_url()
    {
        $res = (new HengxinPay())->remitOrderCallback();
        
        //$res = '{"code":1,"msg":"success","data":"{\"amount\":2,\"merOrderNo\":\"2019083012375446558968\",\"orderNo\":\"201908301652254776239\",\"orderState\":1}"}';
        //$res1 = '{"code":0,"msg":"11签名错误","data":"{\"amount\":2,\"merOrderNo\":\"2019083012375446558968\",\"orderNo\":\"201908301652254776239\",\"orderState\":1}"}';
        $rst = json_decode($res);
        $rst->data = json_decode($rst->data);
        // 下发接口失败,审核表 备注更新失败原因, 订单表 修改状态 下发失败
        // 下发接口成功,审核表 备注下发json数据, 订单表 修改状态 下发成功
        // return success
        $order = $this->order->GetOrderByNo($rst->data->merOrderNo);
        $recheck = $this->recheck->GetRecheckByOrderId($order->order_id);
        // 走事务
        \DB::beginTransaction();
        try {
            if(!$rst->code){
                $recheck->desc = $rst->msg;
                $order->order_status = Order::XIAFA_FAIL[0];
            }else{
                $recheck->desc = json_encode($rst->data);
                $order->order_status = Order::XIAFA_SUCCESS[0];
            }
            $order->save();
            $recheck->save();
            \DB::commit();
            return 'success';
        } catch (\Exception $th) {
            \DB::rollBack();
            return 'fail';
        }
        return 'success';
    }
}
