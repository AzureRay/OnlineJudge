<?php
namespace Exam\Controller;

use Think\Controller;

class IndexController extends TemplateController
{

    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $table = 'exam';
        $where['visible'] = 'Y';
        $mypage = splitpage($table, $where);

        $row = M($table)->field('exam_id,title,start_time,end_time')
            ->where($where)->order('exam_id desc')->limit($mypage['sqladd'])
            ->select();
        $this->assign('row', $row);
        $this->assign('mypage', $mypage);
        layout(true);
        $this->display();
    }

    public function about() {
        if (I('get.eid')) {
            $eid = I('get.eid', '', 'intval');
            $user_id = session('user_id');
            $row = D('Examadmin')->chkexamprivilege($eid, $user_id);
            if (!is_array($row)) {
                if ($row == 0) $this->error('You have no privilege!');
                else if ($row == -1)
                    $this->error('No Such Exam');
                else if ($row == -2)
                    $this->error('Do not login in diff machine,Please Contact administrator');
            }
            $isruning = D('Examadmin')->chkruning($row['start_time'], $row['end_time']);

            $name = M('users')->field('nick')->where("user_id='%s'", $user_id)->find();

            $this->assign('isruning', $isruning);
            $this->assign('row', $row);
            $this->assign('name', $name['nick']);

            $this->auto_display();
        } else {
            $this->error('No Such Exam');
        }
    }

    public function score() {
        $user_id = session('user_id');
        $row = M('users')->field('nick,email,reg_time')
            ->where("user_id='%s'", $user_id)->find();
        $query = "SELECT `title`,`exam`.`exam_id`,`score`,`choosesum`,`judgesum`,`fillsum`,`programsum` FROM `exam`,`ex_student` WHERE `ex_student`.`user_id`='" . $user_id . "'
		 AND `exam`.`visible`='Y' AND `ex_student`.`exam_id`=`exam`.`exam_id` ORDER BY `exam`.`exam_id` DESC";
        $score = M()->query($query);
        $this->assign('score', $score);
        $this->assign('row', $row);
        $this->auto_display();
    }
}
