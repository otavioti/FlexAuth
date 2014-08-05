<?php

App::uses('ModelBehavior', 'Model');
App::uses('Hash', 'Utility');
App::uses("Group", "FlexAuth.Model");


class UserFlexTreeBehavior extends ModelBehavior {
    
    
    
    public function setup(Model $model, $config = array()) {
        $this->settings['groupClass']=isset($config['groupClass'])?$config['groupClass']:"Group";
        $this->settings['plugin']=isset($config['plugin'])?$config['plugin']:"FlexAuth";
        $groupClass = $this->settings['groupClass']; 
        $className = $groupClass;
        if(!empty($this->settings['plugin'])) {
            $className = $this->settings['plugin'].".".$className;
        }
        
        if(!isset($model->{$groupClass})) {
            $model->bindModel(array('belongsTo'=>array($groupClass=>array(
                                                                     'className' => $className,
                                                                      'foreignKey'=>'parent_id',
                                                                      'conditions'=>array($groupClass.'.model'=>null)
                
                                                                          )
                                                        )
                                    ),false
                     );
        }
        $model->bindModel(array('hasOne'=>array("Node"=>array(
                'className' => $className,
                'foreignKey'=>'foreign_key',
                'conditions'=>array('Node.model'=>$model->name)
        
                    )
                    )
                    ), false
        );
        if(!isset($model->{$groupClass})) {
        
            $model->{$groupClass} = ClassRegistry::init($className);
        }
        
    }
    
    
    
    public function beforeSave(Model $model, $options = array()) {
        
        return true;
    }
    
    
    public function getNode(Model $model, $id=null) {
        $groupClass = $this->settings['groupClass'];
        if(is_null($id)) {
            if(empty($model->id)) {
                trigger_error(__d('cake_dev', 'Id nao foi defindo em getNode()'), E_USER_WARNING);
            }
            $id = $model->id;
        }
        
        $node = $model->{$groupClass}->find('first',
                array('conditions'=>array('model'=>$model->name,
                                          'foreign_key'=>$model->id,
                        )));
       
        return $node;
    }
    
    public function afterSave(Model $model, $created=true, $options = array()) {
        $groupClass = $this->settings['groupClass'];
        
           
        $data[$groupClass]['foreign_key'] = $model->id;
        $data[$groupClass]['model'] = $model->name;
           
        if(!$created) {
            $n=$this->getNode($model,$model->data[$model->name][$model->primaryKey]);
            $data=$n;
        }
        
        $data[$groupClass][$model->{$groupClass}->displayField]=$model->data[$model->name][$model->displayField];

        if(isset($model->data[$model->name]['parent_id'])) {
              $data[$groupClass]['parent_id'] = $model->data[$model->name]['parent_id'];
        }
        
        $model->{$groupClass}->create();
        $model->{$groupClass}->save($data);
        
    }
    
    
    public function afterDelete(Model $model) {
        $groupClass = $this->settings['groupClass'];
        $n=$this->getNode($model,$model->id);
        $model->{$groupClass}->delete($n[$groupClass]['id']);
    }
    
    public function parentNode(Model $model) {
    
    
        if (!$model->id && empty($model->data)) {
    
            return null;
    
        }
    
        $data = $model->data;
    
        if (empty($model->data)) {
            $data = $model->read();
        }
        $foreignKey=Inflector::underscore($this->settings['groupClass']);
        $foreignKey.="_id";
        if (!$data[$model->name][$foreignKey]) {
            return null;
        } else {
            return array($this->settings['groupClass'] => array('id' => $data[$model->name][$foreignKey]));
        }
    
    }
    
}


?>