<?php

require_once 'common/rbac/RBAC_Engine.class.php';

Mock::generate('User');
Mock::generate('UserManager');
Mock::generate('RoleExplicit');
Mock::generatePartial('RBAC_Engine', 'RBAC_EngineTestVersion', array('_getUserManager'));

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

    function testHasCurrentUserRoleToBeAProjectAdminAccess() {
        $role = new MockRoleExplicit($this);
        $role->expectOnce('hasPermission');
        $role->setReturnValue('hasPermission', true, array('project_admin', 101, null));

        $user = new MockUser($this);
        $user->setReturnValue('getId', 102);

        $e = new RBAC_EngineTestVersion($this);
        $e->addRoleForUser($user, $role);

        $um = new MockUserManager($this);
        $um->setReturnValue('getCurrentUser', $user);
        $e->setReturnValue('_getUserManager', $um);

        $this->assertTrue($e->isActionAllowed('project_admin', 101));
    }

    function testHasCurrentUserRoleToBeSiteAdmin() {
        $role = new MockRoleExplicit($this);
        $role->expectOnce('hasPermission');
        $role->setReturnValue('hasPermission', true, array('site_admin', -1, null));

        $user = new MockUser($this);
        $user->setReturnValue('getId', 102);

        $e = new RBAC_EngineTestVersion($this);
        $e->addRoleForUser($user, $role);

        $um = new MockUserManager($this);
        $um->setReturnValue('getCurrentUser', $user);
        $e->setReturnValue('_getUserManager', $um);

        $this->assertTrue($e->isGlobalActionAllowed('site_admin'));
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