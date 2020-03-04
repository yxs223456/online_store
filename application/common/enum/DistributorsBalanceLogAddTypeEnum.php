<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-04
 * Time: 16:15
 */

namespace app\common\enum;

/**
 * 分销商收入增加类型
 * Class DistributorsBalanceLogTypeEnum
 * @package app\common\enum
 */
class DistributorsBalanceLogAddTypeEnum
{
    use EnumTrait;

    const NONE = 1;
    const NONE_DESC = "销售提成";

    const SON = 2;
    const SON_DESC = "一级子分销商提成";

    const DESCENDANT = 3;
    const DESCENDANT_DESC = "非一级子分销商提成";

}