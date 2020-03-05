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

    /**
     * 根据uuid和数据更新某一实体
     * @param $uuid
     * @param $data
     * @return mixed
     */
    public function updateByUuidAndData($uuid, $data)
    {
        return $this->currentModel->isUpdate(true)->save($data, ["uuid" => $uuid]);
    }

    /**
     * 根据goodsUuid删除数据
     *
     * @param $goodsUuid
     * @return mixed
     */
    public function deleteByGoodsUuid($goodsUuid)
    {
        return $this->currentModel
            ->where("goods_uuid",$goodsUuid)
            ->update(array("is_delete" => DbDataIsDeleteEnum::YES));
    }

    /**
     * 生成uuid
     *
     * @param $length
     * @return string
     */
    public function getUuid($length)
    {
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $key = "";
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, 35)}; //生成php随机数
        }
        return $key;
    }
}
