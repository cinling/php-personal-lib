<?php


namespace cin\personalLib\vos;


/**
 * Class LogConfigVo 日志服务配置参数
 * @package cin\personalLib\vos
 */
class LogConfigVo extends BaseVo {
    /**
     * @var string 保存目录。默认是入口文件的相对路径
     */
    public $path = "./runtime/cin-log";
    /**
     * @var integer 单文件最大长度。单位：字节。默认2MB
     */
    public $fileMaxSize = 2097152;
}