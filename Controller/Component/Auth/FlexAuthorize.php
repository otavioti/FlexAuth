<?php
App::uses('BaseAuthorize', 'Controller/Component/Auth');

class FlexAuthorize extends BaseAuthorize {
	
	
	public function authorize($user,CakeRequest $request) {
		
		
		if(isset($this->settings['user_liberado'])) {
			if($user['User']['username']==$this->settings['user_liberado']) {
				return true;
			}
		}
		
		if(!is_array($this->_Controller->liberados)) {
		    $this->_Controller->liberados= array($this->_Controller->liberados);
		}
		
		if(in_array($request->params['action'], $this->_Controller->liberados)) {
			return true;
		} 		
		
		$rs=null;
		
		if(method_exists($this->_Controller, "isAuthorized")) {
		    $rs=$this->_Controller->isAuthorized($user);
		} 
		
		if(is_null($rs)) {
		    
		    $rs=$this->check($user, $request->params['controller'], $request->params['action'],$request->params['plugin']);
		}
		
		
		if($rs===true) {
		    return true;
		}

		$response=false;
		if(isset($this->settings['default'])) {
		   $response = $this->settings['default'];
		}
			
		if($rs === false ) {
		    
		    $response = false;
		}

		if(!$response) {
		    //$this->_Controller->Session->setFlash($this->settings['authorizarionError']);
		}
		
		return $response;
		
	}
	
	/**
	 * Verifica se o usuario pode acessar a action
	 * @param unknown_type $user
	 * @param unknown_type $controller
	 * @param unknown_type $action
	 * @param unknown_type $plugin
	 */
	public function check($user,$controller,$action,$plugin=null) {
	    $controller = Inflector::camelize($controller);
	    if(!is_null($plugin)) {
	        $plugin = Inflector::camelize($plugin).".";
	    }
	    
	    $permission=is_null($plugin)?"":$plugin;
	    $permission .=$controller.".".$action;
        
	    $roles_groups=$user['Roles'];
	    
		return AuthFlexComponent::checkPermission($permission,$roles_groups);
	} 
	
	
}


?>