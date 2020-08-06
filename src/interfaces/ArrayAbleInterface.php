<?php


namespace cin\personalLib\interfaces;

/**
 * Interface AArrayAble 可转为数组
 * @package cin\personalLib\vos
 */
interface ArrayAbleInterface
{
    /**
     * 将数据转为数组
     * @return array
     */
    public function toArray();

    /**
     * 将数组数据写入到对象中
     * @param $attrs
     * @return void
     */
    public function setAttrs($attrs);
}
