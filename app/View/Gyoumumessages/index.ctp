<div class="gyoumumessages index">
	<h2><?php echo __('Gyoumumessages'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('gyoumucomment'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('delflg'); ?></th>
			<th><?php echo $this->Paginator->sort('kaisyas_id'); ?></th>
			<th><?php echo $this->Paginator->sort('departmentmasters_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($gyoumumessages as $gyoumumessage): ?>
	<tr>
		<td><?php echo h($gyoumumessage['Gyoumumessage']['id']); ?>&nbsp;</td>
		<td><?php echo h($gyoumumessage['Gyoumumessage']['gyoumucomment']); ?>&nbsp;</td>
		<td><?php echo h($gyoumumessage['Gyoumumessage']['created']); ?>&nbsp;</td>
		<td><?php echo h($gyoumumessage['Gyoumumessage']['modified']); ?>&nbsp;</td>
		<td><?php echo h($gyoumumessage['Gyoumumessage']['delflg']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($gyoumumessage['Kaisyas']['id'], array('controller' => 'kaisyas', 'action' => 'view', $gyoumumessage['Kaisyas']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($gyoumumessage['Departmentmasters']['id'], array('controller' => 'departmentmasters', 'action' => 'view', $gyoumumessage['Departmentmasters']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $gyoumumessage['Gyoumumessage']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $gyoumumessage['Gyoumumessage']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $gyoumumessage['Gyoumumessage']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $gyoumumessage['Gyoumumessage']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Gyoumumessage'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Kaisyas'), array('controller' => 'kaisyas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Kaisyas'), array('controller' => 'kaisyas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Departmentmasters'), array('controller' => 'departmentmasters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Departmentmasters'), array('controller' => 'departmentmasters', 'action' => 'add')); ?> </li>
	</ul>
</div>
