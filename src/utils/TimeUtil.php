<?php

namespace cin\personalLib\utils;


use Closure;

/**
 * Class TimeUtil 时间工具
 * @package cin\personalLib\utils
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
     * 时间戳转日期
     * @param int $stamp
     * @return string
     */
    public static function stampToDate($stamp) {
        return self::date(self::DateFormatHorizontalLine, $stamp);
    }

    /**
     * @param int $stamp 时间戳转日期时间
     * @return string
     */
    public static function stampToDatetime($stamp) {
        return self::datetime(self::DatetimeFormatHorizontalLine, $stamp);
    }

    /**
     * 获取多少天后的时间戳
     * @param int $stamp 时间戳
     * @param int $days 多少天后
     * @param bool $roundToDateStart 是否将时间转为今天的0点
     * @return int
     */
    public static function nextDateStamp($stamp, $days = 1, $roundToDateStart = false) {
        $stamp += 86400 * $days;
        return $roundToDateStart ? self::getDateStart($stamp) : $stamp;
    }

    /**
     * 获取多少天后的时间戳
     * @param int $stamp 时间戳
     * @param int $days 多少天后
     * @param bool $roundToDateStart 是否将时间转为今天的0点
     * @return int
     */
    public static function prevDateStamp($stamp, $days = 1, $roundToDateStart = false) {
        $stamp -= 86400 * $days;
        return $roundToDateStart ? self::getDateStart($stamp) : $stamp;
    }

    /**
     * 获取下一周的时间戳
     * @param $stamp
     * @param int $weeks
     * @return int
     */
    public static function nextWeekStamp($stamp, $weeks = 1) {
        return $stamp + 604800 * $weeks;
    }

    /**
     * 获取上一周的时间戳
     * @param $stamp
     * @param int $weeks
     * @return int
     */
    public static function prevWeekStamp($stamp, $weeks = 1) {
        return $stamp - 604800 * $weeks;
    }

    /**
     * 时间戳转日期
     * @param int $stamp
     * @return string
     */
    public static function toDate($stamp) {
        return date("Y-m-d", $stamp);
    }

    /**
     * 时间戳转日期时间
     * @param int $stamp
     * @return string
     */
    public static function toDatetime($stamp) {
        return date("Y-m-d H:i:s", $stamp);
    }

    /**
     * 获取今日起始时间戳
     * @param int $stamp
     * @return int
     */
    public static function getDateStart($stamp) {
        $date = self::toDate($stamp);
        return strtotime($date);
    }

    /**
     * 获取今日结束时间戳
     * @param $stamp
     * @return int
     */
    public static function getDatEnd($stamp) {
        $stamp = self::nextDateStamp($stamp, 1, true);
        return $stamp - 1;
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
     * 获取日期
     * @param string $format
     * @param int|null $stamp
     * @return string 今天的日期
     */
    public static function date($format = self::DateFormatHorizontalLine, $stamp = null) {
        if ($stamp === null) {
            $stamp = time();
        }
        return date($format, $stamp);
    }

    /**
     * 获取今天的日期
     * @return string
     */
    public static function todayDate() {
        return self::date();
    }

    /**
     * 获取昨天的日期
     */
    public static function yesterdayDate() {
        $stamp = self::stamp() - 86400;
        return self::stampToDate($stamp);
    }

    /**
     * 获取现在的日期时间
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