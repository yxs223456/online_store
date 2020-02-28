<?php

namespace app\admin\controller;

class Company extends Common {

    const LENGTH = 16;

    /**
     * 配置列表
     * @return mixed
     */
    public function index() {

        $requestMap = $this->convertRequestToMap();
        $requestMap['condition']['is_delete'] = 0;
        $list = $this->companyService->paginateList($requestMap);

        $this->assign('list',$list);

        return $this->fetch();

    }

    /**
     * 添加配置页面
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

        !$this->validateName($param["name"]) && $this->error('公司名称值不合法');
        !$this->validateCode($param["code"]) && $this->error("公司编号值不合法");
        !$this->validatePhone($param["phone"]) && $this->error("公司电话值不合法");
        !$this->validateUuid($param["uuid"]) && $this->error("重新提交");
        try {

            $result = $companyService->saveByAllowField($param);

            if($result === false){
                $this->error($companyService->getError());
            }

            $this->success("配置添加成功",url("index"));

        } catch(\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * 编辑配置页面
     * @return mixed
     */
    public function edit() {

        $id = input('param.id');

        $companyService = $this->companyService;

        $info = $companyService->findById($id);

        $this->assign('info', $info);

        return $this->fetch();

    }

    /**
     * 编辑配置操作
     * @return array|\think\response\Json
     */
    public function editPost() {

        $param = input('post.');
        $param["update_time"] = time();
        $companyService = $this->companyService;
        !$this->validateName($param["name"]) && $this->error('公司名称值不合法');
        !$this->validateCode($param["code"]) && $this->error("公司编号值不合法");
        !$this->validatePhone($param["phone"]) && $this->error("公司电话值不合法");
        try{

            $result = $companyService->updateByAllowFieldAndId($param,$param["id"]);

            if($result === false){
                $this->error($companyService->getError());
            }

            $this->success("配置成功",url("index"));

        } catch(\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * 删除操作
     * @return array|\think\response\Json
     */
    public function delete() {

        $id = input('param.id');

        $companyService = $this->companyService;

        try{

            $result = $companyService->updateByIdAndData($id, array("is_delete" => 1));

            if(false === $result){
                $this->error($companyService->getError());
            }


            $this->success("删除成功");

        } catch(\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    private function validateName($name)
    {
        return !empty($name);
    }

    private function validateCode($code)
    {
        return !empty($code);
    }

    private function validatePhone($phone)
    {
        preg_match("/^1[3|4|5|7|8][0-9]{9}$/", $phone, $result);
        return !empty($phone) && !empty($result);
    }

    private function validateUuid($uuid)
    {
        $data = $this->companyService->findByMap(array('uuid' => $uuid));
        return empty($data);
    }

}