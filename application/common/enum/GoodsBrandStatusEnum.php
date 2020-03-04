<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-03-02
 * Time: 14:16
 */

namespace app\common\enum;

/**
 * 品牌状态
 * Class GoodsBrandStatusEnum
 * @package app\common\enum
 */
class GoodsBrandStatusEnum
{
    use EnumTrait;

    const YES = 1;
    const YES_DESC = "正常";

    const FORBIDDEN = 0;
    const FORBIDDEN_DESC = "禁用";
}