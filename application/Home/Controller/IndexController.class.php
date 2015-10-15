<?php
namespace Home\Controller;

use Think\Controller;
use Home\Model\UserModel;

class IndexController extends TemplateController
{

    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        layout(true);
        $this->display();
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
        dbg($users);
    }
}
