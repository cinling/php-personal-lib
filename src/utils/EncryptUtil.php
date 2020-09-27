<?php


namespace cin\personalLib\utils;

/**
 * Class EncryptUtil 加密工具
 * @package cin\personalLib\utils
 */
class EncryptUtil {
    /**
     * @param string $str 加密字符串
     * @return string sha512 加密后的值
     */
    public static function sha512($str) {
        return hash("sha512", $str);
    }

    /**
     * @param string $str 加密字符串
     * @return string sha1 加密后的值
     */
    public static function sha1($str) {
        return sha1($str);
    }

    /**
     * @param string $str
     * @return string
     */
    public static function md5($str) {
        return md5($str);
    }

    /**
     * 反序列字符串
     * @param string $str
     * @return mixed
     */
    public static function unSerialize($str) {
        return unserialize($str);
    }

    /**
     * 序列化变量值
     * @param mixed $value
     * @return string
     */
    public static function serialize($value) {
        return serialize($value);
    }

    /**
     * 将字符串 base64 加密
     * @param string $str
     * @return string
     */
    public static function base64Encode($str) {
        return base64_encode($str);
    }

    /**
     * 解析 base64 编码。如果 base64 不是 base64 编码，则返回原数据
     * @param string $base64
     * @return string
     */
    public static function base64Decode($base64) {
        if (!StringUtil::isBase64($base64)) {
            return $base64;
        }
        return base64_decode($base64);
    }
}