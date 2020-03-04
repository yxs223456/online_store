<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-04
 * Time: 14:05
 */

namespace app\admin\controller;

use think\Db;

class Distributors extends Common
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

                case "phone":
                    $whereSql .= " and phone = '$value'";
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

        $list = $this->distributorsService->getListByCondition($condition);

        $this->assign('list', $list);

        return $this->fetch("list");
    }

    public function addPost() {

        $param = input('post.');

        $count = $this->distributorsService->findByMap(["phone"=>$param["phone"]]);
        if ($count) {
            $this->error('分销商已存在');
        }

        $param["uuid"] = getRandomString(6);
        $param["create_time"] = $param["update_time"] = time();

        Db::startTrans();
        try {

            $this->distributorsService->saveByData($param);

            Db::commit();

        } catch(\PDOException $e) {

            Db::rollback();

            $this->error('服务器错误,请稍后再试');

        }

        $this->success("添加成功",url("index"));

    }
}