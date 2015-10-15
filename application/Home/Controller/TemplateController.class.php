<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UserModel;
use Home\Model\PrivilegeModel;

class TemplateController extends Controller
{

    protected $userInfo = null;
    protected $isNeedLogin = false;
    protected $isNeedFilterSql = true;

    public function _initialize() {

        header("Pragma: no-cache");
         // HTTP/1.0
        header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
         // HTTP/1.1

        $this->initSqlInjectionFilter();
        $this->initLoginUserInfo();
    }

    private function initLoginUserInfo() {
        $userId = session('user_id');
        if (!empty($userId)) {
            $field = array('user_id', 'nick');
            $this->userInfo = UserModel::instance()->getUserByUid($userId, $field);
        }
        if (empty($userId) && $this->isNeedLogin) {
            redirect(U('User/login'));
        }
    }

    private function initSqlInjectionFilter() {
        if (function_exists('sqlInjectionFilter') && $this->isNeedFilterSql) {
            sqlInjectionFilter();
        }
    }

    protected function initSessionByUserId($userId) {
        session('user_id', $userId);
        $_privileges = PrivilegeModel::instance()->getPrivilegesByUserId($userId);
        foreach ($_privileges as $privilege) {
            session($privilege['rightstr'], true);
        }
    }
}
