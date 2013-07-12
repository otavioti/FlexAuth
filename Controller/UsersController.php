<?php
App::uses('FlexAuthAppController', 'FlexAuth.Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends FlexAuthAppController {

    public $name = 'Users';
    
    public $uses = array('FlexAuth.User');
    
    public $components = array ('FlexAuth.Login');
    
	public function isAuthorized($user) {
	    if($this->request->params['action']=="password") {
	        
	        return true;
	    }
	
	    return parent::isAuthorized($user);
	}
    
    public function beforeFilter() {
        $this->AuthFlex->allow("login");
        $this->AuthFlex->allow("logout");
    }
    
    
    /**
     * login method
     */
    public function login() {
        if($this->request->is("post")) {
            if($this->AuthFlex->login()) {
                return $this->redirect($this->AuthFlex->redirectUrl());
            } else {
                $this->Session->setFlash(__('Username or password incorrect!'), 'default', array(), 'auth');
            }
        }
    }
    
    /**
     * logout method
     *
     */
    public function logout() {
        $this->redirect($this->AuthFlex->logout());
    }//logout
    
    
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$parents = $this->User->Group->getGroupList();
		$this->set(compact('parents'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$parents = $this->User->Group->getGroupList();
		$this->set(compact('parents'));
	}
	
	
	
	
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function password($id = null) {
		
		if(AuthFlexComponent::isPermitted("FlexAuth.AdminPermission") OR 
		        AuthFlexComponent::isPermitted("FlexAuth.Users.password")) {
		    goto continua;
		} else {
		    if(!AuthFlexComponent::isPermitted("FlexAuth.ChangePassword")) {
		        throw new ForbiddenException(__("You don't have permission for change password!"));
		    } else {
		        $id=null;
		    }
		}
		
		continua:
		
		if(is_null($id)) {
		    $u = AuthFlexComponent::user();
		    $id=$u['User']['id'];
		}
		
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The password has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
				
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
			unset($this->request->data['User']['password']);
		}
		
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
