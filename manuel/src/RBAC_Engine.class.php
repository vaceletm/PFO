<?php

require_once 'RoleExplicit.class.php';
require_once 'RBACEngineDao.class.php';

require_once 'common/user/UserManager.class.php';

class RBAC_Engine {
    protected $userRoles = array();

    public function addRoleForUser($user, $role) {
        $this->userRoles[$user->getId()][] = $role;
    }

    public function getRolesForUser($user) {
        if (!isset($this->userRoles[$user->getId()])) {
            $this->userRoles[$user->getId()] = array();
            $dao = $this->getDao();
            $dar = $dao->searchDynamicGroupsForUser($user->getId());
            foreach ($dar as $row) {
                $role = new RoleExplicit();
                $role->initFromDynamic($row);
                $this->addRoleForUser($user, $role);
            }
            $dar = $dao->searchStaticGroupsForUser($user->getId());
            foreach ($dar as $row) {
                $role = new RoleExplicit();
                $role->initFromStatic($row);
                $this->addRoleForUser($user, $role);
            }
        }
        return $this->userRoles[$user->getId()];
    }

    /**
     * Main function to test access to a ressource.
     *
     * Nota: as of today, it's just an alias to isActionAllowedForUser
     * but might evolve if non explicit roles arise
     */
    public function isActionAllowed($section, $reference, $action = null) {
        $user = $this->getUserManager()->getCurrentUser();
        return $this->isActionAllowedForUser($user, $section, $reference, $action);
    }

    public function isGlobalActionAllowed($section, $action = null) {
        $user = $this->getUserManager()->getCurrentUser();
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

    protected function getUserManager() {
    }

    protected function getDao() {
    }

}

?>
