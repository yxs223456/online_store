<?php

namespace app\admin\service;
use app\common\enum\DbDataIsDeleteEnum;
use com\BaseServiceTrait;
use think\Model;
use think\Db;

class Base extends Model {

    use BaseServiceTrait;

    protected $currentModel;

    /**
     * service层数据返回
     * @param $code
     * @param $msg
     * @param $data
     * @return \think\response\Json
     */
    public function serviceReturn($code,$msg,$data) {
        $returnData["code"] = $code;
        $returnData["msg"] = $msg;
        $returnData["data"] = $data;

        return $returnData;
    }

    public function getListByCondition($condition)
    {

        $list = $this->currentModel
            ->where($condition['whereSql'])
            ->where("is_delete", DbDataIsDeleteEnum::NO)
            ->order('id desc')
            ->paginate(\config("paginate.list_rows"), false,
                ["query" => $condition['pageMap']]);

        return $list;
    }

    public function getTableName()
    {
        return $this->currentModel->getTable();
    }
}
