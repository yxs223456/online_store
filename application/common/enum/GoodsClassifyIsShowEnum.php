<?php
/**
 * Created by PhpStorm.
 * User: yangxiushan
 * Date: 2020-02-29
 * Time: 13:58
 */

namespace app\common\enum;

/**
 * 商品分类是否显示
 * Class GoodsClassifyIsShowEnum
 * @package app\common\enum
 */
class GoodsClassifyIsShowEnum
{
    use EnumTrait;

    const YES = 1;
    const YES_DESC = "正常";

    const NO = 0;
    const NO_DESC = "不显示";
}