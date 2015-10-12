<?php
namespace Home\Controller;

use Think\Controller;

class ProblemController extends TemplateController {

	public function _initialize() {
		parent::_initialize();
	}

	public function detail() {
		layout(true);
		$this->display();
	}

	public function plist() {

	}

	public function status() {

	}

	public function submit() {

	}
}