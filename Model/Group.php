<?php
App::uses("FlexAuthAppModel", "FlexAuth.Model");
App::uses("TreeBehavior", "Model/Behavior");

class Group extends FlexAuthAppModel {
	public $name="Group";
	public $actsAs = array('Tree');
	
	public $displayField="name";
	
	public $validate=array('name'=>array('rule'=>'uniqName','message'=>'Este grupo ja existe!'));
		
	public $useTable ="users_tree";
	
	
	public $hasAndBelongsToMany = array(
	        "Role"=>array(
	                'className'              => 'FlexAuth.Role',
	                'joinTable'              => 'role_users_tree',
	                'foreignKey'             => 'users_tree_id',
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
	
	
	
	public function beforeDelete($cascade=true) {
        $c=$this->childCount($this->id,true);
   	    $childs=$this->children($this->id,true);
	    if($c) {
    	    $user=ClassRegistry::init("FlexAuth.User");
    	    
    	    foreach ($childs as $child) {
    	        $child['User']['parent_id']=null;
    	        $user->create();
    	        $user->save($child);
    	    }
	    }
	    return true;
	}
	
	/**
	 * Checa se o registro é de um grupo ou de um usuário
	 * @param unknown_type $id
	 * @return boolean
	 */
	function isGroup($id = null) {
	    if ($id === null) {
	        $id = $this->getID();
	    }
	    if ($id === false) {
	        return false;
	    }
	    return (bool)$this->find('count', array(
	            'conditions' => array(
	                    $this->alias . '.' . $this->primaryKey => $id,$this->alias.'.'.'foreign_key'=>null
	            ),
	            'recursive' => -1,
	            'callbacks' => false
	    ));
	}

	/**
	 * Valida se o nome é unico pelo usando uma virtual key  (name + model)
	 * @param unknown_type $check
	 */
	public function uniqName($check) {
	    
	    if(isset($this->data['Group']['id'])) {
	        $g=$this->findById($this->data['Group']['id']);
	        if(!empty($g) && $g['Group']['name']==$check['name']) {
	            return true;
	        }
	    }
	    $check['model']=null;
	    
	    if(isset($this->data['Group']['model'])) {
	        $check['model']=$this->data['Group']['model'];
	    }
	    $c=$this->find('count',array('conditions'=>$check));
	    if($c>0) return false;
	    return true;
	}
	
	
	/**
	 * Retorna a lista de group existentes
	 * @param unknown_type $id
	 * @return multitype:
	 */
	function getGroupList($id=null, $keyPath = null, $valuePath = null, $spacer = '_', $recursive = null) {
	    $conditions=array();
	    if(!is_null($id)) {
	        $conditions['Group.id !=']=$id;
	    }
	    $conditions['Group.foreign_key']=null;
	    
	    $l=$this->generateTreeList($conditions,$keyPath = null, $valuePath = null, $spacer = '_', $recursive = null);
	    return $l;
	   
	}
	
}


?>