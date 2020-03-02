<?php

namespace app\admin\controller;


use app\admin\model\GoodsClassifyModel;
use org\LeftNav;

class Goods extends Common {

    const LENGTH = 16;

    /**
     * 上架商品列表
     * @return mixed
     */
    public function sale() {

        $requestMap = $this->convertRequestToMap();
        $requestMap["condition"]["is_sale"] = \app\admin\model\Goods::IS_SALE_YES;
        $requestMap["condition"]["is_delete"] = \app\admin\model\Goods::IS_DELETE_NO;
        $list = $this->goodsService->paginateList($requestMap);

        $this->assign('list',$list);

        return $this->fetch();
    }

    /**
     * 未上架列表
     * @return mixed
     */
    public function soldOut() {

        $requestMap = $this->convertRequestToMap();
        $requestMap["condition"]["is_sale"] = \app\admin\model\Goods::IS_SALE_NO;
        $requestMap["condition"]["is_delete"] = \app\admin\model\Goods::IS_DELETE_NO;
        $list = $this->goodsService->paginateList($requestMap);

        $this->assign('list',$list);

        return $this->fetch();

    }

    public function detail()
    {
        $id = input('param.id');

        $goodsService = $this->goodsService;

        $result = $goodsService->findById($id);
        if (false === $result) {
            $this->error($goodsService->getError());
        }

        $this->assign("info", $result);
        return $this->fetch();

    }
    /**
     * 添加页面
     * @return mixed
     */
    public function add()
    {
        $list = $this->goodsClassifyService->getAllByParent("");
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 添加操作
     * @return array|\think\response\Json
     */
    public function addPost() {

        $param = input('post.');
        $param["uuid"] = $this->companyService->getUuid(self::LENGTH);
        $classfly = array_filter($param["goods_classify"]);
        isset($param["is_best"]) && $param["is_best"] = 1;
        isset($param["is_recommend"]) && $param["is_recommend"] = 1;
        isset($param["is_hot"]) && $param["is_hot"] = 1;
        isset($param["is_new"]) && $param["is_new"] = 1;
        $param["create_time"] = time();
        $param["update_time"] = time();
        $param["goods_classify_path"] = implode("_",$classfly);
        $param["first_goods_classify"] = array_shift($classfly);
        $param["last_goods_classify"] = array_pop($classfly);
        $param["gallery"] = "";
        $goodsService = $this->goodsService;

//        var_dump($param);exit;
        //数据校验
        $validate = validate("GoodsValidate");
        if(false === $validate->scene($this->request->action(true))
                ->check($param)) {
            $this->error($validate->getError());
        }

        try {

            $result = $goodsService->saveByAllowField($param);

            if($result === false){
                $this->error($goodsService->getError());
            }

            $this->success("添加成功",url("sale"));

        } catch(\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * 添加页面
     * @return mixed
     */
    public function getCat() {

        $param = input('post.');
        $list = $this->goodsClassifyService->getAllByParent($param["uuid"]);

        if (empty($list)) {
            return $this->fetch("get_cat_no");
        }

        $this->assign('list',$list);
        $this->assign('index',$param["index"]);
        return $this->fetch();
    }

    /**
     * 开启推荐操作
     * @return mixed
     */
    public function recommend() {

        $id = input('param.id');

        $menu = $this->goodsService->findById($id);

        if($menu['is_recommend'] == 1) {
            $this->error("该商品已是推荐状态");
        }

        try {

            $result = $this->goodsService->updateByIdAndData($id,["is_recommend"=>1]);

            if(false === $result) {
                $this->error($this->goodsService->getError());
            }

            $this->success("已推荐");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * 取消推荐操作
     * @return mixed
     */
    public function unRecommend() {

        $id = input('param.id');

        $menu = $this->goodsService->findById($id);

        if($menu['is_recommend'] == 0) {
            $this->error("该商品已是未推荐状态");
        }

        try {
            $result = $this->goodsService->updateByIdAndData($id,["is_recommend"=>0]);

            if($result === false) {
                $this->error($this->goodsService->getError());
            }

            $this->success("取消推荐");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * 开启精品操作
     * @return mixed
     */
    public function best() {

        $id = input('param.id');

        $menu = $this->goodsService->findById($id);

        if($menu['is_best'] == 1) {
            $this->error("该商品已是精品状态");
        }

        try {

            $result = $this->goodsService->updateByIdAndData($id,["is_best"=>1]);

            if(false === $result) {
                $this->error($this->goodsService->getError());
            }

            $this->success("设置精品成功");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * 取消精品操作
     * @return mixed
     */
    public function unBest() {

        $id = input('param.id');

        $menu = $this->goodsService->findById($id);

        if($menu['is_best'] == 0) {
            $this->error("该商品已是未精品状态");
        }

        try {
            $result = $this->goodsService->updateByIdAndData($id,["is_best"=>0]);

            if($result === false) {
                $this->error($this->goodsService->getError());
            }

            $this->success("取消精品成功");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * 开启新品操作
     * @return mixed
     */
    public function isNew() {

        $id = input('param.id');

        $menu = $this->goodsService->findById($id);

        if($menu['is_new'] == 1) {
            $this->error("该商品已是新品状态");
        }

        try {

            $result = $this->goodsService->updateByIdAndData($id,["is_new"=>1]);

            if(false === $result) {
                $this->error($this->goodsService->getError());
            }

            $this->success("设置新品成功");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * 取消新品操作
     * @return mixed
     */
    public function unNew() {

        $id = input('param.id');

        $menu = $this->goodsService->findById($id);

        if($menu['is_new'] == 0) {
            $this->error("该商品已是未新品状态");
        }

        try {
            $result = $this->goodsService->updateByIdAndData($id,["is_new"=>0]);

            if($result === false) {
                $this->error($this->goodsService->getError());
            }

            $this->success("取消新品成功");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    public function hot() {

        $id = input('param.id');

        $menu = $this->goodsService->findById($id);

        if($menu['is_hot'] == 1) {
            $this->error("该商品已是热销状态");
        }

        try {

            $result = $this->goodsService->updateByIdAndData($id,["is_hot"=>1]);

            if(false === $result) {
                $this->error($this->goodsService->getError());
            }

            $this->success("设置热销成功");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }


    public function unHot() {

        $id = input('param.id');

        $menu = $this->goodsService->findById($id);

        if($menu['is_hot'] == 0) {
            $this->error("该商品已是未热销状态");
        }

        try {
            $result = $this->goodsService->updateByIdAndData($id,["is_hot"=>0]);

            if($result === false) {
                $this->error($this->goodsService->getError());
            }

            $this->success("取消热销成功");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    public function delete() {

        $id = input('param.id');

        $menu = $this->goodsService->findById($id);

        if($menu['is_delete'] == 1) {
            $this->error("该商品已删除状态");
        }

        try {
            $result = $this->goodsService->updateByIdAndData($id,["is_delete"=>1]);

            if($result === false) {
                $this->error($this->goodsService->getError());
            }

            $this->success("删除成功");

        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

}