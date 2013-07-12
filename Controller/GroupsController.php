<?php
App::uses('FlexAuthAppController', 'FlexAuth.Controller');
/**
 * Groups Controller
 *
 * @property Group $Group
 */
class GroupsController extends FlexAuthAppController {

    public $uses = array('FlexAuth.Group');
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Group->recursive = 0;
		$this->set('groups', $this->paginate($conditions = array('Group.foreign_key'=>null)));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Group->exists($id)) {
			throw new NotFoundException(__('Invalid group'));
		}
		$options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
		$this->set('group', $this->Group->find('first', $options));
		$this->set('parent',$this->Group->getParentNode($id));
		$this->set('path_list',$this->Group->getPath($id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Group->create();
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		}
		
		
		$this->set('group_list',$this->Group->getGroupList());
		$roles = $this->Group->Role->find('list');
		$this->set(compact('roles'));
		
	
		
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Group->isGroup($id)) {
			throw new NotFoundException(__('Invalid group'));
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
			$this->request->data = $this->Group->find('first', $options);
		}
		$this->set('group_list',$this->Group->getGroupList($id));
		$roles = $this->Group->Role->find('list');
		$this->set(compact('roles'));
		
	}
/**
 * edit role method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit_roles($id = null) {
		if (!$this->Group->exists($id)) {
			throw new NotFoundException(__('Invalid group'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
		    
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved'));
				$this->redirect(array('action' => 'index','controller'=>'users','plugin'=>'flex_auth'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
			$this->request->data = $this->Group->find('first', $options);
		}
		$this->set('group_list',$this->Group->getGroupList($id));
		$roles = $this->Group->Role->find('list');
		$this->set(compact('roles'));
		
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Group->id = $id;
		if (!$this->Group->exists()) {
			throw new NotFoundException(__('Invalid group'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Group->removeFromTree($id,true)) {
		    
		    $u=& ClassRegistry::getInstance("FlexAuth.User");
		    $u->create();
		    $u->updateAll('parent_id',$user['Group']['parent_id'],array('User.parent_id'=>$id));
		    
			$this->Session->setFlash(__('Group deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Group was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
