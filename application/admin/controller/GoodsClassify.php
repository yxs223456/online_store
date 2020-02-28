<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-02-28
 * Time: 17:16
 */

namespace app\admin\controller;

use org\LeftNav;
use think\Db;

class GoodsClassify extends Common
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

                case "question":
                    $whereSql .= " and question LIKE '%" . $value . "%'";
                    break;

            }

            $pageMap[$key] = $value;
            $this->assign($key, $value);

        }

        $data["whereSql"] = $whereSql;
        $data["pageMap"] = $pageMap;

        return $data;

    }

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

        Db::startTrans();
        try {

            $this->goodsClassifyService->updateByIdAndData($param['id'], $param);

            Db::commit();

        } catch(\PDOException $e) {

            Db::rollback();

            $this->error('服务器错误,请稍后再试');

        }

        $this->success("菜单分类成功",url("index"));

    }

    public function order() {

        $param = input('post.');

        $goods_classify = Db::name('goods_classify');

        foreach ($param as $id => $weight){
            $goods_classify->where(array('id' => $id ))->setField('weight',$weight);
        }

        $this->success("排序更新成功");

    }

    public function activate() {

        $id = input('param.id');

        $menu = $this->goodsClassifyService->findById($id);

        if($menu['is_show'] == 1) {
            $this->error("该分类已是展示状态");
        }

        try {

            $result = $this->goodsClassifyService->updateByIdAndData($id,["is_show"=>1]);

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

        $menu = $this->goodsClassifyService->findById($id);

        if($menu['is_show'] == 0) {
            $this->error("该分类已是隐藏状态");
        }

        try {
            $result = $this->goodsClassifyService->updateByIdAndData($id,["is_show"=>0]);

            if($result === false) {
                $this->error($this->goodsClassifyService->getError());
            }

            $this->success("已隐藏");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }
}