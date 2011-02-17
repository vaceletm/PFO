<?php

// Roland
interface FusionForge_RBAC_Resource_Global_Interface extends PFO_RBAC_Resource_Interface {
    public function getSection();
    public function getAction();
}


interface FusionForge_RBAC_Resource_Project_Interface extends PFO_RBAC_Resource_Interface {
    public function getSection();
    public function getAction();
    public function getReference();
}

// Christian
interface FusionForge_RBAC_Resource_Global_Interface extends PFO_RBAC_Resource_Interface {
    public function getLabel();
    public function getCapacity();
}

interface FusionForge_RBAC_Resource_Project_Interface extends PFO_RBAC_Resource_Interface {
    public function getLabel();
    public function getCapacity();
    public function getReference();
}

?>