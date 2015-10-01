<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UserModel;

class TemplateController extends Controller {

	public $userInfo = null;
	public $isNeedLogin = false;
	private $ISDEBUG = false;

	public function _initialize() {

		header("Pragma: no-cache"); // HTTP/1.0
        header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");// HTTP/1.1

        $this->ISDEBUG = C('ISDEBUG');

        $this->initSqlInjectionFilter();
        $this->initUserInfo();
	}

	private function initUserInfo() {
		dbg("initUserInfo");
		$this->userInfo = UserModel::instance()->getLoginUserInfo();

		// TODO
		if (empty($this->userInfo) && $this->isNeedLogin) {
			// redirect to login page
		}
	}

	private function initSqlInjectionFilter() {
		dbg("initSqlInjectionFilter");
		if (function_exists ('sqlInjectionFilter')) {
            sqlInjectionFilter();
            dbg("sqlInjectionFilter function_exists");
        }
	}
}