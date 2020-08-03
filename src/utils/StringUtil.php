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
    public static function startWidth($origin, $start) {
        return strpos($origin, $start) === 0;
    }

    /**
     * @param string $origin 原字符串
     * @param $end $start 字符串结尾的部分
     * @return bool
     */
    public static function endWidth($origin, $end) {
        return substr($origin, strpos($origin, $end)) === $end;
    }

    /**
     * 将下划线转为驼峰
     * @param string $underline 下划线变量。如：user_type
     * @param bool $isFirstBig 首字母是否大写
     * @return string 驼峰命名。如：userType
     */
    public static function underlineToHump($underline, $isFirstBig = false) {
        $hump = preg_replace_callback('/([-_]+([a-z]{1}))/i', function ($matches) {
            return strtoupper($matches[2]);
        }, $underline);
        if ($isFirstBig) {
            $hump[0] = strtoupper($hump[0]);
        }
        return $hump;
    }

    /**
     * 将驼峰转为下划线
     * @param string $hump
     * @return string 下划线命名
     */
    public static function humpToUnderline($hump) {
        $underline = preg_replace_callback('/([A-Z]{1})/', function ($matches) {
            return '_' . strtolower($matches[0]);
        }, $hump);
        return ltrim($underline, "_");
    }
}
