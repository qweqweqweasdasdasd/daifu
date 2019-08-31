<?php

namespace App\Repositories;

use DB;
use App\Bank;
use App\Order;

class CountRepository extends BaseRepository
{
    /**
     * 统计银行卡数 (不含停用)
     */
    public function CountBankNumber()
    {
        $data = [
            'count' => Bank::where('bank_status',1)->count(),
            'name' => '银行卡数(正常)'
        ];
        return $data;
    }

    /**
     * 统计提交订单数量
     */
    public function CountOrderNumber()
    {
        $data = [
            'count' => Order::count(),
            'name'  => '提交笔数'
        ];
        return $data;
    }

    /**
     * 统计到账笔数
     */
    public function CountDaozhangNumber()
    {
        $data = [
            'count' => Order::where('order_status',3)->count(),
            'name' => '到账笔数' 
        ];
        return $data;
    }

    /**
     * 提交金额总和
     */
    public function CountTJAmountNumber()
    {
        $data = [
            'count' => Order::select(DB::raw(" sum(amount) as tj_total"))->value('tj_total'),
            'name' => '提交金额' 
        ];
        return $data;
    }

    /**
     * 下发金额总和
     */
    public function CountXFAmountNumber()
    {
        $data = [
            'count' => Order::select(DB::raw(" sum(amount) as xf_total"))->where('order_status',3)->value('xf_total'),
            'name' => '下发金额' 
        ];
        return $data;
    }
}