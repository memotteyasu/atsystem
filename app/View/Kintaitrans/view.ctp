<div class="row-fluid">
	<div class="span9">
		<h2><?php  echo __('Kintaitran');?></h2>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($kintaitran['Kintaitran']['id']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Datestart'); ?></dt>
			<dd>
				<?php echo h($kintaitran['Kintaitran']['datestart']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Dateend'); ?></dt>
			<dd>
				<?php echo h($kintaitran['Kintaitran']['dateend']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Kintaikubun'); ?></dt>
			<dd>
				<?php echo h($kintaitran['Kintaitran']['kintaikubun']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Kyukeitime'); ?></dt>
			<dd>
				<?php echo h($kintaitran['Kintaitran']['kyukeitime']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Heijitsuzangyo'); ?></dt>
			<dd>
				<?php echo h($kintaitran['Kintaitran']['heijitsuzangyo']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Heijitsushinya'); ?></dt>
			<dd>
				<?php echo h($kintaitran['Kintaitran']['heijitsushinya']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Kyujitsuzangyo'); ?></dt>
			<dd>
				<?php echo h($kintaitran['Kintaitran']['kyujitsuzangyo']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Kyujitsushinya'); ?></dt>
			<dd>
				<?php echo h($kintaitran['Kintaitran']['kyujitsushinya']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Houteikyujitsu'); ?></dt>
			<dd>
				<?php echo h($kintaitran['Kintaitran']['houteikyujitsu']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Houteikyujitsushinya'); ?></dt>
			<dd>
				<?php echo h($kintaitran['Kintaitran']['houteikyujitsushinya']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Delflg'); ?></dt>
			<dd>
				<?php echo h($kintaitran['Kintaitran']['delflg']); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Users'); ?></dt>
			<dd>
				<?php echo $this->Html->link($kintaitran['Users']['id'], array('controller' => 'users', 'action' => 'view', $kintaitran['Users']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Kintaidate'); ?></dt>
			<dd>
				<?php echo h($kintaitran['Kintaitran']['kintaidate']); ?>
				&nbsp;
			</dd>
		</dl>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('Edit %s', __('Kintaitran')), array('action' => 'edit', $kintaitran['Kintaitran']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete %s', __('Kintaitran')), array('action' => 'delete', $kintaitran['Kintaitran']['id']), null, __('Are you sure you want to delete # %s?', $kintaitran['Kintaitran']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Kintaitrans')), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Kintaitran')), array('action' => 'add')); ?> </li>
			<li><?php echo $this->Html->link(__('List %s', __('Users')), array('controller' => 'users', 'action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New %s', __('Users')), array('controller' => 'users', 'action' => 'add')); ?> </li>
		</ul>
		</div>
	</div>
</div>

