<?php

namespace app\admin\validate;

use think\Validate;

class GoodsValidate extends Validate
{

    protected $rule = [
        'uuid' => 'unique:goods',
        'goods_no' => 'require|max:20|unique:goods',
        'product_no' => 'require|max:20|unique:goods',
        'goods_name' => 'require|unique:goods',
        'market_price' => 'require|float',
        'shop_price' => 'require|float',
        'stock' => 'require|number',
        'weight' => 'number',
        'goods_unit' => 'require|max:10',
//        'is_sale' => 'require',
        'goods_classify_path' => 'require|max:255',
        'first_goods_classify' => 'require|max:32',
        'last_goods_classify' => 'require|max:32',
//        'brand' => 'require',
        'description' => 'require',
    ];

    protected $message = [
        'uuid.unique' => 'uuid重复,请重新提交',
        'goods_no.require' => '商品编号不能为空',
        'goods_no.max' => '商品编号长度不能超过20',
        'goods_no.unique' => '商品编号重复',
        'product_no.require' => '商品货号不能为空',
        'product_no.max' => '商品货号长度不能超过20',
        'product_no.unique' => '商品货号重复',
        'goods_name.require' => '商品名称不能为空',
        'goods_name.unique' => '商品名称重复',
        'market_price.require' => '市场价格不能为空',
        'market_price.float' => '市场价格格式不正确',
        'shop_price.require' => '商店价格不能为空',
        'shop_price.float' => '商店价格格式不正确',
        'stock.require' => '库存不能为空',
        'stock.number' => '库存格式不正确',
        'weight.number' => '排序格式不正确',
        'goods_unit.require' => '商品单位不能为空',
        'goods_unit.max' => '商品单位长度不能超过10',
        'goods_classify_path.require' => '商品分类不能为空',
        'first_goods_classify.require' => '商品分类不能为空',
        'last_goods_classify.require' => '商品分类不能为空',
        'description.require' => '商品描述不能为空',
    ];

    protected $scene = [
        'addPost' => ["uuid", "goods_no", "product_no", "goods_name", "market_price", "shop_price",
            "stock", "weight", "goods_unit", "goods_classify_path", "first_goods_classify", "last_goods_classify",
            "description"],
        'editPost' => ["market_price", "shop_price",
            "stock", "weight", "goods_unit", "goods_classify_path", "first_goods_classify", "last_goods_classify",
            "description"]
    ];
}