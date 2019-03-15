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
        if (!function_exists($method)) {
            return false;
        }

        ksort($arr_data, SORT_STRING);
        $arr_data['key'] = $token;
        $param_string = "";

        foreach ($arr_data as $key => $value) {
            if (!$value) {
                unset($arr_data[$key]);
            }

            if (strlen($param_string) == 0) {
                $param_string .= $key . "=" . $value;
            } else {
                $param_string .= "&" . $key . "=" . $value;
            }
        }

        $tmp_sign = $method($param_string);
        $sign = strtoupper($tmp_sign);
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
        $param_string = '';
        foreach ($arr_data as $key => $value) {
            if (!$value) {
                unset($arr_data[$key]);
            }
            if (strlen($param_string) == 0) {
                $param_string .= $key . "=" . $value;
            } else {
                $param_string .= "&" . $key . "=" . $value;
            }
        }
        return $param_string;
    }
}