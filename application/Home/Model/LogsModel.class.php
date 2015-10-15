<?php
namespace Home\Model;

use Think\Exception;

class LogsModel
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

    public function getLoginlog($where, $limit = 1, $order = array()) {
        $logDao = M('loginlog');
        $res = $logDao->where($where)->order($order)->limit($limit)->select();
        return $res;
    }

    public function add2Loginlog($userId, $password) {
        $logDao = M('loginlog');
        $now = date('Y-m-d H:i:s');
        $ip = get_client_ip();
        $option = array('user_id' => $userId, 'password' => $password, 'ip' => $ip, 'time' => $now);
        try {
            $logDao->data($option)->add();
        }
        catch(Exception $e) {
        }
    }
}
