<?php
App::uses('AuthComponent', 'Controller/Component');
App::uses('FlexAuthenticate', 'FlexAuth.Controller/Component/Auth');
App::uses('Permission', 'FlexAuth.Model');
App::uses('Group', 'FlexAuth.Model');

class AuthFlexComponent extends AuthComponent {
    
    public $loginAction = array(
            'controller' => 'users',
            'action' => 'login',
            'plugin' => 'flex_auth'
    );
    
    public $authenticate = array('FlexAuth.Flex');
    
    /**
     * Pega as Roles existentes e herdadas do usuário
     * @param unknown_type $node_id
     */
    public static function getRoles($node_id=null) {
        $user = self::user();
         
        if(is_null($node_id)) {
            if(!empty($user)) {
                // $userModel = $this->settings['userModel'];
                // list(, $model) = pluginSplit($userModel);
                $node_id=$user['Node']['id'];
                 
                if(isset($user['Roles'])) {
                    return $user['Roles'];
                }
                 
            } else {
                return array();
            }
        }
         
        $groupModel =ClassRegistry::init('FlexAuth.Group');
        $groups=$groupModel->getPath($node_id,null,1);
        
        $idr=array();
        foreach($groups as $group) {
            foreach($group['Role'] as $role) {
                $idr[$group['Group']['name']][$role['id']]=$role['id'];
            }
        }
        
        return $idr;
    }  
    
    
    public static function getPermissionObject() {
        if(ClassRegistry::isKeySet("FlexAuth.permission")) {
            return ClassRegistry::getObject("FlexAuth.permission");
        } else {
            $permission = ClassRegistry::init("FlexAuth.Permission");
            ClassRegistry::addObject("FlexAuth.permission", $permission);
            return $permission;
        }
    }
    
    
    /**
     * Verifica permissao
     * @param unknown_type $permission
     * @param unknown_type $roles_groups
     */
    public static function checkPermission($permission,$roles_groups) {
        $inverted_groups=array_reverse($roles_groups,true);
        
        foreach($inverted_groups as $roles) {
            $p = self::getPermissionObject()->checkPermission($permission,$roles);
        
            if($p===false) {
                return false;
        
            } elseif($p===true) {
        
                return true;
            }
        
        }
        
        return null;
        
    }
    
    
    /**
     * Retorna true se o aquela permissao for garantida para o usuario
     * falso se nao e null se a permissão não foi atribuida ao usuario/grupos
     * @param string $permission
     * @param id $user_id
     */
    public static function isPermitted($permission,$user_id=null,$roles_groups=array()) {
        
        if(empty($roles_groups)) {
            $roles_groups=self::getRoles($user_id);
        }
        
        return self::checkPermission($permission, $roles_groups);
        
    }
    
    
}


?>