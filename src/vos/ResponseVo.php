<?php


namespace cin\personalLib\vos;

/**
 * Class ResponseVo
 * @package cin\personalLib\vos
 */
class ResponseVo extends BaseVo {
    /**
     * @var int 返回代码
     */
    public $c = 0;
    /**
     * @var string 返回消息
     */
    public $m = "";
    /**
     * @var array|mixed 返回数据。一般是数组，也可以是单个字符串或数字
     */
    public $v = [];


}