<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-04
 * Time: 16:12
 */

namespace app\admin\controller;

use app\common\enum\DistributorsBalanceLogAddTypeEnum;
use app\common\enum\DistributorsBalanceLogReduceTypeEnum;
use app\common\enum\DistributorsBalanceLogTypeEnum;

class DistributorsBalanceLog extends Common
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

                case "distributors_uuid":
                    $whereSql .= " and distributors_uuid = '$value'";
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

        $list = $this->distributorsBalanceLogService->getListByCondition($condition);

        foreach ($list as $item) {
            $item["type"] = DistributorsBalanceLogTypeEnum::getEnumDescByValue($item["type"]);
            $item["add_type"] = DistributorsBalanceLogAddTypeEnum::getEnumDescByValue($item["add_type"]);
            $item["reduce_type"] = DistributorsBalanceLogReduceTypeEnum::getEnumDescByValue($item["reduce_type"]);
        }

        $this->assign('list', $list);

        $logTypes = DistributorsBalanceLogTypeEnum::getAllList();
        $addTypes = DistributorsBalanceLogAddTypeEnum::getAllList();
        $reduceTypes = DistributorsBalanceLogReduceTypeEnum::getAllList();
        $this->assign("log_types", $logTypes);
        $this->assign("add_types", $addTypes);
        $this->assign("reduce_types", $reduceTypes);

        return $this->fetch("list");
    }
}