<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-01
 * Time: 17:04
 */


namespace app\admin\controller;

use app\common\enum\DbDataIsDeleteEnum;
use think\Db;

class UserLevel extends Common
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

                case "account":
                    $whereSql .= " and account = '$value'";
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

        $list = $this->userLevelService->getListByCondition($condition);

        $this->assign('list', $list);

        return $this->fetch("list");
    }

    public function addPost() {

        $param = input('post.');

        $allLevel = $this->userLevelService->getAllLevel();

        foreach ($allLevel as $level) {
            if (!($param["start_score"] > $level["end_score"] || $param["start_score"] < $level["start_score"])) {
                $this->error("积分范围和已存在等级有重叠");
            }
            if (!($param["end_score"] > $level["end_score"] || $param["end_score"] < $level["start_score"])) {
                $this->error("积分范围和已存在等级有重叠");
            }
        }
        $param["create_time"] = $param["update_time"] = time();


        Db::startTrans();
        try {

            $this->userLevelService->saveByData($param);

            Db::commit();

        } catch(\PDOException $e) {

            Db::rollback();

            $this->error('服务器错误,请稍后再试');

        }

        $this->success("菜单添加成功",url("index"));

    }

    public function edit() {

        $id = input('param.id');

        $info = $this->userLevelService->findById($id);

        $this->assign('info', $info);

        return $this->fetch();

    }

    public function editPost() {

        $param = input('post.');

        $allLevel = $this->userLevelService->getAllLevel();

        foreach ($allLevel as $level) {
            if ($level["id"] == $param["id"]) {
                continue;
            }

            if (!($param["start_score"] > $level["end_score"] || $param["start_score"] < $level["start_score"])) {
                $this->error("积分范围和已存在等级有重叠");
            }
            if (!($param["end_score"] > $level["end_score"] || $param["end_score"] < $level["start_score"])) {
                $this->error("积分范围和已存在等级有重叠");
            }
        }

        $param["update_time"] = time();

        Db::startTrans();
        try {

            $this->userLevelService->updateByIdAndData($param['id'], $param);

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

            $result = $this->userLevelService->updateByIdAndData($id, $updateData);

            if(false === $result) {
                $this->error($this->userLevelService->getError());
            }

            $this->success("删除成功", url('index'));

        }catch(\PDOException $e){
            $this->error($e->getMessage());
        }

    }

}