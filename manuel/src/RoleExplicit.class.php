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
	public function hasPermission($section, $reference, $action = NULL) {
        if (isset($this->capabilities[$section][$reference])) {
            return $this->capabilities[$section][$reference];
        }
        return false;
    }

}