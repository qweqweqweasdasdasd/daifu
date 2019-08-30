<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Server\Pay\HengxinPay;
use App\Http\Controllers\Controller;

class AppController extends Controller
{

    /**
     * 账户余额
     */
    public function BalanceQuery()
    {
        $res = (new HengxinPay())->CheckBalance();

        if($res->code != 200){
            return ['code'=>0,'msg'=>'未知错误'];
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
     */
    public function notify_url()
    {
        $res = (new HengxinPay())->remitOrderCallback();

        
        app('log')->info('回调详情:'.json_encode($res));

    }
}
