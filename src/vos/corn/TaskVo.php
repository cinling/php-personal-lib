<?php


namespace cin\personalLib\vos\corn;


use cin\personalLib\vos\BaseVo;

/**
 * Class TaskVo 任务列表
 * @package cin\personalLib\vos\corn
 */
class TaskVo extends BaseVo {
    /**
     * 任务状态：运行中
     */
    const StateRunning = 1;
    /**
     * 运行状态：运行结束
     */
    const StateEnd = 2;

    /**
     * @var int 任务id
     */
    public $id;
    /**
     * @var string 任务名字（唯一名字）
     */
    public $name;
    /**
     * @var string 任务命令
     */
    public $command;
    /**
     * @var string 运行时间配置。 crontab 格式。 如： "* * * * *"
     */
    public $cronTime;
    /**
     * @var int 任务状态。1运行中 2运行结束
     */
    public $state;
    /**
     * @var int 上次运行时间。单位：时间戳（秒）
     */
    public $lastRunTime;
    /**
     * @var int 下次运行时间。单位：时间戳（秒）
     */
    public $nextRunTime;
    /**
     * @var int 是否激活。1激活 2禁用
     */
    public $active;
    /**
     * @var int 创建时间戳
     */
    public $createAt;
    /**
     * @var int 更新时间戳
     */
    public $updateAt;
}