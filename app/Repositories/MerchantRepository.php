<?php

namespace App\Repositories;

use DB;
use App\Merchant;

class MerchantRepository extends BaseRepository
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->table = 'merchant';
        $this->id = 'mer_id';    
    }

    /**
     * 获取到所有的商户信息
     */
    public function Merchants($d)
    {
        return Merchant::where(function($query) use($d){
            if( !empty($d['mer_status']) ){
                $query->where('mer_status',$d['mer_status']);
            }
            if( !empty($d['key']) ){
                $query->where('merchant_id',$d['key']);
            }
            if( !empty($d['start']) && !empty($d['end']) &&  $d['end'] >= $d['start']){
                $query->whereBetween('merchant.created_at',[$d['start'],$d['end']] );
            }
        })->paginate(9);
    }

    /**
     * 正常商户所有信息
     */
    public function GetMerchants()
    {
        return Merchant::get(['mer_id','mer_name','mer_status','merchant_id']);
    }
    /**
     * 获取到所有商户信息名称和id
     */
    public function GetMerchantNameId()
    {
        return Merchant::where('mer_status',1)->get(['mer_name','mer_status','mer_id']);
    }
}