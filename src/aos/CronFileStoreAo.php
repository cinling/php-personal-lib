<?php


namespace cin\personalLib\aos;


use cin\personalLib\interfaces\CronTaskStoreInterface;
use cin\personalLib\services\FileCacheService;
use cin\personalLib\vos\BaseVo;
use cin\personalLib\vos\corn\TaskRecordVo;
use cin\personalLib\vos\corn\TaskVo;

/**
 * Class CronFileStoreAo 定时任务 文件存取 实现
 * @package cin\personalLib\aos
 */
class CronFileStoreAo extends BaseVo implements CronTaskStoreInterface {
    /**
     * 文件缓存的key
     */
    const CacheTaskVoList = "CronFileStore_CacheTaskVoList";
    /**
     * 运行记录保存的数据
     */
    const CacheTaskRecordVoList = "CronFileStore_CacheTaskRecordVoList";

    /**
     * @var FileCacheService 文件缓存服务
     */
    private $fileCacheSrv = null;

    public function onInit() {
        parent::onInit();
        $this->fileCacheSrv = FileCacheService::getIns();
    }

    /**
     * @return TaskVo[]
     */
    public function getTaskVoList() {
        $voList = $this->fileCacheSrv->get(self::CacheTaskVoList);
        if (empty($voList)) {
            $voList = [];
        }
        return $voList;
    }

    /**
     * @param TaskVo[] $taskVoList
     */
    public function setTaskVoList(array $taskVoList) {
        // 获取最大id
        $maxId = 0;
        foreach ($taskVoList as $taskVo) {
            if (!empty($taskVo->id) && $taskVo->id > $maxId) {
                $maxId = $taskVo->id;
            }
        }
        $lastId = $maxId + 1;
        // 分配id
        foreach ($taskVoList as $taskVo) {
            if (empty($taskVo->id)) {
                $taskVo->id = $lastId++;
            }
        }
        $this->fileCacheSrv->set(self::CacheTaskVoList, $taskVoList);
    }

    /**
     * @param TaskVo $taskVo
     */
    public function setTaskVo(TaskVo $taskVo) {
        $taskVoList = $this->getTaskVoList();
        foreach ($taskVoList as $key => $tmpTaskVo) {
            if ($tmpTaskVo->name === $taskVo->name) {
                $taskVoList[$key] = $taskVo;
                $this->setTaskVoList($taskVoList);
                return;
            }
        }

        // 如果没有找到相同的任务，则新写入一个数据
        $taskVoList[] = $taskVo;
        $this->setTaskVoList($taskVoList);
    }

    /**
     * @param TaskRecordVo $taskRecordVo
     * @param int $limit
     */
    public function addTaskRecordVo(TaskRecordVo $taskRecordVo, $limit = 0) {
        $voList = $this->getTaskRecordVoList();
        if ($limit > 0 && count($voList) >= $limit) {
            unset($voList[0]);
        }
        $voList[] = $taskRecordVo;

        $this->fileCacheSrv->set(self::CacheTaskRecordVoList, $voList);
    }

    /**
     * @return TaskRecordVo[]
     */
    public function getTaskRecordVoList() {
        $voList = $this->fileCacheSrv->get(self::CacheTaskRecordVoList);
        if (empty($voList)) {
            $voList = [];
        }
        return $voList;
    }
}