<?php

namespace App\Http\Controllers\Server;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    protected $PayServer = [
        'hengxin' => \App\Server\Pay\HengxinPay::class,
    ];
    /**
     * 查询余额接口
     */
    public function CheckBalance($way)
    {
        $hx = new $this->PayServer[$way];
        
        return $hx->CheckBalance();
    }
}
