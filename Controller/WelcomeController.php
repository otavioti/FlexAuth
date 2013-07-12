<?php
class WelcomeController extends FlexAuthAppController {
    
    public $name = "Welcome";
    
    public $uses = array();
    
    
    public function isAuthorized($user) {
        return true;
    }
    
    public function index() {
        
    }
    
    
    
}


?>