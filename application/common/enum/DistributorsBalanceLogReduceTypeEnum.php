<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-04
 * Time: 16:15
 */

namespace app\common\enum;

/**
 * 分销商余额消耗类型
 * Class DistributorsBalanceLogTypeEnum
 * @package app\common\enum
 */
class DistributorsBalanceLogReduceTypeEnum
{
    use EnumTrait;

    const WITHDRAW = 1;
    const WITHDRAW_DESC = "提现";
}