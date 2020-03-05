<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-02-28
 * Time: 20:54
 */

namespace app\admin\service;

use app\admin\model\GoodsSpec;
use app\common\enum\DbDataIsDeleteEnum;

class GoodsSpecService extends Base
{

    public function __construct()
    {
        parent::__construct();
        $this->currentModel = new GoodsSpec();
    }

    /**
     * 获取插入数据库数据
     *
     * @param $data
     * @param $goods
     * @return array
     */
    public function getInsertData($data, $goods)
    {
        unset($data["goods_uuid"]);
        $isDefault = isset($data["is_default"][0]) ? $data["is_default"][0] : "";
        unset($data["is_default"]);

        $baseData = array(
            'goods_uuid' => $goods["uuid"],
            'sale_num' => 0,
            'create_time' => time(),
            'update_time' => time()
        );

        $res = [];

        $keys = array_keys($data);
        $values = array_values($data);
        for ($i = 0;$i < count(array_pop($data));$i ++) {
            $res[] = $baseData + array_combine($keys,array_column($values,$i));
        }

        foreach ($res as &$item) {
            if($item["goods_spec_item_uuids"] == $isDefault) {
                $item["is_default"] = 1;
            } else {
                $item["is_default"] = 0;
            }
        }

        if (array_sum(array_column($res,"is_default")) < 1) {
            $res[0]["is_default"] = 1;
        }
        return $res;
    }

    public function deleteByUuid($goodsUuid)
    {
        return $this->currentModel->update(array("is_delete" => DbDataIsDeleteEnum::YES),array("goods_uuid" => $goodsUuid));
    }

    public function insertMany($data)
    {
        return $this->currentModel->insertAll($data);
    }

    public function getSpecUsedItemUuids($goodsUuid)
    {
        $data = $this->currentModel
            ->where("is_delete",DbDataIsDeleteEnum::NO)
            ->where("goods_uuid",$goodsUuid)
            ->column("goods_spec_item_uuids");
        $res = [];
        foreach ($data as $value) {
            $d = explode("_",$value);
            $res = array_merge($res, $d);
        }
        return array_unique($res);
    }

    public function findByGoodsUuid($goodsUuid)
    {
        return $this->currentModel
            ->where("is_delete",DbDataIsDeleteEnum::NO)
            ->where("goods_uuid",$goodsUuid)
            ->column("product_no,goods_spec_item_uuids,market_price,shop_price,stock,sale_num,is_default");
    }

    public function formatList($list,$itemData) {
        foreach ($list as &$value) {
            foreach (array_reverse(explode("_",$value["goods_spec_item_uuids"])) as $item) {
                array_unshift($value,$itemData[$item]);
            }
        }
        return $list;
    }

    public function deleteByItemUuid($itemUuid)
    {
        return $this->currentModel
            ->whereLike("goods_spec_item_uuids",$itemUuid)
            ->update(array("is_delete" => DbDataIsDeleteEnum::YES));
    }
}