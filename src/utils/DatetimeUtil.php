<?php


namespace cin\personalLib\utils;

/**
 * Class DatetimeUtil 日期-时间 格式工具
 * @package cin\personalLib\utils
 */
class DatetimeUtil {
    /**
     * 【时间】格式：常规（横线）
     */
    const DateFormatHorizontalLine = "Y-m-d";

    /**
     * 【时间-日期】格式：常规（横线）
     */
    const DatetimeFormatHorizontalLine = "Y-m-d H:i:s";

    /**
     * @param string $format
     * @return string 今天的日期
     */
    public static function date($format = self::DateFormatHorizontalLine) {
        return date($format);
    }

    /**
     * @param string $format
     * @return string 当前日期时间
     */
    public static function datetime($format = self::DatetimeFormatHorizontalLine) {
        return date($format);
    }

    /**
     * 将 日期-时间 转为时间戳
     * @param $datetime
     * @return int
     */
    public static function datetimeToTimestamp($datetime) {
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