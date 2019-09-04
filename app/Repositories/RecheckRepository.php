<?php

namespace App\Repositories;

use DB;
use App\Recheck;

class RecheckRepository extends BaseRepository
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->table = 'recheck';
        $this->id = 'recheck_id';    
    }

    /**
     * 获取指定的审核
     */
    public function Recheck($id)
    {
        return Recheck::where(['order_id'=>$id])->first();
    }

    /**
     *  统计未处理数量 
     */
    public function CountRecheckNo()
    {
        return Recheck::where(['recheck_status'=>2])->count();
    }

    /**
     * 根据order_id 获取到审核信息
     */
    public function GetRecheckByOrderId($order_id)
    {
        return Recheck::where(['order_id'=>$order_id])->first();
    }
    /**
     * 获取到所有的审核资源
     */
    public function GetRecheck($d)
    {
        return Recheck::leftJoin('order','recheck.order_id','order.order_id')
                    ->where(function($query) use($d){
                        if( !empty($d['recheck_status']) ){
                            $query->where('recheck_status',$d['recheck_status']);
                        }
                        if( !empty($d['merchant_id']) ){
                            $query->where('recheck.merchant_id',$d['merchant_id']);
                        }
                        if( !empty($d['merOrderNo']) ){
                            $query->where('merOrderNo',$d['merOrderNo']);
                        }
                        if( !empty($d['start']) && !empty($d['end']) &&  $d['end'] >= $d['start']){
                            $query->whereBetween('recheck.created_at',[$d['start'],$d['end']] );
                        }
                    })
                    ->orderBy('recheck_id','desc')
                    ->paginate(11);
    }
    
}