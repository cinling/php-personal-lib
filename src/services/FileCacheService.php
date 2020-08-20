<?php


namespace cin\personalLib\services;


use cin\personalLib\traits\SingleTrait;
use cin\personalLib\utils\EncryptUtil;
use cin\personalLib\vos\FileCacheConfigVo;

/**
 * Class FileCacheService 文件缓存服务。可以保存一些数据
 * @package cin\personalLib\services
 */
class FileCacheService {
    use SingleTrait;

    /**
     * @var FileCacheConfigVo 服务配置
     */
    private $config;

    protected function __construct() {
        $this->config = new FileCacheConfigVo();
    }

    /**
     * @param FileCacheConfigVo $config 服务配置
     */
    public function setConfig($config) {
        $this->config = $config;
    }

    /**
     * 设置缓存
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value) {
        $path = $this->getSavePath($key);
        $dir = dirname($path);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($path, EncryptUtil::serialize($value));
    }

    /**
     * 获取缓存数据
     * @param $key
     * @return mixed
     */
    public function get($key) {
        $path = $this->getSavePath($key);
        if (!file_exists($path)) {
            return null;
        }
        $value = file_get_contents($path);
        return EncryptUtil::unSerialize($value);
    }

    /**
     * 获取文件保存路径。并自动创建目录
     * @param string $key
     * @return string
     */
    private function getSavePath($key) {
        $filename = EncryptUtil::sha1($key);
        return $this->config->path . "/" . $this->getRelativeFilename($filename);
    }

    /**
     * 获取相对于配置的路径
     * @param string $filename
     * @return string
     */
    private function getRelativeFilename($filename) {
        $dir = "";
        $len = strlen($filename);
        for ($i = 0; $i < $this->config->pathDeeps; $i++) {
            if (!empty($dir)) {
                $dir .= "/";
            }
            $startIndex = $i * $this->config->pathUnitLen;
            if ($startIndex + $this->config->pathUnitLen >= $len) {
                $startIndex = $len - $this->config->pathUnitLen - 1;
            }
            $dir .= substr($filename, $startIndex, $this->config->pathUnitLen);
        }

        return $dir . "/" . $filename . ".cache";
    }
}