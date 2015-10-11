<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UserModel;

class TemplateController extends Controller {

	protected $userInfo = null;
	protected $privileges = null;
	protected $isNeedLogin = false;
	protected $isNeedFilterSql = true;

	public function _initialize() {

		header("Pragma: no-cache"); // HTTP/1.0
		header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");// HTTP/1.1

		$this->initSqlInjectionFilter();
		$this->initUserInfo();
	}

	protected function initSessionByUserId($userId) {
		session('userId', $userId);
		// TODO add log to loginlog and init privilege
	}

	private function initUserInfo() {

		$this->userInfo = UserModel::instance()->getLoginUserInfo();
		// TODO
		if (empty($this->userInfo) && $this->isNeedLogin) {
			// redirect to login page
		}
	}

	private function initSqlInjectionFilter() {
		if (function_exists('sqlInjectionFilter') && $this->isNeedFilterSql) {
			sqlInjectionFilter();
		}
	}
}