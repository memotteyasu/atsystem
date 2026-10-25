<div class="flgmasters view">
<h2><?php echo __('Flgmaster'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($flgmaster['Flgmaster']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Flgname'); ?></dt>
		<dd>
			<?php echo h($flgmaster['Flgmaster']['flgname']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Flggroup'); ?></dt>
		<dd>
			<?php echo h($flgmaster['Flgmaster']['flggroup']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Flgmaster'), array('action' => 'edit', $flgmaster['Flgmaster']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Flgmaster'), array('action' => 'delete', $flgmaster['Flgmaster']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $flgmaster['Flgmaster']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Flgmasters'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Flgmaster'), array('action' => 'add')); ?> </li>
	</ul>
</div>
