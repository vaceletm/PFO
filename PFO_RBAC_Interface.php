<?php
/**
 * API for role-based access control
 * Defined at Planetforge.org
 *
 * Copyright 2010, Roland Mas
 *
 * This file is part of FusionForge.
 *
 * FusionForge is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published
 * by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 * 
 * FusionForge is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with FusionForge; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
 * USA
 */

/**
 * Interface for the RBAC engine
 * @author Roland Mas
 *
 * Its methods use the session management to decide what roles are
 * available within the current session (if any),
 * and to provide the answer to the question “Does the current client
 * have the permission for this action?”.
 * Other interesting questions that this interface is meant to answer
 * include “does another account have the permission for that action?”
 * and, more generically, “who is allowed that action?”.
 *
 * In the following code, we have 3 main parameter to describe a
 * permission:
 * $section   describes the ressource. Eg. 'project_admin' or
 *            'tracker_tech'
 * $reference describes the identififer associated to $section (101)
 * $action    optional argument that add extra possibility to $section
 *
 * <pre>
 * // Test if current user is allowed to administrate project 101
 * $engine = new PFO_RBACEngine();
 * $engine->isActionAllowed('project_admin', 101);
 *
 * // Test is a given user is member of project 102
 * // $user provided by Forge library
 * $engine->isActionAllowedForUser($user, 'project_member', 102);
 * </pre>
 *
 */
interface PFO_RBACEngine {

    /**
     * Return the list of roles of the current session.
     *
     * This list is made of the explicites roles (Developer, Tester,
     * ...) and context dependent (VLAN, ...).
     *
     * @return Array of PFO_Role
     */
    public function getAvailableRoles();

    /**
     * Check permission according to current session.
     *
     * Be aware, some roles might not be static (role associated to LAN access).
     *
     * @param String $section
     * @param String $reference
     * @param String $action
     *
     * @return Boolean
     */
	public function isActionAllowed($section, $reference, $action = NULL) ;

    /**
     * Check permission at Forge level.
     *
     * Applies to "site_news_approval", "site_admin", ...
     *
     * @param String $section
     * @param String $action
     *
     * @return Boolean
     */
	public function isGlobalActionAllowed($section, $action = NULL) ;

    /**
     * Check permission according to user's explicits roles
     *
     * This method doesn't take into account Roles that depend of user
     * context.
     *
     * @param User   $user
     * @param String $section
     * @param String $reference
     * @param String $action
     *
     * @return Boolean
     */
	public function isActionAllowedForUser($user, $section, $reference, $action = NULL) ;

    /**
     * Check global permissions according to user's explicits roles
     *
     * @param User   $user
     * @param String $section
     * @param String $action
     *
     * @return Boolean
     */
	public function isGlobalActionAllowedForUser($user, $section, $action = NULL) ;

    /**
     * Return all roles able to perform "section" for "reference"
     *
     * @param String $section
     * @param String $reference
     * @param String $action
     *
     * @return Array of PFO_Role
     */
	public function getRolesByAllowedAction($section, $reference, $action = NULL) ;

    /**
     * Return all users able to perform "section" for "reference"
     *
     * @param String $section
     * @param String $reference
     * @param String $action
     *
     * @return Array of User
     */
	public function getUsersByAllowedAction($section, $reference, $action = NULL) ;
}


/**
 * General definition of capabilities
 *
 * This interface is not meant to be implemented directly.
 */
interface PFO_Role {

    /**
     * Role name (Developer, etc...)
     */
	public function getName() ;
	public function setName($name) ;
	public function getID() ;

    /**
     * The role can be referenced in another project
     */
	public function isPublic() ;
	public function setPublic($flag) ;

    /**
     * Define if the role is associated to a project or global (return null)
     *
     * @return Project or null
     */
	public function getHomeProject() ;

    /**
     * List of projects that reference the role
     */
	public function getLinkedProjects() ;
	public function linkProject($project) ;
	public function unlinkProject($project) ;

    /**
     * Get list of users who have this Role
     *
     * @return array of User
     */
	public function getUsers() ;

    /**
     * Does the given user have this Role
     *
     * @param User $user
     *
     * @return Boolean
     */
	public function hasUser($user) ;

    /**
     * Can the given Role access the specified ressource
     *
     * @param String $section
     * @param Mixed  $reference
     * @param String $action
     *
     * @return Boolean
     */
	public function hasPermission($section, $reference, $action = NULL) ;

    /**
     * Forge-wide permissions not linked to a specific tool, such as project approval
     * news approval, site admin, stats (in fusionforge)
     *
     * @param String $section
     * @param String $action
     *
     * @return Boolean
     */
	public function hasGlobalPermission($section, $action = NULL) ;

}

interface PFO_RoleExplicit extends PFO_Role {
	public function addUsers($users) ;
	public function removeUsers($users) ;
}

interface PFO_RoleUnion extends PFO_Role {
	public function addRole($role) ;
	public function removeRole($role) ;
}

interface PFO_RoleAnonymous extends PFO_Role {
}

interface PFO_RoleLoggedin extends PFO_Role {
}

// Local Variables:
// mode: php
// c-file-style: "bsd"
// End:

?>