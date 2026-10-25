<div class="productmasters view">
<h2><?php echo __('Productmaster'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($productmaster['Productmaster']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Pname'); ?></dt>
		<dd>
			<?php echo h($productmaster['Productmaster']['pname']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Pnameshort'); ?></dt>
		<dd>
			<?php echo h($productmaster['Productmaster']['pnameshort']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Pcode'); ?></dt>
		<dd>
			<?php echo h($productmaster['Productmaster']['pcode']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($productmaster['Productmaster']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($productmaster['Productmaster']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Delflg'); ?></dt>
		<dd>
			<?php echo h($productmaster['Productmaster']['delflg']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Productmaster'), array('action' => 'edit', $productmaster['Productmaster']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Productmaster'), array('action' => 'delete', $productmaster['Productmaster']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $productmaster['Productmaster']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Productmasters'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Productmaster'), array('action' => 'add')); ?> </li>
	</ul>
</div>
