<?php


namespace cin\personalLib\utils;

/**
 * Class HttpUtil http 工具
 * @package cin\personalLib\utils
 */
class HttpUtil {
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
     * 发送 get 请求
     * @param $url
     * @return string
     */
    public static function get($url) {
        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
        $output=curl_exec($ch);
        $fh=fopen("out.html",'w');
        fwrite($fh,$output);
        fclose($fh);
        return $output;
    }
}