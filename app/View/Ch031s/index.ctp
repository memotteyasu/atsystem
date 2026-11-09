<div class="ch-container">
	<div class="row">
		<?php echo $this->element('Sidemenu'); ?>
		<div id="content" class="col-lg-10 col-sm-10">
			<ul class="breadcrumb">
				<li>
					<?php $this->Html->addCrumb('Top', array('controller' => $role . 'Tops', 'action' => 'index')); ?>
					<?php echo $this->element('breadcrumbs'); ?>
					<?php echo $this->Flash->render(); ?>
				</li>
			</ul>

			<div class="box">
				<div class="box-inner">
					<div class="box-header well"><h2><i class="glyphicon glyphicon-calendar"></i> 化学品 配車計画ボード</h2></div>
					<div class="box-content">
						<?php echo $this->Form->create(false, array('type' => 'get', 'class' => 'form-inline board-filter')); ?>
							<div class="form-group">
								<label for="board-date">対象日</label>
								<input id="board-date" class="form-control" type="date" name="date" value="<?php echo h($paramsDate); ?>">
							</div>
							<div class="form-group">
								<label for="board-branch">営業所</label>
								<?php echo $this->Form->select('branchmasters_id', $branchmasters, array('value' => $branchmastersId, 'id' => 'board-branch', 'class' => 'form-control', 'empty' => false)); ?>
							</div>
							<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-search"></i> 表示</button>
						<?php echo $this->Form->end(); ?>
						<p class="text-muted board-note">初期版は閲覧専用です。工程をクリックすると、現行のオーダー詳細を開きます。</p>
					</div>
				</div>
			</div>

			<div class="row board-summary">
				<div class="col-md-2"><div class="well"><small>当日工程</small><strong><?php echo h($summary['processes']); ?></strong></div></div>
				<div class="col-md-2"><div class="well board-unassigned"><small>未配車</small><strong><?php echo h($summary['unassigned']); ?></strong></div></div>
				<div class="col-md-2"><div class="well board-assigned"><small>乗務員設定済み</small><strong><?php echo h($summary['assigned']); ?></strong></div></div>
				<div class="col-md-2"><div class="well board-partner"><small>協力会社対応中</small><strong><?php echo h($summary['partner_pending']); ?></strong></div></div>
				<div class="col-md-2"><div class="well board-confirmed"><small>確定済み</small><strong><?php echo h($summary['confirmed']); ?></strong></div></div>
			</div>

			<div class="row">
				<div class="col-md-4">
					<div class="box"><div class="box-inner"><div class="box-header well"><h2><i class="glyphicon glyphicon-warning-sign"></i> 要対応</h2></div><div class="box-content">
						<?php $hasUnassigned = false; ?>
						<?php foreach ($vehicleLanes as $plateNo => $processes): ?>
							<?php foreach ($processes as $process): ?>
								<?php if ($process['status'] === 'unassigned'): $hasUnassigned = true; ?>
									<a class="board-alert" href="<?php echo $this->Html->url(array('controller' => 'Ch003s', 'action' => 'view', $process['order_id'])); ?>">
										<strong><?php echo h($plateNo); ?></strong> <?php echo h(substr($process['time'], 0, 5)); ?><br>
										<?php echo h($process['shipper']); ?> / <?php echo h($process['workplace']); ?>
									</a>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endforeach; ?>
						<?php if (!$hasUnassigned): ?><p class="text-muted">未配車の工程はありません。</p><?php endif; ?>
					</div></div></div>
				</div>
				<div class="col-md-8">
					<div class="box"><div class="box-inner"><div class="box-header well"><h2><i class="glyphicon glyphicon-road"></i> 車両別タイムライン</h2></div><div class="box-content">
						<?php if (empty($vehicleLanes)): ?>
							<p class="text-muted">対象日の工程がありません。</p>
						<?php else: ?>
							<?php foreach ($vehicleLanes as $plateNo => $processes): ?>
								<section class="vehicle-lane">
									<h3><i class="glyphicon glyphicon-truck"></i> <?php echo h($plateNo); ?></h3>
									<div class="process-list">
										<?php foreach ($processes as $process): ?>
											<a class="process-card <?php echo h($process['status']); ?>" href="<?php echo $this->Html->url(array('controller' => 'Ch003s', 'action' => 'view', $process['order_id'])); ?>">
												<div class="process-time"><?php echo h(substr($process['time'], 0, 5)); ?></div>
												<div class="process-body">
													<span class="label label-default">No. <?php echo h($process['workorder_no']); ?></span>
													<span class="status-label"><?php echo h($process['status_label']); ?></span>
													<strong><?php echo h($process['shipper']); ?> <?php echo h($process['product']); ?></strong>
													<p><?php echo h($process['workplace']); ?>（<?php echo h($process['workname']); ?>）</p>
													<small>乗務員: <span class="assignment-name" data-order-id="<?php echo h($process['order_id']); ?>" data-field="pdriver1id"><?php echo h($process['driver'] ? $process['driver'] : '未定'); ?></span> / 助手: <span class="assignment-name" data-order-id="<?php echo h($process['order_id']); ?>" data-field="pdriver2id"><?php echo h($process['assistant'] ? $process['assistant'] : '未定'); ?></span></small>
													<?php if ($process['partner_status']): ?><small class="partner-info">依頼先: <?php echo h($process['partner_branch']); ?>（<?php echo h($process['partner_status']); ?>）</small><?php endif; ?>
												</div>
											</a>
											<?php if (!$process['partner_status'] && $process['status'] !== 'confirmed'): ?>
												<div class="driver-assignment">
													<?php echo $this->Form->create('Processtran', array('url' => array('controller' => 'Ch031s', 'action' => 'assign_drivers', $process['process_id']), 'class' => 'driver-assignment-form')); ?>
														<?php echo $this->Form->hidden('return_date', array('value' => $paramsDate)); ?>
														<?php echo $this->Form->hidden('return_branchmasters_id', array('value' => $branchmastersId)); ?>
														<?php echo $this->Form->select('pdriver1id', $driverCandidates, array('empty' => '乗務員を選択', 'value' => $process['driver_id'], 'class' => 'form-control input-sm driver-select', 'data-process-id' => $process['process_id'], 'data-field' => 'pdriver1id')); ?>
														<?php echo $this->Form->select('pdriver2id', $driverCandidates, array('empty' => '助手を選択', 'value' => $process['assistant_id'], 'class' => 'form-control input-sm driver-select', 'data-process-id' => $process['process_id'], 'data-field' => 'pdriver2id')); ?>
													<?php echo $this->Form->end(); ?>
												</div>
												<?php if ($process['status'] === 'unassigned' && !empty($partnerBranches)): ?>
												<?php echo $this->Form->create('Processtran', array('url' => array('controller' => 'Ch031s', 'action' => 'request_partner', $process['process_id']), 'class' => 'partner-request-form')); ?>
													<?php echo $this->Form->hidden('return_date', array('value' => $paramsDate)); ?>
													<?php echo $this->Form->hidden('return_branchmasters_id', array('value' => $branchmastersId)); ?>
													<?php echo $this->Form->select('partner_branchmasters_id', $partnerBranches, array('empty' => '協力会社を選択', 'class' => 'form-control input-sm')); ?>
													<button type="submit" class="btn btn-default btn-sm">協力会社へ依頼</button>
												<?php echo $this->Form->end(); ?>
												<?php endif; ?>
											<?php endif; ?>
										<?php endforeach; ?>
									</div>
								</section>
							<?php endforeach; ?>
						<?php endif; ?>
					</div></div></div>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
