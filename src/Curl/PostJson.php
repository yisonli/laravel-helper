<?php

namespace LaravelHelper\Curl;

use LaravelHelper\Log\MonitorLog;

class PostJson
{
    /**
     * 将数组以json格式传递
     * @param string $url
     * @param array|string $data 可以是json串或php数组
     * @param boolean $retToArray 是否把结果变成array
     * @param bool $escapeUnicode
     * @param bool $needSplit 需要将返回内容截取1024字节到日志
     * @param string $logCategory 开启日志打印
     * @param array $header 添加header设定
     * @return array|string
     */
    public static function run($url, $data, $retToArray = true, $escapeUnicode = false, $logCategory="[curl_infoJ]", $needSplit = false, $header = [])
    {
        if ($logCategory) {
            MonitorLog::getInstance()->info($logCategory . 'url:' . $url . '; params:' , $data);
        }

        $data_string = is_array($data) ? $escapeUnicode ? json_encode($data, JSON_UNESCAPED_UNICODE) : json_encode($data) : $data;
        $ch = curl_init($url);

        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $nowHeader = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ];

        if ($header) {
            $newHeader = '';
            foreach ($header as $key => $value) {
                $newHeader .= "{$key}:{$value}";
            }
            $nowHeader = array_merge($nowHeader, array($newHeader));
        }
        if ($logCategory) {
            MonitorLog::getInstance()->info($logCategory . ' header值为：', ['header' => $nowHeader]);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $nowHeader);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $result = curl_exec($ch);

        if ($retToArray && !is_array($result)) {
            $result = json_decode($result, true);
        }

        $error = curl_error($ch);
        if (!empty($error)) {
            MonitorLog::getInstance()->error($logCategory.$error);
        }
        $resultContet = (is_array($result) ? json_encode($result) : $result);
        if ($needSplit) {
            $resultContet = substr($resultContet, 0, 1024);
        }
        if ($logCategory) {
            MonitorLog::getInstance()->info($logCategory . $resultContet);
        }

        return $result;
    }
}