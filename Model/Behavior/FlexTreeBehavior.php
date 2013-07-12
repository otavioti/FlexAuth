<?php
/**
 * Flex Tree behavior class. Based on Acl Behavior 
 *
 * 
 */
App::uses('ModelBehavior', 'Model');
App::uses('AclNode', 'Model');
App::uses('Hash', 'Utility');
App::uses("UsersTree", "FlexAuth.Model");

/**
 * FlexTree behavior
 *
 * Enables objects to easily tie into an FlexTree system
 *
 * @package       Cake.Model.Behavior
 * @link http://book.cakephp.org/2.0/en/core-libraries/behaviors/acl.html
 */
class FlexTreeBehavior extends ModelBehavior {

/**
 * Maps ACL type options to ACL models
 *
 * @var array
 */
	protected $_typeMaps = array('user_group'=>'UsersTree');
	protected $_typeNames = array('UsersTree'=>'FlexAuth.UsersTree');

/**
 * Sets up the configuration for the model, and loads ACL models if they haven't been already
 *
 * @param Model $model
 * @param array $config
 * @return void
 */
	public function setup(Model $model, $config = array()) {
		if (isset($config[0])) {
			$config['type'] = $config[0];
			unset($config[0]);
		}
		$this->settings[$model->name] = array_merge(array('type' => 'controlled'), $config);
		$this->settings[$model->name]['type'] = strtolower($this->settings[$model->name]['type']);

		$types = $this->_typeMaps[$this->settings[$model->name]['type']];

		if (!is_array($types)) {
			$types = array($types);
		}
		foreach ($types as $type) {
		    $typename = $type;
		    if(isset($this->_typeNames[$type])) {
		        $typename=$this->_typeNames[$type];
		    }
			$model->{$type} = ClassRegistry::init($typename);
		}
		if (!method_exists($model, 'parentNode')) {
			//trigger_error(__d('cake_dev', 'Callback parentNode() not defined in %s', $model->alias), E_USER_WARNING);
		}
	}

/**
 * Retrieves the Aro/Aco node for this model
 *
 * @param Model $model
 * @param string|array|Model $ref Array with 'model' and 'foreign_key', model object, or string value
 * @param string $type Only needed when Acl is set up as 'both', specify 'Aro' or 'Aco' to get the correct node
 * @return array
 * @link http://book.cakephp.org/2.0/en/core-libraries/behaviors/acl.html#node
 */
	public function node(Model $model, $ref = null, $type = null) {
		if (empty($type)) {
			$type = $this->_typeMaps[$this->settings[$model->name]['type']];
			if (is_array($type)) {
				trigger_error(__d('cake_dev', 'AclBehavior is setup with more then one type, please specify type parameter for node()'), E_USER_WARNING);
				return null;
			}
		}
		if (empty($ref)) {
			$ref = array('model' => $model->name, 'foreign_key' => $model->id);
		}
		return $model->{$type}->node($ref);
	}

/**
 * Creates a new ARO/ACO node bound to this record
 *
 * @param Model $model
 * @param boolean $created True if this is a new record
 * @return void
 */
	public function afterSave(Model $model, $created) {
		$types = $this->_typeMaps[$this->settings[$model->name]['type']];
		if (!is_array($types)) {
			$types = array($types);
		}
		foreach ($types as $type) {
			$parent = $model->parentNode();
			if (!empty($parent)) {
				$parent = $this->node($model, $parent, $type);
			}  else {
			   if(isset($model->data['Parent']['id'])) {
			       $parent[0][$type]['id'] = $model->data['Parent']['id'];
			   }
			} 
			
			
			if(isset($model->data[$model->alias]['parent_id']) && $model->data[$model->alias]['parent_id'] != 0 ) {
			    $parent[0][$type]['id'] = $model->data[$model->name]['parent_id'];
			}
			
			
			
			$data = array(
				'parent_id' => isset($parent[0][$type]['id']) ? $parent[0][$type]['id'] : null,
				'model' => $model->name,
				'foreign_key' => $model->id,
			);

			
			$alias=$model->read($model->displayField);
			if(!empty($alias)) {
				$data['alias']=$alias[$model->name][$model->displayField];
			}
			
			
			if (!$created) {
				$node = $this->node($model, null, $type);
				$data['id'] = isset($node[0][$type]['id']) ? $node[0][$type]['id'] : null;
			}
			
			
			
			$model->{$type}->create();
			$model->{$type}->save($data);
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see ModelBehavior::beforeDelete()
	 */
	public function beforeDelete(Model $model, $cascade = true) {
	    $type = $this->_typeMaps[$this->settings[$model->name]['type']];
	    $node = $this->node($model);
	    $allChildren = $model->{$type}->children($node[0][$type]['id']);
	    if(!empty($allChildren)) {
	        return false;
	    }
	    return true;
	}
	
	
	
/**
 * Destroys the ARO/ACO node bound to the deleted record
 *
 * @param Model $model
 * @return void
 */
	public function afterDelete(Model $model) {
		$types = $this->_typeMaps[$this->settings[$model->name]['type']];
		if (!is_array($types)) {
			$types = array($types);
		}
		foreach ($types as $type) {
			$node = Hash::extract($this->node($model, null, $type), "0.{$type}.id");
			if (!empty($node)) {
				$model->{$type}->delete($node);
			}
		}
	}
	
	
	
    
	/**
	 * Get Parent Node on Tree linked
	 * @author Otavio Augusto
	 * @param Model $Model
	 * @param integer|string $id The ID of the record to read
     * @param string|array $fields
     * @param integer $recursive The number of levels deep to fetch associated records
     * @return array|boolean Array of data for the parent node
	 */
	public function getParent(Model $model, $id = null, $fields = null, $recursive = null) {
	    $type = $this->_typeMaps[$this->settings[$model->name]['type']];
	    return $model->{$type}->getParentNode($id,$fields,$recursive);
	}

	/**
	 * Conveniente function to get TreeModel
	 */
	public function getTree() {
	    $type = $this->_typeMaps[$this->settings[$model->name]['type']];
	    return $model->{$type};
	}

}
