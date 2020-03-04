<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-04
 * Time: 17:06
 */

namespace app\admin\controller;

use app\common\enum\UserScoreLogAddTypeEnum;
use app\common\enum\UserScoreLogReduceTypeEnum;
use app\common\enum\UserScoreLogTypeEnum;

class UserScoreLog extends Common
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

                case "user_uuid":
                    $whereSql .= " and user_uuid = '$value'";
                    break;

                case "log_type":
                    $whereSql .= " and type = '$value'";
                    break;

                case "add_type":
                    $whereSql .= " and add_type = '$value'";
                    break;

                case "reduce_type":
                    $whereSql .= " and reduce_type = '$value'";
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

        $list = $this->userScoreLogService->getListByCondition($condition);

        foreach ($list as $item) {
            $item["type"] = UserScoreLogTypeEnum::getEnumDescByValue($item["type"]);
            $item["add_type"] = UserScoreLogAddTypeEnum::getEnumDescByValue($item["add_type"]);
            $item["reduce_type"] = UserScoreLogReduceTypeEnum::getEnumDescByValue($item["reduce_type"]);
        }

        $this->assign('list', $list);

        $logTypes = UserScoreLogTypeEnum::getAllList();
        $addTypes = UserScoreLogAddTypeEnum::getAllList();
        $reduceTypes = UserScoreLogReduceTypeEnum::getAllList();
        $this->assign("log_types", $logTypes);
        $this->assign("add_types", $addTypes);
        $this->assign("reduce_types", $reduceTypes);

        return $this->fetch("list");
    }
}