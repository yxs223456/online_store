<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-02
 * Time: 11:33
 */


namespace app\admin\controller;

use app\common\enum\DbDataIsDeleteEnum;
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
}