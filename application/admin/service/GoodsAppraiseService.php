<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-02
 * Time: 17:08
 */

namespace app\admin\service;

use app\admin\model\GoodsAppraiseModel;
use app\common\enum\DbDataIsDeleteEnum;

class GoodsAppraiseService extends Base {

    public function __construct()
    {
        parent::__construct();
        $this->currentModel = new GoodsAppraiseModel();
    }

    public function getListByCondition($condition)
    {
        $list = $this->currentModel->alias("ga")
            ->leftJoin("user_base u", "ga.user_uuid=u.uuid")
            ->where($condition['whereSql'])
            ->where("ga.is_delete", DbDataIsDeleteEnum::NO)
            ->field("ga.*,u.account user_account")
            ->order('id desc')
            ->paginate(\config("paginate.list_rows"), false,
                ["query" => $condition['pageMap']]);

        return $list;
    }
}