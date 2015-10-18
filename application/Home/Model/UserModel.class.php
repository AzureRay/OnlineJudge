<?php
namespace Home\Model;

use Think\Exception;
use Think\Model;

class UserModel
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

    public function isRightPassword($userId, $password) {
        if (empty($userId) || empty($password)) {
            return array('code' => 1002, 'msg' => '用户名和密码不能为空!');
        }
        $userInfo = $this->getUserByUid($userId, array('password'));
        if (empty($userInfo)) {
            return array('code' => 1002, 'msg' => '用户名不存在!');
        }
        $oldPassword = $userInfo['password'];
        if ($this->confirmPassword($password, $oldPassword)) {
            return array('code' => 1001, 'msg' => '登陆成功');
        } else {
            return array('code' => 1002, 'msg' => '密码错误!');
        }
    }

    public function getUnickByUid($userId) {
        $res = $this->getUserByUid($userId, array('nick'));
        return $res['nick'] ?: '';
    }

    public function getUserByUid($userId, $field = array()) {
        $userDao = M('users');
        $where = array('user_id' => $userId);
        $res = $userDao->field($field)->where($where)->find();
        return $res;
    }

    public function getUsersByUids($userIds, $field = array()) {
        $number = count($userIds);
        if ($number > 50 || $number < 1) {
            return array();
        } else {
            $userDao = M('users');
            $where = array('user_id' => array('in', $userIds));
            $return = $userDao->field($field)->where($where)->select();
            return $return;
        }
    }

    public function addUserInfo($userId, $unick, $_password, $school, $email) {
        $ip = get_client_ip();
        $nowstr = date('Y-m-d H:i:s');
        $accessTime = $nowstr;
        $registerTime = $nowstr;
        $password = $this->generatePassword($_password);

        $option = array('user_id' => $userId, 'nick' => $unick, 'school' => $school, 'email' => $email, 'ip' => $ip, 'password' => $password, 'reg_time' => $registerTime, 'accesstime' => $accessTime);

        $userDao = M('users');
        try {
            $res = $userDao->data($option)->add();
        } catch (Exception $e) {
            $res = 0;
        }
        return $res;
    }

    public function updateUserInfo($where, $option, $limit = 1) {
        $userDao = M('users');
        try {
            $userDao->where($where)->data($option)->limit($limit)->save();
        } catch (Exception $e) {
        }
    }

    public function getUserByQuery($query, $field = array()) {
        $where = array();
        $userDao = M('users');
        if (!empty($query['user_id'])) {
            $where['user_id'] = $query['user_id'];
        }
        if (!empty($query['nick'])) {
            $where['nick'] = $query['nick'];
        }
        if (!empty($query['defunct'])) {
            $where['defunct'] = $query['defunct'];
        }

        $total = $userDao->where($where)->count();

        $userDao->field($field)->where($where);
        if (!empty($query['order'])) {
            $order = $query['order'];
            $userDao->order($order);
        }
        if (!empty($query['limit'])) {
            $userDao->limit($query['limit']);
        }
        if (!empty($query['page'])) {
            $userDao->page($query['page']);
        }
        $res = $userDao->select();
        return array('total' => $total, 'data' => $res);
    }

    public function generatePassword($password, $md5ed = false) {
        if ($md5ed === false) {
            $password = md5($password);
        }
        $salt = sha1(rand());
        $salt = substr($salt, 0, 4);
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }

    private function confirmPassword($nowPassword, $oldPassword) {
        if ($this->isOldPassword($oldPassword)) {
            $password = md5($nowPassword);
            if ($password == $oldPassword) {
                return true;
            } else {
                return false;
            }
        }
        $_oldPassword = base64_decode($oldPassword);
        $salt = substr($_oldPassword, 20);
        $hash = base64_encode(sha1(md5($nowPassword) . $salt, true) . $salt);
        if (strcmp($hash, $oldPassword) == 0) {
            return true;
        } else {
            return false;
        }
    }

    private function isOldPassword($password) {
        for ($i = strlen($password) - 1; $i >= 0; $i--) {
            $c = $password[$i];
            if ('0' <= $c && $c <= '9') continue;
            if ('a' <= $c && $c <= 'f') continue;
            if ('A' <= $c && $c <= 'F') continue;
            return false;
        }
        return true;
    }
}
