<div class="ch-container"><div class="row">
	<?php echo $this->element(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= 20 ? 'ClerkSidemenu' : 'HostSidemenu'); ?>
	<div id="content" class="col-lg-10 col-sm-10">
		<ul class="breadcrumb"><li><?php echo $this->Html->link('予定管理', array('action' => 'index')); ?> <span class="divider">/</span> 予定編集<?php echo $this->Flash->render(); ?></li></ul>
		<div class="row"><div class="box col-md-12"><div class="box-inner">
			<div class="box-header well"><h2><i class="glyphicon glyphicon-pencil"></i> 予定編集</h2></div>
			<div class="box-content"><?php echo $this->element('HostSchedules/form'); ?></div>
		</div></div></div>
	</div>
</div></div>
