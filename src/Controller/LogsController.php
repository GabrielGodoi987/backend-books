<?php

namespace Backend\Products\Controller;

use Backend\Products\Enum\LogsEnum;
use Backend\Products\Model\LogsModel;

class LogsController
{

    private $logsModel;
    public function __construct()
    {
        $this->logsModel = new LogsModel();
    }
    public function getAllLogs()
    {
        echo $this->logsModel->getAllLogs();
    }

    public function createNewLog($productData)
    {
        echo $this->logsModel->createLog($productData, LogsEnum::CREATION);
    }

    public function getLogById($productId)
    {
        echo $this->logsModel->findLogOfAProduct($productId);
    }
}
