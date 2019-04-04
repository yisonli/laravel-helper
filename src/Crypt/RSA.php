<?php

namespace LaravelHelper\Crypt;

/**
 * Class RSA
 *
 * RSA 调用需要传入2份文件的路径
 * $selfPri : 己方的 RSA 私钥
 * $partnerPub : 合作方的 RSA 公钥
 * $type : 当前与java对接的时候，json_encode之后的数据可能与java不一致，所以这里加上type做区分
 *
 * PS : 此 RSA 类仅支持JSON串加密，1024位加密
 */
class RSA
{
    private $_priKey;
    private $_pubKey;
    private $_type;

    public function __construct($selfPri, $partnerPub, $type = 'default')
    {
        $this->_priKey = $selfPri;
        $this->_pubKey = $partnerPub;
        $this->_type = $type;
    }

    public function decrypt($encryptedData)
    {
        if (empty($encryptedData)) {
            return '';
        }
        $privateKey = file_get_contents($this->_priKey);

        $encryptedData = base64_decode($encryptedData);
        $decryptedList = array();
        $step = 128;

        for ($i = 0, $len = strlen($encryptedData); $i < $len; $i += $step) {
            $data = substr($encryptedData, $i, $step);
            $decrypted = '';
            openssl_private_decrypt($data, $decrypted, $privateKey);
            $decryptedList[] = $decrypted;
        }

        return join('', $decryptedList);
    }

    public function encrypt($data)
    {
        is_array($data) && ksort($data) && $data = json_encode($data);
        if (is_file($this->_pubKey)) {
            $pubKey = file_get_contents($this->_pubKey);
        } else {
            //去除字符串的前后回车
            $pubKey = rtrim($this->_pubKey, PHP_EOL);
            $pubKey = ltrim($pubKey, PHP_EOL);
        }
        $encryptedList = array();
        $step = 117;

        for ($i = 0, $len = strlen($data); $i < $len; $i += $step) {
            $tmpData = substr($data, $i, $step);
            $encrypted = '';
            openssl_public_encrypt($tmpData, $encrypted, $pubKey);
            $encryptedList[] = ($encrypted);
        }

        $encryptedData = base64_encode(join('', $encryptedList));

        return $encryptedData;
    }

    public function sign($data)
    {
        is_array($data) && ksort($data) && $data = json_encode($data);

        if ($this->_type === 'java') {
            $data = stripslashes($data);
        }

        $priKey = file_get_contents($this->_priKey);
        $res = openssl_get_privatekey($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        $sign = base64_encode($sign);

        return $sign;
    }

    public function verify($data, $sign)
    {
        is_array($data) && ksort($data) && $data = json_encode($data, JSON_UNESCAPED_UNICODE);

        if ($this->_type === 'java') {
            $data = stripslashes($data);
        }
        if (is_file($this->_pubKey)) {
            $pubKey = file_get_contents($this->_pubKey);
        } else {
            //去除字符串的前后回车
            $pubKey = rtrim($this->_pubKey, PHP_EOL);
            $pubKey = ltrim($pubKey, PHP_EOL);
        }
        $res = openssl_get_publickey($pubKey);
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        openssl_free_key($res);

        return $result;
    }
}