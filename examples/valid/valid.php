<?php
require_once "../../vendor/autoload.php";

use cin\personalLib\services\ValidFactoryService;
use cin\personalLib\utils\ConsoleUtil;
use cin\personalLib\utils\DevelUtil;
use cin\personalLib\vos\BaseVo;
use cin\personalLib\vos\RuleVo;

class ValidTestVo extends BaseVo {
    public $v1;
    public $v2;
    public $v3;
    public $v4;
    public $v5;
    public $v6;

    /**
     * 验证规则
     * @return RuleVo[]
     */
    public function rules() {
        $srv = ValidFactoryService::getIns();

        return array_merge(parent::rules(), [
            RuleVo::initBase(["v1", "v2"], [$srv->requireHandler()]),
            RuleVo::initBase(["v0", "v1", "v2", "v3", "v4", "v5"], [$srv->lenBetween(1, 4)]),
            RuleVo::initBase(["v1", "v2", "v3", "v4", "v5"], [$srv->number()]),
            RuleVo::initBase(["v1", "v2", "v3", "v4", "v5"], [$srv->integer()]),
        ]);
    }
}


$ms = DevelUtil::useMS(function () {
    $vo = new ValidTestVo();
    $vo->v1 = "";
    $vo->v2 = "12345";
    $vo->v3 = "A12345";
    $vo->v4 = [];
    $vo->v5 = "12345@abc.com";
    $vo->v6 = 123456;
    if (!$vo->valid()) {
        print_r($vo->getErrorDict());
    }
    ConsoleUtil::output($vo->getFirstError());
});

ConsoleUtil::output("耗时：" . $ms . "ms");
