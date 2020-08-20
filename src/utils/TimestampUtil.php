<?php


namespace cin\personalLib\utils;


/**
 * Class TimestampUtil 时间戳工具
 * @deprecated 和 DatetimeUtil 合并为 TimeUtil
 * @package cin\personalLib\utils
 */
class TimestampUtil {
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
     * 时间戳转日期
     * @param int $timestamp
     * @return string
     */
    public static function toDate($timestamp) {
        return date("Y-m-d", $timestamp);
    }

    /**
     * 时间戳转日期时间
     * @param int $timestamp
     * @return string
     */
    public static function toDatetime($timestamp) {
        return date("Y-m-d H:i:s", $timestamp);
    }

    /**
     * 获取今日起始时间戳
     * @param int $timestamp
     * @return int
     */
    public static function getDateStart($timestamp) {
        $date = self::toDate($timestamp);
        return strtotime($date);
    }

    /**
     * 获取今日结束时间戳
     * @param $timestamp
     * @return int
     */
    public static function getDatEnd($timestamp) {
        $timestamp = self::nextDateTimestamp($timestamp, 1, true);
        return $timestamp - 1;
    }

    /**
     * 获取多少天后的时间戳
     * @param int $timestamp 时间戳
     * @param int $days 多少天后
     * @param bool $roundToDateStart 是否将时间转为今天的0点
     * @return int
     */
    public static function nextDateTimestamp($timestamp, $days = 1, $roundToDateStart = false) {
        $timestamp += 86400 * $days;
        return $roundToDateStart ? self::getDateStart($timestamp) : $timestamp;
    }
}