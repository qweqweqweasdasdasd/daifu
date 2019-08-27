<?php 

namespace App\Resphonse;

/**
 * 返回统一的json格式数据
 */
class JsonResphonse
{
    /**
     * 错误信息格式化
     */
    public static function JsonData($code,$msg,$data=[])
    {
        $resphonse = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ];

        return self::ToJson($resphonse);
    }

    /**
     * 成功信息格式化
     */
    public static function ResphonseSuccess($data = [])
    {
        $resphonse = [
            'code' => 1,
            'msg'  => 'success',
            'data' => $data
        ];

        return self::ToJson($resphonse);
    }

    /**
     * 返回信息格式json
     */
    public static function ToJson($d)
    {
        $resphonse = [
            'code' => $d['code'],
            'msg'  => $d['msg'],
            'data' => $d['data']
        ];

        return json_encode($resphonse);
    }
}
