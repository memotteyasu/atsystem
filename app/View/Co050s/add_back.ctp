<!-- 受注一覧 -->
<!-- Co050s/add -->
<div class="ch-container">
	<div class="row">

		<!-- left menu starts -->
			<?php echo $this->element('Sidemenu'); ?>
		<!-- left menu ends -->

		<div id="content" class="col-lg-10 col-sm-10">
			<!-- content starts -->
			<div>
				<ul class="breadcrumb">
					<li>
						<?php
							$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
							$this->Html->addCrumb('受注一覧', ['controller' => 'Co050s', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- 受注内容工程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>受注内容</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>荷主</th>
											<th>商品</th>
											<th>商品備考</th>
											<th>車両</th>
											<th>納入日時</th>
											<th>回戦</th>
											<th>積地</th>
											<th>納入先</th>
											<th>積み備考</th>
											<th>卸し備考</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><?php echo ($ordertrans['Shippermaster']['shippername']); ?></td>
											<td><?php echo ($ordertrans['Productdetailmaster']['pdname']); ?></td>
											<td><?php echo ($ordertrans['Ordertran']['productnote']); ?></td>
											<td><?php echo ($ordertrans['Carmaster']['plateno']); ?></td>
											<td style="color:#ff0000;">
												<?php //echo ($ordertrans['Ordertran']['deliverydate'].' '.$ordertrans['Ordertran']['deliverytime']); ?>
												<?php
													$deliverydate = new DateTime($ordertrans['Ordertran']['deliverydate'].$ordertrans['Ordertran']['deliverytime']);
													echo $deliverydate->format('Y-m-d H:i');
												?>
											</td>
											<td><?php echo ($ordertrans['Ordertran']['kaisen']); ?></td>
											<td><?php echo ($ordertrans['Loadingmaster']['loadingname']); ?></td>
											<td><?php echo ($ordertrans['Deliverymaster']['deliveryname']); ?></td>
											<td><?php echo ($ordertrans['Ordertran']['loadingnote']); ?></td>
											<td><?php echo ($ordertrans['Ordertran']['deliverynote']); ?></td>
											<td>
												<?php echo $this->Html->link(('編集'), ['controller'=>'Co000s','action' => 'edit', $ordertrans['Ordertran']['id'], 5], ['class' => ['btn btn-info']]); ?>
												<?php //echo $this->Form->postLink(('削除'), ['action' => 'delete', $ordertrans['Ordertran']['id']], ['confirm' => __('削除しますか？'), 'class'=>['btn btn-danger']]); ?>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>登録済工程内容</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<!--
											<th><?php echo $this->Paginator->sort('users_id', '作業者'); ?></th>
											<th><?php echo $this->Paginator->sort('workdetailmasters_id', '作業名称'); ?></th>
											<th><?php echo $this->Paginator->sort('workddetailcomment', '作業備考'); ?></th>
											<th><?php echo $this->Paginator->sort('processstartdate', '作業開始日時'); ?></th>
											<th><?php echo $this->Paginator->sort('processenddate', '作業終了日時'); ?></th>
											-->
											<th>作業者</th>
											<th>作業名称</th>
											<th>作業備考</th>
											<th>作業開始日時</th>
											<th>作業終了日時</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($processtrans as $processtran): ?>
											<tr>
												<td><?php echo ($processtran['User']['usernameK']); ?></td>
												<td><?php echo ($processtran['Workdetailmaster']['workname']); ?></td>
												<td><?php echo ($processtran['Processtran']['workddetailcomment']); ?></td>
												<td>
													<?php
														$processstart = new DateTime($processtran['Processtran']['processstartdate'].$processtran['Processtran']['processstarttime']);
														echo $processstart->format('Y-m-d H:i');
													?>
												</td>
												<td>
													<?php
														$processend = new DateTime($processtran['Processtran']['processenddate'].$processtran['Processtran']['processendtime']);
														echo $processend->format('Y-m-d H:i');
													?>
												</td>
												<td>
													<?php echo $this->Html->link(('編集'), ['action' => 'edit', $processtran['Processtran']['id'], $processtran['Processtran']['ordertrans_id']], ['class' => ['btn btn-info']]); ?>
													<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $processtran['Processtran']['id'], $processtran['Processtran']['ordertrans_id']], ['confirm' => __('削除しますか？'), 'class'=>['btn btn-danger']]); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<?php echo $this->Html->link(('過去の工程をコピー'), ['action' => 'index', 'controller' => 'Co051s', $ordertrans['Ordertran']['id'], $ordertrans['Ordertran']['loadingmasters_id'], $ordertrans['Ordertran']['deliverymasters_id']], ['class' => ['btn btn-info']]); ?>
							</div>

						</div>
					</div>
				</div>

				<div class="row">
					<div class="box col-lg-8 col-md-8 col-sm-8 col-xs-8">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>受注内容工程登録</h2>
							</div>

							<?php echo $this->Form->create('Processtran', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

								<br>
								<div class="form-group">
									<label class="col-sm-2 control-label">作業者</label>
									<div class="form-inline">
										<div class="col-sm-4">
											<?php
												echo $this->Form->input('users_id', [
													'label'=>false,
													'type'=>'select',
													'div'=>false,
													'class'=>'form-control',
													'empty'=>[ 0 => '未定'],
													//'id'=>'makerPulldown'
												]);
											?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label">作業名称</label>
									<div class="form-inline">
										<div class="col-sm-4">
											<?php
												echo $this->Form->input('workdetailmasters_id', [
													'label'=>false,
													'type'=>'select',
													'div'=>false,
													'class'=>'form-control',
													'empty'=>'選択してください',
													//'id'=>'makerPulldown'
												]);
											?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label">作業備考</label>
									<div class="col-sm-6">
										<?php
											echo $this->Form->input('workddetailcomment', [
												'label'=>false,
												'type'=>'textarea',
												'rows' => 3,
												'div'=>false,
												'class'=>'form-control',
												'placeholder' => '作業備考を入力'
											]);
										?>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label">開始日時</label>
									<div class="form-inline">
										<div class="col-sm-7">
											<?php
												echo $this->Form->input('processstartdate',[
													'label'=>false,
													'div'=>false,
													'class'=>'datepicker1 form-control',
													'type'=>'text',
												]);
											?>
											<?php
												echo $this->Form->input('processstarttime',[
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'timepicker form-control',
													'style' => 'width:100px',
												]);
											?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label">終了日時</label>
									<div class="form-inline">
										<div class="col-sm-7">
											<?php
												echo $this->Form->input('processenddate',[
													'label'=>false,
													'div'=>false,
													'class'=>'datepicker1 form-control',
													'type'=>'text',
												]);
											?>
											<?php
												echo $this->Form->input('processendtime',[
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'timepicker form-control',
													'style' => 'width:100px',
												]);
											?>
										</div>
									</div>
								</div>

								<hr>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<?php echo $this->Form->submit('登録', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']); ?>
									</div>
								</div>

							<?php echo $this->Form->end(); ?>
						</div>
					</div>
				</div>

				<p>
					<?php //echo $this->Html->link(('新規登録'), array('action' => 'add'), array('class'=>array('btn btn-success'))); ?>
				</p>

			<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<script type="text/javascript">
jQuery(function(){
	jQuery(document).ready(function(){
	    jQuery('input.timepicker').timepicker({
	    	timeFormat: 'HH:mm',
	    	interval: 30,
	    	minTime: '0',
	    	//defaultTime: 'now',
	    	startTime: '0',
	    	dynamic: false,
	    	scrollbar: true
	    });
	})
});
</script>
