<div class="productmasters index">
	<h2><?php echo __('Productmasters'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('pname'); ?></th>
			<th><?php echo $this->Paginator->sort('pnameshort'); ?></th>
			<th><?php echo $this->Paginator->sort('pcode'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('delflg'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($productmasters as $productmaster): ?>
	<tr>
		<td><?php echo h($productmaster['Productmaster']['id']); ?>&nbsp;</td>
		<td><?php echo h($productmaster['Productmaster']['pname']); ?>&nbsp;</td>
		<td><?php echo h($productmaster['Productmaster']['pnameshort']); ?>&nbsp;</td>
		<td><?php echo h($productmaster['Productmaster']['pcode']); ?>&nbsp;</td>
		<td><?php echo h($productmaster['Productmaster']['created']); ?>&nbsp;</td>
		<td><?php echo h($productmaster['Productmaster']['modified']); ?>&nbsp;</td>
		<td><?php echo h($productmaster['Productmaster']['delflg']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $productmaster['Productmaster']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $productmaster['Productmaster']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $productmaster['Productmaster']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $productmaster['Productmaster']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Productmaster'), array('action' => 'add')); ?></li>
	</ul>
</div>
