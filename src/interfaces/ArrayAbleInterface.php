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
}
