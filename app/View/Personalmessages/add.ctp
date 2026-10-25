<div class="personalmessages form">
<?php echo $this->Form->create('Personalmessage'); ?>
	<fieldset>
		<legend><?php echo __('Add Personalmessage'); ?></legend>
	<?php
		echo $this->Form->input('personalcomment');
		echo $this->Form->input('delflg');
		echo $this->Form->input('users_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Personalmessages'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Users'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
