<?php
namespace Exam\Controller;

use Think\Controller;
use Home\Model\UserModel;

class IndexController extends TemplateController
{

    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        layout(true);
        $this->assign('row', array());
        $this->display();
    }
}
