<?php
namespace Teacher\Controller;

class IndexController extends TemplateController
{

    public function index() {
        $sch = getexamsearch();
        $key = set_get_key();
        $mypage = splitpage('exam', $sch['sql']);
        $row = M('exam')->field('exam_id,title,start_time,end_time,creator')
            ->where($sch['sql'])->order('exam_id desc')
            ->limit($mypage['sqladd'])->select();
        $this->assign('row', $row);
        $this->assign('mypage', $mypage);
        $this->assign('search', $sch['search']);
        $this->assign('mykey', $key);
        $this->auto_display();
    }

    public function choose() {
        $sch = getproblemsearch();
        $key = set_get_key();
        $isadmin = checkAdmin(1);
        $mypage = splitpage('ex_choose', $sch['sql']);
        $numofchoose = 1 + ($mypage['page'] - 1) * $mypage['eachpage'];
        $row = M('ex_choose')->field('choose_id,question,creator,point,easycount')
            ->where($sch['sql'])->order('choose_id asc')->limit($mypage['sqladd'])
            ->select();
        $this->assign('row', $row);
        $this->assign('mypage', $mypage);
        $this->assign('numofchoose', $numofchoose);
        $this->assign('isadmin', $isadmin);
        $this->assign('mykey', $key);
        $this->assign('search', $sch['search']);
        $this->assign('problem', $sch['problem']);
        $this->auto_display();
    }

    public function judge() {

        $sch = getproblemsearch();
        $key = set_get_key();
        $isadmin = checkAdmin(1);
        $mypage = splitpage('ex_judge', $sch['sql']);
        $numofjudge = 1 + ($mypage['page'] - 1) * $mypage['eachpage'];
        $row = m('ex_judge')->field('judge_id,question,creator,point,easycount')
            ->where($sch['sql'])->order('judge_id asc')->limit($mypage['sqladd'])
            ->select();
        $this->assign('row', $row);
        $this->assign('numofjudge', $numofjudge);
        $this->assign('isadmin', $isadmin);
        $this->assign('mykey', $key);
        $this->assign('mypage', $mypage);
        $this->assign('search', $sch['search']);
        $this->assign('problem', $sch['problem']);
        $this->auto_display();
    }

    public function fill() {
        $sch = getproblemsearch();
        $key = set_get_key();
        $isadmin = checkAdmin(1);
        $mypage = splitpage('ex_fill', $sch['sql']);
        $numoffill = 1 + ($mypage['page'] - 1) * $mypage['eachpage'];
        $row = m('ex_fill')->field('fill_id,question,creator,point,easycount,kind')
            ->where($sch['sql'])->order('fill_id asc')->limit($mypage['sqladd'])
            ->select();
        $this->assign('row', $row);
        $this->assign('mypage', $mypage);
        $this->assign('numoffill', $numoffill);
        $this->assign('isadmin', $isadmin);
        $this->assign('mykey', $key);
        $this->assign('search', $sch['search']);
        $this->assign('problem', $sch['problem']);
        $this->auto_display();
    }

    public function point() {
        if (!checkAdmin(1)) {
            $this->error('Sorry,Only admin can do');
        }
        $pnt = M('ex_point')->order('point_pos')->select();
        $this->assign('pnt', $pnt);
        $this->auto_display();
    }

    public function dosort() {
        if (IS_AJAX && I('get.id') && I('get.pos')) {
            $arr['point_id'] = intval($_GET['id']);
            $arr['point_pos'] = intval($_GET['pos']);
            M('ex_point')->data($arr)->save();
            echo "success";
        } else
            echo "wrong method";
    }
}

?>