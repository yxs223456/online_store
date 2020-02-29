<?php

namespace app\admin\model;

use think\Model;

class Goods extends Model {
    const IS_SALE_YES = 1;
    const IS_SALE_NO = 0;

    const IS_DELETE_YES = 1;
    const IS_DELETE_NO = 0;
}