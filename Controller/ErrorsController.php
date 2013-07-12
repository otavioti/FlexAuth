<?php

App::uses('FlexAuthAppController', 'FlexAuth.Controller');

class ErrorsController extends FlexAuthAppController {
    
    public $name = 'Errors';
    public $uses=array();
    
    public function beforeFilter() {
        $this->AuthFlex->allow('error403');
    }
    
    public function error403() {
        //$this->layout = 'default';
        //$this->Session->setFlash("Você não tem permissao")
    }
}


?>