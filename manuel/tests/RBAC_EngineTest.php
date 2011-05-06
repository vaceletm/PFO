<?php

require_once 'common/rbac/RBAC_Engine.class.php';

Mock::generate('User');
Mock::generate('RoleExplicit');

class RBAC_EngineTest extends UnitTestCase {

   function testIsProjectAdministrationAllowedToProjectAdmins() {
        $e = new RBAC_Engine();

        $role = new MockRoleExplicit($this);
        $role->setReturnValue('hasPermission', true, array('project_admin', 101, null));

        $user = new MockUser($this);
        $user->setReturnValue('getId', 102);

        $e->addRoleForUser($user, $role);

        $this->assertTrue($e->isActionAllowedForUser($user, 'project_admin', 101));
    }

    function testIsProjectAdministrationBlockedToNonProjectAdmins() {
        $e = new RBAC_Engine();

        $user = new MockUser($this);

        $this->assertFalse($e->isActionAllowedForUser($user, 'project_admin', 101));
    }

    /*    function testLoadAllUsersRoles() {
        $e = new RBAC_Engine();

        $role = new MockRoleExplicit($this);

        $dao = new MockRoleDao($this);
        $dao->setReturnValue('searchRoleForUser', array(), array(102));

        $user = new MockUser($this);

        $roles = $e->getRolesForUser($user);

        $this->assertTrue($roles, array($role));
        }*/

}