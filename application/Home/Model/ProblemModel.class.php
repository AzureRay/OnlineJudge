<?php
namespace Home\Model;

class ProblemModel
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

    public function getProblemInfo($where, $field = array()) {
        $problemDao = M('problem');
        $res = $problemDao->field($field)->where($where)->find();
        return $res;
    }

    public function isAvailableProblem($problemId) {
        $where = array(
            'problem_id' => $problemId
        );
        $field = array('defunct');
        $problemDao = M('problem');
        $res = $problemDao->field($field)->where($where)->find();
        if (empty($res)) {
            return false;
        } else {
            return ($res['defunct'] == 'Y' ? false : true);
        }
    }

    public function getProblemByQuery($query, $field = array()) {
        $where = array();
        $problemDao = M('problem');

        if (!empty($query['problem_id'])) {
            $where['problem_id'] = $query['problem_id'];
        }
        if (!empty($query['defunct'])) {
            $where['defunct'] = $query['defunct'];
        }
        if (!empty($query['title'])) {
            $where['title'] = $query['title'];
        }
        if (!empty($query['source'])) {
            $where['source'] = $query['source'];
        }

        $total = $problemDao->where($where)->count();

        $problemDao->field($field)->where($where);
        if (!empty($query['order'])) {
            $problemDao->order($query['order']);
        }
        if (!empty($query['limit'])) {
            $problemDao->limit($query['limit']);
        }
        if (!empty($query['page'])) {
            $problemDao->page($query['page']);
        }
        $res = $problemDao->select();

        return array('total' => $total, 'data' => $res);
    }
}
