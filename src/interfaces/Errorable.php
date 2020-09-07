<?php


namespace cin\personalLib\interfaces;

/**
 * Interface Errorable 错误管理接口
 * @package cin\personalLib\interfaces
 */
interface Errorable {
    /**
     * 添加错误信息
     * @param string $error 错误内容
     * @param string $prop 错误的字段
     */
    public function addError($error, $prop = "__errorDict");

    /**
     * @return bool 是否有错误
     */
    public function hasError();

    /**
     * @return array[] 获取所有错误
     */
    public function getErrorDict();

    /**
     * @return string 获取第一个错误
     */
    public function getFirstError();
}