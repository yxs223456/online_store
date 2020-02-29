<?php

namespace app\admin\controller;

use app\admin\service\GoodsClassifyService;
use app\admin\service\GoodsSpecClassifyService;
use think\Controller;

use app\admin\service\Admin as adminService;
use app\admin\service\AuthGroup as authGroupService;
use app\admin\service\AuthGroupAccess as authGroupAccessService;
use app\admin\service\AuthRule as authRuleService;
use app\admin\service\Company as companyService;

class Base extends Controller {

    protected $adminService;
    protected $authGroupService;
    protected $authGroupAccessService;
    protected $authRuleService;
    protected $companyService;
    protected $goodsClassifyService;
    protected $goodsSpecClassifyService;

    /**
     * 依赖注入
     * Base constructor.
     * @param adminService $adminService
     * @param authGroupService $authGroupService
     * @param authGroupAccessService $authGroupAccessService
     * @param authRuleService $authRuleService
     * @param GoodsClassifyService $goodsClassifyService
     * @param companyService $companyService
     * @param GoodsSpecClassifyService $goodsSpecClassifyService
     */
    public function __construct( AdminService $adminService, AuthGroupService $authGroupService,
                                AuthGroupAccessService $authGroupAccessService, AuthRuleService $authRuleService,
                                 GoodsClassifyService $goodsClassifyService, companyService $companyService,
                                 GoodsSpecClassifyService $goodsSpecClassifyService){

        parent::__construct();

        $this->adminService = $adminService;
        $this->authGroupService = $authGroupService;
        $this->authGroupAccessService = $authGroupAccessService;
        $this->authRuleService = $authRuleService;
        $this->companyService = $companyService;
        $this->goodsClassifyService = $goodsClassifyService;
        $this->goodsSpecClassifyService = $goodsSpecClassifyService;
    }
}