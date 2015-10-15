<?php
namespace Home\Model;

class PrivilegeModel
{

    private static $_instance = null;

    private function __construct() {
    }
    private function __clone() {
    }

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function getPrivilegesByUserId($userId) {
        $privilegeDao = M('privilege');
        $where = array('user_id' => $userId);
        $field = array('rightstr');
        $privileges = $privilegeDao->field($field)->where($where)->select();
        return $privileges;
    }

    public function isAdministrator($userId) {
        $privilegeDao = M('privilege');
        $where = array('user_id' => $userId, 'rightstr' => 'administrator');
        $res = $privilegeDao->field('user_id')->where($where)->find();
        return !empty($res);
    }
}
