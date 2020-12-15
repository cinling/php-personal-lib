<?php


namespace cin\personalLib\traits;


/**
 * Trait HttpTrait http插件
 * @package cin\personalLib\traits
 */
trait HttpTrait {
    /**
     * 发送post请求
     * @param $url
     * @param $values
     * @return string
     */
    public static function post($url, $values) {
        if (is_array($values) || is_object($values)) {
            $postData = http_build_query($values);
        } else {
            $postData = $values;
        }

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postData,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        return file_get_contents($url, false, $context);
    }

    /**
     * @param $url
     * @param array $values
     * @return false|string
     */
    public static function get($url, $values = []) {
        if (is_array($values) || is_object($values)) {
            $postData = http_build_query($values);
        } else {
            $postData = $values;
        }

        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postData,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        return file_get_contents($url, false, $context);
    }
}