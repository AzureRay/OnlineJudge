<?php
namespace Home\Controller;

use Think\Controller;
use Home\Model\UserModel;

class IndexController extends TemplateController {

    public function index() {
    	$userModel = UserModel::instance();
    	echo $userModel->test();
    }
}