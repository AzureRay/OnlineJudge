<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UserModel;
use Home\Model\RankModel;

class IndexController extends TemplateController {

	public function _initialize() {
		parent::_initialize();
	}

	public function index() {
		layout(true);
		$this->display();
	}

	public function ranklist() {
	    $search=I('get.search', '', 'trim,htmlspecialchars');
	    if(!empty($search)){
	        $user = RankModel::instance()->getUser($search,array('user_id','nick','solved','submit'));
	    }else {
	        $user = RankModel::instance()->getUser('',array('user_id','nick','solved','submit'));
	    }
	    dbg($user);
	}
}