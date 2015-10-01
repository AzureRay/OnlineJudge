<?php
namespace Home\Model;

class UserModel {

	private static $_instance = null;

	private function __construct() {}
	private function __clone() {}

	public static function instance() {
	    if (is_null(self::$_instance)) {
	        self::$_instance = new self;
	    }
	    return self::$_instance;
	}

	public function loginByUidPassword($userId, $password) {
		if (empty($userId) || empty($password)) {
			return -1;
		}
		$userInfo = self::getUserByUid($userId);
		if (empty($userInfo)) {
			return -1;
		}

		// TODO check password and do login
	}

	public function getLoginUserInfo() {

	}

	public function getUnameByUid($userId) {

	}

	public function getUnickByUid($userId) {

	}

	public function getUserByUid($userId) {

	}

	public function getUsersByUids($userIds) {

	}
}