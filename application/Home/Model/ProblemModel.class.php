<?php
namespace Home\Model;

class ProblemModel {

	private static $_instance = null;

	private function __construct() {}
	private function __clone() {}

	public static function instance() {
	    if (is_null(self::$_instance)) {
	        self::$_instance = new self;
	    }
	    return self::$_instance;
	}

	public function getProblemInfo($where, $field = array()) {
		$problemDao = M('problem');
		$res = $problemDao->field($field)->where($where)->find();
		return $res;
	}
}