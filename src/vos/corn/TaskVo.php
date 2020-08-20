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
     * 激活状态：激活
     * 每次运行时，都会判断是否需要执行
     */
    const ActiveOn = 1;
    /**
     * 激活状态：关闭
     * 运行时不判断是否可执行。相当于被暂停
     */
    const ActiveOff = 2;

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
     * @see TaskVo::StateRunning
     * @see TaskVo::StateEnd
     */
    public $state;
    /**
     * @var int 上次运行时间。单位：时间戳（秒）
     */
    public $lastRunAt;
    /**
     * @var int 下次运行时间。单位：时间戳（秒）
     */
    public $nextRunAt;
    /**
     * @var int 是否激活。1激活 2关闭
     * @see TaskVo::ActiveOn
     * @see TaskVo::ActiveOff
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

    /**
     * 初始化一个基础数据
     * @param string $name 任务名字
     * @param string $command 任务执行的命令
     * @param string $cronTime 任务执行时间配置。 crontab 格式
     * @return TaskVo
     */
    public static function initBase($name, $command, $cronTime) {
        $vo = new TaskVo();
        $vo->name = $name;
        $vo->command = $command;
        $vo->cronTime = $cronTime;
        return $vo;
    }

    public function setAttrs($attrs) {
        parent::setAttrs($attrs);
        $this->id = intval($this->id);
        $this->state = intval($this->state);
        $this->lastRunAt = intval($this->lastRunAt);
        $this->nextRunAt = intval($this->nextRunAt);
        $this->active = intval($this->active);
        $this->createAt = intval($this->createAt);
        $this->updateAt = intval($this->updateAt);
     }

    /**
     * 是否运行中
     * @return bool
     */
    public function isRunning() {
        return $this->state === self::StateRunning;
    }
}