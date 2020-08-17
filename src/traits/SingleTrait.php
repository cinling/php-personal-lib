<?php


namespace cin\personalLib\traits;


trait SingleTrait {
    /**
     * @var null|static
     */
    protected static $ins = null;

    /**
     * SingleTrait constructor.
     * 保护构造函数
     */
    protected function __construct() {

    }

    /**
     * 保护克隆函数
     */
    protected function __clone() {

    }

    /**
     * 防止序列化获取对象
     * @return array
     */
    protected function __sleep() {
       return [];
    }
}