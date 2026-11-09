<style>
	.schedule-form-actions .btn { display: inline-block; margin-right: 6px; vertical-align: middle; }
</style>
<?php echo $this->Form->create('Schedule', array('class' => 'form-horizontal', 'novalidate' => true)); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<div class="form-group">
	<label class="col-sm-2 control-label">対象</label>
	<div class="col-sm-4">
		<?php if (!empty($fixedCompanySchedule)): ?>
			<input type="hidden" name="company_schedule" value="1">
			<p class="form-control-static">社内全体</p>
		<?php elseif (!empty($fixedScheduleUser)): ?>
			<?php echo $this->Form->input('users_id', array('type' => 'hidden', 'value' => $fixedScheduleUser['id'])); ?>
			<input type="hidden" name="fixed_user" value="1">
			<p class="form-control-static"><?php echo h($fixedScheduleUser['name']); ?></p>
		<?php else: ?>
			<?php echo $this->Form->input('users_id', array('label' => false, 'div' => false, 'class' => 'form-control', 'options' => $scheduleUsers, 'empty' => '選択してください')); ?>
		<?php endif; ?>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2 control-label">日付</label>
	<?php $scheduledate = !empty($this->request->data['Schedule']['scheduledate']) ? $this->request->data['Schedule']['scheduledate'] : date('Y-m-d'); ?>
	<div class="col-sm-3">
		<input type="date" name="data[Schedule][scheduledate]" id="ScheduleScheduledate" class="form-control" value="<?php echo h($scheduledate); ?>" required>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2 control-label">予定内容</label>
	<div class="col-sm-5"><?php echo $this->Form->input('schedulename', array('label' => false, 'div' => false, 'class' => 'form-control', 'maxlength' => 100)); ?></div>
</div>
<div class="form-group">
	<label class="col-sm-2 control-label">表示色</label>
	<div class="col-sm-2"><?php echo $this->Form->input('displaycolor', array('label' => false, 'div' => false, 'class' => 'form-control', 'type' => 'color', 'default' => '#5bc0de')); ?></div>
</div>
<div class="form-group">
	<label class="col-sm-2 control-label">備考</label>
	<div class="col-sm-6"><?php echo $this->Form->input('note', array('label' => false, 'div' => false, 'class' => 'form-control', 'maxlength' => 255)); ?></div>
</div>
<div class="form-group schedule-form-actions"><div class="col-sm-offset-2 col-sm-10">
	<?php echo $this->Form->submit(isset($this->request->params['action']) && $this->request->params['action'] === 'edit' ? '更新' : '登録', array('class' => 'btn btn-primary', 'div' => false)); ?>
	<?php echo $this->Html->link('キャンセル', array('action' => 'index'), array('class' => 'btn btn-default')); ?>
</div></div>
<?php echo $this->Form->end(); ?>
