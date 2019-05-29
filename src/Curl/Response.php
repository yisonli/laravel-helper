<?php

namespace LaravelHelper\Curl;

use LaravelHelper\Misc\Datas;

class Response
{

    /**
     * 成功返回
     * @param $result
     * @param array $header
     * @param int $isHump 是否把数组下划线转驼峰
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public static function success($result = 'success', $header = [], $isHump = 0)
    {
        $error = ResCode::SUCCESS;
        $response = [
            'errorCode' => $error['code'],
            'data' => $isHump ? Datas::arrayToHump($result) : $result,
            'message' => $error['msg'],
        ];
        $urlHeaders = ['content-type' => 'application/json;charset=utf-8'];
        $header = array_merge($header, $urlHeaders);
        return response(json_encode($response, JSON_UNESCAPED_UNICODE), 200, $header);
    }

    /**
     * 错误返回
     * @param $error
     * @param string $message
     * @param array $header
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public static function error($error, $message = '', $header = [])
    {
        $response = [
            'errorCode' => $error['code'],
            'message' => $message ? $message : $error['msg'],
        ];
        $urlHeaders = ['content-type' => 'application/json;charset=utf-8'];
        $header = array_merge($header, $urlHeaders);
        if (config("app.env") != 'prod') {
            $response['message'] = '[' . config("app.env") . ']' . $response['message'];
            $response['data'] = isset($error['data']) ? $error['data'] : '';
        }
        return response(json_encode($response, JSON_UNESCAPED_UNICODE), 200, $header);
    }

    /**
     * 失败返回
     * @param $error
     * @param string $message
     * @param int $status
     * @param array $header
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public static function fail($error, $message = '', $status = 400, $header = [])
    {
        $response = [
            'errorCode' => $error['code'],
            'message' => !empty($message) ? $message : $error['msg'],
        ];
        $header = array_merge($header, [
            'Access-Control-Allow-Origin' => '*',
            'content-type' => 'application/json;charset=utf-8'
        ]);
        return response(json_encode($response, JSON_UNESCAPED_UNICODE), $status, $header);
    }

}
