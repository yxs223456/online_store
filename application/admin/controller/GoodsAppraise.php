<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-02
 * Time: 17:11
 */

namespace app\admin\controller;


use app\common\enum\GoodsAppraiseStatusEnum;
use think\Db;

class GoodsAppraise extends Common
{
    public function convertRequestToWhereSql()
    {

        $whereSql = " 1=1";
        $pageMap = [];

        $params = input("param.");

        foreach ($params as $key => $value) {

            if ($value == "-999" || $value === "")
                continue;

            switch ($key) {

                case "goods_uuid":
                    $whereSql .= " and ga.goods_uuid = '$value'";
                    break;

                case "goods_score":
                    if (is_numeric($value)) {
                        $value = (int) $value;
                        $whereSql .= " and ga.goods_score = '$value'";
                    }
                    break;

            }

            $pageMap[$key] = $value;
            $this->assign($key, $value);

        }

        $data["whereSql"] = $whereSql;
        $data["pageMap"] = $pageMap;

        return $data;
    }

    public function index()
    {
        $goodsUuid = input("goods_uuid");
        if (empty($goodsUuid)) {
            throw new \Exception("goods_uuid empty");
        }

        $condition = $this->convertRequestToWhereSql();

        $list = $this->goodsAppraiseService->getListByCondition($condition);

        foreach ($list as $item) {
            $item["images"] = explode(",", $item["images"]);
        }

        $this->assign('list', $list);

        if (input("goods_score") === null || input("goods_score") === "") {
            $this->assign("goods_score", "");
        }

        return $this->fetch("list");
    }

    public function shopReply()
    {
        $id = input('param.id');

        $goodsAppraise = $this->goodsAppraiseService->findById($id);

        if($goodsAppraise['shop_reply'] != "") {
            $this->error("店铺已回复改评论");
        }

        $shopReply = input("shop_reply");

        $data = [
            "shop_reply" => $shopReply,
            "shop_reply_time" => time(),
            "update_time" => time(),
        ];

        try {
            $this->goodsAppraiseService
                ->updateByIdAndData($id,$data);

            $this->success("回复成功");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    public function activate() {

        $id = input('param.id');

        $goodsAppraise = $this->goodsAppraiseService->findById($id);

        if($goodsAppraise['status'] == GoodsAppraiseStatusEnum::NORMAL) {
            $this->error("该评论已是正常状态");
        }

        Db::startTrans();
        try {

            $this->goodsAppraiseService
                ->updateByIdAndData($id,["status"=>GoodsAppraiseStatusEnum::NORMAL,"update_time"=>time()]);

            $this->goodsScoreService->activate($goodsAppraise);

            Db::commit();
            $this->success("已展示");

        } catch (\PDOException $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

    }

    public function adminRevoke() {

        $id = input('param.id');

        $goodsAppraise = $this->goodsAppraiseService->findById($id);

        if($goodsAppraise['status'] != GoodsAppraiseStatusEnum::NORMAL) {
            $this->error("该评论已是撤销状态");
        }

        Db::startTrans();
        try {
            $this->goodsAppraiseService
                ->updateByIdAndData($id,["status"=>GoodsAppraiseStatusEnum::ADMIN_REVOKE,"update_time"=>time()]);

            $this->goodsScoreService->adminRevoke($goodsAppraise);

            Db::commit();
            $this->success("已撤销");

        } catch (\PDOException $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

    }
}