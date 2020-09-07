<?php


namespace cin\personalLib\traits;

/**
 * Trait LabelTrait 标签插件
 * @package cin\personalLib\traits
 */
trait LabelTrait {

    /**
     * @return string[]
     */
    public function labels() {
        return [];
    }

    /**
     * @param string $prop
     * @return string
     */
    public function label($prop) {
        $labels = $this->labels();
        return isset($labels[$prop]) ? $labels[$prop] : $prop;
    }
}