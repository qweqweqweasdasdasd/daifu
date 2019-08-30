<?php 
namespace App\Libs;

use Illuminate\Support\Facades\Redis;

class ToolRedis
{
    /**
     * 判断下发请求接口订单提交是否重复
     */
    public static function RecheckOrderUniqu($prefix,$merOrderNo)
    {
        $check = Redis::exists($prefix.'-'.$merOrderNo);
        // 判断不存在保存
        if(!$check){
            Redis::set($prefix.'-'.$merOrderNo,'T');
            Redis::expire($prefix.'-'.$merOrderNo,60);      // 60s
            return false;    
        };
        return true;
    }
}