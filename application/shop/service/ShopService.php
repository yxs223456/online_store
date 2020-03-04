<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-03
 * Time: 16:23
 */

namespace app\shop\service;

use app\shop\model\ShopModel;

class ShopService extends Base
{

    public function __construct()
    {
        parent::__construct();
        $this->currentModel = new ShopModel();
    }

    public function findShopForLogin($account) {

        $where['account'] = $account;
        $info = $this->currentModel
            ->where($where)
            ->find();
        return $info;

    }
}