.board-filter { display: flex; gap: 12px; align-items: end; flex-wrap: wrap; }
.board-filter label { display: block; margin-bottom: 4px; }
.board-note { margin: 14px 0 0; }
.board-summary .well { border-left: 5px solid #6b7b8c; margin-bottom: 15px; }
.board-summary .well strong { display: block; font-size: 28px; line-height: 1.1; }
.board-summary .board-unassigned { border-color: #d9534f; }
.board-summary .board-assigned { border-color: #f0ad4e; }
.board-summary .board-partner { border-color: #5bc0de; }
.board-summary .board-confirmed { border-color: #5cb85c; }
.board-alert { display: block; color: #8a2723; background: #fff1f0; border-left: 4px solid #d9534f; margin-bottom: 8px; padding: 10px; text-decoration: none; }
.board-alert:hover { color: #8a2723; background: #fde1df; }
.vehicle-lane { border: 1px solid #b8c8d8; margin-bottom: 14px; }
.vehicle-lane h3 { background: #3d668f; border-bottom: 2px solid #274968; color: #fff; font-size: 16px; font-weight: bold; margin: 0; padding: 10px 12px; }
.process-list { padding: 10px; }
.process-card { border-left: 5px solid #d9534f; color: #34495e; display: flex; margin: 0 0 8px; padding: 10px; text-decoration: none; }
.process-card:hover { background: #f7fafc; color: #34495e; }
.process-card.assigned { border-color: #f0ad4e; }
.process-card.partner { border-color: #5bc0de; }
.process-card.confirmed { border-color: #5cb85c; }
.process-time { font-size: 20px; font-weight: bold; min-width: 62px; }
.process-body { min-width: 0; }
.process-body strong { display: block; margin-top: 5px; }
.process-body p { margin: 3px 0; }
.status-label { color: #667; font-size: 12px; margin-left: 8px; }
.partner-info { display: block; color: #31708f; margin-top: 4px; }
.partner-request-form { display: flex; gap: 7px; margin: -3px 0 10px 67px; }
.driver-assignment { margin: -3px 0 8px 67px; }
.driver-assignment-form { display: flex; gap: 7px; }
@media (max-width: 767px) { .process-card { display: block; } }
</style>
<script>
$(function() {
	$('.driver-select').on('change', function() {
		var $select = $(this);
		$select.prop('disabled', true);
		$.ajax({
			url: '<?php echo $this->Html->url(array('controller' => 'Ch031s', 'action' => 'ajax_assign_driver')); ?>',
			type: 'POST',
			dataType: 'json',
			data: {
				process_id: $select.data('process-id'),
				field: $select.data('field'),
				driver_id: $select.val()
			},
			success: function(response) {
				if (response.success) {
					$('.assignment-name[data-order-id="' + response.ordertrans_id + '"][data-field="' + response.field + '"]').text(response.driver_name);
					if (response.field === 'pdriver1id') {
						$('.process-card').filter(function() {
							return $(this).find('.assignment-name[data-order-id="' + response.ordertrans_id + '"]').length > 0;
						}).removeClass('unassigned').addClass('assigned').find('.status-label').text('乗務員設定済み');
					}
					$select.prop('disabled', false);
					return;
				}
				alert(response.message);
				$select.prop('disabled', false);
			},
			error: function() {
				alert('乗務員の保存中にエラーが発生しました。');
				$select.prop('disabled', false);
			}
		});
	});
});
</script>
