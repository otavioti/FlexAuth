<?php //debug($permissions)?>
<div class="roles form">
<?php echo $this->Form->create('Role'); ?>
	<fieldset>
		<legend><?php echo __('Add Role'); ?></legend>
	<?php
		echo $this->Form->input('name');
		//echo $this->Form->input('Permission');
	?>
	<table cellpadding="0" cellspacing="0">
	<tr>
	    <th><?php echo __('Name Permission')?></th><th><?php echo __('Permission grant')?></th>
	</tr>
	<?php 
	    $this->Html->css('FlexAuth.flexauth',null,array('block'=>'css'));
	    $options=array('grant'=>__('grant'),'deny'=>__('deny'),'inheritance'=>__('inheritance'));
	    $attributes=array('separator'=>'&nbsp','legend'=>false,'label'=>false, 'hiddenField' => false,'class'=>'locRad');
	
	?>
	<?php foreach($permissions as $k=> $permission):?>
	<tr>
	    <td><?php echo $permission?></td>
	    <td>
	        <?php $attributes['value']='inheritance'?>
	        <?php echo $this->Form->radio('Permission.'.$k.'.permission',$options,$attributes)."\n";?>
	    </td>
	</tr>
	<?php endforeach;?>
	</table>
	</fieldset>
	<?php $this->Html->script('FlexAuth.flexauth',array('block'=>'script'));?>
	<?php echo $this->Html->link(__('Reset Permissions'),"#",array('onClick'=>'resetRadioPermissions()'))?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Roles'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Permissions'), array('controller' => 'permissions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Permission'), array('controller' => 'permissions', 'action' => 'add')); ?> </li>
	</ul>
</div>
