<?php


namespace cin\personalLib\traits;

/**
 * Trait ErrorTrait 错误插件
 * @package cin\personalLib\traits
 */
trait ErrorTrait {
    /**
     * @var array[] 错误记录
     */
    private $__errorDict = [];

    /**
     * 添加错误信息
     * @param string $error 错误内容
     * @param string $prop 错误的字段
     */
    public function addError($error, $prop = "__errorDict") {
        if (!isset($this->__errorDict[$prop])) {
            $this->__errorDict[$prop] = [];
        }
        $this->__errorDict[$prop][] = $error;
    }

    /**
     * @return bool 是否有错误
     */
    public function hasError() {
        return count($this->getErrorDict()) !== 0;
    }

    /**
     * @return array[] 获取所有错误
     */
    public function getErrorDict() {
        return $this->__errorDict;
    }

    /**
     * @return string 获取第一个错误
     */
    public function getFirstError() {
        $error = "";
        if ($this->hasError()) {
            foreach ($this->getErrorDict() as $key => $errors) {
                foreach ($errors as $subKey => $tmpError) {
                    if (!empty($tmpError)) {
                        $error = $tmpError;
                        break 2;
                    }
                }
            }
        }
        return $error;
    }
}