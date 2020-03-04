<?php

namespace app\shop\controller;

class Common extends Base {

    protected $beforeActionList = [
        'filterAdmin',
    ];

    protected function filterAdmin() {

        if(!session('shop_uuid')||!session('shop_name')){
            $this->redirect("shop/Login/index");
        }

        $this->assign([
            'shop_name' => session('shop_name'),
            'shop_image' => session('shop_image'),
        ]);

        //读取配置文件
        $config = cache('db_config_data');

        if(!$config){
            $config = api('Config/lists');
            cache('db_config_data',$config);
        }
        config($config);

        //单独设置后台分页数量（二维配置无法和一维配置同时应用）
        config("paginate.list_rows",intval($config["paginate.list_rows"]));

    }

    /**
     * 请求转换为查询条件
     * @return array
     */
    protected function convertRequestToMap() {

        $map = [];
        $conditionMap = [];
        $pageMap = [];

        foreach(input("param.") as $key => $value) {
            if(!in_array($key,["page","export"])) {
                if(!isNullOrEmpty($value)) {
                    $conditionMap[$key] = $value;
                    $arr1 = explode('-',$key);
                    $arr2 = explode('#', $arr1[0]);
                    $trueKey = count($arr2) > 1 ? $arr2[1] : $arr2[0];
                    $pageMap[urlencode($key)] = $value;
                    $this->assign($trueKey, $value);
                }
            }
        }

        $map["condition"] = $conditionMap;
        $map["page"] = $pageMap;

        $this->assign("pageNum", input("?param.page") ? input("param.page") : 1);

        return $map;

    }

}