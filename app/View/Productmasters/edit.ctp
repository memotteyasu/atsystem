<div class="productmasters form">
<?php echo $this->Form->create('Productmaster'); ?>
	<fieldset>
		<legend><?php echo __('Edit Productmaster'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('pname');
		echo $this->Form->input('pnameshort');
		echo $this->Form->input('pcode');
		echo $this->Form->input('delflg');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Productmaster.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Productmaster.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Productmasters'), array('action' => 'index')); ?></li>
	</ul>
</div>
