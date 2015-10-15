<?php
namespace Home\Controller;
use Home\Model\UserModel;
use Home\Model\LogsModel;
use Home\Model\PrivilegeModel;

class UserController extends TemplateController
{

    public function _initialize() {
        parent::_initialize();
    }

    public function login() {
        layout(true);
        $this->display();
    }

    public function register() {
        layout(true);
        $this->display();
    }

    public function modify() {
        layout(true);
        $this->display();
    }

    public function doLogin() {
        if (empty($this->userInfo['user_id'])) {
            $userId = I('post.userId', '', 'trim,htmlspecialchars');
            $password = I('post.password', '', 'trim,htmlspecialchars');
            $res = UserModel::instance()->isRightPassword($userId, $password);
            if ($res['code'] != 1001) {
                resultReturn($res['code'], array('msg' => $res['msg']));
            }
            else {
                do {
                    if (C('OJ_VIP_CONTEST')) {
                        $isAdmin = PrivilegeModel::instance()->isAdministrator($userId);
                        if ($isAdmin) {
                            break;
                        }
                        else {
                            $today = date('Y-m-d');
                            $ip = get_client_ip();
                            $where = array('user_id' => $userId, 'ip' => array('neq', $ip), 'time' => array('egt', $today));
                            $order = array('time' => 'desc');
                            $res = LogsModel::instance()->getLoginlog($where, 1, $order);
                            if (!empty($res)) {
                                resultReturn(1002, array('msg' => '比赛期间请不要在不同机器上登录账号！请联系管理员!'));
                            }
                        }
                    }
                } while (false);

                $this->initSessionByUserId($userId);
                $_password = UserModel::instance()->generatePassword($password);
                LogsModel::instance()->add2Loginlog($userId, $_password);
                $this->success('欢迎使用SDIBTOJ系统,加油AC吧!', U('Index/index'), 3);
            }
        }
        else {
            resultReturn(1002, array('msg' => '您已经登陆！'));
        }
    }

    public function doRegister() {
        $userId = I('post.userId', '');
        $unick = I('post.nick', '');
        $password = I('post.password', '');
        $rptPassword = I('post.rptpassword', '');
        $school = I('post.school', '');
        $email = I('post.email', '');

        $vcode = I('post.vcode', '');

        $sessionVCode = session('vcode');
        if ($sessionVCode != $vcode || empty($vcode)) {
            session('vcode', null);
            resultReturn(1002, array('msg' => '验证码错误!'));
        }

        $this->filterParam($userId, $unick, $password, $rptPassword, $school, $email);

        $userModel = UserModel::instance();
        $user = $userModel->getUserByUid($userId, array('user_id'));
        if (empty($user)) {
            $res = $userModel->addUserInfo($userId, $unick, $password, $school, $email);
            if ($res > 0) {
                $this->initSessionByUserId($userId);
                $_password = $userModel->generatePassword($password);
                LogsModel::instance()->add2Loginlog($userId, $_password);
                $this->success('欢迎使用SDIBTOJ系统,加油AC吧!', U('Index/index'), 3);
            }
            else {
                resultReturn(1002, array('msg' => '系统错误,注册失败!'));
            }
        }
        else {
            resultReturn(1002, array('msg' => '用户已存在!'));
        }
    }

    public function doModify() {
        if (empty($this->userInfo)) {
            redirect(U('login'), 1, '请先登录账号!');
        }
        else {
            $userId = session('user_id');
            $unick = I('post.nick', $userId);
            $password = I('post.password', '');
            $npassword = I('post.npassword', '');
            $rptPassword = I('post.rptpassword', '');
            $school = I('post.school', '');
            $email = I('post.email', '');

            $res = UserModel::instance()->isRightPassword($userId, $password);
            if ($res['code'] != 1001) {
                resultReturn($res['code'], array('msg' => $res['msg']));
            }
            else {
                if (strlen($npassword) != 0) {
                    $password = $npassword;
                }
                else {
                    $rptpassword = $password;
                }

                $this->filterParam($userId, $unick, $password, $rptPassword, $school, $email);
                $password = UserModel::instance()->generatePassword($password);
                $where = array('user_id' => $userId);
                $option = array('nick' => $unick, 'school' => $school, 'email' => $email, 'password' => $password,);
                UserModel::instance()->updateUserInfo($where, $option);
                $this->success('个人信息修改成功~', U('modify'), 2);
            }
        }
    }

    public function doLogout() {
        session('user_id', null);
        session_destroy();
        $this->success('记得下次再来AC哦!', U('Index/index'), 2);
    }

    private function filterParam($userId, &$unick, $password, $rptpassword, $school, $email) {
        if (!isValidStringLength($userId, 3, 20)) {
            resultReturn(1002, array('msg' => '用户ID长度限制在3-20之间!'));
        }
        if (!isValidUserId($userId)) {
            resultReturn(1002, array('msg' => '用户ID只能包含数字和字母!'));
        }

        if (!isValidStringLength($unick, -1, 100)) {
            resultReturn(1002, array('msg' => '用户昵称长度不符合规范!'));
        }

        if (empty($unick)) {
            $unick = $userId;
        }

        if (strcmp($password, $rptpassword) != 0) {
            resultReturn(1002, array('msg' => '密码填写不一致!'));
        }

        if (!isValidStringLength($password, 6)) {
            resultReturn(1002, array('msg' => '密码长度至少6位!'));
        }

        if (!isValidStringLength($school, -1, 100)) {
            resultReturn(1002, array('msg' => '学校名称长度不符合规范!'));
        }

        if (!isValidStringLength($email, -1, 100)) {
            resultReturn(1002, array('msg' => '邮箱长度不符合规范!'));
        }
        else {
            if (!empty($email) && !isValidEmail($email)) {
                resultReturn(1002, array('msg' => '邮箱格式不符合规范!'));
            }
        }
    }
}
