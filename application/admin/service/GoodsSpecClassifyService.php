<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-02-28
 * Time: 20:54
 */

namespace app\admin\service;

use app\admin\model\GoodsSpecClassifyModel;
use app\common\enum\DbDataIsDeleteEnum;
use app\common\enum\GoodsSpecAllowImageEnum;

class GoodsSpecClassifyService extends Base
{

    public function __construct()
    {
        parent::__construct();
        $this->currentModel = new GoodsSpecClassifyModel();
    }

    public function getAllowImageCountByGoodsClassify($goodsClassifyUuid)
    {
        return $this->currentModel
            ->where("goods_classify_uuid",$goodsClassifyUuid)
            ->where("allow_image", GoodsSpecAllowImageEnum::YES)
            ->count();
    }

    public function getListByCondition($condition)
    {

        $list = $this->currentModel
            ->where($condition['whereSql'])
            ->where("is_delete", DbDataIsDeleteEnum::NO)
            ->order('goods_classify_uuid')
            ->paginate(\config("paginate.list_rows"), false,
                ["query" => $condition['pageMap']]);

        return $list;
    }
}