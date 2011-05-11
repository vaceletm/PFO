<?php

class RoleExplicit {
    protected $id;
    protected $name;

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

    public function initFromDynamic($row) {
        isset($row['id']) ? $this->setId($row['id']) : null;
        isset($row['name']) ? $this->setName($row['name']) : null;
    }

    public function initFromStatic($row) {
        isset($row['id']) ? $this->setId($row['id']) : null;
        isset($row['name']) ? $this->setName($row['name']) : null;
    }

    public function getName() {
        return $this->name;
    }
    public function setName($name) {
        $this->name = $name;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
}
