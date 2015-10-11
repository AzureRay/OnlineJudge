<?php
namespace Home\Controller;

use Think\Controller;

class ProblemController extends TemplateController {

	public function _initialize() {
		self::$isNeedLogin = true;
		parent::_initialize();
	}

	public function detail() {
		echo 'hehe';
	}

	public function plist() {

	}

	public function status() {

	}

	public function submit() {

	}
}