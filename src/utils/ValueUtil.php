<?php


namespace cin\personalLib\utils;


class ValueUtil {

    /**
     * @param $value
     * @return int
     */
    public static function toInt($value) {
        if (!empty($value)) {
            $value = intval($value);
        }
        return $value;
    }

    /**
     * @param $value
     * @return bool
     */
    public static function toBool($value) {
        if ($value === "true" || $value > 0 || $value) {
            return true;
        }
        return false;
    }

    /**
     * 获取标签字典中的标签
     * @param mixed[] $dict 标签字典
     * @param string|int $key 字典key
     * @param mixed $defaultValue 默认值。当 $key 不存在字典中时返回
     * @return mixed
     */
    public static function getValue($dict, $key, $defaultValue = "") {
        return isset($dict[$key]) ? $dict[$key] : $defaultValue;
    }
}