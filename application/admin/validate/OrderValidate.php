<?php

namespace app\admin\validate;

use think\Validate;

class OrderValidate extends Validate
{

    protected $rule = [
        'express_company_uuid' => 'require',
        'express_code' => 'require|max:64',
    ];

    protected $message = [
        'express_company_uuid.require' => '请选择物流公司',
        'express_code.require' => '请填写物流单号',
        'express_code.max' => '物流单号长度不能超过64'
    ];

    protected $scene = [
        'editDeliveryPost' => ["express_code","express_company_uuid"]
    ];
}