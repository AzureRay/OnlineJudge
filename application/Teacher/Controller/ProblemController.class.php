<?php
namespace Teacher\Controller;

class ProblemController extends TemplateController
{

    private $eid = null;

    public function _initialize() {
        parent::_initialize();
        if (isset($_GET['eid']) && isset($_GET['type'])) {
            $this->eid = intval($_GET['eid']);
            $type = intval($_GET['type']);
            $this->assign('eid', $this->eid);
            $this->assign('type', $type);
            if (!$this->isowner($this->eid)) {
                $this->error('You have no privilege of this exam~');
            }
        } else if (isset($_POST['eid'])) {
            $this->eid = intval($_POST['eid']);
        } else {
            $this->error('No Such Exam!');
        }
    }

    public function add() {
        $type = I('get.type', 1, 'intval');
        switch ($type) {
            case 1:
                $this->addchoose();
                break;
            case 2:
                $this->addjudge();
                break;
            case 3:
                $this->addfill();
                break;
            case 4:
                $this->addprogram();
                break;
            default:
                $this->error('Invaild Path');
                break;
        }
    }

    private function addchoose() {

        $sch = getproblemsearch();
        $isadmin = checkAdmin(1);
        $mypage = splitpage('ex_choose', $sch['sql']);
        $numofchoose = 1 + ($mypage['page'] - 1) * $mypage['eachpage'];
        $row = M('ex_choose')->field('choose_id,question,creator,point,easycount')
            ->where($sch['sql'])->order('choose_id asc')->limit($mypage['sqladd'])
            ->select();

        $haveadded = array();
        if ($row) {
            foreach ($row as $value) {
                $haveadded[$value['choose_id']] = $this->checkadded($this->eid, 1, $value['choose_id']);
            }
        }
        $this->assign('row', $row);
        $this->assign('added', $haveadded);
        $this->assign('mypage', $mypage);
        $this->assign('numofchoose', $numofchoose);
        $this->assign('isadmin', $isadmin);
        $this->assign('search', $sch['search']);
        $this->assign('problem', $sch['problem']);

        layout(true);
        $this->display('choose');
    }

    private function addjudge() {
        $sch = getproblemsearch();
        $isadmin = checkAdmin(1);
        $mypage = splitpage('ex_judge', $sch['sql']);
        $numofjudge = 1 + ($mypage['page'] - 1) * $mypage['eachpage'];
        $row = m('ex_judge')->field('judge_id,question,creator,point,easycount')
            ->where($sch['sql'])->order('judge_id asc')->limit($mypage['sqladd'])
            ->select();
        $haveadded = array();
        if ($row) {
            foreach ($row as $value) {
                $haveadded[$value['judge_id']] = $this->checkadded($this->eid, 2, $value['judge_id']);
            }
        }
        $this->assign('row', $row);
        $this->assign('added', $haveadded);
        $this->assign('numofjudge', $numofjudge);
        $this->assign('isadmin', $isadmin);
        $this->assign('mypage', $mypage);
        $this->assign('search', $sch['search']);
        $this->assign('problem', $sch['problem']);

        $this->auto_display('judge');
        layout(true);
        $this->display('judge');
    }

    private function addfill() {
        $sch = getproblemsearch();
        $isadmin = checkAdmin(1);
        $mypage = splitpage('ex_fill', $sch['sql']);
        $numoffill = 1 + ($mypage['page'] - 1) * $mypage['eachpage'];
        $row = m('ex_fill')->field('fill_id,question,creator,point,easycount,kind')
            ->where($sch['sql'])->order('fill_id asc')->limit($mypage['sqladd'])
            ->select();
        $haveadded = array();
        if ($row) {
            foreach ($row as $value) {
                $haveadded[$value['fill_id']] = $this->checkadded($this->eid, 3, $value['fill_id']);
            }
        }
        $this->assign('added', $haveadded);
        $this->assign('mypage', $mypage);
        $this->assign('numoffill', $numoffill);
        $this->assign('isadmin', $isadmin);
        $this->assign('row', $row);
        $this->assign('search', $sch['search']);
        $this->assign('problem', $sch['problem']);
        layout(true);
        $this->display('fill');
    }

    public function addprogram() {
        if (IS_POST && I('post.eid')) {
            if (!check_post_key()) {
                $this->error('发生错误！');
            } else if (!checkAdmin(2)) {
                $this->error('You have no privilege of this exam');
            } else {
                $eid = I('post.eid', 0, 'intval');
                $flag = D('Adminproblem')->addprogram($eid);
                if ($flag === true)
                    $this->success('程序题添加成功', U('Teacher/Problem/addprogram', array('eid' => $eid, 'type' => 4)), 2);
                else
                    $this->error('Invaild Path');
            }
        } else {
            $ansrow = M('exp_question')->field('question_id')
                ->where('exam_id=%d and type=4', $this->eid)->order('question_id')
                ->select();
            $answernumC = M('exp_question')->where('exam_id=%d and type=4', $this->eid)
                ->count();
            $key = set_post_key();
            $this->assign('mykey', $key);
            $this->assign('answernumC', $answernumC);
            $this->assign('ansrow', $ansrow);
            layout(true);
            $this->display('program');
        }
    }

    public function addpte() {
        if (isset($_POST['eid']) && isset($_POST['id']) && isset($_POST['type']) && isset($_POST['sid'])) {
            $eid = intval($_POST['eid']);
            $quesid = intval($_POST['id']);
            $typeid = intval($_POST['type']);
            if ($this->isowner($eid) && $eid > 0 && $quesid > 0 && $typeid >= 1 && $typeid <= 3) {
                $arr['type'] = $typeid;
                $arr['exam_id'] = $eid;
                $arr['question_id'] = $quesid;
                if (M('exp_question')->add($arr))
                    echo "已添加";
                else
                    echo "添加失败";
            } else {
                echo "No Privilege";
            }
        } else {
            echo "Invaild path";
        }
    }

    public function delpte() {
        if (isset($_POST['eid']) && isset($_POST['id']) && isset($_POST['type']) && isset($_POST['sid'])) {
            $eid = intval($_POST['eid']);
            $quesid = intval($_POST['id']);
            $typeid = intval($_POST['type']);
            if ($this->isowner($eid) && $eid > 0 && $quesid > 0 && $typeid >= 1 && $typeid <= 3) {
                $arr['type'] = $typeid;
                $arr['exam_id'] = $eid;
                $arr['question_id'] = $quesid;
                if (M('exp_question')->where($arr)->delete())
                    echo "ok";
                else
                    echo "删除错误";
            } else {
                echo "No Privilege";
            }
        } else {
            echo "Invaild path";
        }
    }
}

?>
