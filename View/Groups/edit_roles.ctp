<?php echo $this->Html->script("FlexAuth.flexauth",array('block'=>'script'))?>

<div class="groups form">
<?php echo $this->Form->create('Group'); ?>
	<fieldset>
		<legend><?php echo __('Edit Roles for '); ?><?php echo $this->data['Group']['name']?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name',array('type'=>'hidden'));
		echo $this->Form->input('Role');
	?>
	<?php echo $this->Html->link("Reset Roles","#",array('onClick'=>"resetSelect('RoleRole')"))?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Edit User'), array('controller'=>'users', 'action' => 'edit', $this->data['Group']['foreign_key'])); ?> </li>
		<li><?php echo $this->Html->link(__('View User'), array('controller'=>'users', 'action' => 'view', $this->data['Group']['foreign_key'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller'=>'users','action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
	</ul>
</div>
