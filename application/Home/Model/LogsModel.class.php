<?php
namespace Home\Model;

class LogsModel {

	private static $_instance = null;

	private function __construct() {}
	private function __clone() {}

	public static function instance() {
	    if (is_null(self::$_instance)) {
	        self::$_instance = new self;
	    }
	    return self::$_instance;
	}

	public function getLog($where, $limit = 1, $order) {
		$logDao = M('loginlog');
		$res = $logDao->where($where)->order($order)->limit($limit)->select();
		return $res;
	}
}