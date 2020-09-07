<?php


namespace cin\personalLib\interfaces;

use cin\personalLib\traits\ErrorTrait;
use cin\personalLib\vos\RuleVo;

/**
 * Class Verifiable 可验证接口
 * @package cin\personalLib\interfaces
 */
interface Verifiable {
    /**
     * @return RuleVo[]
     */
    public function rules();

    /**
     * 验证是否合法
     * @return bool
     */
    public function valid();
}