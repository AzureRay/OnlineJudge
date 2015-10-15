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
}
