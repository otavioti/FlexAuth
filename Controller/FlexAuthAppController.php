<?php

App::uses('FlexAuth.FlexAuthorize', 'FlexAuth/Controller/Component/Auth');
App::uses('FlexAuth.FlexAuthenticate', 'FlexAuth/Controller/Component/Auth');

App::uses('Controller', 'Controller');
App::uses('AppController', 'Controller');

class FlexAuthAppController extends AppController {

    
    public $helpers = array('Form',"Html","Session");
    
    
    public $components = array(
        'Session',
    );
    
    
    public function isAuthorized($user) {
        if(AuthFlexComponent::isPermitted("FlexAuth.AdminPermission")) {
            return true;
        }
    
        return null;
    }
    
}


?>