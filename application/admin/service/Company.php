<?php

namespace app\admin\service;

use app\admin\model\ExpressCompany;

class Company extends Base {

     public function __construct() {
         parent::__construct();
         $this->currentModel = new ExpressCompany();
     }
}