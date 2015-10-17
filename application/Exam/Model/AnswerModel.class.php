<?php
namespace Exam\Model;

class AnswerModel
{

    /**
     * 答案保存
     * @param  string $user_id 用户ID
     * @param  number $eid 比赛编号
     * @param  number $type 题目类型
     * @param  boolean $issave 是保存试卷还是提交试卷,默认为保存
     */
    public function answersave($user_id, $eid, $type, $issave = true) {
        switch ($type) {
            case 1:
                return $this->savechoose($user_id, $eid, $issave);
                break;
            case 2:
                return $this->savejudge($user_id, $eid, $issave);
                break;
            case 3:
                return $this->savefill($user_id, $eid, $issave);
                break;
        }
    }

    public function getrightprogram($user_id, $eid, $start_timeC, $end_timeC) {
        $query = "SELECT distinct `question_id`,`result` FROM `exp_question`,`solution` WHERE `exam_id`='$eid' AND `type`='4' AND `result`='4'
		AND `in_date`>'$start_timeC' AND `in_date`<'$end_timeC' AND `user_id`='" . $user_id . "' AND `exp_question`.`question_id`=`solution`.`problem_id`";
        $row = M()->query($query);
        $row_cnt = count($row);
        return $row_cnt;
    }

    private function savechoose($user_id, $eid, $issave) {
        $cntchoose = 0;
        $tempsql = "";
        $right = 0;
        $chooseq = $this->getquestion($eid, 1, $issave);
        foreach ($chooseq as $value) {
            $id = $value['question_id'];
            if (isset($_POST["xzda$id"])) {
                $myanswer = trim($_POST["xzda$id"]);
                if ($cntchoose == 0) {
                    $tempsql = "INSERT INTO `ex_stuanswer` VALUES('$user_id','$eid','1','$id','1','$myanswer')";
                    $cntchoose = 1;
                } else {
                    $tempsql = $tempsql . ",('$user_id','$eid','1','$id','1','$myanswer')";
                }
                if (!$issave) {
                    if ($myanswer == $value['answer'])
                        $right++;
                }
            }
        }
        if (!empty($tempsql)) {
            $tempsql = $tempsql . " on duplicate key update `answer`=values(`answer`)";
            M()->execute($tempsql);
        }
        return $right;
    }

    private function savejudge($user_id, $eid, $issave) {
        $cntjudge = 0;
        $tempsql = "";
        $right = 0;
        $judgeq = $this->getquestion($eid, 2, $issave);
        foreach ($judgeq as $value) {
            $id = $value['question_id'];
            if (isset($_POST["pdda$id"])) {
                $myanswer = trim($_POST["pdda$id"]);
                if ($cntjudge == 0) {
                    $tempsql = "INSERT INTO `ex_stuanswer` VALUES('$user_id','$eid','2','$id','1','$myanswer')";
                    $cntjudge = 1;
                } else {
                    $tempsql = $tempsql . ",('$user_id','$eid','2','$id','1','$myanswer')";
                }
                if (!$issave) {
                    if ($myanswer == $value['answer'])
                        $right++;
                }
            }
        }
        if (!empty($tempsql)) {
            $tempsql = $tempsql . " on duplicate key update `answer`=values(`answer`)";
            M()->execute($tempsql);
        }
        return $right;
    }

    private function savefill($user_id, $eid, $issave) {
        $cntfill = 0;
        $tempsql = "";
        $fillq = $this->getquestion($eid, 3, $issave);
        if (!$issave) {
            $score = M('exam')->field('fillscore,prgans,prgfill')
                ->where('exam_id=%d', $eid)->find();
        }
        foreach ($fillq as $value) {
            $aid = $value['answer_id'];
            $fid = $value['fill_id'];
            $name = $fid . "tkda";
            if (isset($_POST["$name$aid"])) {
                $myanswer = $_POST["$name$aid"];
                $myanswer = test_input($myanswer);
                $myanswer = addslashes($myanswer);
                if ($cntfill == 0) {
                    $tempsql = "INSERT INTO `ex_stuanswer` VALUES('$user_id','$eid','3','$fid','$aid','$myanswer')";
                    $cntfill = 1;
                } else {
                    $tempsql = $tempsql . ",('$user_id','$eid','3','$fid','$aid','$myanswer')";
                }
                if (!$issave) {
                    $rightans = addslashes($value['answer']);
                    if ($myanswer == $rightans && strlen($myanswer) == strlen($rightans)) {
                        if ($value['kind'] == 1)
                            $fillsum += $score['fillscore'];
                        else if ($value['kind'] == 2)
                            $fillsum = $fillsum + $score['prgans'] / $value['answernum'];
                        else if ($value['kind'] == 3)
                            $fillsum = $fillsum + $score['prgfill'] / $value['answernum'];
                    }
                }
            }
        }
        if (!empty($tempsql)) {
            $tempsql = $tempsql . " on duplicate key update `answer`=values(`answer`)";
            M()->execute($tempsql);
        }
        if (!$issave)
            return $fillsum;
    }

    /**
     * 获取在一场考试中的不同种类的题目的编号
     * @param  number $eid Exam ID
     * @param  number $type Question ID
     * @param  boolean $issave 是保存试卷还是提交试卷,默认为保存
     * @return Array        Questions in Exam$eid
     */
    private function getquestion($eid, $type, $issave = true) {
        if ($issave) {
            if ($type == 3) {
                $query = "SELECT `fill_id`,`answer_id` FROM `fill_answer` WHERE `fill_id` IN
				( SELECT `question_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='$type')";
                $arr = M()->query($query);
            } else {
                $arr = M('exp_question')->field('question_id')
                    ->where('exam_id=%d and type=%d', $eid, $type)->select();
            }
        } else {
            if ($type == 1) {
                $sql = "SELECT `question_id`,`answer` FROM `ex_choose`,`exp_question` WHERE `exam_id`='$eid' AND `type`='1' AND `choose_id`=`question_id`";
            } else if ($type == 2) {
                $sql = "SELECT `question_id`,`answer` FROM `ex_judge`,`exp_question` WHERE `exam_id`='$eid' AND `type`='2' AND `judge_id`=`question_id`";
            } else if ($type == 3) {
                $sql = "SELECT `fill_answer`.`fill_id`,`answer_id`,`answer`,`answernum`,`kind` FROM `fill_answer`,`ex_fill` WHERE `fill_answer`.`fill_id`=`ex_fill`.`fill_id` AND `fill_answer`.`fill_id` IN ( SELECT `question_id` FROM `exp_question` WHERE `exam_id`='$eid' AND `type`='3')";
            }
            $arr = M()->query($sql);
        }
        return $arr;
    }
}

?>