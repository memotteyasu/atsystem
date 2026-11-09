<div class="ch-container"><div class="row">
	<?php echo $this->element(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= 20 ? 'ClerkSidemenu' : 'HostSidemenu'); ?>
	<div id="content" class="col-lg-10 col-sm-10">
		<ul class="breadcrumb"><li><?php echo $this->Html->link('Top', array('controller' => 'HostTops', 'action' => 'index')); ?> <span class="divider">/</span> 車両予定<?php echo $this->Flash->render(); ?></li></ul>
		<style>
			.vehicle-schedule-controls { margin-bottom: 8px; white-space: nowrap; }
			.vehicle-schedule-controls select { display: inline-block; width: auto; min-width: 150px; vertical-align: middle; }
			.vehicle-schedule-controls .btn { margin-left: 4px; vertical-align: middle; }
			.vehicle-weekly-schedule { width: 100%; min-width: 680px; font-size: 11px; table-layout: fixed; }
			.vehicle-weekly-schedule th, .vehicle-weekly-schedule td { padding: 4px 3px; text-align: center; vertical-align: middle; }
			.vehicle-weekly-schedule .vehicle-name { width: 16%; font-size: 12px; text-align: left; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
			.vehicle-weekly-schedule .schedule-cell-link { display: block; min-height: 17px; color: inherit; text-decoration: none; }
			.vehicle-weekly-schedule .schedule-cell-link:hover { background-color: #f5f5f5; text-decoration: none; }
			.vehicle-weekly-schedule .schedule-entry { display: block; margin: 1px 0; padding: 1px 2px; border: 2px solid; color: #333; font-weight: bold; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
			.vehicle-weekly-schedule .saturday { background: #f5f9ff; }
			.vehicle-weekly-schedule .sunday { background: #fff6f6; }
		</style>
		<div class="row"><div class="box col-md-12"><div class="box-inner">
			<div class="box-header well"><h2><i class="glyphicon glyphicon-road"></i> 営業車予定（<?php echo h($weekStart->format('n/j')); ?>〜<?php echo h($weekEnd->format('n/j')); ?>）</h2></div>
			<div class="box-content">
				<form action="<?php echo $this->Html->url(array('action' => 'index')); ?>" method="get" class="vehicle-schedule-controls">
					<label for="week-offset">表示期間&nbsp;</label>
					<?php if ((int)$weekOffset > -11): ?><?php echo $this->Html->link('前週', array('action' => 'index', '?' => array('week_offset' => (int)$weekOffset - 1)), array('class' => 'btn btn-warning btn-sm')); ?><?php else: ?><button type="button" class="btn btn-warning btn-sm" disabled>前週</button><?php endif; ?>
					<select name="week_offset" id="week-offset" class="form-control input-sm" onchange="this.form.submit()"><?php foreach ($weekOptions as $value => $label): ?><option value="<?php echo h($value); ?>"<?php echo (int)$value === (int)$weekOffset ? ' selected' : ''; ?>><?php echo h($label); ?></option><?php endforeach; ?></select>
					<?php if ((int)$weekOffset < 11): ?><?php echo $this->Html->link('次週', array('action' => 'index', '?' => array('week_offset' => (int)$weekOffset + 1)), array('class' => 'btn btn-warning btn-sm')); ?><?php else: ?><button type="button" class="btn btn-warning btn-sm" disabled>次週</button><?php endif; ?>
					<?php echo $this->Html->link('従業員予定', array('controller' => 'HostTops', 'action' => 'index'), array('class' => 'btn btn-default btn-sm')); ?>
				</form>
				<div class="table-responsive"><table class="table table-bordered table-condensed vehicle-weekly-schedule"><thead><tr><th class="vehicle-name">車両</th><?php foreach ($weekDates as $weekDate): ?><th class="<?php echo $weekDate['weekday'] === 6 ? 'saturday' : ($weekDate['weekday'] === 7 ? 'sunday' : ''); ?>"><?php echo h($weekDate['label']); ?></th><?php endforeach; ?></tr></thead><tbody>
					<?php foreach ($vehicles as $vehicleId => $vehicleName): ?><tr><td class="vehicle-name"><?php echo h($vehicleName); ?></td><?php foreach ($weekDates as $weekDate): ?><?php $entries = isset($entriesByVehicleAndDate[$vehicleId][$weekDate['date']]) ? $entriesByVehicleAndDate[$vehicleId][$weekDate['date']] : array(); ?><?php $addUrl = $this->Html->url(array('action' => 'add', '?' => array('companyvehicles_id' => $vehicleId, 'scheduledate' => $weekDate['date']))); ?><td class="<?php echo empty($entries) ? ($weekDate['weekday'] === 6 ? 'saturday' : ($weekDate['weekday'] === 7 ? 'sunday' : '')) : ''; ?>"><a href="<?php echo $addUrl; ?>" class="schedule-cell-link" title="車両予定を登録"><?php foreach ($entries as $entry): ?><span class="schedule-entry" style="border-color:<?php echo h($entry['color']); ?>;" title="<?php echo h($entry['name']); ?>"><?php echo h($entry['name']); ?></span><?php endforeach; ?></a></td><?php endforeach; ?></tr><?php endforeach; ?>
					<?php if (empty($vehicles)): ?><tr><td colspan="8">表示できる現役車両がありません。</td></tr><?php endif; ?>
				</tbody></table></div>
			</div>
		</div></div></div>
	</div>
</div></div>
