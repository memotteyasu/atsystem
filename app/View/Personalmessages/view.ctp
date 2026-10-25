<div class="personalmessages view">
<h2><?php echo __('Personalmessage'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($personalmessage['Personalmessage']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Personalcomment'); ?></dt>
		<dd>
			<?php echo h($personalmessage['Personalmessage']['personalcomment']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Delflg'); ?></dt>
		<dd>
			<?php echo h($personalmessage['Personalmessage']['delflg']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Users'); ?></dt>
		<dd>
			<?php echo $this->Html->link($personalmessage['Users']['id'], array('controller' => 'users', 'action' => 'view', $personalmessage['Users']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Personalmessage'), array('action' => 'edit', $personalmessage['Personalmessage']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Personalmessage'), array('action' => 'delete', $personalmessage['Personalmessage']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $personalmessage['Personalmessage']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Personalmessages'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Personalmessage'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Users'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
