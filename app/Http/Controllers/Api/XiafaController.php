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
            "merOrderNo" => trim($request->get('merOrderNo')),
            "amount" => trim($request->get('amount')),
            "bankCode" => trim($request->get('bankCode')),
            "bankAccountNo" => trim($request->get('bankAccountNo')),
            "bankAccountName" => trim($request->get('bankAccountName')),
            "notifyUrl" => "http://www.payment.cc/api/notify_url",
            "remarks" => trim($request->get('remarks')),
        ];
        
        $res = (new HengxinPay())->remitSubmit($data);

        dd($res);
    }

}
