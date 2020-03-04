<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-01
 * Time: 16:49
 */

namespace app\common\enum;

/**
 * 会员状态
 * Class UserStatusEnum
 * @package app\common\enum
 */
class UserStatusEnum
{
    use EnumTrait;

    const YES = 1;
    const YES_DESC = "正常";

    const FORBIDDEN = 0;
    const FORBIDDEN_DESC = "禁用";
}