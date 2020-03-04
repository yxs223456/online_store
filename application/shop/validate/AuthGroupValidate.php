<?php

namespace app\shop\validate;
use think\Validate;

class AuthGroupValidate extends Validate
{
    protected $rule = [
        "title|角色名" => 'require',
    ];

    protected $message  =   [

    ];

}