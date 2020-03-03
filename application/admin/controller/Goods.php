<?php

namespace app\admin\controller;

use think\Db;
use think\Exception;
use think\Validate;

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
        $param["shop"] = "";
        $goodsService = $this->goodsService;

        //数据校验
        $validate = validate("GoodsValidate");
        if(false === $validate->scene($this->request->action(true))
                ->check($param)) {
            $this->error($validate->getError());
        }

        Db::startTrans();
        try {
            $result = $goodsService->saveByAllowField($param);

            if ($result === false) {
                $this->error($goodsService->getError());
            }

//            $goodsScore = $this->goodsScoreService->saveByAllowField(
//                array(
//                    'goods_uuid' => $param['uuid'],
//                    'shop_uuid' => $param['shop_uuid'],
//                    'create_time' => time(),
//                    'update_time' => time()
//                )
//            );
//            if ($goodsScore === false) {
//                throw new Exception("系统错误");
//            }
            Db::commit();

        } catch (\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        $this->success("添加成功",url("sale"));
    }

    /**
     * 编辑页面
     * @return mixed
     */
    public function edit()
    {
        $id = input('param.id');

        $info = $this->goodsService->findById($id);
        if(empty($info)) {
            $this->error("该商品不存在");
        }
        $cateArr = explode("_",$info["goods_classify_path"]);
        $category = [];
        $list = $this->goodsClassifyService->getAllByParent("");
        $category[] = $list;
        foreach ($cateArr as $value) {
            $list = $this->goodsClassifyService->getAllByParent($value);
            !empty($list) && $category[] = $list;
        }

        $this->assign('info', $info);
        $this->assign('category', $category);
        $this->assign('cateArr', $cateArr);
        return $this->fetch();
    }

    /**
     * 添加操作
     * @return array|\think\response\Json
     */
    public function editPost() {

        $param = input('post.');
        $id = $param["id"];
        $classfly = array_filter($param["goods_classify"]);
        isset($param["is_best"]) ? $param["is_best"] = 1 : $param["is_best"] = 0;
        isset($param["is_recommend"]) ? $param["is_recommend"] = 1 : $param['is_recommend'] = 0;
        isset($param["is_hot"]) ? $param["is_hot"] = 1 : $param["is_hot"] = 0;
        isset($param["is_new"]) ? $param["is_new"] = 1 : $param["is_new"] = 0;
        $param["update_time"] = time();
        $param["goods_classify_path"] = implode("_",$classfly);
        $param["first_goods_classify"] = array_shift($classfly);
        $param["last_goods_classify"] = array_pop($classfly);
        $param["gallery"] = "";
        $goodsService = $this->goodsService;


        $rules = [
            'goods_no' => 'require|max:20|unique:goods,goods_no,' . $id . ',id',
            'product_no' => 'require|max:20|unique:goods,product_no,' . $id . ',id',
            'goods_name' => 'require|unique:goods,goods_name,' . $id . ',id'
        ];

        $msg = [
            'goods_no.require' => '商品编号不能为空',
            'goods_no.max' => '商品编号长度不能超过20',
            'goods_no.unique' => '商品编号重复',
            'product_no.require' => '商品货号不能为空',
            'product_no.max' => '商品货号长度不能超过20',
            'product_no.unique' => '商品货号重复',
            'goods_name.require' => '商品名称不能为空',
            'goods_name.unique' => '商品名称重复',
        ];
        $validate   = Validate::make($rules,$msg);
        $result = $validate->check($param);

        if(!$result) {
            $this->error($validate->getError());
        }

        //数据校验
        $validate = validate("GoodsValidate");
        if(false === $validate->scene($this->request->action(true))
                ->check($param)) {
            $this->error($validate->getError());
        }

        try {
            $result = $goodsService->updateByAllowFieldAndId($param,$id);

            if($result === false){
                $this->error($goodsService->getError());
            }

            $this->success("添加成功",url("sale"));

        } catch(\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * 编辑页面
     * @return mixed
     */
    public function editSpec()
    {
        $id = input('param.id');

        $info = $this->goodsService->findById($id);
        if(empty($info)) {
            $this->error("该商品不存在");
        }
        $cateArr = explode("_",$info["goods_classify_path"]);
        $category = [];
        $list = $this->goodsClassifyService->getAllByParent("");
        $category[] = $list;
        foreach ($cateArr as $value) {
            $list = $this->goodsClassifyService->getAllByParent($value);
            !empty($list) && $category[] = $list;
        }

        $this->assign('info', $info);
        $this->assign('category', $category);
        $this->assign('cateArr', $cateArr);
        return $this->fetch();
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
        if (isset($param["current_uuid"])) {
            $this->assign('current_uuid',$param["current_uuid"]);
        }

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