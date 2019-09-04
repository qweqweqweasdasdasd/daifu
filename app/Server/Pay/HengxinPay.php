<?php

namespace App\Server\Pay;

use App\Libs\RsaUtil;
use GuzzleHttp\Client;

class HengxinPay 
{
    /**
     * 代付商户分配公钥
     */
    protected $remit_public_key = "";

    /**
     * 代付商户分配私钥
     */
    protected $remit_private_key = "";

    /**
     * 线上网关域名
     */
    protected $domain = 'http://api.hengxinpay.cn';

    /**
     * 测试网关域名
     */
    protected $vdomain = 'http://apivis.hengxinpay.cn';

    /**
     * 代付商户md5加密字符串
     */
    protected $sign = '';   // 56cd5e494bd4435b929c2268d607f197

    /**
     * 商户编号 merId
     */
    protected $merId = '';  // 21910208

    /**
     * 版本号 1.1
     */
    protected $version = '1.1';

    /**
     * 初始化数据
     */
    public function __construct($merchant)
    {
        $this->sign = $merchant->sign;
        $this->merId = $merchant->merchant_id;
        $this->remit_private_key = $merchant->remit_private_key;
        $this->remit_public_key = $merchant->remit_public_key;
    }

    /**
     * 亨鑫余额查询接口
     * 接口请求路径： http://ip:port/api/balance/query
     * post 
     */
    public function CheckBalance()
    {   
        // 组装签名参数
        $map = [
            'merId' => $this->merId,
        ];
        // 自然排序
        ksort($map);
        $str_build = '';
        foreach ($map as $key => $value) {
            $str_build .= $key . '=' . $value . '&';
        }    
        // 签名
        $map['sign'] = strtoupper(md5($str_build . 'key=' . $this->sign)); 

        $rsa = $this->rsa_pub_encode(json_encode($map,JSON_UNESCAPED_UNICODE),$this->remit_public_key);

        $data = [
            'merId' => $this->merId,
            'version' => $this->version,
            'data' => $rsa
        ];
        $result = $this->post_data($this->domain . '/api/balance/query',json_encode($data,JSON_UNESCAPED_UNICODE));
        
        return json_decode($result);
    }

    /**
     * 亨鑫代付回调通知
     */
    public function remitOrderCallback()
    {
        $request=json_decode(file_get_contents("php://input"),true);
        if(!empty($request['data']) && trim($request['data']) != ""){
            $data = $this->rsa_private_decode($request['data'],$this->remit_private_key);
            app('log')->info('亨鑫代付回调通知:'.json_encode($data));
            $map = [
                'amount' => $data['amount'],
                'merOrderNo' => $data['merOrderNo'],
                'orderNo' => $data['orderNo'],
                'orderState' => $data['orderState']
            ];
            ksort($map);
            $str_build='';
            foreach ($map as $key=>$val){
                $str_build .= $key.'='.$val.'&';
            }
            $sign= strtoupper(md5($str_build . 'key=' . $this->sign));
            if($sign != $data['sign']){
                return json_encode(['code'=>0,'msg'=>'回调信息签名不对','data'=>json_encode($map)]);
            }
            // 拿到回调参数
            return json_encode(['code'=>1,'msg'=>'success','data'=>json_encode($map)]);
        }
        // data 数据为空
        return json_encode(['code'=>0,'msg'=>'data数据为空','data'=>json_encode($map)]);
    }

    /**
     * 亨鑫余额代付接口
     */
    public function RemitSubmit($data)
    {
        // 组装签名参数
        $map = [
            'merOrderNo' => $data['merOrderNo'],
            'amount' => $data['amount'],
            'notifyUrl' => $data['notifyUrl'],
            'bankCode' => $data['bankCode'],
            'submitTime' => time() * 1000,
            'bankAccountNo' => $data['bankAccountNo'],
            'bankAccountName' => $data['bankAccountName']
        ];
        app('log')->info('代付详情:'.json_encode($map));
        // 自然排序
        ksort($map);
        $str_build = '';
        foreach ($map as $key => $value) {
            $str_build .= $key . '=' . $value . '&';
        }    
        // 签名
        $map['sign'] = strtoupper(md5($str_build . 'key=' . $this->sign));      
        $map['bankBranchName'] = $data['bankBranchName'] = '';      // 银行分行
        $map['remarks'] = $data['remarks'];                         // 备注
        //公钥加密需开启openssl扩展 公钥和私钥需要按照上面的格式缩进否则无法识别
        $rsa = $this->rsa_pub_encode(json_encode($map,JSON_UNESCAPED_UNICODE),$this->remit_public_key);
        $data = [
            'merId' => $this->merId,
            'version' => $this->version,
            'data' => $rsa
        ];
        
        $result = $this->post_data($this->domain . '/api/remitOrder/submit',json_encode($data,JSON_UNESCAPED_UNICODE));
        return json_decode($result);
    }

    /**
     * 解密rsa
     */
    public function decode($content)
    {
        $res = $this->rsa_private_decode($content,$this->remit_private_key);

        return $res;
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
