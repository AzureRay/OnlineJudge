<?php
namespace Home\Controller;
use Think\Controller;

import('Home.Model.UserModel');

class IndexController extends TemplateController {

	public function _initialize() {
		parent::_initialize();
	}

	public function index() {
		echo 'xxx';
	}
}