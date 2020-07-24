<?php

namespace cin\personalLib\utils;


/**
 * Class TimeUtil 时间工具
 * @package cin\personalLib\utils
 */
class TimeUtil
{
    /**
     * 当前系统时间戳
     * @return int
     */
    public static function timestamp() {
        return time();
    }

    /**
     * 当前系统时间戳（毫秒）
     * @return float 由于数字大小溢出 int 返回，因此只能转为 float
     */
    public static function timestampMS() {
        $tmpArr = explode(" ", microtime());
        $secondPart = floatval($tmpArr[0]);
        $msPart = floatval($tmpArr[1]);
        return floor(($secondPart + $msPart) * 1000);
    }
}