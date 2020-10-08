<?php
require_once "../../vendor/autoload.php";

use cin\personalLib\services\FileCacheService;
use cin\personalLib\services\FtpService;


$fileCacheSrv = FileCacheService::getIns();

$fileCacheSrv->set("123", "312");
echo $fileCacheSrv->get("123");