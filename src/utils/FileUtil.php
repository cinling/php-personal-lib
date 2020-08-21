<?php


namespace cin\personalLib\utils;


/**
 * Class FileUtil 文件工具
 * @package cin\personalLib\utils
 */
class FileUtil {

    /**
     * 删除文件夹及下面所有的问题金，或删除文件
     * @param string $dirOrFile 目录或文件
     */
    public static function delFile($dirOrFile) {
        if (!file_exists($dirOrFile)) {
            return;
        }

        if (StringUtil::endWidth($dirOrFile, "/")) {
            $dirOrFile = substr($dirOrFile, 0, mb_strlen($dirOrFile) - 1);
        }

        if (is_dir($dirOrFile)) {
            $files = scandir($dirOrFile);
            foreach ($files as $file) {
                if (in_array($file, [".", ".."])) {
                    continue;
                }
                $absFilename = $dirOrFile . "/" . $file;
                self::delFile($absFilename);
            }
            rmdir($dirOrFile);
        } else {
            unlink($dirOrFile);
        }
    }
}