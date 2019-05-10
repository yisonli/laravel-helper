<?php

namespace LaravelHelper\Misc;

class Datas
{
    /*
    * string 下划线转驼峰
    */
    public static function lineToHump($str)
    {
        $str = preg_replace_callback('/([-_]+([a-z]{1}))/i', function ($matches) {
            return strtoupper($matches[2]);
        }, $str);
        return $str;
    }

    /*
    * string 驼峰转下划线
    */
    public static function humpToLine($str)
    {
        $str = preg_replace_callback('/([A-Z]{1})/', function ($matches) {
            return '_' . strtolower($matches[0]);
        }, $str);
        return $str;
    }

    /**
     * 数组转换 key转成下划线
     */
    public static function arrayToLine(array $data)
    {
        $result = [];
        foreach ($data as $key => $item) {
            if (is_array($item) || is_object($item)) {
                $result[self::humpToLine($key)] = self::arrayToLine((array)$item);
            } else {
                $result[self::humpToLine($key)] = $item;
            }
        }
        return $result;
    }

    /**
     * 数组转换  key换转成驼峰
     */
    public static function arrayToHump(array $data)
    {
        $result = [];
        foreach ($data as $key => $item) {
            if (is_array($item) || is_object($item)) {
                $result[self::lineToHump($key)] = self::arrayToHump((array)$item);
            } else {
                $result[self::lineToHump($key)] = $item;
            }
        }
        return $result;
    }
}