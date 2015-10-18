<?php
namespace Home\Model;

class SourceModel
{

    private static $_instance = null;

    const COMPILEERROR = 1;
    const RUNTIMEERROR = 2;

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

    public function getErrorBySolutionId($solutionId, $type = self::COMPILEERROR) {
        $errorDao = $this->getErrorDaoByType($type);
        $where = array('solution_id' => $solutionId);
        $field = array('error');
        $res = $errorDao->field($field)->where($where)->find();
        return empty($res) ? '' : $res['error'];
    }

    private function getErrorDaoByType($type) {
        switch ($type) {
            case self::COMPILEERROR:
                $errorDao = M('compileinfo');
                break;

            case self::RUNTIMEERROR:
                $errorDao = M('runtimeinfo');
                break;

            default:
                $errorDao = M('compileinfo');
        }
        return $errorDao;
    }

    public function getSolutionsByProblemIdAndUserId($problemId, $userId, $field = array()) {
        if (empty($problemId)) {
            return array();
        } else {
            $solutionDao = M('solution');
            $where = array(
                'problem_id' => $problemId,
                'user_id'    => $userId
            );
            $res = $solutionDao->field($field)->where($where)->select();
            return $res;
        }
    }

    public function getSolutionsByQuery($query, $field = array()) {
        $where = array();
        if (!empty($query['problem_id'])) {
            $where['problem_id'] = $query['problem_id'];
        }
        if (!empty($query['user_id'])) {
            $where['user_id'] = $query['user_id'];
        }
        if (!empty($query['result'])) {
            $where['result'] = $query['result'];
        }
        if (!empty($query['contest_id'])) {
            $where['contest_id'] = $query['contest_id'];
        }

        $solutionDao = M('solution');
        $res = $solutionDao->field($field)->where($where)->select();
        return $res;
    }
}
