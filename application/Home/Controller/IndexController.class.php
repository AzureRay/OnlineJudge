<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UserModel;

class IndexController extends TemplateController {

	public function _initialize() {
		parent::_initialize();
	}

	public function index() {
		dbg(I('session.'));
		echo 'xxx';
	}

	public function
}