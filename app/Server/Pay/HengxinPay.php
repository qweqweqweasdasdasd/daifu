<?php

namespace App\Server\Pay;

use App\Libs\RsaUtil;
use GuzzleHttp\Client;

class HengxinPay 
{
        /**
     * 代付商户分配公钥
     */
const REMIT_PUBLICK_KEY = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCU8ZvQVcUv5CJnw+kPCGlBW3XS
RdhgpZ7LenvJYj9lU7P3IBWXMzeydMxq902RaMAvmChdyjiubnEUUs31Y7wn5gzc
wyQw6kKOKvuik/0pedUNxuypk9k3x0oXrAuYJcdH5uEciVSznIUz8KN4PgeRPPrj
NcPzMHuNIoWLlcA7NQIDAQAB
-----END PUBLIC KEY-----";

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
     * 亨鑫余额代付接口
     */
    public function remitSubmit($data)
    {
        // 自然排序
        ksort($data);
        $str_build = '';
        foreach ($data as $key => $value) {
            $str_build .= $key . '=' . $value . '&';
        }
        
        // 签名
        $map['sign'] = strtoupper(md5($str_build . 'key=56cd5e494bd4435b929c2268d607f197'));
        $map['bankBranchName'] = $data['bankBranchName'] = '';
        $map['remarks'] = $data['remarks'];

        //公钥加密需开启openssl扩展 公钥和私钥需要按照上面的格式缩进否则无法识别
        $rsa = $this->rsa_pub_encode(json_encode($map,JSON_UNESCAPED_UNICODE),self::REMIT_PUBLICK_KEY);
        
        $data = [
            'merId' => '21910208',
            'version' => '1.1',
            'data' => $rsa
        ];

        $result = $this->post_data('http://api.hengxinpay.cn/api/remitOrder/query',json_encode($data,JSON_UNESCAPED_UNICODE));

        return json_decode($result);
    }

    /**
     * 发送请求
     */
    function post_data($url,$data){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data))
        );
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 加密
     */
    function rsa_pub_encode($str,$key){
        $crypto='';
        //分段加密
        foreach (str_split($str, 117) as $chunk) {
            openssl_public_encrypt($chunk, $encrypted, $key);
            $crypto .= $encrypted;
        }
        return $crypto?base64_encode($crypto):null;
    
    }

    /**
     * 解密
     */
    function rsa_private_decode($str,$key){
        $crypto = '';
        //分段解密
        foreach (str_split(base64_decode($str), 128) as $chunk) {
            openssl_private_decrypt($chunk, $encrypted, $key);
            $crypto.= $encrypted;
        }
        return json_decode($crypto,true);
    }
}
