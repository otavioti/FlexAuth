<?php
class ManageShell extends AppShell {
    
    public $uses = array('FlexAuth.User','FlexAuth.Group','FlexAuth.Permission','FlexAuth.Role');
    
    public $scaffoldActions = array('index', 'view', 'add', 'edit');
    
    public $blacklist=array('isauthorized','login','logout');
    
    public $permissions=array();
    
    public function getOptionParser() {
        $parser = parent::getOptionParser();
        
        $plugin = array(
                'short' => 'p',
                'help' => __d('cake_console', 'The plugin to use.'),
        );
        $controller = array(
                'short' => 'c',
                'help' => __d('cake_console', 'Controller para carregar. Se não especificado carregada todos.'),
        );
        
        $admin = array(
                'short' => 'a',
                'boolean' => true,
                'help' => __d('cake_console', 'Inclui uma Role Permissão que permite acesso completo aos Controllers FlexAuth.'),
        );
        
        $parser->addSubcommand('createuser', array(
                'help' => 'Cria o usuario.',
                'parser'=>array(
                            'description'=>array('Use este comando para criar o usuario inicial'),
                            'arguments'=>array(
                                'username'=>array('help'=>'Login do usuario.','required'=>true),
                                'password'=>array('help'=>'Senha do usuario.','required'=>true),
                                'group'=>array('help'=>'Grupo.','required'=>false),
                             ),
                            'options' => compact('admin'),
                    ),
               ))->addSubcommand('creategroup', array(
                'help' => 'Cria o grupo.',
                'parser'=>array(
                            'description'=>array('Use este comando para criar o grupo inicial'),
                            'arguments'=>array(
                                'name'=>array('help'=>'Nome do grupo.','required'=>true),
                                'parent'=>array('help'=>'Grupo a qual este pertence','required'=>false),
                             )
                    ),
                
                ))->addSubcommand('view',array(
                        'help'=>'lista objetos',
                         'parser'=>array(
                                   'description'=>array('lista todos os itens selecionados'),
                                   'arguments'=>array(
                                     'objeto'=>array('help'=>'Objeto a ser listado.','required'=>true,'choices'=>array('groups','users')),
                                )
                           ),
                
                ))->addSubcommand('loadactions',array(
                        'help'=>'lista objetos',
                         'parser'=>array(
                                   'description'=>array('criar permissoes para todas as actions no padrao [Plugin.]Controller.action'),
                                   'options' => compact('plugin','controller'),
                                   
                           ),
                ));
        return $parser;
    }
    
    
    public function loadactions() {
        
        $app_dir=ROOT.DS.APP_DIR;
        
        App::uses("AppController", 'Controller');
        $plugin_app_controller=null;
        if(isset($this->params['plugin'])) {
            $app_dir.=DS.'Plugin'.DS.$this->params['plugin'];
            
            $plugin_app_controller=$this->params['plugin']."AppController";
            $this->params['plugin'].=".";
            App::uses($plugin_app_controller, $this->params['plugin'].'Controller');
        } else {
            $this->params['plugin']="";
        }
        
        if(isset($this->params['controller'])) {
            $controllers[] = $this->params['controller'];   
        } else {
            
            $controller_dir=$app_dir.DS.'Controller';
            
            $d = dir($controller_dir);
            while (false !== ($entry = $d->read())) {
                if($entry == "AppController.php") {
                    continue;
                }
                
                if(!is_null($plugin_app_controller)) {
                    $pname=$plugin_app_controller.".php";
                    if($entry == $pname) {
                        continue;
                    }
                }
                if(strpos($entry,'Controller')!==false) {
                    list($controller,)=explode("Controller",$entry);
                    $controllers[]=$controller;
                }
            }
            $d->close();
        }

        foreach($controllers as $controller) {
            $this->_loadPermissions($controller, $this->params['plugin']);
        }
        
        $this->_savePermissions();
        
    }
    
    /**
     * Salva ass permissoes no banco
     */
    public function _savePermissions() {
        foreach($this->permissions as $perm) {
            $data=array();
            $data['Permission']['name']=$perm;
            $this->Permission->create();
            $this->Permission->save($data);
        }
    }
    
