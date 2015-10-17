<?php
namespace Teacher\Model;

class AdminjudgeModel
{

    public function upd_question() {
        $judgeid = intval($_POST['judgeid']);
        $tmp = M("ex_judge")->field('creator,isprivate')
            ->where('judge_id=%d', $judgeid)->find();
        $creator = $prirow['creator'];
        $private2 = $prirow['isprivate'];
        if (!$tmp || !checkAdmin(4, $tmp['creator'])) {
            return -1;
        } else if ($tmp['isprivate'] == 2 && !checkAdmin(1)) {
            return -1;
        } else {
            $arr['question'] = test_input($_POST['judge_des']);
            $arr['answer'] = $_POST['answer'];
            $arr['point'] = test_input($_POST['point']);
            $arr['easycount'] = intval($_POST['easycount']);
            $arr['isprivate'] = intval($_POST['isprivate']);
            $result = M('ex_judge')->where('judge_id=%d', $judgeid)->data($arr)
                ->save();
            if ($result !== false)
                return 1;
            else
                return -2;
        }
    }

    public function add_question() {
        $arr['question'] = test_input($_POST['judge_des']);
        $arr['point'] = test_input($_POST['point']);
        $arr['answer'] = $_POST['answer'];
        $arr['easycount'] = intval($_POST['easycount']);
        $arr['isprivate'] = intval($_POST['isprivate']);
        $arr['creator'] = $_SESSION['user_id'];
        $arr['addtime'] = date('Y-m-d H:i:s');
        if (M('ex_judge')->add($arr))
            return true;
        else
            return false;
    }
}

?>