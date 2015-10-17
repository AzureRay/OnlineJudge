<?php
namespace Teacher\Model;

class AdminproblemModel
{

    public function addprogram($eid) {
        $ansnum = I('post.numanswer', 0, 'intval');
        $sql = "DELETE FROM `exp_question` WHERE `exam_id`={$eid} AND `type`='4'";
        M()->execute($sql);
        for ($i = 1; $i <= $ansnum; $i++) {
            $programid = test_input($_POST["answer$i"]);
            if (!is_numeric($programid)) {
                return false;
            } else {
                $programid = intval($programid);
                $arr['exam_id'] = $eid;
                $arr['type'] = 4;
                $arr['question_id'] = $programid;
                M('exp_question')->data($arr)->add();
                M('problem')->where('problem_id=%d', $programid)
                    ->data(array("defunct" => "Y"))->save();
            }
        }
        return true;
    }

    public function getproblemans($eid, $type) {
        switch ($type) {
            case 1:
                return $this->getchooseans($eid);
                break;

            case 2:
                return $this->getjudgeans($eid);
                break;

            case 3:
                return $this->getfillans($eid);
                break;

            case 4:
                return $this->getfillans2($eid);
                break;

            case 5:
                return $this->getprogram($eid);
                break;
        }
    }

    private function getchooseans($eid) {
        $ans = array();
        $sql = "SELECT `ex_choose`.`choose_id`,`question`,`ams`,`bms`,`cms`,`dms`,`answer` FROM `ex_choose`,`exp_question`
		WHERE `exam_id`='$eid' AND `type`='1' AND `ex_choose`.`choose_id`=`exp_question`.`question_id` ORDER BY `choose_id`";
        $ans = M()->query($sql);
        return $ans;
    }

    private function getjudgeans($eid) {
        $ans = array();
        $sql = "SELECT `ex_judge`.`judge_id`,`question`,`answer` FROM `ex_judge`,`exp_question`
		WHERE `exam_id`='$eid' AND `type`='2' AND `ex_judge`.`judge_id`=`exp_question`.`question_id` ORDER BY `judge_id`";
        $ans = M()->query($sql);
        return $ans;
    }

    private function getfillans($eid) {
        $ans = array();
        $sql = "SELECT `ex_fill`.`fill_id`,`question`,`answernum`,`kind` FROM `ex_fill`,`exp_question`
		WHERE `exam_id`='$eid' AND `type`='3' AND `ex_fill`.`fill_id`=`exp_question`.`question_id` ORDER BY `fill_id`";
        $ans = M()->query($sql);
        return $ans;
    }

    private function getfillans2($fillid) {
        $ans = M('fill_answer')->field('answer_id,answer')
            ->where('fill_id=%d', $fillid)
            ->order('answer_id')
            ->select();
        return $ans;
    }

    private function getprogram($eid) {
        $ans = array();
        $sql = "SELECT `question_id` as `program_id`,`title`,`description`,`input`,`output`,`sample_input`,`sample_output` FROM `exp_question`,`problem` WHERE `exam_id`='$eid' AND `type`='4' AND `question_id`=`problem_id`";
        $ans = M()->query($sql);
        return $ans;
    }
}

?>