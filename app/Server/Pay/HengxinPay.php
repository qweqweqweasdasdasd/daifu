<?php

namespace App\Server\Pay;

use App\Libs\RsaUtil;
use GuzzleHttp\Client;

class HengxinPay 
{
    /**
     * 线上网关域名 
     */
    protected $domain = 'http://api.hengxinpay.cn';

    /**
     * 测试网关域名 
     */
    protected $vdomain = 'http://apivis.hengxinpay.cn';

    /**
     * 商户编号 merId
     */
    protected $merId = '21910208';

    /**
     * 版本号 1.1
     */
    protected $version = '1.1';

    /**
     * guzzle 实例化对象
     */
    protected $client = '';

    /**
     * 初始化guzzle
     */
    public function __construct()
    {
        $this->client = new Client([
            'headers' => [ 
                'Content-Type' => 'application/json'
            ],
            'timeout' => 2.0,
        ]);
    }

    /**
     * 亨鑫余额查询接口
     * 接口请求路径： http://ip:port/api/balance/query
     * post 
     */
    public function CheckBalance()
    {
        
    }

    /**
     * 
     */
}
