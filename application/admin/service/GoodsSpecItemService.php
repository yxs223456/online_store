<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-02-28
 * Time: 20:54
 */

namespace app\admin\service;

use app\admin\model\GoodsSpecClassifyModel;
use app\admin\model\GoodsSpecItem;
use app\common\enum\DbDataIsDeleteEnum;
use app\common\enum\GoodsSpecAllowImageEnum;

class GoodsSpecItemService extends Base
{

    public function __construct()
    {
        parent::__construct();
        $this->currentModel = new GoodsSpecItem();
    }


    public function getItemByGoodsUuid($uuid)
    {
        return $this->currentModel
            ->where("is_delete",DbDataIsDeleteEnum::NO)
            ->whereIn("goods_uuid", $uuid)->all()->toArray();
    }

    public function getClassifyUuid($uuids) {
        return $this->currentModel
            ->where("is_delete",DbDataIsDeleteEnum::NO)
            ->whereIn("uuid",$uuids)->column("goods_spec_classify");
    }
}