<div class="ch-container">
	<style>
		.vehicle-schedules-management-link a { width: auto; padding: 2px 6px; }
	</style>
	<div class="row">
		<?php echo $this->element(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= 20 ? 'ClerkSidemenu' : 'HostSidemenu'); ?>
		<div id="content" class="col-lg-10 col-sm-10">
			<ul class="breadcrumb"><li><?php echo $this->Html->link('車両予定', array('action' => 'index')); ?> <span class="divider">/</span> 車両予定管理<?php echo $this->Flash->render(); ?></li></ul>
			<div class="row"><div class="box col-md-12"><div class="box-inner">
				<div class="box-header well"><h2><i class="glyphicon glyphicon-road"></i> 車両予定管理</h2><div class="box-icon vehicle-schedules-management-link"><?php echo $this->Html->link('カレンダー表示', array('action' => 'index'), array('class' => 'btn btn-xs btn-default')); ?> <?php echo $this->Html->link('車両予定を登録', array('action' => 'add'), array('class' => 'btn btn-xs btn-primary')); ?></div></div>
				<div class="box-content"><div class="table-responsive"><table class="table table-bordered table-condensed">
					<thead><tr><th>日付</th><th>車両</th><th>予定内容</th><th>備考</th><th></th></tr></thead>
					<tbody>
						<?php foreach ($vehicleSchedules as $vehicleSchedule): ?>
							<tr>
								<td><?php echo h($vehicleSchedule['VehicleSchedule']['scheduledate']); ?></td>
								<td><?php echo h($vehicleSchedule['CompanyVehicle']['vehiclename']); ?></td>
								<td><span class="label" style="background-color:<?php echo h($vehicleSchedule['VehicleSchedule']['displaycolor']); ?>; color:#333;"><?php echo h($vehicleSchedule['VehicleSchedule']['schedulename']); ?></span></td>
								<td><?php echo h($vehicleSchedule['VehicleSchedule']['note']); ?></td>
								<td class="text-right"><?php echo $this->Html->link('編集', array('action' => 'edit', $vehicleSchedule['VehicleSchedule']['id']), array('class' => 'btn btn-xs btn-info')); ?> <?php echo $this->Form->postLink('削除', array('action' => 'delete', $vehicleSchedule['VehicleSchedule']['id']), array('class' => 'btn btn-xs btn-danger'), 'この車両予定を削除しますか？'); ?></td>
							</tr>
						<?php endforeach; ?>
						<?php if (empty($vehicleSchedules)): ?><tr><td colspan="5">登録されている今後の車両予定はありません。</td></tr><?php endif; ?>
					</tbody>
				</table></div></div>
			</div></div></div>
		</div>
	</div>
</div>
