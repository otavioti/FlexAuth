<?php

App::uses('FlexAuthAppModel', 'FlexAuth.Model');

class Role extends FlexAuthAppModel {
    public $name = "Role";
    
    public $hasAndBelongsToMany = array(
                    "Permission"=>array(
                        'className'              => 'FlexAuth.Permission',
                        'joinTable'              => 'role_permissions',
                        'foreignKey'             => 'role_id',
                        'associationForeignKey'  => 'permission_id',
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
            
   
                    "Group"=>array(
                        'className'              => 'FlexAuth.Group',
                        'joinTable'              => 'role_users_tree',
                        'foreignKey'             => 'role_id',
                        'associationForeignKey'  => 'users_tree_id',
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
            
    
    public function afterSave($created) {
        
        $id=$this->id;
        
        if(isset($this->data['Permission'])){
            if(!$created) {
                $id=$this->data['Role']['id'];
            } else {
                $this->RolePermission->deleteAll(array('RolePermission.role_id'=>$id));
            }
            foreach($this->data['Permission'] as $k => $p) {
                if($p['permission'] != 'inheritance') {
                    $perm=array('RolePermission'=>array('role_id'=>$id));
                    $perm['RolePermission']['permission_id']=$k;
                    if($p['permission']=="grant") {
                        $perm['RolePermission']['granted']=true;
                    } else {
                        $perm['RolePermission']['granted']=false;
                    }
                    
                    $this->RolePermission->create();
                    $this->RolePermission->save($perm);
                    
                }
            }
        }
    }
}


?>