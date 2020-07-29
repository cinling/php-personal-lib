<?php


namespace cin\personalLib\utils;


/**
 * Class StringUtil 字符串工具
 * @package cin\personalLib\utils
 */
class StringUtil
{
    /**
     * @param string $origin 原字符串
     * @param string $start 字符串开头的部分
     * @return bool
     */
    public static function StartWidth($origin, $start) {
        return strpos($origin, $start) === 0;
    }

    /**
     * @param string $origin 原字符串
     * @param $end $start 字符串结尾的部分
     * @return bool
     */
    public static function EndWidth($origin, $end) {
        return substr($origin, strpos($origin, $end)) === $end;
    }
}