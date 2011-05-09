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

    /**
     * Main function to test access to a ressource.
     *
     * Nota: as of today, it's just an alias to isActionAllowedForUser
     * but might evolve if non explicit roles arise
     */
    public function isActionAllowed($section, $reference, $action = null) {
        $user = $this->_getUserManager()->getCurrentUser();
        return $this->isActionAllowedForUser($user, $section, $reference, $action);
    }

    public function isGlobalActionAllowed($section, $action = null) {
        $user = $this->_getUserManager()->getCurrentUser();
        return $this->isActionAllowedForUser($user, $section, -1, $action);
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
