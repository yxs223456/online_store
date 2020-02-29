<?php

namespace app\admin\service;

use app\admin\model\Goods;

class GoodsService extends Base {

    public function __construct()
    {
        parent::__construct();
        $this->currentModel = new Goods();
    }

    /**
     * 生成uuid
     *
     * @param $length
     * @return string
     */
    public function getUuid($length)
    {
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $key = "";
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, 35)}; //生成php随机数
        }
        return $key;
    }
}