    /**
     * Pega as permissoes do controller
     * @param unknown_type $controller
     * @param unknown_type $plugin
     */
    public function _loadPermissions($controller,$plugin="",$plugin_app_controller=null) {
        
            $controllerClassName=$controller."Controller";
            App::uses($controllerClassName, $plugin . 'Controller');
            if (!class_exists($controllerClassName)) {
                $file = $controllerClassName . '.php';
                $this->err(__d('cake_console', "The file '%s' could not be found.\nIn order to bake a view, you'll need to first create the controller.", $file));
                $this->_stop();
            }
            $methods=$this->_getMethods($controller,$plugin_app_controller=null);
            foreach($methods as $method) {
                if(!in_array($method,$this->blacklist)) {
                    $this->permissions[]=$plugin.$controller.".".$method;
                }
            }
            
        
    }
    
    /**
     * Retorna os metodos que são actions
     * @param unknown_type $controller
     */
    public function _getMethods($controller,$plugin_app_controller=null) {
        $methods=array();
        if(is_null($plugin_app_controller) && class_exists($plugin_app_controller)) {
            $methods = array_diff(
                    array_map('strtolower', get_class_methods($controller . 'Controller')),
                    array_map('strtolower', get_class_methods('AppController')),
                    array_map('strtolower', get_class_methods($plugin_app_controller))
            );
        } else {
            $methods = array_diff(
                    array_map('strtolower', get_class_methods($controller . 'Controller')),
                    array_map('strtolower', get_class_methods('AppController'))
            );
        }
        if (empty($methods)) {
            $methods = $this->scaffoldActions;
        }
        
        foreach($methods as $i => $method) {
            if ($method[0] === '_' || $method == strtolower($controller . 'Controller')) {
                unset($methods[$i]);
            }
        }
        return $methods;
    }
    
    public function _listuser() {
        $users = $this->User->find('all');
        $co=$this->getMaxwidth($users);
        
        $uline=$this->montaLinha($co['uc']);
        $gline=$this->montaLinha($co['gc']);
        //header
        $this->out("+".$uline."+".$gline."+");
        $this->out("|<info>".$this->centerString("Username", $co['uc'])."</info>|<info>".$this->centerString("Groupname", $co['gc'])."</info>|");
        $this->out("+".$uline."+".$gline."+");
        
        reset($users);
        foreach($users as $user) {
           $this->out("|".$this->centerString($user['User']['username'], $co['uc'])."|".$this->centerString($user['Group']['name'], $co['gc'])."|");
           $this->out("+".$uline."+".$gline."+");
        }
        
    }
    
    
    public function centerString($string,$width) {
        $len=strlen($string);
        if($len==$width) return $string;
        
        if($len < $width) {
            $string= str_pad($string, $width," ",STR_PAD_BOTH);
        }
        return $string;
    }
    
    public function getMaxwidth($users) {
        
        
        $utitle="Username";
        $gtitle="Groupname";
        
        $uc=strlen($utitle);
        $gc=strlen($gtitle);
        
        
        foreach($users as $user) {
            $u_c=strlen($user['User']['username']);
            if($u_c > $uc) $uc=$u_c;

            $g_c=strlen($user['Group']['name']);
            if($g_c > $gc) $gc=$g_c;
            
        }
        return array('uc'=>$uc+2,"gc"=>$gc+2);        
        
    }
    
    
    public function montaLinha($c) {
        $line="";
        for($i=0;$i<$c;$i++) {
            $line.="-";
        }
        return $line;
    }
    
    
    public function _listgroup() {
       $groups=$this->Group->getGroupList();
      // print_r($groups);
       foreach($groups as $group) {
           $line="";
           if(strpos($group,"_")!==false) {
               $c=substr_count($group, "_");
               $group=str_replace("_", "", $group);
               $line="";
               for($i=1;$i<$c;$i++) {
                   $line.=" ";
               }
               $line.="|_";
           } else {
               $this->out("---------------------------------------------------------------");
           }
           $this->out($line.$group);
       }
    }
   
    
    public function view() {
        switch ($this->args[0]) {
            case "groups":
                $this->_listgroup();
                break;
            case "users":
                $this->_listuser();
                
        }
    }
    
    
    public function _getAdminPermission() {
        $name="FlexAuth.AdminPermission";
        $p=$this->Permission->findByName($name);
        if(empty($p)) {
            $data=array();
            $data['Permission']['name']=$name;
            $data['Permission']['description']="Da acesso a todos os métodos do plugin FlexAuth";
            $this->Permission->create();
            $this->Permission->save($data);
            return $this->Permission->id;
        }
        return $p['Permission']['id'];
        
    }
    
