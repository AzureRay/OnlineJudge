<?php
namespace Home\Controller;
use Home\Model\UserModel;

class UserController extends TemplateController {

	public function _initialize() {
		parent::_initialize();
	}

	public function login() {
	}

	public function register() {
	}

	public function modify() {
	}

	public function logout() {
	}

	public function doLogin() {
		if (empty($this->userInfo['userId'])) {
			$userId = I('get.userId', '', 'trim,htmlspecialchars');
			$password = I('get.password', '', 'trim,htmlspecialchars');
			$res = UserModel::instance()->loginByUidPassword($userId, $password);
			if ($res['code'] != 1001) {
				errorReturn($res['code'], $res['msg']);
			} else {
				$this->initSessionByUserId($userId);
				echo $res['msg'];
			}
		} else {
			errorReturn(1002, '您已经登陆！');
		}
	}

	public function doRegister() {
		$userId = I('post.userId', '');
		$unick  = I('post.nick', $userId);
		$password = I('post.password', '');
		$rptPassword = I('post.rptpassword', '');
		$school = I('post.school', '');
		$email  = I('post.email', '');

		$vcode  = I('post.vcode', '');

		$sessionVCode = session('vcode');
		if ($sessionVCode != $vcode || empty($vcode)) {
			session('vcode', null);
			errorReturn(1002, array('msg' => '验证码错误!'));
		}

		$this->filterParam($userId, $unick, $password, $rptpassword, $school, $email);

		$userModel = UserModel::instance();
		$user = $userModel->getUserByUid($userId, array('user_id'));
		if (empty($user)) {
			$res = $userModel->addUserInfo($userId, $unick, $password, $school, $email);
			if ($res > 0) {
				// TODO do login thing
			} else {
				errorReturn(1002, array('msg' => '系统错误,注册失败!'));
			}
		} else {
			errorReturn(1002, array('msg' => '用户已存在!'));
		}
	}

	public function doModify() {
		$userId = I('post.userId', '');
		$unick  = I('post.nick', $userId);
		$password = I('post.password', '');
		$rptPassword = I('post.rptpassword', '');
		$school = I('post.school', '');
		$email  = I('post.email', '');

		$this->filterParam($userId, $unick, $password, $rptpassword, $school, $email);

	}

	public function doLogout() {
		session('userId', null);
		session('[destory]');
	}

	private function filterParam($userId, $unick, $password, $rptpassword, $school, $email) {
		if (!isValidStringLength($userId, 3, 20)) {
			errorReturn(1002, array('msg' => '用户ID长度限制在3-20之间!'));
		}
		if (!isValidUserId($userId)) {
			errorReturn(1002, array('msg' => '用户ID只能包含数字和字母!'));
		}

		if (!isValidStringLength($unick, -1, 100)) {
			errorReturn(1002, array('msg' => '用户昵称长度不符合规范!'));
		}

		if (strcmp($password, $rptpassword) != 0) {
			errorReturn(1002, array('msg' => '密码填写不一致!'));
		}

		if (!isValidStringLength($password, 6)) {
			errorReturn(1002, array('msg' => '密码长度至少6位!'));
		}

		if (!isValidStringLength($school, -1, 100)) {
			errorReturn(1002, array('msg' => '学校名称长度不符合规范!'));
		}

		if (!isValidStringLength($email, -1, 100)) {
			errorReturn(1002, array('msg' => '邮箱长度不符合规范!'));
		} else {
			if (!empty($email) && !$isValidEmail($email)) {
				errorReturn(1002, array('msg' => '邮箱格式不符合规范!'));
			}
		}
	}
}