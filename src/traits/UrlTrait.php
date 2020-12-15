<?php


namespace cin\personalLib\traits;


use cin\personalLib\utils\StringUtil;

/**
 * Class UrlTrait url路径的特性
 * @package cin\personalLib\traits
 */
trait UrlTrait {
    /**
     * 给路径添加上域名
     * @param string $url 相对域名的绝对访问路径
     * @return string
     */
    public static function withDomain($url) {
        if (StringUtil::startWidth($url, "http")) {
            return $url;
        }
        return self::getDomain() . $url;
    }

    /**
     * 去除域名。 如： https://abc.com/public/abc => /public.abc
     * 【注】只能去除目前请求的域名，如果是其他域名，则不会去除
     * @param string $url
     * @return string|string[]
     */
    public static function withoutDomain($url) {
        return str_replace(self::getDomain(), "", $url);
    }

    /**
     * 获取域名
     * @param bool $withProtocol 是否带上 http 活鹅 https 的协议头
     * @return string
     */
    public static function getDomain($withProtocol = true) {
        $domain = $_SERVER['HTTP_HOST'];
        if ($withProtocol) {
            $protocol = $_SERVER['HTTPS'] === "on" ? 'https://' : 'http://';
            $domain = $protocol . $domain;
        }
        return $domain;
    }
}