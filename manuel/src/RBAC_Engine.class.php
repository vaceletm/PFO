<?php

require_once 'common/user/UserManager.class.php';
require_once 'RoleExplicit.class.php';

class RBAC_Engine {
    protected $userRoles = array();

    public function addRoleForUser($user, $role) {
        $this->userRoles[$user->getId()][] = $role;
    }

    public function getRolesForUser($user) {
        if (isset($this->userRoles[$user->getId()])) {
            return $this->userRoles[$user->getId()];
        }
        return array();
    }

    public function isActionAllowed($section, $reference, $action = null) {
        $user = $this->_getUserManager()->getCurrentUser();
        return $this->isActionAllowedForUser($user, $section, $reference, $action);
    }

	public function isActionAllowedForUser($user, $section, $reference, $action = null) {
        $roles = $this->getRolesForUser($user);
        foreach ($roles as $role) {
            if ($role->hasPermission($section, $reference, $action)) {
                return true;
            }
        }
        return false;
    }

    protected function _getUserManager() {

    }

}

?>
