<?php

namespace LaravelHelper\Curl;

use LaravelHelper\Log\MonitorLog;

class Get
{
    /*********************************************************************
     * 通用的CURL GET 方法
     * @param string $url 访问的URL
     * @param array $params 参数
     * @param string $logCategory
     * @param boolean $retToArray 是否把结果转换为Array
     * @param boolean $needSplit 是否需要将返回内容截取1024字节到日志
     * @return bool|mixed ******************************************************************
     */
    public static function run($url, $params = array(), $retToArray = false, $logCategory = '[curl_info]', $needSplit = true)
    {
        if (strstr($url, '?') === false) {
            $url = $url . '?' . http_build_query($params);
        } else {
            $url = $url . '&' . http_build_query($params);
        }

        if ($logCategory) {
            MonitorLog::getInstance()->info($logCategory . $url);
        }

        $curl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);

        $content = curl_exec($curl);
        $status = curl_getinfo($curl);
        $errno = curl_errno($curl);
        if ($errno) {
            $errorStr = curl_error($curl);
            MonitorLog::getInstance()->error($logCategory . " errorStr:" . $errorStr . ", errno:" . $errno);
        }
        curl_close($curl);

        if ($logCategory) {
            if ($needSplit) {
                $logContent = substr($content, 0, 1024);
            } else {
                $logContent = $content;
            }
            MonitorLog::getInstance()->info($logCategory . ' content: ' . $logContent);
        }


        if (intval($status["http_code"]) == 200) {
            return $retToArray ? json_decode($content, true) : $content;
        } else {
            MonitorLog::getInstance()->error($logCategory . ' status ' , $status);
            return false;
        }
    }
}
