<?php
App::uses("AuthFlexComponent", "FlexAuth.Controller/Component");
App::uses("FlexAuthAppModel", "FlexAuth.Model");
App::uses("Group", "FlexAuth.Model");

class User extends FlexAuthAppModel {
	public $name="User";
	public $actsAs = array(
	                       'FlexAuth.UserFlexTree'=>array('groupClass'=>'Group'),
	                       
	        );
	
	public $displayField="username";
	
	public $validate=array('username'=>array('rule'=>'isUnique','message'=>'Este usuario ja existe!','allowEmpty' => true),
	                       'email'=>array('rule'=>'email','message'=>'Entre com um email valido','allowEmpty' => true),
	                       'password2'=>array('rule'=>'equalPassword','message'=>'As senhas devem ser iguais!','allowEmpty' => true)
	                   );
	
	public function beforeSave($options = array()) {
		
		if (isset($this->data['User']['password'])) {
			$this->data['User']['password'] = AuthFlexComponent::password($this->data['User']['password']);
		}
		return true;
	}
	
	
	public function equalPassword($check) {
	    if($check['password2'] != $this->data['User']['password']) {
	        return false;
	    }
	    return true;
	    
	}
	
	

}


?>