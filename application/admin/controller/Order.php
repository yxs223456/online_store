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
     * 已收货列表
     * @return mixed
     */
    public function receive() {

        $requestMap = $this->convertRequestToMap();
        $requestMap["condition"]["status"] = GoodsOrder::STATUS_RECEIVE;
        $list = $this->orderService->paginateList($requestMap);

        $this->assign('list',$list);

        return $this->fetch();

    }

    /**
     * 待支付列表
     * @return mixed
     */
    public function waitPay() {

        $requestMap = $this->convertRequestToMap();
        $requestMap["condition"]["status"] = GoodsOrder::STATUS_SUBMIT;
        $list = $this->orderService->paginateList($requestMap);

        $this->assign('list',$list);

        return $this->fetch();

    }

    /**
     * 待发货列表
     * @return mixed
     */
    public function waitDelivery() {

        $requestMap = $this->convertRequestToMap();
        $requestMap["condition"]["status"] = GoodsOrder::STATUS_WAIT_DELIVERY;
        $list = $this->orderService->paginateList($requestMap);

        $this->assign('list',$list);

        return $this->fetch();

    }

    /**
     * 已发货列表
     * @return mixed
     */
    public function delivery() {

        $requestMap = $this->convertRequestToMap();
        $requestMap["condition"]["status"] = GoodsOrder::STATUS_DELIVERY;
        $list = $this->orderService->paginateList($requestMap);

        $this->assign('list',$list);

        return $this->fetch();

    }

    /**
     * 已取消列表
     * @return mixed
     */
    public function cancel() {

        $requestMap = $this->convertRequestToMap();
        $requestMap["condition"]["status"] = GoodsOrder::STATUS_CANCEL;
        $list = $this->orderService->paginateList($requestMap);

        $this->assign('list',$list);

        return $this->fetch();

    }

    /**
     * 待退款列表
     * @return mixed
     */
    public function refund() {

        $requestMap = $this->convertRequestToMap();
        $requestMap["condition"]["pay_status"] = GoodsOrder::PAY_STATUS_REFUND_PROCESS;
        $list = $this->orderService->paginateList($requestMap);

        $this->assign('list',$list);

        return $this->fetch();

    }

    public function detail()
    {
        $id = input('param.id');

        $orderService = $this->orderService;


        $result = $orderService->findById($id);
        if (false === $result) {
            $this->error($orderService->getError());
        }

        $this->assign("info", $result);
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
    public function editDelivery() {

        $id = input('param.id');

        $orderService = $this->orderService;

        $company = $this->companyService->selectByMap(["is_delete"=>0]);
        $result = $orderService->findById($id);
        if (false === $result) {
            $this->error($orderService->getError());
        }
        $this->assign('company',$company);
        $this->assign("info", $result);
        return $this->fetch();
    }

    /**
     * 修改发货操作
     * @return array|\think\response\Json
     */
    public function editDeliveryPost() {

        $param = input('post.');
        $orderService = $this->orderService;
        //数据校验
        $validate = validate("OrderValidate");
        if(false === $validate->scene($this->request->action(true))
                ->check($param)) {
            $this->error($validate->getError());
        }

        $param["status"] = GoodsOrder::STATUS_DELIVERY;

        try{

            $result = $orderService->updateByIdAndData($param["id"], $param);

            if(false === $result){
                $this->error($orderService->getError());
            }

            $this->success("修改成功",url("waitDelivery"));

        } catch(\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * 修改退款操作
     * @return array|\think\response\Json
     */
    public function editRefund() {

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