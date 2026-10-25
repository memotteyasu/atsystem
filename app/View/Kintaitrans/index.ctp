<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Kintaitrans'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('datestart');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('dateend');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('kintaikubun');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('kyukeitime');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('heijitsuzangyo');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('heijitsushinya');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('kyujitsuzangyo');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('kyujitsushinya');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('houteikyujitsu');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('houteikyujitsushinya');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('delflg');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('users_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('kintaidate');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($kintaitrans as $kintaitran): ?>
			<tr>
				<td><?php echo h($kintaitran['Kintaitran']['id']); ?>&nbsp;</td>
				<td><?php echo h($kintaitran['Kintaitran']['datestart']); ?>&nbsp;</td>
				<td><?php echo h($kintaitran['Kintaitran']['dateend']); ?>&nbsp;</td>
				<td><?php echo h($kintaitran['Kintaitran']['kintaikubun']); ?>&nbsp;</td>
				<td><?php echo h($kintaitran['Kintaitran']['kyukeitime']); ?>&nbsp;</td>
				<td><?php echo h($kintaitran['Kintaitran']['heijitsuzangyo']); ?>&nbsp;</td>
				<td><?php echo h($kintaitran['Kintaitran']['heijitsushinya']); ?>&nbsp;</td>
				<td><?php echo h($kintaitran['Kintaitran']['kyujitsuzangyo']); ?>&nbsp;</td>
				<td><?php echo h($kintaitran['Kintaitran']['kyujitsushinya']); ?>&nbsp;</td>
				<td><?php echo h($kintaitran['Kintaitran']['houteikyujitsu']); ?>&nbsp;</td>
				<td><?php echo h($kintaitran['Kintaitran']['houteikyujitsushinya']); ?>&nbsp;</td>
				<td><?php echo h($kintaitran['Kintaitran']['delflg']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($kintaitran['Users']['id'], array('controller' => 'users', 'action' => 'view', $kintaitran['Users']['id'])); ?>
				</td>
				<td><?php echo h($kintaitran['Kintaitran']['kintaidate']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $kintaitran['Kintaitran']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $kintaitran['Kintaitran']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $kintaitran['Kintaitran']['id']), null, __('Are you sure you want to delete # %s?', $kintaitran['Kintaitran']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Kintaitran')), array('action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Users')), array('controller' => 'users', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Users')), array('controller' => 'users', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>