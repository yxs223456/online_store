<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-04
 * Time: 16:15
 */

namespace app\common\enum;

/**
 * 用户积分增加类型
 * Class UserScoreLogAddTypeEnum
 * @package app\common\enum
 */
class UserScoreLogAddTypeEnum
{
    use EnumTrait;

    const BUY_GOODS = 1;
    const BUY_GOODS_DESC = "购买商品";

}