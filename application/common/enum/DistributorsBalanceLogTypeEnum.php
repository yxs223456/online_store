<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-04
 * Time: 15:39
 */

namespace app\common\enum;

/**
 * 分销商收入流水类型
 * Class DistributorsBalanceLogTypeEnum
 * @package app\common\enum
 */
class DistributorsBalanceLogTypeEnum
{
    use EnumTrait;

    const ADD = 1;
    const ADD_DESC = "增加";


    const REDUCE = 2;
    const REDUCE_DESC = "减少";
}