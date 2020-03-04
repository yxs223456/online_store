<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-03
 * Time: 10:19
 */

namespace app\common\enum;

/**
 * 商品评价状态
 * Class GoodsAppraiseStatusEnum
 * @package app\common\enum
 */
class GoodsAppraiseStatusEnum
{
    use EnumTrait;

    const NORMAL = 1;
    const NORMAL_DESC = "正常";

    const USER_REVOKE = 2;
    const USER_REVOKE_DESC = "用户撤销";

    const ADMIN_REVOKE = 3;
    const ADMIN_REVOKE_DESC = "管理员撤销";
}