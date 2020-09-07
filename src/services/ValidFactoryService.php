<?php


namespace cin\personalLib\services;


use cin\personalLib\interfaces\Errorable;
use cin\personalLib\traits\SingleTrait;
use Closure;

/**
 * Class ValidFactoryService 验证工厂服务。提供验证定义好的验证方法
 * @package cin\personalLib\services
 */
class ValidFactoryService{
    use SingleTrait;

    /**
     * 必填项
     * @return Closure
     */
    public function requireHandler() {
        /**
         * @param Errorable $errorTrait
         * @param string $prop 属性
         * @param string $propLabel 属性标签（便于阅读）
         * @param mixed $value 任何值
         */
        return function (Errorable $errorTrait, $prop, $propLabel, $value) {
            if ($value === "" || $value === null || $value === []) {
                $errorTrait->addError($propLabel . " 不能为空", $prop);
            }
        };
    }

    /**
     * 字符串最小长度限制
     * @param int $min
     * @return Closure
     */
    public function minLen($min) {
        /**
         * @param Errorable $errorTrait
         * @param string $prop 属性
         * @param string $propLabel 属性标签（便于阅读）
         * @param mixed $value 任何值
         */
        return function (Errorable $errorTrait, $prop, $propLabel, $value) use ($min) {
            if (is_string($value) && strlen($value) < $min) {
                $errorTrait->addError($propLabel . " 长度不能少于" . $min . "字节", $prop);
            }
        };
    }

    /**
     * 字符串最大长度限制
     * @param $max
     * @return Closure
     */
    public function maxLen($max) {
        /**
         * @param Errorable $errorTrait
         * @param string $prop 属性
         * @param string $propLabel 属性标签（便于阅读）
         * @param mixed $value 任何值
         */
        return function (Errorable $errorTrait, $prop, $propLabel, $value) use ($max) {
            if (is_string($value) && strlen($value) > $max) {
                $errorTrait->addError($propLabel . " 长度不能多于" . $max . "字节", $prop);
            }
        };
    }

    /**
     * 字符串长度范围限制
     * @param $min
     * @param $max
     * @return Closure
     */
    public function lenBetween($min, $max) {
        /**
         * @param Errorable $errorTrait
         * @param string $prop 属性
         * @param string $propLabel 属性标签（便于阅读）
         * @param mixed $value 任何值
         */
        return function (Errorable $errorTrait, $prop, $propLabel, $value) use ($min, $max) {
            if (is_string($value)) {
                $strLen = strlen($value);
                if ($strLen < $min || $strLen > $max) {
                    $errorTrait->addError($propLabel . " 长度只能介于" . $min . "-" . $max . "字节", $prop);
                }
            }
        };
    }

    /**
     * 必须时数字，允许字符串类型的数字
     * @return Closure
     */
    public function number() {
        /**
         * @param Errorable $errorTrait
         * @param string $prop 属性
         * @param string $propLabel 属性标签（便于阅读）
         * @param mixed $value 任何值
         */
        return function (Errorable $errorTrait, $prop, $propLabel, $value) {
            if (!is_numeric($value)) {
                $errorTrait->addError($propLabel . " 必须时数字", $prop);
            }
        };
    }

    /**
     * 必须是数字类型
     * @return Closure
     */
    public function integer() {
        /**
         * @param Errorable $errorTrait
         * @param string $prop 属性
         * @param string $propLabel 属性标签（便于阅读）
         * @param mixed $value 任何值
         */
        return function (Errorable $errorTrait, $prop, $propLabel, $value) {
            if (!is_integer($value)) {
                $errorTrait->addError($propLabel . " 必须时整数", $prop);
            }
        };
    }
}