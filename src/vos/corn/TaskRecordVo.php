<?php


namespace cin\personalLib\vos\corn;


use cin\personalLib\utils\TimeUtil;
use cin\personalLib\vos\BaseVo;

/**
 * Class TaskRecordVo
 * @package cin\personalLib\vos\corn
 */
class TaskRecordVo extends BaseVo {
    /**
     * 运行状态：成功
     */
    const StateDone = 1;
    /**
     * 运行状态：失败
     */
    const StateFail = -1;

    /**
     * @var int 任务id
     */
    public $taskId;
    /**
     * @var int 运行状态。1成功 -1失败
     */
    public $state;
    /**
     * @var int 用时。单位：毫秒
     */
    public $useMS;
    /**
     * @var int 创建时间
     */
    public $createAt;

    /**
     * 初始化数据
     */
    public function onInit() {
        parent::onInit();
        $this->createAt = TimeUtil::stamp();
    }
}