<?php
namespace cin\personalLib\utils;

use cin\personalLib\exceptions\CronException;

/**
 * Class CronParser cron time parse utils
 * @copyright  https://gitee.com/jianglibin/codes/nfo3y04l6p1q9tu7xdske90
 * @package common\utils
 */
class CronParseUtil
{

    protected static $tags = [];

    protected static $weekMap = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    /**
     * supported check by $contTime
     * @param string $cronTime
     * @param bool $checkCount
     * @return boolean true|false
     */
    public static function check($cronTime, $checkCount = true)
    {
        $cronTime = trim($cronTime);

        $splitTags = preg_split('#\s+#', $cronTime);

        if ($checkCount && count($splitTags) !== 5) {
            return false;
        }

        foreach ($splitTags as $tag) {
            $r = '#^\*(\/\d+)?|\d+([\-\/]\d+(\/\d+)?)?(,\d+([\-\/]\d+(\/\d+)?)?)*$#';
            if (preg_match($r, $tag) == false) {
                return false;
            }
        }

        return true;
    }

    /**
     * 格式化 crontab 格式字符串
     * @param string $cronTime
     * @param int $maxSize the number of time will returned
     * @return array the list of next running datetime
     * @throws CronException
     */
    public static function formatToDate($cronTime, $maxSize = 1)
    {
        if (!static::check($cronTime)) {
            throw new CronException("formatError: $cronTime", 1);
        }
        self::$tags = preg_split('#\s+#', $cronTime);

        $cronArr = [
            'minutes' => static::parseTag(self::$tags[0], 0, 59), //分钟
            'hours'   => static::parseTag(self::$tags[1], 0, 23), //小时
            'day'     => static::parseTag(self::$tags[2], 1, 31), //一个月中的第几天
            'month'   => static::parseTag(self::$tags[3], 1, 12), //月份
            'week'    => static::parseTag(self::$tags[4], 0, 6), // 星期
        ];

        $cronArr['week'] = array_map(function($item){
            return static::$weekMap[$item];
        }, $cronArr['week']);

        return self::getDateList($cronArr, $maxSize);
    }

    /**
     * 根据当前时间。获取下一个运行的时间戳
     * @param $cronTime
     * @return int
     * @throws CronException
     */
    public static function getNextRunAt($cronTime) {
        $array = self::formatToDate($cronTime);
        return strtotime($array[0]);
    }

    /**
     * 递归获取符合格式的日期,直到取到满足$maxSize的数为止
     * @param  array  $cronArr 解析 crontab 字符串后的数组
     * @param  integer  $maxSize 最多返回多少数据的时间
     * @param  integer $year  指定年
     * @return array|null 符合条件的日期
     */
    private static function getDateList(array $cronArr, $maxSize, $year = null)
    {
        $dates = [];

        // 年份基点
        $nowYear = ($year) ? $year : date('Y');

        // 时间基点已当前为准,用于过滤小于当前时间的日期
        $nowTime = strtotime(date("Y-m-d H:i"));

        foreach ($cronArr['month'] as $month) {
            // 获取此月最大天数
            $maxDay = CronParseUtil::getDaysByYearMonth($nowYear, $month);
            foreach (range(1, $maxDay) as $day) {
                foreach ($cronArr['hours'] as $hours) {
                    foreach ($cronArr['minutes'] as $minutes) {
                        $i = mktime($hours, $minutes, 0, $month, $day, $nowYear);
                        if ($nowTime >= $i) {
                            continue;
                        }

                        $date = getdate($i);

                        // 解析是第几天
                        if (self::$tags[2] != '*' && in_array($date['mday'], $cronArr['day'])) {
                            $dates[] = date('Y-m-d H:i', $i);
                        }

                        // 解析星期几
                        if (self::$tags[4] != '*' && in_array($date['weekday'], $cronArr['week'])) {
                            $dates[] = date('Y-m-d H:i', $i);
                        }

                        // 天与星期几
                        if (self::$tags[2] == '*' && self::$tags[4] == '*') {
                            $dates[] = date('Y-m-d H:i', $i);
                        }

                        $dates = array_unique($dates);

                        if (isset($dates) && count($dates) == $maxSize) {
                            break 4;
                        }
                    }
                }
            }
        }

        // 已经递归获取了.但是还是没拿到符合的日期时间,说明指定的时期时间有问题
        if ($year && !count($dates)) {
            return [];
        }

        if (count($dates) != $maxSize) {
            // 向下一年递归
            $dates = array_merge(self::getDateList($cronArr, $maxSize, ($nowYear + 1)), $dates);
        }

        return $dates;
    }

    /**
     * parse tag
     * @param string $tag
     * @param integer $min
     * @param integer $max
     * @return array
     * @throws CronException
     */
    private static function parseTag($tag, $min, $max)
    {
        if ($tag == '*') {
            return range($min, $max);
        }

        $step = 1;
        $dateList = [];

        // x-x/2 情况
        if (false !== strpos($tag, ',')) {
            $tmp = explode(',', $tag);
            // 处理 xxx-xxx/x,x,x-x
            foreach ($tmp as $t) {
                if (self::checkExp($t)) {// 递归处理
                    $dateList = array_merge(self::parseTag($t, $min, $max), $dateList);
                } else {
                    $dateList[] = $t;
                }
            }
        }
        else if (false !== strpos($tag, '/') && false !== strpos($tag, '-')) {
            list($number, $mod) = explode('/', $tag);
            list($left, $right) = explode('-', $number);
            if ($left > $right) {
                throw new CronException("[{$tag}] is not supported");
            }
            foreach (range($left, $right) as $n) {
                if ($n % $mod === 0) {
                    $dateList[] = $n;
                }
            }
        }
        else if (false !== strpos($tag, '/')) {
            $tmp = explode('/', $tag);
            $step = isset($tmp[1]) ? $tmp[1] : 1;
            $dateList = range($min, $max, $step);
        }
        else if (false !== strpos($tag, '-')) {
            list($left, $right) = explode('-', $tag);
            if ($left > $right) {
                throw new CronException("[{$tag}] is not supported");
            }
            $dateList = range($left, $right, $step);
        }
        else {
            $dateList = array($tag);
        }

        // 越界判断
        foreach ($dateList as $num) {
            if ($num < $min || $num > $max) {
                throw new CronException('index out of range');
            }
        }

        sort($dateList);

        return array_unique($dateList);

    }

    /**
     * 判断tag是否可再次切割
     * @param $tag
     * @return bool
     */
    private static function checkExp($tag)
    {
        return (false !== strpos($tag, ',')) || (false !== strpos($tag, '-')) || (false !== strpos($tag, '/'));
    }

    /**
     * get number of days by YYYY-mm
     * @param string $year example: 2020
     * @param string $month example: 2,02
     * @return string
     */
    private static function getDaysByYearMonth($year, $month) {
        if (strlen($month) === 1) {
            $month = "0" . $month;
        }
        return date("t", strtotime("{$year}-{$month}"));
    }
}