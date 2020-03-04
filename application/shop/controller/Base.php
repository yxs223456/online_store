<?php

namespace app\shop\controller;

use app\shop\service\ShopService;
use think\Controller;


class Base extends Controller {

    protected $adminService;
    protected $authGroupService;
    protected $authGroupAccessService;
    protected $authRuleService;
    protected $shopService;

    /**
     * 依赖注入
     * Base constructor.
     * @param adminService $adminService
     * @param authGroupService $authGroupService
     * @param authGroupAccessService $authGroupAccessService
     * @param authRuleService $authRuleService
     * @param ShopService $shopService
     */
    public function __construct(
                                 ShopService $shopService){

        parent::__construct();


        $this->shopService = $shopService;
    }
}