<?php
App::uses('AclNode', 'Model');

class UsersTree extends AclNode {
    
    public $useTable = "users_tree";
    
    public $name = "UsersTree";
    
    public $displayName="alias";
    
    
}


?>