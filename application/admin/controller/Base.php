<?php

namespace app\admin\controller;

use app\admin\model\GoodsClassifyModel;
use app\admin\service\GoodsClassifyService;
use think\Controller;

use app\admin\service\Admin as adminService;
use app\admin\service\AuthGroup as authGroupService;
use app\admin\service\AuthGroupAccess as authGroupAccessService;
use app\admin\service\AuthRule as authRuleService;

class Base extends Controller {

    protected $adminService;
    protected $authGroupService;
    protected $authGroupAccessService;
    protected $authRuleService;
    protected $goodsClassifyService;

    /**
     * 依赖注入
     * Base constructor.
     * @param adminService $adminService
     * @param authGroupService $authGroupService
     * @param authGroupAccessService $authGroupAccessService
     * @param authRuleService $authRuleService
     * @param GoodsClassifyService $goodsClassifyService
     */
    public function __construct( AdminService $adminService, AuthGroupService $authGroupService,
                                AuthGroupAccessService $authGroupAccessService, AuthRuleService $authRuleService,
                                 GoodsClassifyService $goodsClassifyService){

        parent::__construct();

        $this->adminService = $adminService;
        $this->authGroupService = $authGroupService;
        $this->authGroupAccessService = $authGroupAccessService;
        $this->authRuleService = $authRuleService;
        $this->goodsClassifyService = $goodsClassifyService;
    }
}