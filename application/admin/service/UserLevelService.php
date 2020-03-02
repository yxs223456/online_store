<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-01
 * Time: 17:06
 */

namespace app\admin\service;

use app\admin\model\UserLevelConfigModel;
use app\common\enum\DbDataIsDeleteEnum;

class UserLevelService extends Base {

    public function __construct()
    {
        parent::__construct();
        $this->currentModel = new UserLevelConfigModel();
    }

    public function getAllLevel()
    {
        return $this->currentModel
            ->where("is_delete", DbDataIsDeleteEnum::NO)
            ->select()
            ->toArray();
    }

    public function getListByCondition($condition)
    {

        $list = $this->currentModel
            ->where($condition['whereSql'])
            ->where("is_delete", DbDataIsDeleteEnum::NO)
            ->order('start_score asc')
            ->paginate(\config("paginate.list_rows"), false,
                ["query" => $condition['pageMap']]);

        return $list;
    }
}