<?php
namespace Home\Controller;

use Home\Model\UserModel;
use Home\Model\PrivilegeModel;
use Think\Controller;

class TemplateController extends Controller
{

    protected $userInfo = null;

    protected $isNeedLogin = false;
    protected $isNeedFilterSql = false;

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
            redirect(U('Home/User/login'), 1, 'Please Login First!');
        }
    }

    private function initSqlInjectionFilter() {
        if (function_exists('sqlInjectionFilter') && $this->isNeedFilterSql) {
            dbg('sqlInjectionFilter');
            sqlInjectionFilter();
        }
    }

    protected function alertError($errmsg, $url = '') {
        $url = empty($url) ? "window.history.back();" : "location.href=\"{$url}\";";
        echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
        echo "<script>function Mytips(){alert('{$errmsg}');{$url}}</script>";
        echo "</head><body onload='Mytips()'></body></html>";
        exit;
    }

    protected function auto_display($view = null, $layout = true) {
        layout($layout);
        $this->display($view);
    }

    protected function initSessionByUserId($userId) {
        session('user_id', $userId);
        $_privileges = PrivilegeModel::instance()->getPrivilegesByUserId($userId);
        foreach ($_privileges as $privilege) {
            session($privilege['rightstr'], true);
        }
    }
}
