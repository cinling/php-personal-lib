<?php

namespace cin\personalLib\utils;


/**
 * Class TimeUtil 时间工具
 * @package cin\personalLib\utils
 *
 * 标准时间格式： 2006-01-02 15:04:05
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

    /**
     * 日期转时间戳
     * @param $datetime
     * @return int
     */
    public static function datetimeToTimestamp($datetime) {

    }

    /**
     * 将日期转为时间戳
     * @param $time
     */
    public static function getDateEndTimestamp($time) {
        $timestamp = TimeUtil::isTimestamp($time) ? $time : TimeUtil::datetimeToTimestamp($time);
    }

    /**
     * 判断时间是不是时间戳。仅支持 秒 和 毫秒 的时间戳类型。
     * @param float|int|string $time
     * @return bool
     */
    public static function isTimestamp($time) {
        return is_int($time);
    }
}