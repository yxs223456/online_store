<?php

namespace app\admin\service;
use com\BaseServiceTrait;
use think\Model;
use think\Db;

class Base extends Model {

    use BaseServiceTrait;

    protected $currentModel;

    /**
     * service层数据返回
     * @param $code
     * @param $msg
     * @param $data
     * @return \think\response\Json
     */
    public function serviceReturn($code,$msg,$data) {
        $returnData["code"] = $code;
        $returnData["msg"] = $msg;
        $returnData["data"] = $data;

        return $returnData;
    }

}
