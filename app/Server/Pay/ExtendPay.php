<?php

namespace App\Server\Pay;

use App\Merchant;
use App\Server\Pay\HengxinPay;

class ExtendPay 
{
    /**
     * 商户id
     */
    protected $merid;

    /**
     * 商户数据
     */

    /**
     * 初始化数据
     */
    public function __construct($merid)
    {
        $this->merid = $merid;
        $this->GetMerchant();
    }

    /**
     * 获取到对应的商户id
     */
    public function GetMerchant()
    {
        $this->merchant = Merchant::find($this->merid);
    }
    
    /**
     * 下发提交金额是否小于第三方余额
     */
    public function VerifyAmount($amount)
    {
        $hx = (new HengxinPay($this->merchant))->CheckBalance();
        
        if($hx->code != 200){
            return ['code'=>0,'msg'=>'因为某些原因失败'];
        }
        
        if($hx->data->usableAmount < $amount){
            return ['code'=>0,'msg'=>'余额剩下'.$hx->data->usableAmount.'元,你提交了'.$amount.'元'];
        }

        return ['code'=>1,'msg'=>'success'];
    }

    /**
     * 亨鑫余额代付接口
     */

    public function ExtendRemitSubmit($data)
    {
        $hx = (new HengxinPay($this->merchant))->RemitSubmit($data);

        return $hx;
    }

    /**
     * 查询商户余额
     */
    public function ExtendCheckBalance()
    {
        $hx = (new HengxinPay($this->merchant))->CheckBalance();

        return $hx;
    }
}
