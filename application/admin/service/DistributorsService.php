<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-04
 * Time: 14:01
 */

namespace app\admin\service;

use app\admin\model\DistributorsModel;

class DistributorsService extends Base {

    public function __construct()
    {
        parent::__construct();
        $this->currentModel = new DistributorsModel();
    }
}