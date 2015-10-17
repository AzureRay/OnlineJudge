<?php
namespace Teacher\Controller;

use Think\Controller;

class MainController extends Controller
{
    public function _initialize() {

        if (!session('user_id'))
            $this->error('Please Login First!');
        if (!checkAdmin(3))
            $this->error('请先登陆管理员账号！');
    }

    protected function isowner($eid) {
        $prirow = M('exam')->field('creator')->where('exam_id=%d', $eid)
            ->find();
        if (!$prirow || !checkAdmin(4, $prirow['creator'])) {
            return false;
        }
        return true;
    }

    protected function isallow($eid, $isreturn = false) {
        $now = time();
        $prirow = M('exam')->field('creator,start_time,end_time')
            ->where('exam_id=%d', $eid)->find();
        $priflag = 0;
        if ($now > strtotime($prirow['end_time']) && isset($_SESSION['contest_creator'])) {
            $priflag = 1;
        }
        if (!checkAdmin(5, $prirow['creator'], $priflag)) {
            $this->error('You have no privilege of this exam');
        }
        if ($isreturn)
            return $prirow;
    }

    protected function candel($pvt, $crt) {
        if (!checkAdmin(4, $crt)) {
            return false;
        } else if ($pvt == 2 && !checkAdmin(1)) {
            return false;
        }
        return true;
    }

    protected function checkadded($eid, $type, $id) {
        $cnt = M('exp_question')
            ->where('exam_id=%d and type=%d and question_id=%d', $eid, $type, $id)
            ->count();
        return $cnt;
    }
}

?>