<div class="ordertrans index">
	<h2><?php echo __('Ordertrans'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('loadingdate'); ?></th>
			<th><?php echo $this->Paginator->sort('deliverydate'); ?></th>
			<th><?php echo $this->Paginator->sort('loadingnote'); ?></th>
			<th><?php echo $this->Paginator->sort('deliverynote'); ?></th>
			<th><?php echo $this->Paginator->sort('shippermasters_id'); ?></th>
			<th><?php echo $this->Paginator->sort('productdetailmasters_id'); ?></th>
			<th><?php echo $this->Paginator->sort('delflg'); ?></th>
			<th><?php echo $this->Paginator->sort('loadingmasters_has_deliverymasters_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($ordertrans as $ordertran): ?>
	<tr>
		<td><?php echo h($ordertran['Ordertran']['id']); ?>&nbsp;</td>
		<td><?php echo h($ordertran['Ordertran']['loadingdate']); ?>&nbsp;</td>
		<td><?php echo h($ordertran['Ordertran']['deliverydate']); ?>&nbsp;</td>
		<td><?php echo h($ordertran['Ordertran']['loadingnote']); ?>&nbsp;</td>
		<td><?php echo h($ordertran['Ordertran']['deliverynote']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($ordertran['Shippermasters']['id'], array('controller' => 'shippermasters', 'action' => 'view', $ordertran['Shippermasters']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($ordertran['Productdetailmasters']['id'], array('controller' => 'productdetailmasters', 'action' => 'view', $ordertran['Productdetailmasters']['id'])); ?>
		</td>
		<td><?php echo h($ordertran['Ordertran']['delflg']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($ordertran['LoadingmastersHasDeliverymasters']['id'], array('controller' => 'loadingmasters_has_deliverymasters', 'action' => 'view', $ordertran['LoadingmastersHasDeliverymasters']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $ordertran['Ordertran']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $ordertran['Ordertran']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $ordertran['Ordertran']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $ordertran['Ordertran']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Ordertran'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Shippermasters'), array('controller' => 'shippermasters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Shippermasters'), array('controller' => 'shippermasters', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Productdetailmasters'), array('controller' => 'productdetailmasters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Productdetailmasters'), array('controller' => 'productdetailmasters', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Loadingmasters Has Deliverymasters'), array('controller' => 'loadingmasters_has_deliverymasters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Loadingmasters Has Deliverymasters'), array('controller' => 'loadingmasters_has_deliverymasters', 'action' => 'add')); ?> </li>
	</ul>
</div>
