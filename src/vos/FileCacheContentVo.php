<?php


namespace cin\personalLib\vos;


/**
 * Class FileCacheContentVo 文件缓存的数据记录
 * @package cin\personalLib\vos
 */
class FileCacheContentVo extends BaseVo {
    /**
     * @var int 过期时间。0代表永不过期
     */
    public $expire = 0;
    /**
     * @var string 缓存的内容（序列化后的字符串）
     */
    public $value;
}