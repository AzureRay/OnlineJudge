<?php
namespace Teacher\Model;

class AdminexamModel
{
    public function upd_exam() {
        $eid = intval($_POST['examid']);
        $tmp = M("exam")->field('creator')
            ->where('exam_id=%d', $eid)->find();
        if (!$tmp || !checkAdmin(4, $tmp['creator'])) {
            return -1;
        } else {
            $arr['start_time'] = intval($_POST['syear']) . "-" . intval($_POST['smonth']) . "-" . intval($_POST['sday']) . " " . intval($_POST['shour']) . ":" . intval($_POST['sminute']) . ":00";
            $arr['end_time'] = intval($_POST['eyear']) . "-" . intval($_POST['emonth']) . "-" . intval($_POST['eday']) . " " . intval($_POST['ehour']) . ":" . intval($_POST['eminute']) . ":00";
            $title = $_POST['examname'];
            if (get_magic_quotes_gpc()) {
                $title = stripslashes($title);
            }
            $arr['title'] = $title;
            $arr['choosescore'] = I('post.xzfs', 0, 'intval');
            $arr['judgescore'] = I('post.pdfs', 0, 'intval');
            $arr['fillscore'] = I('post.tkfs', 0, 'intval');
            $arr['prgans'] = I('post.yxjgfs', 0, 'intval');
            $arr['prgfill'] = I('post.cxtkfs', 0, 'intval');
            $arr['programscore'] = I('post.cxfs', 0, 'intval');
            $arr['isvip'] = I('post.isvip', 'Y');

            $result = M('exam')->where('exam_id=%d', $eid)->data($arr)->save();
            if ($result !== false)
                return 1;
            else
                return -2;
        }
    }

    public function add_exam() {
        $arr['start_time'] = intval($_POST['syear']) . "-" . intval($_POST['smonth']) . "-" . intval($_POST['sday']) . " " . intval($_POST['shour']) . ":" . intval($_POST['sminute']) . ":00";
        $arr['end_time'] = intval($_POST['eyear']) . "-" . intval($_POST['emonth']) . "-" . intval($_POST['eday']) . " " . intval($_POST['ehour']) . ":" . intval($_POST['eminute']) . ":00";
        $creator = $_SESSION['user_id'];
        $arr['creator'] = $creator;

        $arr['choosescore'] = I('post.xzfs', 0, 'intval');
        $arr['judgescore'] = I('post.pdfs', 0, 'intval');
        $arr['fillscore'] = I('post.tkfs', 0, 'intval');
        $arr['prgans'] = I('post.yxjgfs', 0, 'intval');
        $arr['prgfill'] = I('post.cxtkfs', 0, 'intval');
        $arr['programscore'] = I('post.cxfs', 0, 'intval');
        $arr['isvip'] = I('post.isvip', 'Y');

        $title = $_POST['examname'];
        if (get_magic_quotes_gpc()) {
            $title = stripslashes($title);
        }
        $arr['title'] = $title;
        if (M('exam')->add($arr))
            return true;
        return false;
    }

    public function addexamuser($eid) {
        if ($eid > 0) {
            M('ex_privilege')->where("rightstr='e$eid'")->delete();
            $pieces = explode("\n", trim($_POST['ulist']));
            if (count($pieces) > 0 && strlen($pieces[0]) > 0) {
                for ($i = 0; $i < count($pieces); $i++) {
                    $pieces[$i] = trim($pieces[$i]);
                }
            }
            $pieces = array_unique($pieces);
            if (count($pieces) > 0 && strlen($pieces[0]) > 0) {
                $randnum = rand(1, 39916800);
                $query = "INSERT INTO `ex_privilege`(`user_id`,`rightstr`,`randnum`) VALUES('" . trim($pieces[0]) . "','e$eid','$randnum')";
                for ($i = 1; $i < count($pieces); $i++) {
                    $randnum = rand(1, 39916800);
                    if (isset($pieces[$i]))
                        $query = $query . ",('" . trim($pieces[$i]) . "','e$eid','$randnum')";
                }
                M()->execute($query);
                return true;
            }
        } else {
            return false;
        }
    }

    public function getallscore($eid) {
        $allscore = M('exam')->field('choosescore,judgescore,fillscore,prgans,prgfill,programscore')
            ->where('exam_id=%d', $eid)->find();
        return $allscore;
    }

    public function getuserans($eid, $users, $type) {
        $arr = array();
        if ($type == 1 || $type == 2) {
            $row = M('ex_stuanswer')->field('question_id,answer')
                ->where("exam_id=%d and type=%d and user_id='%s'", $eid, $type, $users)
                ->select();
            if ($row) {
                foreach ($row as $key => $value) {
                    $arr[$value['question_id']] = $value['answer'];
                }
                unset($row);
            }
        } else if ($type == 3) {
            $row = M('ex_stuanswer')->field('question_id,answer_id,answer')
                ->where("exam_id=%d and type=%d and user_id='%s'", $eid, $type, $users)
                ->select();
            if ($row) {
                foreach ($row as $key => $value) {
                    $arr[$value['question_id']][$value['answer_id']] = $value['answer'];
                }
                unset($row);
            }
        }
        return $arr;
    }
}

?>