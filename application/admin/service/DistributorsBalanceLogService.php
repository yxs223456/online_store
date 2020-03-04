<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-04
 * Time: 16:11
 */

namespace app\admin\service;

use app\admin\model\DistributorsBalanceLogModel;

class DistributorsBalanceLogService extends Base {

    public function __construct()
    {
        parent::__construct();
        $this->currentModel = new DistributorsBalanceLogModel();
    }
}