<?php

namespace app\admin\model;

use think\Model;

class GoodsOrder extends Model {
    const STATUS_SUBMIT = 0;//订单提交
    const STATUS_WAIT_DELIVERY = 1; //待发货
    const STATUS_DELIVERY = 2;//派送中
    const STATUS_RECEIVE = 3;//签收
    const STATUS_CANCEL = 10;//取消

    const PAY_STATUS_WAIT_PAY = 0;//待支付
    const PAY_STATUS_PAY_SUCCESS = 1;//支付成功
    const PAY_STATUS_REFUND_PROCESS = 2;//退款中
    const PAY_STATUS_REFUND_DOWN = 3;//退款完成

    public static $statusList = array(
        self::STATUS_SUBMIT => '已提交',
        self::STATUS_WAIT_DELIVERY => '待发货',
        self::STATUS_DELIVERY => '派送中',
        self::STATUS_RECEIVE => '签收',
        self::STATUS_CANCEL => '取消'
    );

    public static $payStatusList = array(
        self::PAY_STATUS_WAIT_PAY => '待支付',
        self::PAY_STATUS_PAY_SUCCESS => '支付成功',
        self::PAY_STATUS_REFUND_PROCESS => '退款中',
        self::PAY_STATUS_REFUND_DOWN => '退款完成',
    );

    public static function getStatus($status)
    {
        return isset(self::$statusList[$status]) ? self::$statusList[$status] : "";
    }

    public static function getPayStatus($status) {
        return isset(self::$payStatusList[$status]) ? self::$payStatusList[$status] : "";
    }
}