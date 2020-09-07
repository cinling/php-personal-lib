<?php


namespace cin\personalLib\utils;


use Closure;

/**
 * Class DevelUtil 开发工具
 * @package cin\personalLib\utils
 */
class DevelUtil {
    /**
     * 获取运行用时
     * @param Closure $handle
     * @return float
     */
    public static function useMS(Closure $handle) {
        $startMS = TimeUtil::stampMS();
        $handle();
        $endMS = TimeUtil::stampMS();
        return $endMS - $startMS;
    }
}