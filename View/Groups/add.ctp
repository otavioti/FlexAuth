<div class="groups form">
<?php echo $this->Form->create('Group'); ?>
	<fieldset>
		<legend><?php echo __('Add Group'); ?></legend>
	<?php
		echo $this->Form->input('parent_id',array('empty'=>'ROOT','options'=>$group_list));
		echo $this->Form->input('name');
		echo $this->Form->input('Role');
	?>
	<?php echo $this->Html->script("FlexAuth.flexauth",array('block'=>'script'))?>
	<?php echo $this->Html->link("Reset Roles","#",array('onClick'=>"resetSelect('RoleRole')"))?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Groups'), array('action' => 'index')); ?></li>
	</ul>
</div>
