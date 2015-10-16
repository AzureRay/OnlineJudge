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

    public function getProblemIdsInContest($contestId = 0) {
        if ($contestId != 0) {
            $where = array('contest_id' => $contestId);
        } else {
            $where = array();
        }
        $contestProblemDao = M('contest_problem');
        $field = array('problem_id');
        $problemIds = $contestProblemDao->field($field)->where($where)->select();
        return $problemIds;
    }
}
