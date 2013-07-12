<?php
App::uses('FlexAuthAppModel', 'FlexAuth.Model');
/**
 * Permission Model
 *
 *
 */
class Permission extends FlexAuthAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name'=>array('rule'=>'isUnique','message'=>'ja existe uma permissao com este nome!','allowEmpty' => false),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $hasAndBelongsToMany = array(
	        "Role"=>array(
	                'className'              => 'FlexAuth.Role',
	                'joinTable'              => 'role_permissions',
	                'foreignKey'             => 'permission_id',
	                'associationForeignKey'  => 'role_id',
	                'unique'                 => true,
	                'conditions'             => '',
	                'fields'                 => '',
	                'order'                  => '',
	                'limit'                  => '',
	                'offset'                 => '',
	                'finderQuery'            => '',
	                'deleteQuery'            => '',
	                'insertQuery'            => ''
	        ),
	
	);
	
	/**
	 * Verifica se uma Role tem permissao garantida
	 * @param mixed $roles
	 */
	function checkPermission($name,$roles) {
	    if(!is_array($roles)) {
	        $roles=array($roles);
	    }
	    
	    $permission = $this->find('first',array('conditions'=>array('Permission.name'=>$name),'recursive'=>-1));
	    
	    if(empty($permission)) {
	        return null;
	    }
	    
	    $role_permissions = $this->RolePermission->find('all',
	                    array('conditions'=>array('RolePermission.permission_id'=>$permission['Permission']['id'], 'RolePermission.role_id'=>$roles)));
	    
	    if(empty($role_permissions)) {
	        return null;
	    }
	    
	    foreach($role_permissions as $rolep) {
	        if($rolep['RolePermission']['granted']) {
	            return true;
	        } 
	    }
	    
	    return false;
	    
	}
	
	


}
