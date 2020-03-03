<?php

namespace app\admin\controller;

use app\admin\service\GoodsBrandService;
use app\admin\service\GoodsClassifyService;
use app\admin\service\GoodsService;
use app\admin\service\GoodsSpecClassifyService;
use app\admin\service\GoodsSpecItemService;
use app\admin\service\GoodsSpecService;
use app\admin\service\UserBaseService;
use app\admin\service\UserLevelService;
use think\Controller;

use app\admin\service\Admin as adminService;
use app\admin\service\AuthGroup as authGroupService;
use app\admin\service\AuthGroupAccess as authGroupAccessService;
use app\admin\service\AuthRule as authRuleService;
use app\admin\service\Company as companyService;
use app\admin\service\Order as orderService;

class Base extends Controller {

    protected $adminService;
    protected $authGroupService;
    protected $authGroupAccessService;
    protected $authRuleService;
    protected $companyService;
    protected $orderService;
    protected $goodsBrandService;
    protected $goodsClassifyService;
    protected $goodsSpecClassifyService;
    protected $goodsService;
    protected $userLevelService;
    protected $userService;
    protected $goodsSpecItemService;
    protected $goodsSpecService;


    /**
     * 依赖注入
     * Base constructor.
     * @param adminService $adminService
     * @param authGroupService $authGroupService
     * @param authGroupAccessService $authGroupAccessService
     * @param authRuleService $authRuleService
     * @param companyService $companyService
     * @param orderService $orderService
     * @param UserBaseService $userBaseService
     * @param UserLevelService $userLevelService
     * @param GoodsBrandService $goodsBrandService
     * @param GoodsSpecClassifyService $goodsSpecClassifyService
     * @param GoodsClassifyService $goodsClassifyService
     * @param goodsService $goodsService
     * @param GoodsSpecItemService $goodsSpecItemService
     * @param GoodsSpecService $goodsSpecService
     */
    public function __construct( AdminService $adminService, AuthGroupService $authGroupService,
                                 AuthGroupAccessService $authGroupAccessService, AuthRuleService $authRuleService,
                                 companyService $companyService, orderService $orderService,UserBaseService $userBaseService,
                                 UserLevelService $userLevelService, GoodsBrandService $goodsBrandService,
                                 GoodsSpecClassifyService $goodsSpecClassifyService, GoodsClassifyService $goodsClassifyService,
                                 GoodsService $goodsService,GoodsSpecItemService $goodsSpecItemService,
                                 GoodsSpecService $goodsSpecService) {

        parent::__construct();

        $this->adminService = $adminService;
        $this->authGroupService = $authGroupService;
        $this->authGroupAccessService = $authGroupAccessService;
        $this->authRuleService = $authRuleService;
        $this->companyService = $companyService;
        $this->goodsBrandService = $goodsBrandService;
        $this->goodsClassifyService = $goodsClassifyService;
        $this->goodsSpecClassifyService = $goodsSpecClassifyService;
        $this->goodsService = $goodsService;
        $this->orderService = $orderService;
        $this->userLevelService = $userLevelService;
        $this->userService = $userBaseService;
        $this->goodsSpecItemService = $goodsSpecItemService;
        $this->goodsSpecService = $goodsSpecService;
    }
}