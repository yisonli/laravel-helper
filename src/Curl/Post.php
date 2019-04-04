<?php

namespace LaravelHelper\Curl;

use LaravelHelper\Log\MonitorLog;

class Post
{
    /*********************************************************************
     * 通用的CURL POST 方法
     * @param string $url 访问的URL
     * @param string|array $params 参数
     * @param string $logCategory
     * @param boolean $retToArray 是否把结果从Json转换为PHP数组
     * @param boolean $redirectUrl 是否获取跳转地址（当本值为true且http_code=302时返回跳转地址）
     * @param boolean $needSplit 是否需要将返回内容截取1024字节到日志
     * @return bool|mixed ******************************************************************
     */
    public static function run($url, $params, $retToArray = false, $logCategory = "[curl_info]", $redirectUrl = false, $needSplit = true)
    {
        if ($logCategory) {
            MonitorLog::getInstance()->info($logCategory . 'url:' . $url . '; params:' , $params);
        }

        $curl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        if (is_string($params)) {
            $strPOST = $params;
        } else {
            $strPOST = http_build_query($params);
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $strPOST);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);

        $content = curl_exec($curl);
        $status = curl_getinfo($curl);

        $errno = curl_errno($curl);
        if ($errno) {
            $errorStr = curl_error($curl);
            MonitorLog::getInstance()->error($logCategory . ' errorStr: ' . $errorStr . '; errno:' . $errno);
        }
        curl_close($curl);

        if ($logCategory) {
            if ($needSplit) {
                $logContent = substr($content, 0, 1024);
            } else {
                $logContent = $content;
            }
            MonitorLog::getInstance()->info($logCategory . 'content: ' . $logContent);
        }
        if (intval($status["http_code"]) == 200) {
            return $retToArray ? json_decode($content, true) : $content;
        } else if (intval($status["http_code"]) == 302 && $redirectUrl) {
            return $status['redirect_url'];
        } else {
            MonitorLog::getInstance()->error($logCategory . ' status: ' , $status);
            return false;
        }

    }

    /**
     * 请求时需要带上头部信息的结果
     * @param $url
     * @param $params
     * @param $header 头部信息
     * @param bool $retToArray
     * @param string $logCategory
     * @return bool|mixed|string
     */
    public static function runWithHeader($url, $params, $header, $retToArray = false, $logCategory = "[curl_infoH]")
    {
        if ($logCategory) {
            MonitorLog::getInstance()->info($logCategory . ' url:' . $url . '; params:' , $params);
        }

        $curl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        if (is_string($params)) {
            $strPOST = $params;
        } else {
            $strPOST = http_build_query($params);
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $strPOST);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);


        if ($header) {
            $newHeader = '';

            foreach ($header as $key => $value) {
                $newHeader .= "{$key}:{$value}\n";
            }

            curl_setopt($curl, CURLOPT_HTTPHEADER, array($newHeader));
        }

        $content = curl_exec($curl);
        $status = curl_getinfo($curl);
        curl_close($curl);

        if ($logCategory) {
            MonitorLog::getInstance()->info($logCategory . $content);
        }

        if (intval($status["http_code"]) == 200) {
            return $retToArray ? json_decode($content, true) : $content;
        }

        return false;
    }

}
