<?php
namespace Teacher\Model;

class AdminchooseModel
{

    public function upd_question() {
        $chooseid = intval($_POST['chooseid']);
        $tmp = M("ex_choose")->field('creator,isprivate')
            ->where('choose_id=%d', $chooseid)->find();
        if (!$tmp || !checkAdmin(4, $tmp['creator'])) {
            return -1;
        } else if ($tmp['isprivate'] == 2 && !checkAdmin(1)) {
            return -1;
        } else {
            $arr['question'] = test_input($_POST['choose_des']);
            $arr['ams'] = test_input($_POST['ams']);
            $arr['bms'] = test_input($_POST['bms']);
            $arr['cms'] = test_input($_POST['cms']);
            $arr['dms'] = test_input($_POST['dms']);
            $arr['point'] = test_input($_POST['point']);
            $arr['answer'] = $_POST['answer'];
            $arr['easycount'] = intval($_POST['easycount']);
            $arr['isprivate'] = intval($_POST['isprivate']);
            $result = M('ex_choose')->where('choose_id=%d', $chooseid)
                ->data($arr)->save();
            if ($result !== false)
                return 1;
            else
                return -2;
        }
    }

    public function add_question() {
        $arr['question'] = test_input($_POST['choose_des']);
        $arr['ams'] = test_input($_POST['ams']);
        $arr['bms'] = test_input($_POST['bms']);
        $arr['cms'] = test_input($_POST['cms']);
        $arr['dms'] = test_input($_POST['dms']);
        $arr['answer'] = $_POST['answer'];
        $arr['creator'] = $_SESSION['user_id'];
        $arr['point'] = test_input($_POST['point']);
        $arr['addtime'] = date('Y-m-d H:i:s');
        $arr['easycount'] = intval($_POST['easycount']);
        $arr['isprivate'] = intval($_POST['isprivate']);
        if (M('ex_choose')->add($arr))
            return true;
        return false;
    }
}

?>