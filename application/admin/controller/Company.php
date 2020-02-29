<?php

namespace app\admin\controller;

class Company extends Common {

    const LENGTH = 16;

    /**
     * 列表
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
        $param["is_delete"] = 0;
        $companyService = $this->companyService;

        //数据校验
        $validate = validate("CompanyValidate");
        if(false === $validate->scene($this->request->action(true))
                ->check($param)) {
            $this->error($validate->getError());
        }

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
     * 编辑页面
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
     * 编辑操作
     * @return array|\think\response\Json
     */
    public function editPost() {

        $param = input('post.');
        $param["update_time"] = time();
        $param["is_delete"] = 0;
        $companyService = $this->companyService;
        //数据校验
        $validate = validate("CompanyValidate");
        if(false === $validate->scene($this->request->action(true))
                ->check($param)) {
            $this->error($validate->getError());
        }
        try{

            $result = $companyService->updateByAllowFieldAndId($param,$param["id"]);

            if($result === false){
                $this->error($companyService->getError());
            }

            $this->success("编辑成功",url("index"));

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

}