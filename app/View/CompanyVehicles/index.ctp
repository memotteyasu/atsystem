<div class="ch-container"><div class="row">
	<?php echo $this->element(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= 20 ? 'ClerkSidemenu' : 'HostSidemenu'); ?>
	<div id="content" class="col-lg-10 col-sm-10">
		<ul class="breadcrumb"><li><?php echo $this->Html->link('車両予定', array('controller' => 'VehicleSchedules', 'action' => 'index')); ?> <span class="divider">/</span> 営業車マスタ<?php echo $this->Flash->render(); ?></li></ul>
		<div class="row"><div class="box col-md-12"><div class="box-inner"><div class="box-header well"><h2><i class="glyphicon glyphicon-road"></i> 営業車マスタ</h2></div><div class="box-content"><div class="table-responsive">
			<table class="table table-bordered table-condensed"><thead><tr><th>表示順</th><th>車両名</th><th>ナンバー</th><th>所属営業所</th><th></th></tr></thead><tbody>
			<?php foreach ($companyVehicles as $companyVehicle): ?><tr><td><?php echo h($companyVehicle['CompanyVehicle']['displayno']); ?></td><td><?php echo h($companyVehicle['CompanyVehicle']['vehiclename']); ?></td><td><?php echo h($companyVehicle['CompanyVehicle']['plateno']); ?></td><td><?php echo h($companyVehicle['Branchmaster']['branchname']); ?></td><td class="text-right"><?php echo $this->Html->link('編集', array('action' => 'edit', $companyVehicle['CompanyVehicle']['id']), array('class' => 'btn btn-xs btn-info')); ?> <?php echo $this->Form->postLink('利用停止', array('action' => 'delete', $companyVehicle['CompanyVehicle']['id']), array('class' => 'btn btn-xs btn-danger'), 'この営業車を利用停止にしますか？'); ?></td></tr><?php endforeach; ?>
			<?php if (empty($companyVehicles)): ?><tr><td colspan="5">登録されている営業車はありません。</td></tr><?php endif; ?>
			</tbody></table>
		</div><div><?php echo $this->Html->link('営業車を登録', array('action' => 'add'), array('class' => 'btn btn-primary')); ?></div></div></div></div></div>
	</div>
</div></div>
