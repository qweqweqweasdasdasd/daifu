<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Server\Pay\HengxinPay;
use App\Http\Controllers\Controller;

class XiafaController extends Controller
{
    /**
     * 下发视图
     */
    public function xiafa()
    {
        return view('api.xiafa.index');
    }

    /**
     * 下发提交
     */
    public function remitSubmit(Request $request)
    {
        $data = [
            "merOrderNo" => ($request->get('merOrderNo')),
            "amount" => ($request->get('amount')),
            "bankCode" => ($request->get('bankCode')),
            "bankAccountNo" => ($request->get('bankAccountNo')),
            "bankAccountName" => ($request->get('bankAccountName')),
            "notifyUrl" => "http://www.payment.cc/api/notify_url",
            "remarks" => ($request->get('remarks')),
        ];
        
        $res = (new HengxinPay())->remitSubmit($data);

        dd($res);
    }

    /**
     * 账号余额
     */
    public function BalanceQuery()
    {
        $res = (new HengxinPay())->CheckBalance();

        dd($res);
    }
}
