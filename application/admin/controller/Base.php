<?php

namespace app\admin\controller;

use app\admin\service\DistributorsBalanceLogService;
use app\admin\service\DistributorsService;
use app\admin\service\GoodsAppraiseService;
use app\admin\service\GoodsBrandService;
use app\admin\service\GoodsClassifyService;
use app\admin\service\GoodsScoreService;
use app\admin\service\GoodsService;
use app\admin\service\GoodsSpecClassifyService;
use app\admin\service\GoodsSpecItemService;
use app\admin\service\GoodsSpecService;
use app\admin\service\UserBaseService;
use app\admin\service\UserLevelService;
use app\admin\service\UserScoreLogService;
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
    protected $distributorsService;
    protected $distributorsBalanceLogService;
    protected $orderService;
    protected $goodsAppraiseService;
    protected $goodsBrandService;
    protected $goodsClassifyService;
    protected $goodsScoreService;
    protected $goodsSpecClassifyService;
    protected $goodsService;
    protected $userLevelService;
    protected $userScoreLogService;
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
     * @param DistributorsService $distributorsService
     * @param DistributorsBalanceLogService $distributorsBalanceLogService
     * @param orderService $orderService
     * @param UserBaseService $userBaseService
     * @param UserLevelService $userLevelService
     * @param UserScoreLogService $userScoreLogService
     * @param GoodsAppraiseService $goodsAppraiseService
     * @param GoodsBrandService $goodsBrandService
     * @param GoodsScoreService $goodsScoreService
     * @param GoodsSpecClassifyService $goodsSpecClassifyService
     * @param GoodsClassifyService $goodsClassifyService
     * @param goodsService $goodsService
     * @param GoodsSpecItemService $goodsSpecItemService
     * @param GoodsSpecService $goodsSpecService
     */
    public function __construct( AdminService $adminService, AuthGroupService $authGroupService,
                                 AuthGroupAccessService $authGroupAccessService, AuthRuleService $authRuleService,
                                 companyService $companyService, DistributorsService $distributorsService,
                                 DistributorsBalanceLogService $distributorsBalanceLogService,
                                 orderService $orderService,UserBaseService $userBaseService,
                                 UserLevelService $userLevelService, UserScoreLogService $userScoreLogService,
                                 GoodsAppraiseService $goodsAppraiseService,
                                 GoodsBrandService $goodsBrandService, GoodsScoreService $goodsScoreService,
                                 GoodsSpecClassifyService $goodsSpecClassifyService, GoodsClassifyService $goodsClassifyService,
                                 GoodsService $goodsService,GoodsSpecItemService $goodsSpecItemService,
                                 GoodsSpecService $goodsSpecService) {

        parent::__construct();

        $this->adminService = $adminService;
        $this->authGroupService = $authGroupService;
        $this->authGroupAccessService = $authGroupAccessService;
        $this->authRuleService = $authRuleService;
        $this->companyService = $companyService;
        $this->distributorsService = $distributorsService;
        $this->distributorsBalanceLogService = $distributorsBalanceLogService;
        $this->goodsAppraiseService = $goodsAppraiseService;
        $this->goodsBrandService = $goodsBrandService;
        $this->goodsClassifyService = $goodsClassifyService;
        $this->goodsSpecClassifyService = $goodsSpecClassifyService;
        $this->goodsService = $goodsService;
        $this->orderService = $orderService;
        $this->userLevelService = $userLevelService;
        $this->userScoreLogService = $userScoreLogService;
        $this->userService = $userBaseService;
        $this->goodsSpecItemService = $goodsSpecItemService;
        $this->goodsSpecService = $goodsSpecService;
        $this->goodsScoreService = $goodsScoreService;
    }
}