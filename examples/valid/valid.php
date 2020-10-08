<?php
require_once "../../vendor/autoload.php";

use cin\personalLib\services\ValidFactoryService;
use cin\personalLib\utils\ConsoleUtil;
use cin\personalLib\utils\DevelUtil;
use cin\personalLib\vos\BaseVo;
use cin\personalLib\vos\RuleVo;

class ValidTestVo extends BaseVo {
    /**
     * @var string 名字
     */
    public $name;
    /**
     * @var string 手机号码
     */
    public $phone;
    /**
     * @var string 邮箱地址
     */
    public $email;
    /**
     * @var int 年龄
     */
    public $age;
    /**
     * @var int 分数
     */
    public $score;
    /**
     * @var string 性别
     */
    public $sex;

    /**
     * @return string[]
     */
    public function labels() {
        return [
            "name" => "名字",
            "phone" => "手机号",
            "email" => "邮箱地址",
            "age" => "年龄",
            "score" => "分数",
            "sex" => "性别"
        ];
    }

    /**
     * 验证规则
     * @return RuleVo[]
     */
    public function rules() {
        $srv = ValidFactoryService::getIns();

        return array_merge(parent::rules(), [
            RuleVo::initBase(["name"], [$srv->lenBetween(1, 20)]),
            RuleVo::initBase(["sex"], [$srv->in(["男", "女"])]),
            RuleVo::initBase(["score"], [$srv->gte(91)]),
        ]);
    }
}


$ms = DevelUtil::useMS(function () {
    $vo = new ValidTestVo();
    $vo->name = "小明";
    $vo->sex = "男";
    $vo->score = 90;
    if (!$vo->valid()) {
//        print_r($vo->getErrorDict());
        ConsoleUtil::output($vo->getFirstError());
    } else {
        ConsoleUtil::output("验证通过");
    }
});

ConsoleUtil::output("耗时：" . $ms . "ms");
