<?php


namespace cin\personalLib\services;


use cin\personalLib\exceptions\CronException;
use cin\personalLib\traits\SingleTrait;
use cin\personalLib\utils\CronParseUtil;
use cin\personalLib\utils\TimeUtil;
use cin\personalLib\vos\corn\CronConfigVo;
use cin\personalLib\vos\corn\TaskRecordVo;
use cin\personalLib\vos\corn\TaskVo;

/**
 * Class CronService 定时任务服务
 * @package cin\personalLib\services
 */
class CronService {
    use SingleTrait;

    /**
     * @var CronConfigVo 配置
     */
    private $configVo;

    protected function __construct() {
        $this->configVo = new CronConfigVo();
    }

    /**
     * 设置配置数据
     * @param CronConfigVo $configVo
     * @throws CronException
     */
    public function setConfigVo(CronConfigVo $configVo) {
        $this->configVo = $configVo;
        foreach ($configVo->taskVoList as $taskVo) {
            if (!CronParseUtil::check($taskVo->cronTime)) {
                throw new CronException("非法 cronTime 格式：" . $taskVo->cronTime);
            }
        }
    }

    /**
     * 初始化（重置任务列表）
     * @throws CronException
     */
    public function init() {
        // 当前系统中存储的任务列表
        $taskVoDict = $this->getTaskVoDict();
        foreach ($taskVoDict as $newTaskVo) {
            $newTaskVo->active = TaskVo::ActiveOff;
        }
        // 配置中的任务
        $newTaskVoList = $this->configVo->taskVoList;
        foreach ($newTaskVoList as $newTaskVo) {
            if (!isset($taskVoDict[$newTaskVo->name])) { // 新增
                $taskVoDict[$newTaskVo->name] = $newTaskVo;
            } else { // 更新定时任务配置
                $taskVoDict[$newTaskVo->name]->command = $newTaskVo->command;
                $taskVoDict[$newTaskVo->name]->cronTime = $newTaskVo->cronTime;
            }
            $taskVo = $taskVoDict[$newTaskVo->name];
            $taskVo->active = TaskVo::ActiveOn;
            if (empty($taskVo->state)) {
                $taskVo->state = TaskVo::StateEnd;
            }
            if (empty($taskVo->nextRunAt)) {
                $taskVo->nextRunAt = CronParseUtil::getNextRunAt($taskVo->cronTime);
            }
        }

        $this->configVo->store->setTaskVoList(array_values($taskVoDict));
    }

    /**
     * 运行
     * @throws CronException
     */
    public function run() {
        $taskVoDict = $this->getRunTaskVoDict();

        $startMS = TimeUtil::stampMS();

        /** @var resource[] $procTaskIdDict 进程字典 */
        $procTaskIdDict = [];
        /** @var mixed $pipesDict 渠道字典 */
        $pipesDict = [];
        foreach ($taskVoDict as $taskVo) {
            $pipes = [["pipe" => "w"]];
            $process = proc_open($taskVo->command, [],  $pipesDict[$taskVo->id]);
            if (!is_resource($process)) {
                $this->addFailRecord($taskVo, $startMS); // TODO 未测试
                continue;
            }
            $taskVo->state = TaskVo::StateRunning;
            $procTaskIdDict[$taskVo->id] = $process;
            $pipesDict[$taskVo->id] = $pipes;
        }

        while (count($procTaskIdDict)) {
            foreach ($procTaskIdDict as $taskId => $process) {
                $status = proc_get_status($process);
                if ($status['running'] == FALSE) { // 不是正在运行的状态，说明已经运行结束

                    $exitCode = $status["exitcode"];
                    proc_close($process);
                    unset($procTaskIdDict[$taskId]);

                    $taskVo = $taskVoDict[$taskId];
                    $taskVo->state = TaskVo::StateEnd;
                    $taskVo->lastRunAt = TimeUtil::stamp();
                    $taskVo->nextRunAt = CronParseUtil::getNextRunAt($taskVo->cronTime);
                    $taskVo->updateAt = TimeUtil::stamp();
                    $this->configVo->store->setTaskVo($taskVo);

                    if ($exitCode < 0) {
                        $this->addFailRecord($taskVo, $startMS);
                    } else {
                        $this->addDoneRecord($taskVo, $startMS);
                    }
                }
            }

            usleep(100);
        }
    }

    /**
     * 添加运行失败的记录
     * @param TaskVo $taskVo 任务
     * @param float $startMS 开始运行的时间
     * @throws CronException
     */
    private function addFailRecord(TaskVo $taskVo, $startMS) {
        $recordVo = new TaskRecordVo();
        $recordVo->taskId = $taskVo->id;
        $recordVo->state = TaskRecordVo::StateFail;
        $recordVo->useMS = TimeUtil::stampMS() - $startMS;
        $this->configVo->store->addTaskRecordVo($recordVo, 0);
    }

    /**
     * 添加运行成功的记录
     * @param TaskVo $taskVo 任务
     * @param float $startMS 开始运行的时间
     * @throws CronException
     */
    private function addDoneRecord(TaskVo $taskVo, $startMS) {
        $recordVo = new TaskRecordVo();
        $recordVo->taskId = $taskVo->id;
        $recordVo->state = TaskRecordVo::StateDone;
        $recordVo->useMS = TimeUtil::stampMS() - $startMS;
        $this->configVo->store->addTaskRecordVo($recordVo, 0);
    }

    /**
     * 获取需要运行的任务列表
     * @return TaskVo[] id => $taskVo
     * @throws CronException
     */
    protected function getRunTaskVoDict() {
        $taskVoDict = $this->getTaskVoDict();
        $runTaskVoDict = [];
        $now = TimeUtil::stamp();
        foreach ($taskVoDict as $taskVo) {
            if ($taskVo->nextRunAt <= $now && !$taskVo->isRunning()) {
                $runTaskVoDict[$taskVo->id] = $taskVo;
            }
        }
        return $runTaskVoDict;
    }

    /**
     * 获取所有任务列表。并转为 name => $taskVo 的字典
     * @return TaskVo[]
     * @throws CronException
     */
    protected function getTaskVoDict() {
        $taskVoList = $this->configVo->store->getTaskVoList();
        $taskVoDict = [];
        foreach ($taskVoList as $taskVo) {
            $taskVoDict[$taskVo->name] = $taskVo;
        }
        return $taskVoDict;
    }

}