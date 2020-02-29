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

    public function getSubClassifyCount($uuid)
    {
        $count = $this->currentModel
            ->where("parent_uuid", $uuid)
            ->where("is_delete", DbDataIsDeleteEnum::NO)
            ->count();

        return $count;
    }

    public function getAllParentUuid($uuid)
    {
        $data = [$uuid];
        $parentUuid = $this->currentModel->where("uuid", $uuid)->column("parent_uuid");
        if (empty($parentUuid[0])) {
            return $data;
        } else {
            return array_merge($this->getAllParentUuid($parentUuid[0]), $data);
        }
    }

    public function getNameByUuids(array $uuids)
    {
        return $this->currentModel->whereIn("uuid", $uuids)->column("name", "uuid");
    }
}
