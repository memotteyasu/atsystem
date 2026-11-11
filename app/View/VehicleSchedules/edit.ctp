<div class="ch-container"><div class="row">
	<?php echo $this->element(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= 20 ? 'ClerkSidemenu' : 'HostSidemenu'); ?>
	<div id="content" class="col-lg-10 col-sm-10">
		<ul class="breadcrumb"><li><?php echo $this->Html->link('車両予定管理', array('action' => 'management')); ?> <span class="divider">/</span> 車両予定編集<?php echo $this->Flash->render(); ?></li></ul>
		<div class="row"><div class="box col-md-12"><div class="box-inner"><div class="box-header well"><h2><i class="glyphicon glyphicon-edit"></i> 車両予定編集</h2></div><div class="box-content">
			<?php echo $this->Form->create('VehicleSchedule', array('class' => 'form-horizontal', 'novalidate' => true)); ?>
			<div class="form-group"><label class="col-sm-2 control-label">車両</label><div class="col-sm-4"><?php echo $this->Form->input('companyvehicles_id', array('label' => false, 'div' => false, 'class' => 'form-control', 'options' => $vehicles, 'empty' => '選択してください')); ?></div></div>
			<?php $scheduledate = !empty($this->request->data['VehicleSchedule']['scheduledate']) ? $this->request->data['VehicleSchedule']['scheduledate'] : date('Y-m-d'); ?>
			<div class="form-group"><label class="col-sm-2 control-label">日付</label><div class="col-sm-3"><input type="date" name="data[VehicleSchedule][scheduledate]" class="form-control" value="<?php echo h($scheduledate); ?>" required></div></div>
			<div class="form-group"><label class="col-sm-2 control-label">予定内容</label><div class="col-sm-5"><?php echo $this->Form->input('schedulename', array('label' => false, 'div' => false, 'class' => 'form-control', 'maxlength' => 100)); ?></div></div>
			<div class="form-group"><label class="col-sm-2 control-label">表示色</label><div class="col-sm-2"><?php echo $this->Form->input('displaycolor', array('label' => false, 'div' => false, 'class' => 'form-control', 'type' => 'color', 'default' => '#5bc0de')); ?></div></div>
			<div class="form-group"><label class="col-sm-2 control-label">備考</label><div class="col-sm-6"><?php echo $this->Form->input('note', array('label' => false, 'div' => false, 'class' => 'form-control', 'maxlength' => 255)); ?></div></div>
			<div class="form-group"><div class="col-sm-offset-2 col-sm-10"><?php echo $this->Form->submit('更新', array('class' => 'btn btn-primary', 'div' => false)); ?> <?php echo $this->Html->link('キャンセル', array('action' => 'management'), array('class' => 'btn btn-default')); ?></div></div>
			<?php echo $this->Form->end(); ?>
		</div></div></div></div>
	</div>
</div></div>
