<?php


namespace cin\personalLib\utils;

use cin\personalLib\interfaces\ArrayAbleInterface;

/**
 * Class ArrayUtil 数组工具
 * @package cin\personalLib\utils
 */
class ArrayUtil
{
    /**
     * 将数据转为 数组
     * @param mixed $object 需要转为数组的对象（可以是任何类型）
     * @return array
     */
    public static function toArray($object) {
        if (is_array($object)) {
            foreach ($object as &$value) {
                if (is_object($value) || is_array($value))  {
                    $value = ArrayUtil::toArray($value);
                }
            }
            return $object;
        } else if (is_object($object)) {
            if ($object instanceof ArrayAbleInterface) {
                $object = $object->toArray();
            } else {
                $object = get_object_vars($object);
            }
            foreach ($object as &$value) {
                if (is_object($value) || is_array($value))  {
                    $value = ArrayUtil::toArray($value);
                }
            }
            return $object;
        }

        return [$object];
    }
}
