<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-02
 * Time: 11:30
 */

namespace app\admin\service;

use app\admin\model\GoodsBrandModel;

class GoodsBrandService extends Base {

    public function __construct()
    {
        parent::__construct();
        $this->currentModel = new GoodsBrandModel();
    }
}