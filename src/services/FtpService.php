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
     * @var array[] 远端文件数结构。减少网络请求提升效率
     */
    private $remoteDirTree = [];

    /**
     * FtpService constructor.
     */
    protected function __construct() {
    }

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
        $this->autoMakeRemoteDir(dirname($removeFile));
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
     * 远端创建目录
     * @param $dir
     */
    public function remoteMkdir($dir) {
        ftp_mkdir($this->conn, $dir);
    }

    /**
     * @param $dir
     * @return array
     */
    public function remoteLs($dir) {
        $lines = ftp_rawlist($this->conn, $dir);
        $dirs = [];
        foreach ($lines as $line) {
            $matchArr = [];
            // $line 的样例： drwxr-xr-x    3 1000       www              4096 Aug 25 17:53 .well-known
            if (preg_match("/[drwx-]+\s+[a-zA-Z0-9]+\s+[a-zA-Z0-9]+\s+[a-zA-Z0-9]+\s+[0-9]+\s+[a-zA-Z]+\s+[0-9]+\s+[0-9]{2}:[0-9]{2}\s+([.|a-zA-Z0-9_-]+)/", $line, $matchArr)) {
                $name = $matchArr[1];
                if (in_array($name, [".", ".."])) {
                    continue;
                }
                $dirs[] = $name;
            }
        }
        return $dirs;
    }

    /**
     * 自动创建远端路径
     * @param $dir
     */
    public function autoMakeRemoteDir($dir) {
        if (empty($dir) || $dir === "/") {
            return;
        }
        if (!$this->isRemoteDirExists($dir)) {
            $this->autoMakeRemoteDir(dirname($dir));
            // 忽略创建的错误
            @ftp_mkdir($this->conn, $dir);
        }
        $this->addRemoteDirTree($dir);
    }

    /**
     * 判断远端路径是否存在
     * @param string $dir
     * @return bool
     */
    private function isRemoteDirExists($dir) {
        $exists = $this->isRemoteDirExistsInTree(explode("/", $dir), $this->remoteDirTree);
        if (!$exists) {
            $exists = count($this->remoteLs($dir)) > 0;
        }
        if ($exists) {
            $this->addRemoteDirTree($dir);
        }
        return $exists;
    }

    /**
     * 从本地记录查询目录是否存在
     * @param $dirs
     * @param $node
     * @return bool
     */
    private function isRemoteDirExistsInTree(array $dirs, array $node) {
        if (count($dirs) === 0) {
            return true;
        }

        $dir = $dirs[0];
        unset($dirs[0]);
        if (isset($node[$dir])) {
            return $this->isRemoteDirExistsInTree(array_values($dirs), $node[$dir]);
        }

        return false;
    }

    /**
     * 建立本地的树结构
     * @param string $dir
     */
    private function addRemoteDirTree($dir) {
        $names = explode("/", $dir);
        $this->remoteDirTree = $this->addRemoteDirTreeNoteByDir($names, $this->remoteDirTree);
    }

    /**
     * @param array $dirs
     * @param array $node 目录对应的节点
     * @return array
     */
    private function addRemoteDirTreeNoteByDir(array $dirs, array $node = []) {
        if (empty($dirs)) {
            return $node;
        }
        $dir = $dirs[0];
        unset($dirs[0]);
        if (!isset($node[$dir])) {
            $node[$dir] = [];
        }
        $node[$dir] = $this->addRemoteDirTreeNoteByDir(array_values($dirs), $node[$dir]);
        return $node;
    }

    public function test() {
        $this->autoMakeRemoteDir("test1/test2/test3");
        $this->autoMakeRemoteDir("test1/test2/test4");
        print_r($this->remoteDirTree);
    }

    /**
     * 关闭FTP服务器
     */
    public function close() {
        ftp_close($this->conn);
    }
}