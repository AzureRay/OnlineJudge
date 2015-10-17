<?php
namespace Home\Controller;

use Home\Model\UserModel;

class IndexController extends TemplateController
{

    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $this->auto_display();
    }

    public function faqs() {
        $this->auto_display();
    }

    public function ranklist() {
        $page = I('get.page', 1, 'intval');
        $pageSize = 50;
        if ($page < 1) {
            $page = 1;
        }
        $userId = I('get.userId', '');
        $unick = I('get.nick', '');

        $query = array();
        if (!empty($userId)) {
            $query['user_id'] = array('like', '%' . $userId . '%');
        }
        if (!empty($unick)) {
            $query['nick'] = array('like', '%' . $unick . '%');
        }
        $query['limit'] = $pageSize;
        $query['page'] = $page;
        $query['order'] = array('solved' => 'desc', 'submit');
        $field = array('user_id', 'nick', 'solved', 'submit');
        $users = UserModel::instance()->getUserByQuery($query, $field);
        $this->assign('ranklists', $users);
        $this->auto_display();
    }

    public function mail() {
        // mail 功能页面放在index, 发送接口放在user
        $this->auto_display();
    }
}
