<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-02-29
 * Time: 11:55
 */

namespace app\common\enum;

/**
 * 商品规格是否允许添加图片
 * Class GoodsSpecAllowImageEnum
 * @package app\common\enum
 */
class GoodsSpecAllowImageEnum
{
    use EnumTrait;

    const YES = 1;
    const YES_DESC = "允许";

    const NO = 0;
    const NO_DESC = "不允许";
}