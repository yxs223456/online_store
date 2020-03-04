<?php

namespace app\shop\controller;

use app\shop\controller\Base;
use org\Verify;
//use com\Geetestlib;
use think\Controller;
use \think\facade\Session;

class Login extends Base {

    protected $uid;
    protected $username;

    public function _initialize() {
        parent::_initialize();
    }

    //登录页面
    public function index() {
        return $this->fetch('/login');
    }

    //登录操作
    public function doLogin() {

        $username = input("param.username");
        $password = input("param.password");

        if(isNullOrEmpty($username)) {
            $this->error('账号不能为空');
        }

        $shop = $this->shopService->findShopForLogin($username);

        if(isNullOrEmpty($shop)) {
            $this->error('账号不存在');
        }

        if(md5($password) != $shop['password']){
            $this->error("账号或密码错误");
        }

//        if(1 != $shop['status']){
//            $this->error("该账号被禁用");
//        }

        Session::set('shop_uuid', $shop['uuid']);
        Session::set('shop_name', $shop['name']);
        Session::set('shop_image', $shop['image_url']);

        $this->success("登录成功",url('index/index'));
    }

    //退出操作
    public function logout() {
        session('shop_uuid', null);
        session('shop_name', null);
        session('rule', null);
        cache('db_config_data',null);
        $this->redirect("shop/Login/index");
    }

}