<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-04
 * Time: 17:04
 */

namespace app\admin\service;

use app\admin\model\UserScoreLogModel;

class UserScoreLogService extends Base {

    public function __construct()
    {
        parent::__construct();
        $this->currentModel = new UserScoreLogModel();
    }
}