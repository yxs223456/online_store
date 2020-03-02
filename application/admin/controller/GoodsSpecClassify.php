<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-02-28
 * Time: 21:02
 */

namespace app\admin\controller;

use app\common\enum\DbDataIsDeleteEnum;
use app\common\enum\GoodsSpecAllowImageEnum;
use app\common\enum\GoodsSpecClassifyIsShowEnum;
use org\LeftNav;
use think\Db;

class GoodsSpecClassify extends Common
{
    public function convertRequestToWhereSql()
    {

        $whereSql = " 1=1";
        $pageMap = [];

        $params = input("param.");

        foreach ($params as $key => $value) {

            if ($value == "-999"
                || isNullOrEmpty($value))
                continue;

            switch ($key) {

                case "name":
                    $whereSql .= " and name LIKE '%" . $value . "%'";
                    break;

            }

            $pageMap[$key] = $value;
            $this->assign($key, $value);

        }

        $data["whereSql"] = $whereSql;
        $data["pageMap"] = $pageMap;

        return $data;
    }

    //列表
    public function index()
    {
        $condition = $this->convertRequestToWhereSql();
        $list = $this->goodsSpecClassifyService->getListByCondition($condition);
        $allSpecClassifyArr = [];
        foreach ($list as $item) {
            $item["goods_classify_uuids"] = explode("_", $item["goods_classify_uuids"]);
            foreach ($item["goods_classify_uuids"] as $goodsClassifyUuid) {
                $allSpecClassifyArr[] = $goodsClassifyUuid;
            }
        }
        $allSpecClassifyArr = array_unique($allSpecClassifyArr);
        if ($allSpecClassifyArr) {
            $goodsClassifyName = $this->goodsClassifyService->getNameByUuids($allSpecClassifyArr);

            foreach ($list as $item) {
                $goodsClassifyUuids = [];
                foreach ($item["goods_classify_uuids"] as $goods_classify_uuid) {
                    $goodsClassifyUuids[] = $goodsClassifyName[$goods_classify_uuid];
                }
                $item["goods_classify_uuids"] = implode("→", $goodsClassifyUuids);
            }
        }
        $this->assign('list', $list);


        $nav = new LeftNav();
        $goodsClassify = $this->goodsClassifyService->getALlGoodsClassify();
        $arr = $nav::goodsClassifyRule($goodsClassify);
        $this->assign('goodsClassify',$arr);

        return $this->fetch("list");
    }

    //添加规格
    public function addPost() {

        $param = input('post.');

        if ($param["allow_image"] == GoodsSpecAllowImageEnum::YES){
            $count = $this->goodsSpecClassifyService
                ->getAllowImageCountByGoodsClassify($param["goods_classify_uuid"]);

            if ($count > 0) {
                $this->error('同一分类下已存在允许上传图片规格类型，请先修改之后再保存');
            }
        }

        $allParentUuid = $this->goodsClassifyService->getAllParentUuid($param["goods_classify_uuid"]);
        $param["goods_classify_uuids"] = implode("_", $allParentUuid);

        $param["uuid"] = getRandomString("8");
        $param["create_time"] = $param["update_time"] = time();

        Db::startTrans();
        try {

            $this->goodsSpecClassifyService->saveByData($param);

            Db::commit();

        } catch(\PDOException $e) {

            Db::rollback();

            $this->error('服务器错误,请稍后再试');

        }

        $this->success("规格添加成功",url("index"));

    }

    public function edit() {

        $id = input('param.id');

        $goodsClassify = $this->goodsClassifyService->getALlGoodsClassify();

        $nav = new LeftNav();

        $arr = $nav::goodsClassifyRule($goodsClassify);

        $this->assign('goodsSpecClassifyInfo',$this->goodsSpecClassifyService->findById($id));

        $this->assign('goodsClassify',$arr);

        return $this->fetch();
    }

    public function editPost() {

        $param = input('post.');

        if ($param["allow_image"] == GoodsSpecAllowImageEnum::YES){
            $count = $this->goodsSpecClassifyService
                ->getAllowImageCountByGoodsClassify($param["goods_classify_uuid"]);

            if ($count > 0 && $param["old_allow_image"] != GoodsSpecAllowImageEnum::YES) {
                $this->error('同一分类下已存在允许上传图片规格类型，请先修改之后再保存');
            }
        }

        $allParentUuid = $this->goodsClassifyService->getAllParentUuid($param["goods_classify_uuid"]);
        $param["goods_classify_uuids"] = implode("_", $allParentUuid);
        $param["update_time"] = time();

        unset($param["old_allow_image"]);

        Db::startTrans();
        try {

            $this->goodsSpecClassifyService->updateByIdAndData($param['id'], $param);

            Db::commit();

        } catch(\PDOException $e) {

            Db::rollback();

            $this->error('服务器错误,请稍后再试');

        }

        $this->success("修改成功",url("index"));

    }

    public function delete() {

        $id = input('param.id');

        try {
            $updateData = [
                "is_delete" => DbDataIsDeleteEnum::YES,
                "update_time" => time(),
            ];

            $result = $this->goodsSpecClassifyService->updateByIdAndData($id, $updateData);

            if(false === $result) {
                $this->error($this->goodsSpecClassifyService->getError());
            }

            $this->success("删除成功", url('index'));

        }catch(\PDOException $e){
            $this->error($e->getMessage());
        }

    }

    public function activate() {

        $id = input('param.id');

        $goodsSpecClassifyInfo = $this->goodsSpecClassifyService->findById($id);

        if($goodsSpecClassifyInfo['is_show'] == GoodsSpecClassifyIsShowEnum::YES) {
            $this->error("该分类已是展示状态");
        }

        try {

            $result = $this->goodsSpecClassifyService
                ->updateByIdAndData($id,["is_show"=>GoodsSpecClassifyIsShowEnum::YES,"update_time"=>time()]);

            if(false === $result) {
                $this->error($this->goodsSpecClassifyService->getError());
            }

            $this->success("已展示");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    public function deactivate() {

        $id = input('param.id');

        $goodsSpecClassifyInfo = $this->goodsSpecClassifyService->findById($id);

        if($goodsSpecClassifyInfo['is_show'] == GoodsSpecClassifyIsShowEnum::NO) {
            $this->error("该分类已是隐藏状态");
        }

        try {
            $result = $this->goodsSpecClassifyService
                ->updateByIdAndData($id,["is_show"=>GoodsSpecClassifyIsShowEnum::NO,"update_time"=>time()]);

            if($result === false) {
                $this->error($this->goodsSpecClassifyService->getError());
            }

            $this->success("已隐藏");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

}