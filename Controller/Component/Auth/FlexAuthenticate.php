<?php

App::uses('BaseAuthenticate', 'Controller/Component/Auth');

class FlexAuthenticate extends BaseAuthenticate {

    public $settings = array(
		'fields' => array(
			'username' => 'username',
			'password' => 'password'
		),
		'userModel' => 'FlexAuth.User',
		'scope' => array(),
		'recursive' => 0,
		'contain' => null,
        'onlyAdminRoute'=>false,
        'login_method'=>null,
	);
     
          
/**
 * Checks the fields to ensure they are supplied.
 *
 * @param CakeRequest $request The request that contains login information.
 * @param string $model The model used for login verification.
 * @param array $fields The fields to be checked.
 * @return boolean False if the fields have not been supplied. True if they exist.
 */
	protected function _checkFields(CakeRequest $request, $model, $fields) {
		if (empty($request->data[$model])) {
			return false;
		}
		if (
			empty($request->data[$model][$fields['username']]) ||
			empty($request->data[$model][$fields['password']])
		) {
			return false;
		}
		return true;
	}
	
	
	

/**
 * Authenticates the identity contained in a request. Will use the `settings.userModel`, and `settings.fields`
 * to find POST data that is used to find a matching record in the `settings.userModel`. Will return false if
 * there is no post data, either username or password is missing, of if the scope conditions have not been met.
 *
 * @param CakeRequest $request The request that contains login information.
 * @param CakeResponse $response Unused response object.
 * @return mixed False on login failure. An array of User data on success.
 */
	public function authenticate(CakeRequest $request, CakeResponse $response) {
		
				
		$userModel = $this->settings['userModel'];
		list(, $model) = pluginSplit($userModel);

		$fields = $this->settings['fields'];
		if (!$this->_checkFields($request, $model, $fields)) {
			return false;
		}
		
		$user=array();
		if(empty($this->settings['login_method'])) {
    		$userModel=ClassRegistry::init($this->settings['userModel']);
    		$user=$userModel->find('first',
    				array('conditions'=>
    						array(
    								$model.".".$fields['username']=>$request->data[$model][$fields['username']],
    								$model.".".$fields['password']=>$this->_password($request->data[$model][$fields['password']])
    						)
    					)
    				);
    		
		
		} else {
		    if(!method_exists($userModel, $this->settings['login_method'])) {
		        trigger_error(__d('cake_dev', 'Callback %s not defined in %s', $this->settings['login_method'],$model->alias), E_USER_WARNING);
		    }
		   $user= $userModel->{$this->settings['login_method']}($request->data);
		}
		
		if(empty($user)) {
		    return false;
		}
		unset($user[$model][$fields['password']]);
		
		$user['Roles']=AuthFlexComponent::getRoles($user['Node']['id']);
		return $user;
	}
		
}


?>