<?php
App::uses('FlexAuthAppController', 'FlexAuth.Controller');
/**
 * Roles Controller
 *
 * @property Role $Role
 */
class RolesController extends FlexAuthAppController {

    
    public $uses=array('FlexAuth.Role');
    
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Role->recursive = 0;
		$this->set('roles', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Role->exists($id)) {
			throw new NotFoundException(__('Invalid role'));
		}
		$options = array('conditions' => array('Role.' . $this->Role->primaryKey => $id));
		$this->set('role', $this->Role->find('first', $options));
		$role_permissions= $this->Role->RolePermission->find('list',array('fields'=>array('RolePermission.permission_id','RolePermission.granted'), 'conditions'=>array('role_id'=>$id)));
		$this->set(compact('role_permissions'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Role->create();
			if ($this->Role->save($this->request->data)) {
				$this->Session->setFlash(__('The role has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The role could not be saved. Please, try again.'));
			}
		}
		$permissions = $this->Role->Permission->find('list');
		$this->set(compact('permissions'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Role->exists($id)) {
			throw new NotFoundException(__('Invalid role'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Role->save($this->request->data)) {
				$this->Session->setFlash(__('The role has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The role could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Role.' . $this->Role->primaryKey => $id));
			$this->request->data = $this->Role->find('first', $options);
		}
		$permissions = $this->Role->Permission->find('list');
		$this->set(compact('permissions'));
		$role_permissions= $this->Role->RolePermission->find('list',array('fields'=>array('RolePermission.permission_id','RolePermission.granted'), 'conditions'=>array('role_id'=>$id)));
		$this->set(compact('role_permissions'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Role->id = $id;
		if (!$this->Role->exists()) {
			throw new NotFoundException(__('Invalid role'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Role->delete()) {
			$this->Session->setFlash(__('Role deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Role was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
