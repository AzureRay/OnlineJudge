<?php
namespace Home\Controller;
use Home\Model\UserModel;

class UserController extends TemplateController {

	public function _initialize() {
		parent::_initialize();
	}

	public function login() {
		if (empty($this->userInfo['userId'])) {
			$userId = I('get.userId', '', 'trim,htmlspecialchars');
			$password = I('get.password', '', 'trim,htmlspecialchars');
			$res = UserModel::instance()->loginByUidPassword($userId, $password);
			if ($res['code'] != 1001) {
				echo $res['msg'];
			} else {
				echo $res['msg'];
			}
		} else {
			echo 'online';
		}
	}

	public function register() {

	}

	public function modify() {

	}

	public function logout() {

	}

	public function doLogin() {

	}

	public function doRegister() {

	}

	public function doModify() {

	}
}