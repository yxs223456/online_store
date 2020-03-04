<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-02-29
 * Time: 13:59
 */

namespace app\common\enum;

/**
 * 商品分类是否显示
 * Class GoodsSpecClassifyIsShowEnum
 * @package app\common\enum
 */
class GoodsSpecClassifyIsShowEnum
{
    use EnumTrait;

    const YES = 1;
    const YES_DESC = "显示";

    const NO = 0;
    const NO_DESC = "不显示";
}