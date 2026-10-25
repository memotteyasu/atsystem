<div class="flgmasters form">
<?php echo $this->Form->create('Flgmaster'); ?>
	<fieldset>
		<legend><?php echo __('Add Flgmaster'); ?></legend>
	<?php
		echo $this->Form->input('flgname');
		echo $this->Form->input('flggroup');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Flgmasters'), array('action' => 'index')); ?></li>
	</ul>
</div>
