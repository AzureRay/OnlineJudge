<?php
namespace Home\Model;

use Think\Exception;

class ContestModel
{

    private static $_instance = null;

    private function __construct() {
    }

    private function __clone() {
    }

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function getNotEndedContests($field = array(), $limit = 100) {
        $now = date('Y-m-d H:i:s');
        $where = array(
            'end_time' => array('gt', $now)
        );
        $contestDao = M('contest');
        $res = $contestDao->field($field)->where($where)->limit($limit)->select();
        return $res;
    }

    public function getProblemIdsInContests($contestIds) {
        if (empty($contestIds)) {
            return array();
        }
        $where = array(
            'contest_id' => array('in', $contestIds)
        );
        $field = array('problem_id');
        $contestProblemDao = M('contest_problem');
        $problemIds = $contestProblemDao->field($field)->where($where)->select();
        return $problemIds;
    }

    public function getContestIdsByProblemId($problemId) {
        $where = array(
            'problem_id' => $problemId,
        );
        $field = array('contest_id');
        $contestProblemDao = M('contest_problem');
        $contestIds = $contestProblemDao->field($field)->where($where)->select();
        return $contestIds;
    }
}
