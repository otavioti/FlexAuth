<?php

App::uses('Component','Controller/Component');

class LoginComponent extends Component {
    

    public function beforeRender(Controller $controller) {
        
        /**
         * Set layout for login action
         */
        if(Configure::check("FlexAuth.login_layout")) {
            $layout=Configure::read("FlexAuth.login_layout");
            if($layout && $controller->action == "login") {
                $controller->layout = $layout;
            }
        }
    }
}


?>