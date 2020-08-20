<?php

namespace cin\personalLib\utils;


use Closure;

/**
 * Class TimeUtil 时间工具
 * @package cin\personalLib\utils
 * @see DatetimeUtil
 * @see TimestampUtil
 *
 * 标准时间格式： 2006-01-02 15:04:05
 */
class TimeUtil
{
    /**
     * 【时间】格式：常规（横线）
     */
    const DateFormatHorizontalLine = "Y-m-d";

    /**
     * 【时间-日期】格式：常规（横线）
     */
    const DatetimeFormatHorizontalLine = "Y-m-d H:i:s";

    /**
     * 当前系统时间戳
     * @deprecated 已简化为 stamp
     * @return int
     */
    public static function timestamp() {
        return self::stamp();
    }

    /**
     * 当前系统时间戳（毫秒）
     * @deprecated 已简化为 stamp
     * @return float 由于数字大小溢出 int 返回，因此只能转为 float
     */
    public static function timestampMS() {
        return self::stampMS();
    }

    /**
     * 日期转时间戳
     * @deprecated 已简化为 stamp
     * @param $datetime
     * @return int
     */
    public static function datetimeToTimestamp($datetime) {
        return strtotime($datetime);
    }

    /**
     * 判断时间是不是时间戳。仅支持 秒 和 毫秒 的时间戳类型。
     * @deprecated 已简化为 stamp
     * @param float|int|string $time
     * @return bool
     */
    public static function isTimestamp($time) {
        return is_int($time);
    }

    /**
     * 判断时间是不是时间戳。仅支持 秒 和 毫秒 的时间戳类型。
     * @param $time
     * @return bool
     */
    public static function isStamp($time) {
        return is_numeric($time);
    }

    /**
     * 获取当亲时间戳。单位：秒
     * @return int
     */
    public static function stamp() {
        return time();
    }

    /**
     * 当前系统时间戳（毫秒）
     * @return float 由于数字大小溢出 int 返回，因此只能转为 float
     */
    public static function stampMS() {
        $tmpArr = explode(" ", microtime());
        $secondPart = floatval($tmpArr[0]);
        $msPart = floatval($tmpArr[1]);
        return floor(($secondPart + $msPart) * 1000);
    }

    /**
     * @param int $stamp 时间戳转日期
     */
    public static function stampToDatetime($stamp) {
        return self::datetime(self::DatetimeFormatHorizontalLine, $stamp);
    }

    /**
     * 获取多少天后的时间戳
     * @param int $timestamp 时间戳
     * @param int $days 多少天后
     * @param bool $roundToDateStart 是否将时间转为今天的0点
     * @return int
     */
    public static function nextDateStamp($timestamp, $days = 1, $roundToDateStart = false) {
        $timestamp += 86400 * $days;
        return $roundToDateStart ? self::getDateStart($timestamp) : $timestamp;
    }

    /**
     * 获取多少天后的时间戳
     * @param int $timestamp 时间戳
     * @param int $days 多少天后
     * @param bool $roundToDateStart 是否将时间转为今天的0点
     * @return int
     */
    public static function prevDateStamp($timestamp, $days = 1, $roundToDateStart = false) {
        $timestamp -= 86400 * $days;
        return $roundToDateStart ? self::getDateStart($timestamp) : $timestamp;
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
        $timestamp = self::nextDateStamp($timestamp, 1, true);
        return $timestamp - 1;
    }

    /**
     * 计算方法的运行时间
     * @param Closure $func
     * @return int 运行用时。单位：ms
     */
    public static function countUseMS(Closure $func) {
        $startMS = self::stampMS();
        $func();
        $endMS = self::stampMS();
        return $endMS - $startMS;
    }

    /**
     * @param string $format
     * @return string 今天的日期
     */
    public static function date($format = self::DateFormatHorizontalLine) {
        return date($format);
    }

    /**
     * @param string $format
     * @param int $stamp
     * @return string 当前日期时间
     */
    public static function datetime($format = self::DatetimeFormatHorizontalLine, $stamp = null) {
        if ($stamp === null) {
            $stamp = time();
        }
        return date($format, $stamp);
    }

    /**
     * 将 日期-时间 转为时间戳
     * @param $datetime
     * @return int
     */
    public static function datetimeToStamp($datetime) {
        return strtotime($datetime);
    }

    /**
     * 时间取整分钟
     * @param string $datetime
     * @return false|string
     */
    public static function floorMinute($datetime) {
        $format = str_replace("s", "00", self::DatetimeFormatHorizontalLine);
        return date($format, strtotime($datetime));
    }
}