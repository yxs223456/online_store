<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-02-28
 * Time: 17:15
 */

namespace app\admin\service;

use app\admin\model\GoodsClassifyModel;
use app\common\enum\DbDataIsDeleteEnum;

class GoodsClassifyService extends Base
{

    public function __construct()
    {
        parent::__construct();
        $this->currentModel = new GoodsClassifyModel();
    }

    public function getALlGoodsClassify()
    {
        $list = $this->currentModel
            ->where("is_delete", DbDataIsDeleteEnum::NO)
            ->order("weight", "asc")
            ->select();

        return object2array($list);
    }
}