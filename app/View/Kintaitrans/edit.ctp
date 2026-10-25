<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('Kintaitran', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('Edit %s', __('Kintaitran')); ?></legend>
				<?php
				echo $this->BootstrapForm->input('datestart');
				echo $this->BootstrapForm->input('dateend');
				echo $this->BootstrapForm->input('kintaikubun');
				echo $this->BootstrapForm->input('kyukeitime');
				echo $this->BootstrapForm->input('heijitsuzangyo');
				echo $this->BootstrapForm->input('heijitsushinya');
				echo $this->BootstrapForm->input('kyujitsuzangyo');
				echo $this->BootstrapForm->input('kyujitsushinya');
				echo $this->BootstrapForm->input('houteikyujitsu');
				echo $this->BootstrapForm->input('houteikyujitsushinya');
				echo $this->BootstrapForm->input('delflg');
				echo $this->BootstrapForm->input('users_id', array(
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('kintaidate');
				echo $this->BootstrapForm->hidden('id');
				?>
				<?php echo $this->BootstrapForm->submit(__('Submit'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Kintaitran.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Kintaitran.id'))); ?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Kintaitrans')), array('action' => 'index'));?></li>
			<li><?php echo $this->Html->link(__('List %s', __('Users')), array('controller' => 'users', 'action' => 'index')); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Users')), array('controller' => 'users', 'action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
</div>