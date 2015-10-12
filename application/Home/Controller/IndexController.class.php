<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UserModel;

class IndexController extends TemplateController {

	public function _initialize() {
		parent::_initialize();
	}

	public function index() {
		layout(true);
		$this->display();
	}

	public function ranklist() {
		echo 'ranklist action';
	}
}