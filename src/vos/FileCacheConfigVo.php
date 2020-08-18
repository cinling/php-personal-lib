<?php


namespace cin\personalLib\vos;


use cin\personalLib\services\FileCacheService;

/**
 * Class FileCacheConfigVo 文件服务配置
 * @package cin\personalLib\vos
 * @see FileCacheService
 */
class FileCacheConfigVo extends BaseVo {
    /**
     * @var string 保存目录。默认是入口文件的相对路径
     */
    public $path = "./runtime/cin-cache";
    /**
     * @var int 目录深度
     */
    public $pathDeeps = 2;
    /**
     * @var int 单目录的字符数。注意： $pathDeeps * $pathUnitLen <= 64
     */
    public $pathUnitLen = 2;
}