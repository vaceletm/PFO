<?php
/**
 * Propose evolution of PFO_RBAC engine
 *
 * 1. Define a PFO_RBAC_Ressource interface that design the item the
 *    roles manage.
 *    It aims to replace the ($section, $reference, $action = NULL)
 *    parameters and give the possibility to modelize more complex
 *    ressources like Codendi field permissions that have no single
 *    $reference id (the field primary key is (tracker_id, field_id)).
 *
 * 2. Remove PFO_Role::hasGlobalPermission (useless with previous
 *    modification)
 *
 * 3. Mark interfaces explicitely (_Interface suffix)
 *
 * 4. No usage of Role constant (only for DB, in this case it should
 *    not be in interface, it's purely implementation specific ?)
 *    Or at least define them at proper class constant, not
 *    global. In uppercase
 *
 * 5. Is it relevant to have getUsers defined Role level? for instance
 *    could we have PFO_RoleLoggedin->getUsers() or
 *    PFO_RoleAnonymous->getUsers() ?
 *
 * 6. I don't think it's mandatory to force PFO_RBACEngine to be a
 *    singleton. It's up to each project to instanciate as wished.
 *
 */

interface PFO_RBAC_Ressource_Interface {

}

interface PFO_Role_Interface {

    /**
     * Get list of users who has this Role
     *
     * @return array of User
     */
	public function getUsers() ;

    /**
     * Does the given user has this Role
     *
     * @param User $user
     *
     * @return Boolean
     */
	public function hasUser($user) ;


    public function hasPermission(PFO_RBAC_Ressource_Interface $ressource);
}

interface PFO_RBACEngine {
	public function getAvailableRoles();
	public function isRessourceAllowed(PFO_RBAC_Ressource_Interface $ressource) ;
	public function isRessourceAllowedForUser(PFO_RBAC_Ressource_Interface $ressource, User $user);
	public function getRolesByRessource(PFO_RBAC_Ressource_Interface $ressource) ;
	public function getUsersByRessource(PFO_RBAC_Ressource_Interface $ressource) ;
}


?>
