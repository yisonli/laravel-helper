<?php

namespace LaravelHelper\Misc;

class Identity
{

    //性别 1-男 0-女 , 可以自定义男女的返回值
    public static function getSex($identity, $male = 1, $female = 0)
    {
        $sex = substr($identity, (strlen($identity) == 15 ? -1 : -2), 1) % 2 ? $male : $female;
        return $sex;
    }

    public static function hideIdentity($identity, $card = '')
    {
        if ($card == 'P') {
            return preg_replace('/(\d{1}).*(\d{2})/', "$1*****$2", $identity);
        }
        return preg_replace('/(\d{6}).*(\d{2})/', "$1*****$2", $identity);
    }

    public static function hidePhone($phone)
    {
        return preg_replace('/(\d{3}).*(\d{3})/', "$1*****$2", $phone);
    }

    //检证身份证是否正确
    public static function isIdentity($card)
    {
        $card = self::to18Card($card);
        if (strlen($card) != 18) {
            return false;
        }

        $cardBase = substr($card, 0, 17);

        return (self::getVerifyNum($cardBase) == strtoupper(substr($card, 17, 1)));
    }


    //格式化15位身份证号码为18位
    public static function to18Card($card)
    {
        $card = trim($card);

        if (strlen($card) == 18) {
            return $card;
        }

        if (strlen($card) != 15) {
            return false;
        }

        // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
        if (array_search(substr($card, 12, 3), array('996', '997', '998', '999')) !== false) {
            $card = substr($card, 0, 6) . '18' . substr($card, 6, 9);
        } else {
            $card = substr($card, 0, 6) . '19' . substr($card, 6, 9);
        }
        $card = $card . self::getVerifyNum($card);
        return $card;
    }

    // 计算身份证校验码，根据国家标准gb 11643-1999
    private static function getVerifyNum($cardBase)
    {
        if (strlen($cardBase) != 17) {
            return false;
        }
        // 加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);

        // 校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');

        $checksum = 0;
        for ($i = 0; $i < strlen($cardBase); $i++) {
            $checksum += substr($cardBase, $i, 1) * $factor[$i];
        }

        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];

        return $verify_number;
    }

    /**
     * 根据身份证号计算年龄
     * @param $identity
     * @return float|string
     */
    public static function getAge($identity)
    {
        $age = strlen($identity) == 15 ? ('19' . substr($identity, 6, 6)) : substr($identity, 6, 8);
        $date = strtotime($age);
        $today = strtotime('today');
        $diff = floor(($today - $date) / 86400 / 365);
        $age = strtotime($age . ' +' . $diff . 'years') > $today ? ($diff - 1) : $diff;
        return $age;
    }

    /**
     * 根据身份证号计算年龄多少天
     * @param $identity
     * @return float|string
     */
    public static function getAgeDay($identity)
    {
        $age = strlen($identity) == 15 ? ('19' . substr($identity, 6, 6)) : substr($identity, 6, 8);
        $date = strtotime($age);
        $today = strtotime('today');
        $diff = floor(($today - $date) / 86400);
        return $diff;
    }

    /**
     * 根据身份证号计算出生日期
     * @param $identity
     * @return float|string
     */
    public static function getBirthday($identity, $format = 'Y-m-d')
    {
        $age = strlen($identity) == 15 ? ('19' . substr($identity, 6, 6)) : substr($identity, 6, 8);
        $birthday = date($format, strtotime($age));
        return $birthday;
    }

    /**
     * 根据身份证号计算(到$toDate日期时的)年龄
     * @param $identity
     * @param $toDate
     * @return int
     */
    public static function getAgeTo($identity, $toDate)
    {
        $age = strlen($identity) == 15 ? ('19' . substr($identity, 6, 6)) : substr($identity, 6, 8);
        return self::_getAgeBetween(date('Y-m-d', strtotime($age)), $toDate);
    }

    /**
     * @param $born
     * @param $to
     * @return int $age
     */
    private static function _getAgeBetween($born, $to)
    {
        list($yearB, $monthB, $dayB) = explode('-', $born);
        list($yearT, $monthT, $dayT) = explode('-', $to);

        $age = $yearT - $yearB;
        if ($monthT < $monthB || ($monthT == $monthB && $dayT < $dayB)) $age--;
        return $age;
    }
}