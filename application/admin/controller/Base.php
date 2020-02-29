<?php

namespace app\admin\controller;

use app\admin\service\GoodsClassifyService;
use app\admin\service\GoodsService;
use app\admin\service\GoodsSpecClassifyService;
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
    protected $goodsClassifyService;
    protected $goodsSpecClassifyService;
    protected $goodsService;

    /**
     * 依赖注入
     * Base constructor.
     * @param adminService $adminService
     * @param authGroupService $authGroupService
     * @param authGroupAccessService $authGroupAccessService
     * @param authRuleService $authRuleService
     * @param GoodsClassifyService $goodsClassifyService
     * @param GoodsSpecClassifyService $goodsSpecClassifyService
     * @param companyService $companyService
     * @param orderService $orderService
     * @param goodsService $goodsService
     */
    public function __construct( AdminService $adminService, AuthGroupService $authGroupService,
                                 AuthGroupAccessService $authGroupAccessService, AuthRuleService $authRuleService,
                                 companyService $companyService, orderService $orderService,
                                 GoodsSpecClassifyService $goodsSpecClassifyService, GoodsClassifyService $goodsClassifyService,
                                 GoodsService $goodsService) {

        parent::__construct();

        $this->adminService = $adminService;
        $this->authGroupService = $authGroupService;
        $this->authGroupAccessService = $authGroupAccessService;
        $this->authRuleService = $authRuleService;
        $this->companyService = $companyService;
        $this->orderService = $orderService;
        $this->goodsClassifyService = $goodsClassifyService;
        $this->goodsSpecClassifyService = $goodsSpecClassifyService;
        $this->goodsService = $goodsService;
    }
}