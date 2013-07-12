<?php
App::uses('SchemaShell', 'Console/Command');
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
App::uses('CakeSchema', 'Model');

class InitdbShell extends SchemaShell {

    
    
    public function startup() {
        $this->params['name']="FlexAuth.FlexAuth";
        parent::startup();
    }
    
    public function main() {
        
        list($Schema, $table) = $this->_loadSchema();
        $this->_create($Schema, $table);
        
        /*
        $group=ClassRegistry::init("FlexAuth.Group");
             
        $adm_group=array('name'=>"adm");
        $group->save($adm_group);

        $user=ClassRegistry::init("FlexAuth.User");
        //print_r($user);
        $admin_user=array('username'=>'admin','password'=>'admin','group_id'=>$group->id);
        $user->save($admin_user);
        */
    }
}


?>