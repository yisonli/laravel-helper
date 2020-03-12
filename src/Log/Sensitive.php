<?php

namespace LaravelHelper\Log;

class Sensitive
{

    public static $preg = [
        'email' => [
            'pattern' => '/(\w)[-\w.+]*(@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14})/',
            'replacement' => '$1****$2'
        ],
        'bank_card' => [
            'pattern' => '/(\W[1-9]{1}\d{3})(\d{11}|\d{8})(\d{4}\W)/',
            'replacement' => '$1****$3'
        ],
        'identity_18' => [
            'pattern' => '/(\W\d{3})\d{11}(\d{3}[\d|x|X]\W)/',
            'replacement' => '$1****$2'
        ],
        'identity_15' => [
            'pattern' => '/(\W\d{3})\d{8}(\d{4}\W)/',
            'replacement' => '$1****$2'
        ],
        'mobile' => [
            'pattern' => '/(\W1[2-9][0-9])[0-9]{4}([0-9]{4}\W)/',
            'replacement' => '$1****$2'
        ],
        'passport_1' => [
            'pattern' => '/(\W1[45])[0-9]{4}([0-9]{3}\W)/',
            'replacement' => '$1****$2'
        ],
        'passport_2' => [
            'pattern' => '/(\W[P|p|S|s])\d{4}(\d{3}\W)/',
            'replacement' => '$1****$2'
        ],
        'passport_3' => [
            'pattern' => '/(\W[Gg|Tt|Ss|Ll|Qq|Dd|Aa|Ff])\d{5}(\d{3}\W)/',
            'replacement' => '$1****$2'
        ],
        'passport_4' => [
            'pattern' => '/(\W[H|h|M|m])\d{5,7}(\d{3}\W)/',
            'replacement' => '$1****$2'
        ],
    ];

    public static function filter($message)
    {
        $str = $message;
        foreach (self::$preg as $key => $value) {
            $str = preg_replace($value['pattern'], $value['replacement'], $str);
        }
        return $str;
    }
}