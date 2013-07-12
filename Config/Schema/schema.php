<?php 
App::uses('ClassRegistry', 'Utility');

class FlexAuthSchema extends CakeSchema {

    public $name = "FlexAuth";
    
	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	    
	    
	}


	public $users = array(
	        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
	        'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 60),
	        'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 60),
	        'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
	        'parent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
	        'active' => array('type'=>'boolean','null'=>false,'default'=>true),
	        'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
	        'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
	        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
	);
	
	public $users_tree = array(
	        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
	        'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 11),
	        'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 11),
	        'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 60),
	        'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 60),
	        'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
	        'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
	);
	
	public $roles = array(
	        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
	        'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 60),
	);
	
	public $permissions = array(
	        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
	        'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 60),
	        'description'=>array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 255),
	        'indexes' => array('PERMISSION_NAME' => array('column' => 'name', 'unique' => 1)),
	 );
	
	public $role_permissions = array(
	        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
	        'role_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
	        'permission_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
	        'granted' => array('type'=>'boolean','null'=>false,'default'=>true),
	        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'ROLE_PERMISSION_KEY' => array('column' => array('role_id', 'permission_id'), 'unique' => 1))
	);
	
	public $role_users_tree = array(
	        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
	        'role_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
	        'users_tree_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
	        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'ROLE_USERS_TREE_KEY' => array('column' => array('role_id', 'users_tree_id'), 'unique' => 1))
	        
	);
	
	
	public $row_permissions = array(
	        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
	        'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
	        'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
	        'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 11),
	        'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 60),
	        'permission'=>array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 6),
	);
	
}
