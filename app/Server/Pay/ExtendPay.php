<?php

namespace App\Server\Pay;

use App\Server\Pay\HengxinPay;

class ExtendPay 
{
    /**
     * 下发提交金额是否小于第三方余额
     */
    public static function VerifyAmount($amount)
    {
        $cb = (new HengxinPay())->CheckBalance();
        
        if($cb->code != 200){
            return ['code'=>0,'msg'=>'因为某些原因失败'];
        }
        
        if($cb->data->usableAmount < $amount){
            return ['code'=>0,'msg'=>'余额剩下'.$cb->data->usableAmount.'元,你提交了'.$amount.'元'];
        }

        return ['code'=>1,'msg'=>'success'];
    }
}
