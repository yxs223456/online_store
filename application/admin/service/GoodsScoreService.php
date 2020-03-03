<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-03
 * Time: 10:37
 */


namespace app\admin\service;

use app\admin\model\GoodsScoreModel;

class GoodsScoreService extends Base {

    public function __construct()
    {
        parent::__construct();
        $this->currentModel = new GoodsScoreModel();
    }

    public function activate($goodsAppraise)
    {
        $this->currentModel
            ->where("goods_uuid", $goodsAppraise["goods_uuid"])
            ->inc("goods_score", $goodsAppraise["goods_score"])
            ->inc("goods_appraise_times", 1)
            ->inc("service_score", $goodsAppraise["service_score"])
            ->inc("service_appraise_times", 1)
            ->inc("express_score", $goodsAppraise["express_score"])
            ->inc("express_appraise_times", 1)
            ->update(["update_time"=>time()]);
    }

    public function adminRevoke($goodsAppraise)
    {
        $this->currentModel
            ->where("goods_uuid", $goodsAppraise["goods_uuid"])
            ->dec("goods_score", $goodsAppraise["goods_score"])
            ->dec("goods_appraise_times", 1)
            ->dec("service_score", $goodsAppraise["service_score"])
            ->dec("service_appraise_times", 1)
            ->dec("express_score", $goodsAppraise["express_score"])
            ->dec("express_appraise_times", 1)
            ->update(["update_time"=>time()]);
    }
}