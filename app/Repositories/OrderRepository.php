<?php

namespace App\Repositories;

use DB;
use App\Order;

class OrderRepository extends BaseRepository
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->table = 'order';
        $this->id = 'order_id';    
    }

    /**
     * 生成下发订单返回id 
     */
    public function SaveOrder($d)
    {
        return Order::create($d);
    }

    /**
     * 获取到指定的订单
     */
    public function Order($id)
    {
        return Order::find($id);
    }

    /**
     * 根据订单号获取订单信息
     */
    public function GetOrderByNo($no)
    {
        return Order::where('merOrderNo',$no)->first();
    }
    /**
     * 获取到所有订单
     */
    public function GetOrder($d)
    {
        return Order::select('order.*','recheck.*','order.created_at as order_created_at','recheck.created_at as recheck_created_at')
                    ->leftJoin('recheck','order.order_id','recheck.order_id')
                    ->where(function($query) use($d){
                        if( !empty($d['order_status']) ){
                            $query->where('order_status',$d['order_status']);
                        }
                        if( !empty($d['merOrderNo']) ){
                            $query->where('merOrderNo',$d['merOrderNo']);
                        }
                        if( !empty($d['merchant_id']) ){
                            $query->where('merchant_id',$d['merchant_id']);
                        }
                        if( !empty($d['start']) && !empty($d['end']) &&  $d['end'] >= $d['start']){
                            $query->whereBetween('order.created_at',[$d['start'],$d['end']] );
                        }
                    })
                    ->orderBy('order.order_id','desc')
                    ->paginate(11);
    }

    /**
     * 生成订单号
     */
    public function GenerateNo()
    {
        return date('YmdHis',time()) . rand(11111111,99999999);
    }
    
}