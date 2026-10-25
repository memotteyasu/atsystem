<div class="personalmessages index">
	<h2><?php echo __('Personalmessages'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('personalcomment'); ?></th>
			<th><?php echo $this->Paginator->sort('delflg'); ?></th>
			<th><?php echo $this->Paginator->sort('users_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($personalmessages as $personalmessage): ?>
	<tr>
		<td><?php echo h($personalmessage['Personalmessage']['id']); ?>&nbsp;</td>
		<td><?php echo h($personalmessage['Personalmessage']['personalcomment']); ?>&nbsp;</td>
		<td><?php echo h($personalmessage['Personalmessage']['delflg']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($personalmessage['Users']['id'], array('controller' => 'users', 'action' => 'view', $personalmessage['Users']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $personalmessage['Personalmessage']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $personalmessage['Personalmessage']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $personalmessage['Personalmessage']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $personalmessage['Personalmessage']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Personalmessage'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Users'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
