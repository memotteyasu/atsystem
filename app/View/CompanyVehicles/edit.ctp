<div class="ch-container"><div class="row">
	<?php echo $this->element(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= 20 ? 'ClerkSidemenu' : 'HostSidemenu'); ?>
	<div id="content" class="col-lg-10 col-sm-10">
		<ul class="breadcrumb"><li><?php echo $this->Html->link('営業車マスタ', array('action' => 'index')); ?> <span class="divider">/</span> <?php echo empty($this->request->data['CompanyVehicle']['id']) ? '営業車登録' : '営業車編集'; ?><?php echo $this->Flash->render(); ?></li></ul>
		<div class="row"><div class="box col-md-12"><div class="box-inner"><div class="box-header well"><h2><i class="glyphicon glyphicon-road"></i> 営業車マスタ</h2></div><div class="box-content">
			<?php echo $this->Form->create('CompanyVehicle', array('class' => 'form-horizontal', 'novalidate' => true)); ?>
			<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
			<div class="form-group"><label class="col-sm-2 control-label">車両名</label><div class="col-sm-5"><?php echo $this->Form->input('vehiclename', array('label' => false, 'div' => false, 'class' => 'form-control', 'maxlength' => 100)); ?></div></div>
			<div class="form-group"><label class="col-sm-2 control-label">ナンバー</label><div class="col-sm-5"><?php echo $this->Form->input('plateno', array('label' => false, 'div' => false, 'class' => 'form-control', 'maxlength' => 100)); ?></div></div>
			<div class="form-group"><label class="col-sm-2 control-label">表示順</label><div class="col-sm-2"><?php echo $this->Form->input('displayno', array('label' => false, 'div' => false, 'class' => 'form-control', 'type' => 'number', 'min' => 0, 'default' => 0)); ?></div></div>
			<div class="form-group"><label class="col-sm-2 control-label">所属営業所</label><div class="col-sm-4"><?php echo $this->Form->input('branchmasters_id', array('label' => false, 'div' => false, 'class' => 'form-control', 'options' => $branchmasters, 'empty' => '選択してください')); ?></div></div>
			<div class="form-group"><div class="col-sm-offset-2 col-sm-10"><?php echo $this->Form->submit('保存', array('class' => 'btn btn-primary', 'div' => false)); ?> <?php echo $this->Html->link('キャンセル', array('action' => 'index'), array('class' => 'btn btn-default')); ?></div></div>
			<?php echo $this->Form->end(); ?>
		</div></div></div></div>
	</div>
</div></div>
