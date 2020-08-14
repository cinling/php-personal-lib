<?php


namespace cin\personalLib\services;


use cin\personalLib\traits\SingleTrait;
use Exception;

/**
 * Class FtpService FTP 服务
 * @package cin\personalLib\services
 */
class FtpService
{
    use SingleTrait;

    /**
     * @var mixed ftp链接
     */
    private $conn;

    /**
     * 链接FTP服务器
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string|int $port
     * @throws Exception
     */
    public function conn($host, $username, $password, $port = 21) {

        /** @var resource conn */
        $this->conn = ftp_connect($host, $port);
        if (!$this->conn) {
            throw new Exception("FtpService.conn(): Cannot connect to ftp server");
        }
        if (!ftp_login($this->conn, $username, $password)) {
            throw new Exception("FtpService.conn(): Login failed");
        }
    }

    /**
     * 上传文件
     * @param string $localFile
     * @param string $removeFile
     * @throws Exception
     */
    public function upload($localFile, $removeFile) {
        if (!ftp_put($this->conn, $removeFile, $localFile, FTP_BINARY)) {
            throw new Exception("FtpService.conn(): upload file failed: [localFile: " . $localFile . ", removeFile: " . $removeFile . "]");
        }
    }

    /**
     * 下载文件
     * @param string $removeFile
     * @param string $localFile
     * @throws Exception
     */
    public function download($removeFile, $localFile) {
        if (ftp_get($this->conn, $localFile, $removeFile, FTP_BINARY)) {
            throw new Exception("FtpService.conn(): download file failed: [localFile: " . $localFile . ", removeFile: " . $removeFile . "]");
        }
    }

    /**
     * 关闭FTP服务器
     */
    public function close() {
        ftp_close($this->conn);
    }
}