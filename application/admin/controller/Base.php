<?php

namespace app\admin\controller;

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

    /**
     * 依赖注入
     * Base constructor.
     * @param adminService $adminService
     * @param authGroupService $authGroupService
     * @param authGroupAccessService $authGroupAccessService
     * @param authRuleService $authRuleService
     * @param companyService $companyService
     * @param orderService $orderService
     */
    public function __construct( AdminService $adminService, AuthGroupService $authGroupService,
                                AuthGroupAccessService $authGroupAccessService, AuthRuleService $authRuleService,
                                 companyService $companyService,orderService $orderService){

        parent::__construct();

        $this->adminService = $adminService;
        $this->authGroupService = $authGroupService;
        $this->authGroupAccessService = $authGroupAccessService;
        $this->authRuleService = $authRuleService;
        $this->companyService = $companyService;
        $this->orderService = $orderService;
    }
}