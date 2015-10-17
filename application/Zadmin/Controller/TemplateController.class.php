<?php
namespace Zadmin\Controller;

use Home\Model\PrivilegeModel;

class TemplateController extends \Home\Controller\TemplateController
{
    protected $privilegeType = null;

    public function _initialize() {
        $this->isNeedLogin = true;
        parent::_initialize();
        $this->initPrivilegeType();
    }

    private function initPrivilegeType() {
        $privilegeTypes = PrivilegeModel::$adminPrivilegeTypes;
        foreach ($privilegeTypes as $tpName) {
            $this->privilegeType[$tpName] = session($tpName);
        }
    }
}
