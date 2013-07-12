<?php
App::uses('ExceptionRenderer', 'Error');

class AppExceptionRenderer extends ExceptionRenderer {

    public function forbidden($error) {
        $this->controller->redirect(array('controller' => 'errors', 'action' => 'error403','plugin'=>'flex_auth'));
    }
}


?>