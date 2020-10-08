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
    public function required() {
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

    /**
     * 最小值
     * 不能小于这个值
     * @param double|int $min
     * @return Closure
     */
    public function min($min) {
        $min = doubleval($min);
        /**
         * @param Errorable $errorTrait
         * @param string $prop 属性
         * @param string $propLabel 属性标签（便于阅读）
         * @param mixed $value 任何值
         */
        return function (Errorable $errorTrait, $prop, $propLabel, $value) use ($min) {
            if ($value < $min) {
                $errorTrait->addError($propLabel . " 不能少于" . $min, $prop);
            }
        };
    }

    /**
     * 最大值
     * 不能大于这个值
     * @param double|int $max
     * @return Closure
     */
    public function max($max) {
        $max = doubleval($max);
        /**
         * @param Errorable $errorTrait
         * @param string $prop 属性
         * @param string $propLabel 属性标签（便于阅读）
         * @param mixed $value 任何值
         */
        return function (Errorable $errorTrait, $prop, $propLabel, $value) use ($max) {
            if ($value > $max) {
                $errorTrait->addError($propLabel . " 不能大于" . $max, $prop);
            }
        };
    }

    /**
     * 必须在这个范围内
     * @param double|int $min
     * @param double|int $max
     * @return Closure
     */
    public function range($min, $max) {
        $min = doubleval($min);
        $max = doubleval($max);
        /**
         * @param Errorable $errorTrait
         * @param string $prop 属性
         * @param string $propLabel 属性标签（便于阅读）
         * @param mixed $value 任何值
         */
        return function (Errorable $errorTrait, $prop, $propLabel, $value) use ($min, $max) {
            if ($value > $max || $value < $min) {
                $errorTrait->addError($propLabel . " 必须在[$min" . " - " . $max . "]内", $prop);
            }
        };
    }

    /**
     * 值必须是这个数组里面的某个值
     * @param mixed[] $values
     * @return Closure
     */
    public function in($values) {
        /**
         * @param Errorable $errorTrait
         * @param string $prop 属性
         * @param string $propLabel 属性标签（便于阅读）
         * @param mixed $value 任何值
         */
        return function (Errorable $errorTrait, $prop, $propLabel, $value) use ($values) {
            if (!in_array($value, $values)) {
                $errorTrait->addError($propLabel . " 不在规定的值内。可以的值：[" . implode(",", $values) . "]", $prop);
            }
        };
    }

    /**
     * 必须小于
     * @param double|int $num
     * @return Closure
     */
    public function lt($num) {
        $num = doubleval($num);
        /**
         * @param Errorable $errorTrait
         * @param string $prop 属性
         * @param string $propLabel 属性标签（便于阅读）
         * @param mixed $value 任何值
         */
        return function (Errorable $errorTrait, $prop, $propLabel, $value) use ($num) {
            if ($value >= $num) {
                $errorTrait->addError($propLabel . " 必须小于" . $num, $prop);
            }
        };
    }

    /**
     * 必须小于等于
     * @param double|int $num
     * @return Closure
     */
    public function lte($num) {
        $num = doubleval($num);
        /**
         * @param Errorable $errorTrait
         * @param string $prop 属性
         * @param string $propLabel 属性标签（便于阅读）
         * @param mixed $value 任何值
         */
        return function (Errorable $errorTrait, $prop, $propLabel, $value) use ($num) {
            if ($value > $num) {
                $errorTrait->addError($propLabel . " 必须小于等于" . $num, $prop);
            }
        };
    }

    /**
     * 必须大于
     * @param double|int $num
     * @return Closure
     */
    public function gt($num) {
        $num = doubleval($num);
        /**
         * @param Errorable $errorTrait
         * @param string $prop 属性
         * @param string $propLabel 属性标签（便于阅读）
         * @param mixed $value 任何值
         */
        return function (Errorable $errorTrait, $prop, $propLabel, $value) use ($num) {
            if ($value <= $num) {
                $errorTrait->addError($propLabel . " 必须大于" . $num, $prop);
            }
        };
    }

    /**
     * 必须大于等于
     * @param double|int $num
     * @return Closure
     */
    public function gte($num) {
        $num = doubleval($num);
        /**
         * @param Errorable $errorTrait
         * @param string $prop 属性
         * @param string $propLabel 属性标签（便于阅读）
         * @param mixed $value 任何值
         */
        return function (Errorable $errorTrait, $prop, $propLabel, $value) use ($num) {
            if ($value < $num) {
                $errorTrait->addError($propLabel . " 必须大于等于" . $num, $prop);
            }
        };
    }
}