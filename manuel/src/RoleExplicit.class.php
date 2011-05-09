<?php

class RoleExplicit {
    protected $capabilities = array();

    function setCapability($section, $reference) {
        $this->capabilities[$section][$reference] = true;
    }

    /**
     * Can the given Role access the specified ressource
     *
     * @param String $section
     * @param Mixed  $reference
     * @param String $action
     *
     * @return Boolean
     */
	public function hasPermission($section, $reference, $action = null) {
        if (isset($this->capabilities[$section][$reference])) {
            return $this->capabilities[$section][$reference];
        }
        return false;
    }

    /**
     * Forge-wide permissions not linked to a specific tool, such as project approval
     * news approval, site admin, stats (in fusionforge)
     *
     * @param String $section
     * @param String $action
     *
     * @return Boolean
     */
    public function hasGlobalPermission($section, $action = null) {

    }
}
