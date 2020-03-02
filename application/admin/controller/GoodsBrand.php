<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-02
 * Time: 11:33
 */


namespace app\admin\controller;

use app\common\enum\DbDataIsDeleteEnum;
use app\common\enum\GoodsBrandStatusEnum;
use think\Db;

class GoodsBrand extends Common
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

                case "name":
                    $whereSql .= " and name like '%$value%'";
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
        $condition = $this->convertRequestToWhereSql();

        $list = $this->goodsBrandService->getListByCondition($condition);

        $this->assign('list', $list);

        return $this->fetch("list");
    }

    public function add()
    {
        return $this->fetch();
    }

    public function addPost() {

        $param = input('post.');

        $param["uuid"] = getRandomString("10");
        $param["create_time"] = $param["update_time"] = time();

        Db::startTrans();
        try {

            $this->goodsBrandService->saveByData($param);

            Db::commit();

        } catch(\Throwable $e) {

            Db::rollback();

            $this->error('服务器错误,请稍后再试');

        }

        $this->success("添加成功");

    }

    public function edit() {

        $id = input('param.id');

        $info = $this->goodsBrandService->findById($id);

        $this->assign('info', $info);

        return $this->fetch();

    }


    public function editPost() {

        $param = input('post.');

        $param["update_time"] = time();

        Db::startTrans();
        try {

            $this->goodsBrandService->updateByIdAndData($param['id'], $param);

            Db::commit();

        } catch(\PDOException $e) {

            Db::rollback();

            $this->error('服务器错误,请稍后再试');

        }

        $this->success("编辑成功",url("index"));
    }

    public function delete() {

        $id = input('param.id');

        try {
            $updateData = [
                "is_delete" => DbDataIsDeleteEnum::YES,
                "update_time" => time(),
            ];

            $result = $this->goodsBrandService->updateByIdAndData($id, $updateData);

            if(false === $result) {
                $this->error($this->goodsBrandService->getError());
            }

            $this->success("删除成功", url('index'));

        }catch(\PDOException $e){
            $this->error($e->getMessage());
        }

    }


    public function activate() {

        $id = input('param.id');

        $goodsBrand = $this->goodsBrandService->findById($id);

        if($goodsBrand['status'] == GoodsBrandStatusEnum::YES) {
            $this->error("该品牌已是正常使用状态");
        }

        try {

            $this->goodsBrandService
                ->updateByIdAndData($id,["status"=>GoodsBrandStatusEnum::YES,"update_time"=>time()]);

            $this->success("已展示");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    public function deactivate() {

        $id = input('param.id');

        $goodsBrand = $this->goodsBrandService->findById($id);

        if($goodsBrand['status'] == GoodsBrandStatusEnum::FORBIDDEN) {
            $this->error("该品牌已是禁止使用状态");
        }

        try {
            $this->goodsBrandService
                ->updateByIdAndData($id,["status"=>GoodsBrandStatusEnum::FORBIDDEN,"update_time"=>time()]);

            $this->success("成功禁止");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }
}