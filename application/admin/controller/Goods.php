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
        $uuid = input('param.uuid');

        $goodsService = $this->goodsService;

        $result = $goodsService->findByUuid($uuid);
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
        $brand = $this->goodsBrandService->getAllName();

        $this->assign('list', $list);
        $this->assign('brand', $brand);
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

            $goodsScore = $this->goodsScoreService->saveByAllowField(
                array(
                    'goods_uuid' => $param['uuid'],
                    'shop_uuid' => "88888888",
                    'create_time' => time(),
                    'update_time' => time()
                )
            );
            if ($goodsScore === false) {
                throw new Exception("系统错误");
            }
            Db::commit();

        } catch (\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        $this->success("添加成功",url("edit",array("uuid" => $param['uuid'],"tab"=>'editSpec')));
    }

    /**
     * 编辑页面
     * @return mixed
     */
    public function edit()
    {
        $uuid = input('param.uuid');
        $tab = input('param.tab',"edit");

        $info = $this->goodsService->findByUuid($uuid);
        if(empty($info)) {
            $this->error("该商品不存在");
        }

        $cateArr = explode("_",$info["goods_classify_path"]);
        $category = [];
        $list = $this->goodsClassifyService->getAllByParent("");
        $category[] = $list;
        if (!empty(array_filter($cateArr))) {
            foreach ($cateArr as $value) {
                $list = $this->goodsClassifyService->getAllByParent($value);
                !empty($list) && $category[] = $list;
            }
        }

        $brand = $this->goodsBrandService->getAllName();

        // edit 页面数据
        $this->assign('tab', $tab);
        $this->assign('info', $info);
        $this->assign('brand', $brand);
        $this->assign('category', $category);
        $this->assign('cateArr', $cateArr);


        // editSale 页面数据
        // 获取商品分类
        $classifyUuids = explode("_",$info["goods_classify_path"]);
        // 根据商品分类获取规格分类
        $spec = $this->goodsSpecClassifyService->getClassifyByClassifyUuids($classifyUuids);
        // 获取规格分类符合要求的规格
        $classify = $this->goodsService->getClassifyData($classifyUuids, $spec);
        // 获取规格item
        $classifyItem = $this->goodsSpecItemService->getItemByGoodsUuid($info["uuid"]);

        //判断是否使用checkbox数组
        $goodsSpecUsed = $this->goodsSpecService->getSpecUsedItemUuids($info["uuid"]);

        // 获取table数据
        $orglist = $this->goodsSpecService->findByGoodsUuid($info["uuid"]);
        $list = $this->goodsSpecService->formatList($orglist,
            array_combine(array_column($classifyItem,'uuid'),array_column($classifyItem,'name')));

        // 获取table表头
        $specItemUuids = explode("_",array_pop($orglist)["goods_spec_item_uuids"]);
        $specClassifyUuids = $this->goodsSpecItemService->getClassifyUuid($specItemUuids);
        $classifyTitle = $this->goodsSpecClassifyService->getNameByUuids($specClassifyUuids);


        $this->assign("classify",$classify);
        $this->assign("classifyItem",$classifyItem);
        $this->assign("goodsSpecUsed",$goodsSpecUsed);
        $this->assign("classifyTitle",$classifyTitle);
        $this->assign("list",$list);


        // editImg 数据
        $gallery = $info["gallery"];
        if (empty($gallery)) {
            $gallery = array();
        } else {
            $gallery = json_decode($gallery,true);
        }

        $this->assign('gallery', $gallery);
        return $this->fetch();

    }

    /**
     * 添加操作
     * @return array|\think\response\Json
     */
    public function editPost() {

        $param = input('post.');
        $uuid = $param["uuid"];
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
            'goods_no' => 'require|max:20|unique:goods,goods_no,' . $uuid . ',uuid',
            'product_no' => 'require|max:20|unique:goods,product_no,' . $uuid . ',uuid',
            'goods_name' => 'require|unique:goods,goods_name,' . $uuid . ',uuid'
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
            $result = $goodsService->updateByAllowFieldAndUuid($param,$uuid);

            if($result === false){
                $this->error($goodsService->getError());
            }

            $this->success("添加成功",url("edit",array("uuid" => $uuid,"tab"=>'edit')));

        } catch(\PDOException $e) {
            $this->error($e->getMessage());
        }

    }

    /**
     * 编辑页面
     * @return mixed
     */
    public function editSpecPost()
    {
        $param = input('post.');

        $param["uuid"] = $this->companyService->getUuid(self::LENGTH);
        $param['create_time'] = time();
        $param['update_time'] = time();
        $param['is_delete'] = 0;
        $param['shop_uuid'] = "88888888";

        $rules = [
            'uuid' => 'require|unique:goods_spec_item',
            'name' => 'require|unique:goods_spec_item,goods_uuid^goods_spec_classify^name^is_delete'
        ];

        $msg = [
            'uuid.unique' => 'uuid重复,请再次提交',
            'name.require' => '规格不能为空',
            'name.unique' => '规格已存在',
        ];

        if (isset($param["image_url"])) {
            $rules["image_url"] = 'require';
            $msg["image_url.require"] = "请上传图片";
        }

        $validate   = Validate::make($rules,$msg);
        $result = $validate->check($param);

        if(!$result) {
            $this->error($validate->getError());
        }
        try {
            $goodsService = $this->goodsSpecItemService;
            $result = $goodsService->saveByAllowField($param);

            if ($result === false) {
                $this->error($goodsService->getError());
            }

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success("添加成功",url("edit",array("uuid" => $param['goods_uuid'],"tab"=>'editSpec')));
    }

    /**
     * 编辑销售规格页面
     * @return mixed
     */
    public function editSalePost()
    {
        $param = input('post.');

        $goodsUuid = $param["goods_uuid"];
        $goods = $this->goodsService->findByUuid($goodsUuid)->toArray();
        if(empty($goods)) {
            $this->error("该商品不存在");
        }

        if (empty($param["product_no"]) ||
            count($param["product_no"]) != count(array_filter($param["product_no"],
                array($this->goodsService,"filterNullStringZero")))
        ) {
            $this->error("货号不能为空");
        }
        if (empty($param["market_price"]) ||
            count($param["market_price"]) != count(array_filter($param["market_price"],
                array($this->goodsService,"filterNullString")))
        ) {
            $this->error("市场价不能为空");
        }
        if (empty($param["shop_price"]) ||
            count($param["shop_price"]) != count(array_filter($param["shop_price"],
                array($this->goodsService,"filterNullString")))
        ) {
            $this->error("本店价不能为空");
        }
        if (empty($param["stock"]) ||
            count($param["stock"]) != count(array_filter($param["stock"],
                array($this->goodsService,"filterNullString")))
        ) {
            $this->error("库存不能为空");
        }

        $goodsSpecService = $this->goodsSpecService;
        $insertData = $goodsSpecService->getInsertData($param, $goods);

        Db::startTrans();
        try {

            // 删除之前的
            $result = $goodsSpecService->deleteByUuid($goodsUuid);

            if ($result === false) {
                throw new Exception("操作失败");
            }

            // 添加新的
            $result = $goodsSpecService->insertMany($insertData);

            if ($result === false) {
                throw new Exception("操作失败");
            }

            $goods = $this->goodsService->updateByUuidAndData($goodsUuid,array("is_spec" => 1));
            if ($goods === false) {
                throw new Exception("操作失败");
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        $this->success("编辑成功",url("edit",array("uuid" => $goodsUuid,"tab"=>'editSale')));
    }

    public function editImgPost()
    {
        $param = input('post.');
        $uuid = $param['uuid'];
        $gallery = $param["gallery"];
        if (empty($uuid)) {
            $this->error("产品不存在");
        }
        if (empty($gallery)) {
            $this->error("上传至少一张图片");
        }

        $result = $this->goodsService->updateByAllowFieldAndUuid(
            array("gallery" => json_encode($gallery),'update_time' => time()),
            $uuid);
        if($result === false) {
            $this->error($this->goodsService->getError());
        }

        $this->success("上传成功",url("edit",array("uuid" => $uuid,"tab"=>'editImg')));
    }

    /**
     * 删除商品
     *
     * @throws Exception
     */
    public function delete() {

        $uuid = input('param.uuid');

        $goods = $this->goodsService->findByuuId($uuid);

        if($goods['is_delete'] == 1) {
            $this->error("该商品已删除状态");
        }

        DB::startTrans();
        try {
            // delete goods
            $result = $this->goodsService->updateByUuIdAndData($uuid,["is_delete"=>1]);
            if($result === false) {
                throw new Exception($this->goodsService->getError());
            }

            // delete spec item
            $goodsSpec = $this->goodsSpecItemService->deleteByGoodsUuid($uuid);
            if($goodsSpec === false) {
                throw new Exception($this->goodsSpecItemService->getError());
            }

            // delete goods spec
            $goodsSale = $this->goodsSpecService->deleteByGoodsUuid($uuid);
            if($goodsSale === false) {
                throw new Exception($this->goodsSpecService->getError());
            }

            DB::commit();
        } catch (\PDOException $e) {
            DB::rollback();
            $this->error($e->getMessage());
        }
        $this->success("删除成功");
    }

    /**
     * 删除规格
     *
     * @throws Exception
     */
    public function deleteSpec() {

        $uuid = input('param.uuid');
        $goodsUuid = input('param.goodsUuid');

        $item = $this->goodsSpecItemService->findByUuid($uuid);

        if ($item['is_delete'] == 1) {
            $this->error("该规格已删除");
        }

        DB::startTrans();
        try {
            $result = $this->goodsSpecItemService->updateByUuidAndData($uuid, ["is_delete" => 1]);

            if ($result === false) {
                throw new Exception($this->goodsSpecItemService->getError());
            }

            $result = $this->goodsSpecService->deleteByItemUuid($uuid);

            if ($result === false) {
                throw new Exception($this->goodsSpecService->getError());
            }

            $items = $this->goodsSpecItemService->getItemByGoodsUuid($goodsUuid);
            if (empty($items)) {
                $result = $this->goodsService->updateByUuidAndData($goodsUuid, array("is_spec" => 0));
                if ($result === false) {
                    throw new Exception($this->goodsService->getError());
                }
            }

            DB::commit();

        } catch (\PDOException $e) {
            DB::rollback();
            $this->error($e->getMessage());
        }
        $this->success("删除成功",url("edit",array("uuid" => $goodsUuid,"tab"=>'editSpec')));
    }

    /**
     * 编辑销售规格页面
     * @return mixed
     */
    public function getSaleForm() {

        $param = input('post.');

        $data = $this->goodsService->getSaleForm($param["item"]);
        $sale = $data["sale"];
        $uuids = $data["goodsClassify"];
        $classify = $this->goodsSpecClassifyService->getNameByUuids($uuids);

        $productNo = $this->goodsService->getUuid(10);

        $this->assign('sale',array_values($sale));
        $this->assign('itemUuids',array_keys($sale));
        $this->assign('classify',$classify);
        $this->assign('productNo',$productNo);

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

}