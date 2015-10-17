<?php
namespace Teacher\Controller;

class DelController extends TemplateController
{

    private $id = null;
    private $page = null;

    public function _initialize() {
        parent::_initialize();
        if (!check_get_key() || I('get.id') == '')
            $this->error('发生错误');
        $this->id = I('get.id', 0, 'intval');
        $this->page = I('get.page', 1, 'intval');
    }

    public function exam() {
        $tmp = M('exam')->field('creator')->where('exam_id=%d', $this->id)
            ->find();

        if (!checkAdmin(4, $tmp['creator'])) {
            $this->error('You have no privilege!');
        } else {
            $data['visible'] = 'N';
            M('exam')->data($data)->where('exam_id=%d', $this->id)->save();
            $this->success("考试删除成功", U("Teacher/Index/index", array('page' => $this->page)), 2);
            //if the exam was deleted
            //the info of exam was deleted
            // $query="DELETE FROM `exp_question` WHERE `exam_id`='$id'";
            // $query="DELETE FROM `ex_privilege` WHERE `rightstr`='e$id'";
            // $query="DELETE FROM `ex_stuanswer` WHERE `exam_id`='$id'";
            // $query="DELETE FROM `ex_student` WHERE `exam_id`='$id'";
        }
    }

    public function choose() {
        $tmp = M("ex_choose")->field('creator,isprivate')
            ->where('choose_id=%d', $this->id)->find();
        if (!$this->candel($tmp['isprivate'], $tmp['creator'])) {
            $this->error('You have no privilege!');
        } else {
            M('ex_choose')->where('choose_id=%d', $this->id)->delete();
            $sql = "DELETE FROM `exp_question` WHERE `question_id`=$this->id and `type`=1";
            M()->execute($sql);
            $sql = "DELETE FROM `ex_stuanswer` WHERE `question_id`=$this->id and `type`=1";
            M()->execute($sql);
            $this->success("选择题删除成功", U("Teacher/Index/choose", array('page' => $this->page)), 2);
        }
    }

    public function judge() {
        $tmp = M("ex_judge")->field('creator,isprivate')
            ->where('judge_id=%d', $this->id)->find();
        if (!$this->candel($tmp['isprivate'], $tmp['creator'])) {
            $this->error('You have no privilege!');
        } else {
            M('ex_judge')->where('judge_id=%d', $this->id)->delete();
            $sql = "DELETE FROM `exp_question` WHERE `question_id`=$this->id and `type`=2";
            M()->execute($sql);
            $sql = "DELETE FROM `ex_stuanswer` WHERE `question_id`=$this->id and `type`=2";
            M()->execute($sql);
            $this->success("判断题删除成功", U("Teacher/Index/judge", array('page' => $this->page)), 2);
        }
    }

    public function fill() {
        $tmp = M("ex_fill")->field('creator,isprivate')
            ->where('fill_id=%d', $this->id)->find();
        if (!$this->candel($tmp['isprivate'], $tmp['creator'])) {
            $this->error('You have no privilege!');
        } else {
            M('ex_fill')->where('fill_id=%d', $this->id)->delete();
            $sql = "DELETE FROM `fill_answer` WHERE `fill_id`=$this->id";
            M()->execute($sql);
            $sql = "DELETE FROM `exp_question` WHERE `question_id`=$this->id and `type`=3";
            M()->execute($sql);
            $sql = "DELETE FROM `ex_stuanswer` WHERE `question_id`=$this->id and `type`=3";
            M()->execute($sql);
            $this->success("填空题删除成功", U("Teacher/Index/fill", array('page' => $this->page)), 2);
        }
    }
}