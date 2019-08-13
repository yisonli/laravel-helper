<?php

namespace LaravelHelper\Misc;

class TimeStamp
{
    /**
     * 获取一个带毫秒的时间戳，格式为   yyyymmddhhiissxxx
     * @return string
     */
    public static function WithMicrosecond()
    {
        $time = date('YmdHis');
        $millisecond = floor((float)microtime() * 1000);//取得毫秒秒数
        $millisecond = str_pad($millisecond, 3, '0', STR_PAD_LEFT);//未满三位，补零

        return $time . $millisecond;
    }

    public static function fromYYMMDDHHIISS($yymmddhhiiss)
    {
        $year = substr($yymmddhhiiss, 0, 4);
        $month = substr($yymmddhhiiss, 4, 2);
        $day = substr($yymmddhhiiss, 6, 2);
        $hour = substr($yymmddhhiiss, 8, 2);
        $minute = substr($yymmddhhiiss, 10, 2);
        $second = substr($yymmddhhiiss, 12, 2);

        return "{$year}-{$month}-{$day} {$hour}:{$second}:{$minute}";
    }

    public static function toHms($time)
    {
        if ($time <= 0) {
            return '0秒';
        }
        $time = round($time);
        $h = intval($time / 3600);
        $m = intval($time / 60) - $h * 60;
        $s = $time % 60;
        $strH = $h > 0 ? $h . '时' : '';
        $strM = $strH ? $m . '分' : $m > 0 ? $m . '分' : '';
        $strS = $strM ? $s . '秒' : $s > 0 ? $s . '秒' : '';
        return $strH . $strM . $strS;
    }

    //年1位 + 月1位 + 日2位 + 时间戳后5位 = 9位
    public static function specialDate($base_year='2019')
    {
        $codeOfYear = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        ];  //总共支持58年
        $diff_year = (intval(date('Y')) - $base_year) % count($codeOfYear);
        return $codeOfYear[$diff_year] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5);
    }
}