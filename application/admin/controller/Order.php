<?php

namespace app\admin\controller;

use app\admin\model\GoodsOrder;

class Order extends Common {

    const LENGTH = 16;

    /**
     * 订单列表
     * @return mixed
     */
    public function index() {

        $requestMap = $this->convertRequestToMap();
        $list = $this->orderService->paginateList($requestMap);

        $this->assign('list',$list);

        return $this->fetch();

    }

    /**
     * 添加页面
     * @return mixed
     */
    public function add() {
        return $this->fetch();
    }

    /**
     * 添加操作
     * @return array|\think\response\Json
     */
    public function addPost() {

        $param = input('post.');
        $param["uuid"] = $this->companyService->getUuid(self::LENGTH);
        $param["create_time"] = time();
        $param["update_time"] = time();
        $companyService = $this->companyService;


        try {

            $result = $companyService->saveByAllowField($param);

            if($result === false){
                $this->error($companyService->getError());
            }

            $this->success("添加成功",url("index"));

        } catch(\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * 修改发货操作
     * @return array|\think\response\Json
     */
    public function delivery() {

        $id = input('param.id');

        $orderService = $this->orderService;

        try{

            $result = $orderService->updateByIdAndData($id, array("status" => GoodsOrder::STATUS_DELIVERY));

            if(false === $result){
                $this->error($orderService->getError());
            }


            $this->success("修改成功");

        } catch(\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * 修改发货操作
     * @return array|\think\response\Json
     */
    public function refund() {

        $id = input('param.id');

        $orderService = $this->orderService;

        try{

            $result = $orderService->updateByIdAndData($id,
                array(
                    "pay_status" => GoodsOrder::PAY_STATUS_REFUND_DOWN,
                    "status" => GoodsOrder::STATUS_CANCEL
                )
            );

            if(false === $result){
                $this->error($orderService->getError());
            }


            $this->success("修改成功");

        } catch(\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

}