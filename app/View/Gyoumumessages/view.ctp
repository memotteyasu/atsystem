<div class="gyoumumessages view">
<h2><?php echo __('Gyoumumessage'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($gyoumumessage['Gyoumumessage']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Gyoumucomment'); ?></dt>
		<dd>
			<?php echo h($gyoumumessage['Gyoumumessage']['gyoumucomment']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($gyoumumessage['Gyoumumessage']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($gyoumumessage['Gyoumumessage']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Delflg'); ?></dt>
		<dd>
			<?php echo h($gyoumumessage['Gyoumumessage']['delflg']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Kaisyas'); ?></dt>
		<dd>
			<?php echo $this->Html->link($gyoumumessage['Kaisyas']['id'], array('controller' => 'kaisyas', 'action' => 'view', $gyoumumessage['Kaisyas']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Departmentmasters'); ?></dt>
		<dd>
			<?php echo $this->Html->link($gyoumumessage['Departmentmasters']['id'], array('controller' => 'departmentmasters', 'action' => 'view', $gyoumumessage['Departmentmasters']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Gyoumumessage'), array('action' => 'edit', $gyoumumessage['Gyoumumessage']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Gyoumumessage'), array('action' => 'delete', $gyoumumessage['Gyoumumessage']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $gyoumumessage['Gyoumumessage']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Gyoumumessages'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Gyoumumessage'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Kaisyas'), array('controller' => 'kaisyas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Kaisyas'), array('controller' => 'kaisyas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Departmentmasters'), array('controller' => 'departmentmasters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Departmentmasters'), array('controller' => 'departmentmasters', 'action' => 'add')); ?> </li>
	</ul>
</div>