    public function _getAdminRole() {
        $name="AdminRole";
        
        $r=$this->Role->findByName($name);
        if(empty($r)) {
            $permission_id=$this->_getAdminPermission();
            $data=array();
            $data['Role']['name']=$name;
            $this->Role->create();
            $this->Role->save($data);
            
            $data_rp=array();
            $data_rp['RolePermission']['role_id']=$this->Role->id;
            $data_rp['RolePermission']['permission_id']=$permission_id;
            $data_rp['RolePermission']['granted']=true;
            $this->Role->RolePermission->save($data_rp);
            return $data_rp['RolePermission']['role_id'];
            
        }
        return $r['Role']['id'];
    }
    
    /**
     * cria permissoes básicas
     */
    public function _basicPermissions() {
        
        $basics_permissions=array(
                array('name'=> 'FlexAuth.AdminPermission',
                      'description'=>"Da acesso a todos os métodos do plugin FlexAuth"
                     ),
                array('name'=> 'FlexAuth.ChangePassword',
                      'description'=>"Da acesso Para que o usuário troque a própria senha"
                     ),
           );

        
        foreach($basics_permissions as $bp) {
            $data=array();
            $data['Permission']['name']=$bp['name'];
            $data['Permission']['description']=$bp['description'];
            $this->Permission->create();
            $this->Permission->save($data);
        }
    }
    
    /**
     * Cria os usuarios no banco
     */
    public function createuser() {
        $username=$this->args[0];
        $password=$this->args[1];
        $groupname=null;
        
        $admin_user=$this->params['admin'];
        
        $u=array('User'=>array('username'=>$username,'password'=>$password));

        if(isset($this->args[2])) {
            $groupname=$this->args[2];
        
            $group=$this->Group->findByName($groupname);
            if(empty($group)) {
                $this->out("<error>Grupo '$groupname' nao encontrado!</error>");
                return;
            }
            $u['User']['parent_id']=$group['Group']['id'];
        }
        
        //se for o primeiro usuario cria permissao básica;
        
        
        $c=$this->User->find('count');
        if($c<1) {
            $this->_basicPermissions();
        }
        
        $this->User->create();
        if($this->User->save($u)) {
            
            if($admin_user) {
                $user=$this->User->read();
                $role_id=$this->_getAdminRole();
                $data=array();
                $data['RoleUsersTree']['users_tree_id']=$user['Node']['id'];
                $data['RoleUsersTree']['role_id']=$user['Node']['id'];
                $this->Group->RoleUsersTree->save($data);
            }
            
            $this->out("<info>Usuario criado com sucesso!</info>");
        } else {
            $this->out("<error>Erro ao criar usuario</error>");
        }
        
    }
    
    /**
     * Cria um grupo no banco
     */
    public function creategroup() {
        $name=$this->args[0];
        $parent_name = null;
        $parent = array();
        if(isset($this->args[1])) {
            $parent_name = $this->args[1];
        }
        if(!is_null($parent_name)) {
            $parent = $this->Group->findByName($parent_name);
            if(empty($parent)) {
                $this->out("<error>Group Pai nao encontrado: '$parent_name'</error>");
                return;
            }        
        }
        
        $group=array('Group'=>array('name'=>$name));
        
        if(!empty($parent)) {
            
            $group['Group']['parent_id']=$parent['Group']['id'];
        }
        
        $this->Group->create();
        if($this->Group->save($group)) {
            $this->out("<info>Grupo '$name' criado</info>");
        } else {
            $this->out("<error>Erro ao criar grupo</error>");
        }
        
    }
    
}


?>