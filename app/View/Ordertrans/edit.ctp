<div class="ordertrans form">
<?php echo $this->Form->create('Ordertran'); ?>
	<fieldset>
		<legend><?php echo __('Edit Ordertran'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('loadingdate');
		echo $this->Form->input('deliverydate');
		echo $this->Form->input('loadingnote');
		echo $this->Form->input('deliverynote');
		echo $this->Form->input('shippermasters_id');
		echo $this->Form->input('productdetailmasters_id');
		echo $this->Form->input('delflg');
		echo $this->Form->input('loadingmasters_has_deliverymasters_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Ordertran.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Ordertran.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Ordertrans'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Shippermasters'), array('controller' => 'shippermasters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Shippermasters'), array('controller' => 'shippermasters', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Productdetailmasters'), array('controller' => 'productdetailmasters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Productdetailmasters'), array('controller' => 'productdetailmasters', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Loadingmasters Has Deliverymasters'), array('controller' => 'loadingmasters_has_deliverymasters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Loadingmasters Has Deliverymasters'), array('controller' => 'loadingmasters_has_deliverymasters', 'action' => 'add')); ?> </li>
	</ul>
</div>
