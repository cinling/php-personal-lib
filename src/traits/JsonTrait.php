<?php


namespace cin\personalLib\traits;

/**
 * Trait JsonTrait
 * @package cin\personalLib\traits
 */
trait JsonTrait {

    /**
     * @param $array
     * @return string
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