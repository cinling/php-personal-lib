<?php


namespace cin\personalLib\services;


use cin\personalLib\traits\SingleTrait;
use cin\personalLib\utils\TimeUtil;
use cin\personalLib\vos\LogConfigVo;

/**
 * 日志服务
 */
class LogService {
    use SingleTrait;

    /**
     * 日志等级：追踪
     */
    const LogLevelTrace = "TRACE";
    /**
     * 日志等级：调试
     */
    const LogLevelDebug = "DEBUG";
    /**
     * 日志等级：信息
     */
    const LogLevelInfo = "INFO ";
    /**
     * 日志等级：警告
     */
    const LogLevelWarn = "WARN ";
    /**
     * 日志等级：错误
     * 一般错误
     */
    const LogLevelError = "ERROR";
    /**
     * 日志等级：失败
     * 严重性的错误
     */
    const LogLevelFatal = "FATAL";

    /**
     * @var LogConfigVo 配置对象
     */
    private $confVo;

    /**
     * LogService constructor.
     */
    protected function __construct() {
        $this->setConfig(new LogConfigVo());
    }

    /**
     * 设置配置
     * @param LogConfigVo $confVo
     */
    public function setConfig(LogConfigVo $confVo) {
        $this->confVo = $confVo;
        if (!file_exists($this->confVo->path)) {
            mkdir($this->confVo->path, 0755, true);
        }
    }

    /**
     * @param string $level
     * @param string $title
     * @param string $content
     */
    protected function base($level, $title, $content) {
        $filePath = $this->confVo->path . "/cin-LogService.log";
        if (file_exists($filePath) && filesize($filePath) > $this->confVo->fileMaxSize) {
            $bakFilePath = $this->confVo->path . "/cin-LogService-" . TimeUtil::stamp() . ".log";
            rename($filePath, $bakFilePath);
        }

        $writeContent = "[" . TimeUtil::datetime() . " " . $level . " " . $title . "] " . $content . PHP_EOL;
        file_put_contents($filePath, $writeContent, FILE_APPEND | LOCK_EX);
    }

    /**
     * 追踪日志
     * @param $content
     * @param string $title
     */
    public function trace($content, $title = "cin-trace") {
        $this->base(self::LogLevelTrace, $title, $content);
    }

    /**
     * 调试日志
     * @param $content
     * @param string $title
     */
    public function debug($content, $title = "cin-debug") {
        $this->base(self::LogLevelDebug, $title, $content);
    }

    /**
     * 消息日志
     * @param $content
     * @param string $title
     */
    public function info($content, $title = "cin-info") {
        $this->base(self::LogLevelInfo, $title, $content);
    }

    /**
     * 警告日志
     * @param $content
     * @param string $title
     */
    public function warn($content, $title = "cin-warn") {
        $this->base(self::LogLevelWarn, $title, $content);
    }

    /**
     * 错误日志
     * @param $content
     * @param string $title
     */
    public function error($content, $title = "cin-error") {
        $this->base(self::LogLevelError, $title, $content);
    }

    /**
     * 失败日志
     * @param $content
     * @param string $title
     */
    public function fatal($content, $title = "cin-fatal") {
        $this->base(self::LogLevelFatal, $title, $content);
    }
}