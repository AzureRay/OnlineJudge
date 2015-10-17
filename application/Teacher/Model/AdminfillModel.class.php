<?php
namespace Teacher\Model;

class AdminfillModel
{
    public function upd_question() {
        $fillid = intval($_POST['fillid']);
        $tmp = M("ex_fill")->field('creator,isprivate')
            ->where('fill_id=%d', $fillid)->find();
        if (!$tmp || !checkAdmin(4, $tmp['creator'])) {
            return -1;
        } else if ($tmp['isprivate'] == 2 && !checkAdmin(1)) {
            return -1;
        } else {
            $arr['question'] = test_input($_POST['fill_des']);
            $arr['point'] = test_input($_POST['point']);
            $arr['easycount'] = intval($_POST['easycount']);
            $arr['answernum'] = intval($_POST['numanswer']);
            $arr['kind'] = intval($_POST['kind']);
            $arr['isprivate'] = intval($_POST['isprivate']);
            $result = M('ex_fill')->where('fill_id=%d', $fillid)->data($arr)
                ->save();
            if ($result !== false) {
                $sql = "DELETE FROM `fill_answer` WHERE `fill_id`=$fillid";
                M()->execute($sql);
                $ins = array();
                for ($i = 1; $i <= $arr['answernum']; $i++) {
                    $answer = test_input($_POST["answer$i"]);
                    $ins[] = array("fill_id" => "$fillid", "answer_id" => "$i", "answer" => "$answer");
                }
                if ($arr['answernum'])
                    M('fill_answer')->addAll($ins);
                return 1;
            } else
                return -2;
        }
    }

    public function add_question() {
        $arr['question'] = test_input($_POST['fill_des']);
        $arr['point'] = test_input($_POST['point']);
        $arr['easycount'] = intval($_POST['easycount']);
        $arr['answernum'] = intval($_POST['numanswer']);
        $arr['kind'] = intval($_POST['kind']);
        $arr['isprivate'] = intval($_POST['isprivate']);
        $arr['addtime'] = date('Y-m-d H:i:s');
        $arr['creator'] = $_SESSION['user_id'];
        $fillid = M('ex_fill')->add($arr);
        if ($fillid) {
            for ($i = 1; $i <= $arr['answernum']; $i++) {
                $answer = test_input($_POST["answer$i"]);
                $arr2['fill_id'] = $fillid;
                $arr2['answer_id'] = $i;
                $arr2['answer'] = $answer;
                M('fill_answer')->add($arr2);
            }
            return true;
        } else
            return false;
    }
}

?>