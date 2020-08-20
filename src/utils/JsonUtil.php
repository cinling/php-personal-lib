<?php


namespace cin\personalLib\utils;


class JsonUtil {

    /**
     * @param $array
     * @return
     */
    public static function encode($array) {
        return json_encode($array, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $json
     * @return array
     */
    public static function decode($json) {
        return json_decode($json, true);
    }
}