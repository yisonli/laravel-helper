<?php

namespace LaravelHelper\Misc;

class Verify
{
    /**
     * 验证手机是否正确
     * @param $phone
     * @return int
     */
    public static function checkPhone($phone)
    {
        return preg_match("/^1[3|4|5|6|8|7|9][0-9]\d{8}$/", $phone);
    }

    /**
     * 验证身份证
     * @param $identity
     * @return bool
     */
    public static function checkIdentity($identity)
    {
        return Identity::isIdentity($identity);
    }

    /**
     * 判断是否全是中文
     * @param $str
     * @return int
     */
    public static function checkChinese($str)
    {
        return preg_match("/^[\x{36A2}\x{4e00}-\x{9fa5}]+$/u", $str);
    }

    /**
     * 判断是否全是中文+英文+数字
     * @param $str
     * @return int
     */
    public static function checkChEnNum($str)
    {
        return preg_match('/^[a-zA-Z0-9\x{4e00}-\x{9fa5}]+$/u', $str);
    }

    /**
     * 判断是否全是英文+数字
     * @param $str
     * @return int
     */
    public static function checkEnNum($str)
    {
        return preg_match('/^[a-zA-Z0-9]+$/u', $str);
    }

    /**
     * 判断是否是车牌号码
     * @param $str
     * @return int
     */
    public static function checkCarNum($str)
    {
        return preg_match('/[\x80-\xff][A-Z][A-Z0-9]{5}$/', $str);
    }

    /**
     * 判断是否是邮编
     * @param $str
     * @return int
     */
    public static function checkPostalCode($str)
    {
        return preg_match('/^[1-9]\d{5}$/', $str);
    }

    /**
     * 判断日期格式是否正确
     * @param $str
     * @return int
     */
    public static function checkDate($str)
    {
        return preg_match('/2\d{3}(-|\/)?([1-9]|0\d|1[0-2])(-|\/)?([1-9]|0\d|1\d|2\d|3[0-1])$/', $str);
    }

    /**
     * 判断是否邮件
     * @param $str
     * @return false|int
     */
    public static function checkEmail($str)
    {
        return preg_match('/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/', $str);
    }

    /**
     * 判断是否是银行卡号
     * @param $str
     * @return false|int
     */
    public static function checkBankCard($str)
    {
        return preg_match('/^([1-9]{1})(\d{14,18})$/', $str);
    }
}