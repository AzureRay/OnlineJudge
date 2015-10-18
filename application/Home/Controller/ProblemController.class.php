<?php
namespace Home\Controller;

use Home\Model\SourceModel;
use Home\Model\UserModel;
use Home\Model\ProblemModel;
use Home\Model\ContestModel;
use Home\Model\PrivilegeModel;

class ProblemController extends TemplateController
{

    public function _initialize() {
        parent::_initialize();
    }

    public function detail() {
        $this->auto_display();
    }

    public function plist() {

        $page = I('get.page', 0, 'intval');
        $title = I('get.title', '');
        $source = I('get.source', '');
        if (empty($this->userInfo)) {
            $isAdministrator = false;
            $userId = '';
        } else {
            $userId = $this->userInfo['user_id'];
            $isAdministrator = PrivilegeModel::instance()->isAdministrator($userId);
            if ($page == 0) {
                $_res = UserModel::instance()->getUserByUid($userId, array('volume'));
                $page = $_res['volume'];
            } else {
                $where = array(
                    'user_id' => $this->userInfo['user_id']
                );
                $option = array(
                    'volume' => $page
                );
                UserModel::instance()->updateUserInfo($where, $option);
            }
        }
        $offset = 1000;
        $pageSize = 100;
        $page = ($page > 0 ? $page : 1);
        $offsetStart = $offset + ($page - 1) * $pageSize;
        $offsetEnd = $offsetStart + $pageSize;
        if (!empty($title)) {
            $query['title'] = array('like', '%' . $title . '%');
        } else if (!empty($source)) {
            $query['source'] = array('like', '%' . $source . '%');
        } else {
            $query = array(
                'problem_id' => array(array('egt', $offsetStart), array('lt', $offsetEnd)),
                'order' => array('problem_id')
            );
        }
        $field = array('problem_id', 'title', 'defunct', 'submit', 'accepted', 'in_date', 'author');
        $problems = ProblemModel::instance()->getProblemByQuery($query, $field);

        if ($isAdministrator === false) {
            $contests = ContestModel::instance()->getNotEndedContests(array('contest_id'));
            $contestIds = array();
            foreach ($contests as $_contest) {
                $contestIds[] = $_contest['contest_id'];
            }
            $contestProblemIds = ContestModel::instance()->getProblemIdsInContests($contestIds);
            $isInContest = array();
            foreach ($contestProblemIds as $cpId) {
                $isInContest[$cpId] = true;
            }
            unset($contestProblemIds);
        }

        if (!empty($userId)) {
            // 找到该userId对应的该页提交的记录
            $isSubmit = array();
            $where = array(
                'problem_id' => array(array('egt', $offsetStart), array('lt', $offsetEnd)),
                'user_id' => $userId
            );
            $field = array('problem_id', 'result');
            $solutions = SourceModel::instance()->getSolutionsByQuery($where, $field);
            foreach ($solutions as $solution) {
                $_pid = $solution['problem_id'];
                $_re = $solution['result'];
                if (!isset($isSubmit[$_pid])) {
                    $isSubmit[$_pid] = $_re;
                } else {
                    $isSubmit[$_pid] = ($_re == 4 ? $_re : $isSubmit[$_pid]);
                }
            }
            unset($solutions);
        } else {
            $isSubmit = array();
        }

        foreach ($problems['data'] as $key => $_problem) {
            $_pid = $_problem['problem_id'];

            if (isset($isSubmit[$_pid])) {
                if ($isSubmit[$_pid] == 4) {
                    $problems['data'][$key]['isSubmit'] = 1;
                } else {
                    $problems['data'][$key]['isSubmit'] = -1;
                }
            } else {
                $problems['data'][$key]['isSubmit'] = 0;
            }

            if ($isAdministrator === false) {
                if (empty($isInContest[$_pid])) {
                    if ($_problem['defunct'] == 'Y') {
                        if (empty($userId) || strcmp($_problem['author'], $userId) != 0) {
                            unset($problems['data'][$key]);
                            continue;
                        }
                    }
                } else {
                    unset($problems['data'][$key]);
                    continue;
                }
            }
            unset($problems['data'][$key]['defunct']);
            unset($problems['data'][$key]['author']);

        }
        $this->auto_display();
    }

    public function status() {
        // todo later
    }

    public function submit() {
    }
}
