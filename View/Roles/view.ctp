<div class="roles view">
<h2><?php  echo __('Role'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($role['Role']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($role['Role']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Role'), array('action' => 'edit', $role['Role']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Role'), array('action' => 'delete', $role['Role']['id']), null, __('Are you sure you want to delete # %s?', $role['Role']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Roles'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Role'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Permissions'), array('controller' => 'permissions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Permission'), array('controller' => 'permissions', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Permissions'); ?></h3>
	<?php if (!empty($role['Permission'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Grant'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($role['Permission'] as $permission): ?>
		<tr>
			<td><?php echo $permission['id']; ?></td>
			<td><?php echo $permission['name']; ?></td>
			<td><?php echo $permission['description']; ?></td>
			<td><?php 
			    $rs=__("inheritance");
			    if(isset($role_permissions[$permission['id']])) {
			        if($role_permissions[$permission['id']]) {
			            $rs=__("grant");
			        } else {
			            $rs=__("deny");
			        }
			    }
			    echo $rs;
			?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'permissions', 'action' => 'view', $permission['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'permissions', 'action' => 'edit', $permission['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'permissions', 'action' => 'delete', $permission['id']), null, __('Are you sure you want to delete # %s?', $permission['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Permission'), array('controller' => 'permissions', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
