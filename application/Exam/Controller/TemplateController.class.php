<?php
namespace Exam\Controller;

class TemplateController extends \Home\Controller\TemplateController
{

    public function _initialize() {
        $this->isNeedLogin = true;
        parent::_initialize();
    }
}