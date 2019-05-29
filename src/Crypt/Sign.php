<?php

namespace LaravelHelper\Crypt;

use LaravelHelper\Log\MonitorLog;

class Sign
{
    /**
     * 获取签名
     * @param $arr_data 签名数组
     * @param $token 签名钥匙
     * @param string $method 签名方法
     * @return bool|string 签名值
     */
    public static function getSign($arr_data, $token, $method = "md5")
    {
        if (!function_exists($method)) {    //不支持的签名方法
            return false;
        }

        ksort($arr_data, SORT_STRING);      //按字典序排序参数
        $param_string = self::mergeParamString($arr_data);

	$param_string = $param_string . "&key=".$token;     //在string后加入KEY

        $tmp_sign = $method($param_string);

        $sign = strtoupper($tmp_sign);      //所有字符转为大写
        return $sign;
    }

    /**
     * 检查签名
     * @param array $arr_data 签名数组
     * @param $token 签名钥匙
     * @param string $method 签名方法
     *
     * @return boolean|string 签名值
     */
    public static function checkSign($arr_data, $token, $method = "md5")
    {
        $to_check_array = $arr_data;
        unset($to_check_array['sign']);

        $wanted = self::getSign($to_check_array, $token, $method);
        return $wanted == $arr_data['sign'];
    }


    public static function mergeParamString($arr_data)
    {
        $buff = "";
        foreach ($arr_data as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }
}
