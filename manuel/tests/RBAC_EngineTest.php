<?php

require_once 'common/rbac/RBAC_Engine.class.php';

Mock::generate('User');
Mock::generate('UserManager');
Mock::generate('RoleExplicit');
Mock::generatePartial('RBAC_Engine', 'RBAC_EngineTestVersion', array('getUserManager', 'getDao'));
Mock::generate('RBACEngineDao');
Mock::generate('DataAccessResult');

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

    function testIsProjectAdministrationBlockedForNonProjectAdmins() {
        $e = new RBAC_EngineTestVersion($this);

        // No db result
        $dar = new MockDataAccessResult($this);
        $dar->setReturnValue('valid', false);
        $dao = new MockRBACEngineDao($this);
        $dao->setReturnValue('searchDynamicGroupsForUser', $dar);
        $dao->setReturnValue('searchStaticGroupsForUser', $dar);
        $e->setReturnValue('getDao', $dao);

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
        $e->setReturnValue('getUserManager', $um);

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
        $e->setReturnValue('getUserManager', $um);

        $this->assertTrue($e->isGlobalActionAllowed('site_admin'));
    }

    function testLoadOnTheFlyRolesLoad() {
        $e = new RBAC_EngineTestVersion($this);

        $dao = new MockRBACEngineDao($this);

        $dar = new MockDataAccessResult($this);
        $dar->setReturnValueAt(0, 'valid', true);
        $dar->setReturnValueAt(1, 'valid', false);
        $dar->setReturnValue('current', array('id' => 2));
        $dao->setReturnValue('searchDynamicGroupsForUser', $dar);

        $dar = new MockDataAccessResult($this);
        $dar->setReturnValueAt(0, 'valid', true);
        $dar->setReturnValueAt(1, 'valid', false);
        $dar->setReturnValue('current', array('id' => 105));
        $dao->setReturnValue('searchStaticGroupsForUser', $dar);

        $e->setReturnValue('getDao', $dao);

        $user = new MockUser($this);
        $user->setReturnValue('getId', 102);

        $roles = $e->getRolesForUser($user);
        $this->assertEqual($roles[0]->getId(), 2);
        $this->assertEqual($roles[1]->getId(), 105);
    }
}