<?php
namespace Teacher\Controller;

class AddController extends TemplateController
{

    private $typename_ch = array('选择题', '判断题', '填空题', '考试');
    private $typename_en = array('choose', 'judge', 'fill', 'index');

    public function exam() {
        if (IS_POST) {
            if (!check_post_key())
                $this->error('发生错误！');
            if (!checkAdmin(2))
                $this->error('You have no privilege!');
            if (isset($_POST['examid'])) {
                $flag = D('Adminexam')->upd_exam();
                $this->checkflag($flag, 3);
            } else if (isset($_POST['examname'])) {
                $flag = D('Adminexam')->add_exam();
                $this->checkflag($flag, 3);
            }
        } else if (IS_GET && I('get.eid') != '') {
            $eid = I('get.eid', 0, 'intval');
            $page = I('get.page', 1, 'intval');
            $key = set_post_key();
            $row = M('exam')->where("exam_id=%d and visible='Y'", $eid)->find();
            if ($row) {
                if (!checkAdmin(4, $row['creator']))
                    $this->error('You have no privilege!');
                $this->assign('page', $page);
                $this->assign('row', $row);
                $this->assign('mykey', $key);
                $this->auto_display();
            } else {
                $this->error('No Such Exam!');
            }
        } else {
            $page = I('get.page', 1, 'intval');
            $key = set_post_key();
            $this->assign('page', $page);
            $this->assign('mykey', $key);
            $this->auto_display();
        }
    }

    public function choose() {
        if (IS_POST) {
            if (!check_post_key())
                $this->error('发生错误！');
            if (isset($_POST['chooseid'])) {
                $flag = D('Adminchoose')->upd_question();
                $this->checkflag($flag, 0);
            } else if (isset($_POST['choose_des'])) {
                $flag = D('Adminchoose')->add_question();
                $this->checkflag($flag, 0);
            }
        } else if (IS_GET && I('get.id') != '') {
            $id = I('get.id', 0, 'intval');
            $page = I('get.page', 1, 'intval');
            $pnt = M('ex_point')->select();
            $key = set_post_key();
            $row = M('ex_choose')
                ->field('choose_id,question,ams,bms,cms,dms,answer,creator,point,easycount,isprivate')
                ->where('choose_id=%d', $id)->find();
            if ($row) {
                if ($this->checkrow($row['isprivate'], $row['creator']) == -1)
                    $this->error('You have no privilege!');
                $this->assign('page', $page);
                $this->assign('row', $row);
                $this->assign('mykey', $key);
                $this->assign('pnt', $pnt);
                $this->auto_display();
            } else {
                $this->error('No Such Problem!');
            }
        } else {
            $pnt = M('ex_point')->select();
            $page = I('get.page', 1, 'intval');
            $key = set_post_key();
            $this->assign('page', $page);
            $this->assign('mykey', $key);
            $this->assign('pnt', $pnt);
            $this->auto_display();
        }
    }

    public function judge() {
        if (IS_POST) {
            if (!check_post_key()) {
                $this->error('发生错误！');
            }
            if (isset($_POST['judgeid'])) {
                $flag = D('Adminjudge')->upd_question();
                $this->checkflag($flag, 1);
            } else if (isset($_POST['judge_des'])) {
                $flag = D('Adminjudge')->add_question();
                $this->checkflag($flag, 1);
            }
        } else if (IS_GET && I('get.id') != '') {
            $id = I('get.id', 0, 'intval');
            $page = I('get.page', 1, 'intval');
            $pnt = M('ex_point')->select();
            $key = set_post_key();
            $row = M('ex_judge')->field('judge_id,question,answer,creator,point,easycount,isprivate')
                ->where('judge_id=%d', $id)->find();
            if ($row) {
                if ($this->checkrow($row['isprivate'], $row['creator']) == -1)
                    $this->error('You have no privilege!');
            } else {
                $this->error('No Such Problem!');
            }
            $this->assign('page', $page);
            $this->assign('row', $row);
            $this->assign('mykey', $key);
            $this->assign('pnt', $pnt);
            $this->auto_display();
        } else {
            $page = I('get.page', 1, 'intval');
            $pnt = M('ex_point')->select();
            $key = set_post_key();
            $this->assign('page', $page);
            $this->assign('mykey', $key);
            $this->assign('pnt', $pnt);
            $this->auto_display();
        }
    }

    public function fill() {
        if (IS_POST) {
            if (!check_post_key()) {
                $this->error('发生错误！');
            }
            if (isset($_POST['fillid'])) {
                $flag = D('Adminfill')->upd_question();
                $this->checkflag($flag, 2);
            } else if (isset($_POST['fill_des'])) {
                $flag = D('Adminfill')->add_question();
                $this->checkflag($flag, 2);
            }
        } else if (IS_GET && I('get.id') != '') {
            $id = I('get.id', 0, 'intval');
            $page = I('get.page', 1, 'intval');
            $pnt = M('ex_point')->select();
            $key = set_post_key();
            $row = M('ex_fill')
                ->field('fill_id,question,answernum,creator,point,easycount,kind,isprivate')
                ->where('fill_id=%d', $id)->find();
            if ($row) {
                if ($this->checkrow($row['isprivate'], $row['creator']) == -1)
                    $this->error('You have no privilege!');
                if ($row['answernum'] != 0) {
                    $ansrow = M('fill_answer')->field('answer_id,answer')
                        ->where('fill_id=%d', $id)->order('answer_id')->select();
                    $this->assign('ansrow', $ansrow);
                }
                $this->assign('page', $page);
                $this->assign('row', $row);
                $this->assign('mykey', $key);
                $this->assign('pnt', $pnt);
                $this->auto_display();
            } else {
                $this->error('No Such Problem!');
            }
        } else {
            $page = I('get.page', 1, 'intval');
            $pnt = M('ex_point')->select();
            $key = set_post_key();
            $this->assign('page', $page);
            $this->assign('mykey', $key);
            $this->assign('pnt', $pnt);
            $this->auto_display();
        }
    }

    public function point() {
        $action = I('post.action', '', 'htmlspecialchars');
        if ($action == 'add') {
            $data['point'] = I('post.point', '', 'addslashes');
            $id = M('ex_point')->data($data)->add();
            $data['id'] = $id;
            $this->ajaxReturn(json_encode($data));
        } else if ($action == 'del') {
            $id = I('post.id', 0, 'intval');
            M('ex_point')->delete($id);
            echo "ok";
        }
    }

    private function checkrow($pvt, $crt) {
        if ($pvt == 2 && !checkAdmin(1)) {
            return -1;
        }
        if (!checkAdmin(1)) {
            if ($pvt == 1 && $crt != $_SESSION['user_id'])
                return -1;
        }
        return 1;
    }

    private function checkflag($flag, $type, $second = 1) {
        $typech = $this->typename_ch[$type];
        $typeen = $this->typename_en[$type];
        if (is_bool($flag)) {
            if ($flag === true) {
                $page = I('post.page', 1, 'intval');
                $this->success("$typech 添加成功!", U("Teacher/Index/$typeen", array('page' => $page)), $second);
            } else
                $this->error("$typech 添加失败！");
        } else {
            if ($flag === -1) {
                $this->error('You have no privilege to modify it!');
            } else if ($flag === -2) {
                $this->error("$typech 修改失败！");
            } else if ($flag === 1) {
                $page = I('post.page', 1, 'intval');
                $this->success("$typech 修改成功!", U("Teacher/Index/$typeen", array('page' => $page)), $second);
            }
        }
    }
}

?>