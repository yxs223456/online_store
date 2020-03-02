<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-01
 * Time: 15:07
 */

namespace app\admin\service;

use app\admin\model\UserBaseModel;

class UserBaseService extends Base {

    public function __construct()
    {
        parent::__construct();
        $this->currentModel = new UserBaseModel();
    }
}