<?php

require_once 'common/rbac/RoleExplicit.class.php';

class RoleExplicitTest extends UnitTestCase {

    function testRoleAsProjectAdminAccess() {
        $r = new RoleExplicit();
        $r->setCapability('project_admin', 101);

        $this->assertTrue($r->hasPermission('project_admin', 101));
    }

    function testRoleDoesntHaveProjectAdminAccess() {
        $r = new RoleExplicit();

        $this->assertFalse($r->hasPermission('project_admin', 101));
    }

}