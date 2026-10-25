<div class="gyoumumessages form">
<?php echo $this->Form->create('Gyoumumessage'); ?>
	<fieldset>
		<legend><?php echo __('Edit Gyoumumessage'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('gyoumucomment');
		echo $this->Form->input('delflg');
		echo $this->Form->input('kaisyas_id');
		echo $this->Form->input('departmentmasters_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Gyoumumessage.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Gyoumumessage.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Gyoumumessages'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Kaisyas'), array('controller' => 'kaisyas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Kaisyas'), array('controller' => 'kaisyas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Departmentmasters'), array('controller' => 'departmentmasters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Departmentmasters'), array('controller' => 'departmentmasters', 'action' => 'add')); ?> </li>
	</ul>
</div>
