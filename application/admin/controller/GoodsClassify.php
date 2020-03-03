<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-02-28
 * Time: 17:16
 */

namespace app\admin\controller;

use app\common\enum\DbDataIsDeleteEnum;
use app\common\enum\GoodsClassifyIsShowEnum;
use org\LeftNav;
use think\Db;

class GoodsClassify extends Common
{
    //填空题列表
    public function index()
    {

        $nav = new LeftNav();

        $list = $this->goodsClassifyService->getALlGoodsClassify();

        $arr = $nav::goodsClassifyRule($list);

        $this->assign('list',$arr);

        return $this->fetch("list");
    }

    public function addPost() {

        $param = input('post.');
        $param["uuid"] = getRandomString("8");
        $param["create_time"] = $param["update_time"] = time();

        Db::startTrans();
        try {

            $this->goodsClassifyService->saveByData($param);

            Db::commit();

        } catch(\PDOException $e) {

            Db::rollback();

            $this->error('服务器错误,请稍后再试');

        }

        $this->success("菜单添加成功",url("index"));

    }

    public function edit() {

        $id = input('param.id');

        $list = $this->goodsClassifyService->getALlGoodsClassify();

        $nav = new LeftNav();

        $arr = $nav::goodsClassifyRule($list);

        $this->assign('goodsClassify',$this->goodsClassifyService->findById($id));

        $this->assign('list',$arr);

        return $this->fetch();

    }

    public function editPost() {

        $param = input('post.');

        $param["update_time"] = time();
        Db::startTrans();
        try {

            $this->goodsClassifyService->updateByIdAndData($param['id'], $param);

            Db::commit();

        } catch(\PDOException $e) {

            Db::rollback();

            $this->error('服务器错误,请稍后再试');

        }

        $this->success("编辑成功",url("index"));

    }

    public function delete() {

        $uuid = input('param.uuid');

        $subCount = $this->goodsClassifyService->getSubClassifyCount($uuid);

        if($subCount != 0) {
            $this->error("请首先删除子分类");
        }

        try {

            $map["uuid"] = $uuid;
            $updateData = [
                "is_delete" => DbDataIsDeleteEnum::YES,
                "update_time" => time(),
            ];

            $result = $this->goodsClassifyService->updateByMapAndData($map, $updateData);

            if(false === $result) {
                $this->error($this->goodsClassifyService->getError());
            }

            $this->success("分类删除成功", url('index'));

        }catch(\PDOException $e){
            $this->error($e->getMessage());
        }

    }

    public function order() {

        $param = input('post.');

        $goods_classify = Db::name($this->goodsClassifyService->getTableName());

        foreach ($param as $id => $weight){
            $goods_classify->where(array('id' => $id ))->setField('weight',$weight);
        }

        $this->success("排序更新成功");

    }

    public function activate() {

        $id = input('param.id');

        $goodsClassify = $this->goodsClassifyService->findById($id);

        if($goodsClassify['is_show'] == GoodsClassifyIsShowEnum::YES) {
            $this->error("该分类已是展示状态");
        }

        try {

            $result = $this->goodsClassifyService
                ->updateByIdAndData($id,["is_show"=>GoodsClassifyIsShowEnum::YES,"update_time"=>time()]);

            if(false === $result) {
                $this->error($this->goodsClassifyService->getError());
            }

            $this->success("已展示");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    public function deactivate() {

        $id = input('param.id');

        $goodsClassify = $this->goodsClassifyService->findById($id);

        if($goodsClassify['is_show'] == GoodsClassifyIsShowEnum::NO) {
            $this->error("该分类已是隐藏状态");
        }

        try {
            $result = $this->goodsClassifyService
                ->updateByIdAndData($id,["is_show"=>GoodsClassifyIsShowEnum::NO,"update_time"=>time()]);

            if($result === false) {
                $this->error($this->goodsClassifyService->getError());
            }

            $this->success("已隐藏");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }
}