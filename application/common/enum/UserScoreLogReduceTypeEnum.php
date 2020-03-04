<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-04
 * Time: 16:15
 */

namespace app\common\enum;

/**
 * 用户积分消耗类型
 * Class UserScoreLogReduceTypeEnum
 * @package app\common\enum
 */
class UserScoreLogReduceTypeEnum
{
    use EnumTrait;

    const EXCHANGE_GOODS = 1;
    const EXCHANGE_GOODS_DESC = "兑换商品";
}