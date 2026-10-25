<div class="flgmasters index">
	<h2><?php echo __('Flgmasters'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('flgname'); ?></th>
			<th><?php echo $this->Paginator->sort('flggroup'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($flgmasters as $flgmaster): ?>
	<tr>
		<td><?php echo h($flgmaster['Flgmaster']['id']); ?>&nbsp;</td>
		<td><?php echo h($flgmaster['Flgmaster']['flgname']); ?>&nbsp;</td>
		<td><?php echo h($flgmaster['Flgmaster']['flggroup']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $flgmaster['Flgmaster']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $flgmaster['Flgmaster']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $flgmaster['Flgmaster']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $flgmaster['Flgmaster']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Flgmaster'), array('action' => 'add')); ?></li>
	</ul>
</div>
