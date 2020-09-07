<?php


namespace cin\personalLib\interfaces;


use cin\personalLib\exceptions\CronException;
use cin\personalLib\vos\corn\TaskRecordVo;
use cin\personalLib\vos\corn\TaskVo;

/**
 * Class CronTaskStorable 定时任务存取接口
 * @package cin\personalLib\interfaces
 */
interface CronTaskStorable {
    /**
     * @return TaskVo[] 获取所有定时任务
     * @throws CronException
     */
    public function getTaskVoList();

    /**
     * 设置所有可运行的定时任务
     * @param TaskVo[] $taskVoList
     * @throws CronException
     */
    public function setTaskVoList(array $taskVoList);

    /**
     * 保存单个 TaskVo 数据
     * @param TaskVo $taskVo
     * @throws CronException
     */
    public function setTaskVo(TaskVo $taskVo);

    /**
     * 添加任务运行记录
     * @param TaskRecordVo $taskRecordVo 记录
     * @param int $limit 限制条数。0代表不限制
     * @throws CronException
     */
    public function addTaskRecordVo(TaskRecordVo $taskRecordVo, $limit = 0);

    /**
     * @return TaskRecordVo[]
     * @throws CronException
     */
    public function getTaskRecordVoList();
}