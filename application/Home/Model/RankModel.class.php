<?php
namespace Home\Model;
use Think\Page;
class RankModel {

	private static $_instance = null;

	private function __construct() {}
	private function __clone() {}

	public static function instance() {
	    if (is_null(self::$_instance)) {
	        self::$_instance = new self;
	    }
	    return self::$_instance;
	}
	public function getUser($search,$field = array()){
        $userDao = M('users');
        if ($search == ''){
            $res=$this->paging($userDao,'',$field);
        }else {
            $keywords = '%'.$search.'%';
            $where['user_id|nick']=array('like',$keywords);
            $res=$this->paging($userDao,$where,$field);
        }      
        return $res;
	}
	public function paging($userDao,$where,$field){
	    import("ORG.Util.Page");//导入分页助手类
	    $total = $userDao->where($where)->count();
	    $num_per_page = 10;
	    $page = new Page($total,$num_per_page);     
	    $page->setConfig('header','篇文章');
	    $show = $page->show();	     
	    $PageContent=$userDao
	    ->where($where)
	    ->field($field)
	    ->limit("$page->firstRow,$page->listRows")
	    ->order("solved desc")
	    ->select();
	    
	    $Paging = array(
	        'PageContent' => $PageContent,
	        'show' => $show
	    );      
	    return $Paging;
	}
}