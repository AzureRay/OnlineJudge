<?php
/**
 * drunk , fix later
 * Created by PhpStorm.
 * User: jiaying
 * Date: 15/10/18
 * Time: 03:46
 */

namespace Zadmin\Controller;

class IndexController extends TemplateController {

    public function _initialize() {
        $this->isNeedFilterSql = true;
        parent::_initialize();
    }

    public function index() {

    }
}