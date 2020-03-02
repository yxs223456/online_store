<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-01
 * Time: 15:10
 */

namespace app\admin\controller;

use app\common\enum\UserStatusEnum;

class User extends Common
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

                case "status":
                    if (is_numeric($value)) {
                        $value = (int) $value;
                        $whereSql .= " and status = '$value'";
                    }
                    break;
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

        $list = $this->userService->getListByCondition($condition);

        $this->assign('list', $list);

        if (input("status") === null || input("status") === "") {
            $this->assign("status", "");
        }

        return $this->fetch("list");
    }

    public function activate() {

        $id = input('param.id');

        $user = $this->userService->findById($id);

        if($user['status'] == UserStatusEnum::YES) {
            $this->error("该用户已是正常状态");
        }

        try {

            $result = $this->userService
                ->updateByIdAndData($id,["status"=>UserStatusEnum::YES,"update_time"=>time()]);

            if(false === $result) {
                $this->error($this->userService->getError());
            }

            $this->success("已正常");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    public function deactivate() {

        $id = input('param.id');

        $user = $this->userService->findById($id);

        if($user['status'] == UserStatusEnum::FORBIDDEN) {
            $this->error("该用户已是禁用状态");
        }

        try {
            $result = $this->userService
                ->updateByIdAndData($id,["status"=>UserStatusEnum::FORBIDDEN,"update_time"=>time()]);

            if($result === false) {
                $this->error($this->userService->getError());
            }

            $this->success("已禁用");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }
}