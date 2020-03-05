<?php

namespace app\admin\service;

use app\admin\model\Goods;
use app\common\enum\DbDataIsDeleteEnum;

class GoodsService extends Base {

    public function __construct()
    {
        parent::__construct();
        $this->currentModel = new Goods();
    }

    public function findByUuid($uuid) {
        return $this->currentModel
            ->where("is_delete",DbDataIsDeleteEnum::NO)
            ->where("uuid",$uuid)->find();
    }

    public function updateByAllowFieldAndUuid($data,$uuid,$allowField=true) {
        return $this->currentModel->isUpdate(true)->allowField($allowField)->save($data,["uuid" => $uuid]);
    }

    /**
     * 获取商品的分类的所有规格
     * 1. 上传图片的规格只能包含一个，默认包含最小分类的上传图片规格
     *
     * @param array $uuids
     * @param array $data
     * @return array
     */
    public function getClassifyData(array $uuids,array $data) {
        $uuids = array_reverse($uuids);
        $res = [];
        $image = true;
        foreach ($uuids as $value) {
            $ures = [];
            foreach ($data as $k => $v) {
                if ($v["goods_classify_uuid"] == $value) {
                    if ($v["allow_image"] == 1) {
                        if ($image) {
                            $ures[] = $v;
                            $image = false;
                            continue;
                        } else {
                            continue;
                        }
                    }
                    $ures[] = $v;
                }
            }
            $res[] = $ures;
        }
        return array_reduce(array_filter($res), 'array_merge', array());
    }

    /**
     * 获取销售规格数组
     *
     * @param $items
     * @return array
     */
    public function getSaleForm($items)
    {
        // 切割分组
        $data = [];
        foreach($items as $item) {
            $data[] = explode("_",$item);
        }

        // 按照classify分组
        $dataN = [];
        $goodsItems = [];
        foreach ($data as $item) {
            $classifyUuid = array_shift($item);
            $itemUuid = array_shift($item);
            $dataN[$classifyUuid][] = $itemUuid;
            $goodsItems[$itemUuid] = array_shift($item);
        }
        $goodsClassifyUuid = array_keys($dataN);
        // 组装合并规格数据组成 itemuuid_itemuuid_itemuuid 格式数组
        $first = array_shift($dataN);
        foreach ($dataN as $value) {
            $first = $this->merge2array($first,$value);
        }

        $r = [];
        $sale = $this->getSaleArr($first,$goodsItems);
        $sale = array_combine($first,$sale);
        $r["sale"] = $sale;
        $r["goodsClassify"] = $goodsClassifyUuid;

        return  $r;
    }

    /**
     * 合并数组
     *
     * @param $item1
     * @param $item2
     * @return array
     */
    private function merge2array($item1, $item2)
    {
        $data = [];
        foreach ($item1 as $v1) {
            foreach ($item2 as $v2) {
                $data[] = $v1."_".$v2;
            }
        }
        return $data;
    }

    private function getSaleArr($first, $goodsItems)
    {
        $data = [];
        foreach ($first as $value) {
            $item = [];
            $itemUuids = explode("_",$value);
            foreach ($itemUuids as $v) {
                $item[] = $goodsItems[$v];
            }
            $data[] = $item;
        }
        return $data;
    }

    public function filterNullString($var) {
        if ($var === "" || $var === null) {
            return false;
        }
        return true;
    }

    public function filterNullStringZero($var) {
        if ($var === "" || $var === null || $var === 0 || $var === "0") {
            return false;
        }
        return true;
    }
}