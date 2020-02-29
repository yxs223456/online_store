<?php

namespace app\admin\validate;

use think\Validate;

class CompanyValidate extends Validate
{

    protected $rule = [
        "name" => "require|max:100|unique:express_company,is_delete^name",
        "code" => "require|max:20",
        "phone" => "require|max:20|mobile",
        "uuid" => "unique:express_company"
    ];

    protected $message = [
        "name.require" => "公司名称不能为空",
        "name.max" => "公司名不能超过100个字符",
        "name.unique" => "公司已存在",
        "code.require" => "公司代码不能为空",
        "code.max" => "公司代码不能超过20个字符",
        "phone.require" => "公司电话不能为空",
        "phone.max" => "公司电话不能超过20个字符",
        "phone.mobile" => "公司电话格式不正确",
        "uuid.unique" => "uuid重复,请重新提交"
    ];

    protected $scene = [
        "addpost" => ["name", "code", "phone", "uuid"],
        "editpost" => ["name", "code", "phone"],
    ];
}