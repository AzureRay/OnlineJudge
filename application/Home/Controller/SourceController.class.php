<?php
namespace Home\Controller;

use Think\Controller;
use Home\Model\SourceModel;

class SourceController extends TemplateController {

	public function _initialize() {
		$this->isNeedLogin = true;
		parent::_initialize();
	}

	public function detail() {

	}

	public function compare() {

	}

	public function compileError() {
		$solutionId = I('get.sid', 0, 'intval');
		$type = SourceModel::COMPILEERROR;
		$error = SourceModel::instance()->getErrorBySolutionId($solutionId, $type);
	    dbg($error);
    }

	public function runtimeError() {
		$solutionId = I('get.sid', 0, 'intval');
		$type = SourceModel::RUNTIMEERROR;
		$error = SourceModel::instance()->getErrorBySolutionId($solutionId, $type);
	    dbg($error);
    }
}