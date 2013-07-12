<div class="roles form">
<?php echo $this->Form->create('Role'); ?>
	<fieldset>
		<legend><?php echo __('Edit Role'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	
	<?php 
	
	
	   
	
	    $this->Html->css('FlexAuth.flexauth',null,array('block'=>'css'));
    	$this->Html->script('FlexAuth.jquery-1.10.2.min',array('block'=>'script'));
    	$this->Html->script('FlexAuth.flexauth',array('block'=>'script'));
	    
	    $options=array('grant'=>__('grant'),'deny'=>__('deny'),'inheritance'=>__('inheritance'));
	    $attributes=array('separator'=>'&nbsp','legend'=>false,'label'=>false, 'hiddenField' => false,'class'=>'locRad');
	    
	    $tabela=array();
	    
    	foreach($permissions as $k=> $permission) {
    	
            $attributes['value']='inheritance';
            
                if(isset($role_permissions[$k])) {
                   if($role_permissions[$k]) {
                       $attributes['value']='grant';
                   } else {
                       $attributes['value']='deny';
                   }
                }
                
              $code=$this->Form->radio("Permission.".$k.".permission",$options,$attributes);
              $this->TableTree->agrupa($permission,$code); 
    	}
    	debug($this->TableTree->tabela);
    ?>
    <ul>
        <?php echo $this->TableTree->show(); ?>
    </ul>
	        
    
	<table cellpadding="0" cellspacing="0" id="PermissionsTable">
	    <tbody>
	    </tbody>
	</table>
	
	</fieldset>
	<?php echo $this->Html->link(__('Reset Permissions'),"#",array('onClick'=>'resetRadioPermissions()'))?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Role.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Role.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Roles'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Permissions'), array('controller' => 'permissions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Permission'), array('controller' => 'permissions', 'action' => 'add')); ?> </li>
	</ul>
</div>
