<div class="ordertrans view">
<h2><?php echo __('Ordertran'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($ordertran['Ordertran']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Loadingdate'); ?></dt>
		<dd>
			<?php echo h($ordertran['Ordertran']['loadingdate']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deliverydate'); ?></dt>
		<dd>
			<?php echo h($ordertran['Ordertran']['deliverydate']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Loadingnote'); ?></dt>
		<dd>
			<?php echo h($ordertran['Ordertran']['loadingnote']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deliverynote'); ?></dt>
		<dd>
			<?php echo h($ordertran['Ordertran']['deliverynote']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Shippermasters'); ?></dt>
		<dd>
			<?php echo $this->Html->link($ordertran['Shippermasters']['id'], array('controller' => 'shippermasters', 'action' => 'view', $ordertran['Shippermasters']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Productdetailmasters'); ?></dt>
		<dd>
			<?php echo $this->Html->link($ordertran['Productdetailmasters']['id'], array('controller' => 'productdetailmasters', 'action' => 'view', $ordertran['Productdetailmasters']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Delflg'); ?></dt>
		<dd>
			<?php echo h($ordertran['Ordertran']['delflg']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Loadingmasters Has Deliverymasters'); ?></dt>
		<dd>
			<?php echo $this->Html->link($ordertran['LoadingmastersHasDeliverymasters']['id'], array('controller' => 'loadingmasters_has_deliverymasters', 'action' => 'view', $ordertran['LoadingmastersHasDeliverymasters']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Ordertran'), array('action' => 'edit', $ordertran['Ordertran']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Ordertran'), array('action' => 'delete', $ordertran['Ordertran']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $ordertran['Ordertran']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Ordertrans'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Ordertran'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Shippermasters'), array('controller' => 'shippermasters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Shippermasters'), array('controller' => 'shippermasters', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Productdetailmasters'), array('controller' => 'productdetailmasters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Productdetailmasters'), array('controller' => 'productdetailmasters', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Loadingmasters Has Deliverymasters'), array('controller' => 'loadingmasters_has_deliverymasters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Loadingmasters Has Deliverymasters'), array('controller' => 'loadingmasters_has_deliverymasters', 'action' => 'add')); ?> </li>
	</ul>
</div>
