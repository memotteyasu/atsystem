<div class="gasusetrans view">
<h2><?php echo __('Gasusetran'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($gasusetran['Gasusetran']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Gaskind'); ?></dt>
		<dd>
			<?php echo h($gasusetran['Gasusetran']['gaskind']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($gasusetran['Gasusetran']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($gasusetran['Gasusetran']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Chargedate'); ?></dt>
		<dd>
			<?php echo h($gasusetran['Gasusetran']['chargedate']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Amount'); ?></dt>
		<dd>
			<?php echo h($gasusetran['Gasusetran']['amount']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Users Id'); ?></dt>
		<dd>
			<?php echo h($gasusetran['Gasusetran']['users_id']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Gasusetran'), array('action' => 'edit', $gasusetran['Gasusetran']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Gasusetran'), array('action' => 'delete', $gasusetran['Gasusetran']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $gasusetran['Gasusetran']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Gasusetrans'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Gasusetran'), array('action' => 'add')); ?> </li>
	</ul>
</div>
