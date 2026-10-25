<!-- 受注一覧 -->
<!-- Ch050s/add -->
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
							$this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
							$this->Html->addCrumb('承認一覧', ['controller' => 'Ch050s', 'action' => 'index']);
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
											<th>車両</th>
											<th>開始日時</th>
											<th>開始</th>
											<th>終了日時</th>
											<th>終了</th>
											<th>回戦</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><?php echo ($ordertrans['Shippermaster']['shippershortname']); ?></td>
											<td><?php echo ($ordertrans['Carmaster']['plateno']); ?> ( <?php echo $ordertrans['Productdetailmaster']['pdname']; ?> )</td>
											<td style="color:#ff0000;">
												<?php
													$loadingdate = new DateTime($ordertrans['Ordertran']['loadingdate'].$ordertrans['Ordertran']['loadingtime']);
													echo $loadingdate->format('Y-m-d H:i');
												?>
											</td>
											<td><?php echo ($ordertrans['Workplace1']['workplaceshortname']); ?></td>
											<td style="color:#ff0000;">
												<?php
													$deliverydate = new DateTime($ordertrans['Ordertran']['deliverydate'].$ordertrans['Ordertran']['deliverytime']);
													echo $deliverydate->format('Y-m-d H:i');
												?>
											</td>
											<td><?php echo ($ordertrans['Workplace2']['workplaceshortname']); ?></td>
											<td><?php echo ($ordertrans['Ordertran']['kaisen']); ?></td>
											<td>
												<?php echo $this->Html->link(('編集'), ['controller'=>'Ch003s','action' => 'edit', $ordertrans['Ordertran']['id'], 5], ['class' => ['btn btn-info']]); ?>
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
					<div class="box col-lg-7 col-md-7 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>登録済工程内容</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>乗務員・助手</th>
											<th>作業名称</th>
											<th>作業場所</th>
											<th>作業日時</th>
											<th>作業備考</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($processtrans as $processtran): ?>
											<tr>
												<td><?php echo ($processtran['PDriver1']['usernameK'].'・'.$processtran['PDriver2']['usernameK']); ?></td>
												<td><?php echo ($processtran['Workdetailmaster']['workname']); ?></td>
												<td><?php echo ($processtran['Workplace']['workplacename']); ?></td>
												<td>
													<?php
														if($processtran['Processtran']['processstarttime']){
															$processstart = new DateTime($processtran['Processtran']['processstartdate'].$processtran['Processtran']['processstarttime']);
															echo $processstart->format('Y-m-d H:i');
														}else{
															$processstart = new DateTime($processtran['Processtran']['processstartdate'].$processtran['Processtran']['processstarttime']);
															echo $processstart->format('Y-m-d');
														}
														//timenoteも表示
														if($processtran['Processtran']['timenote']){
															echo '(';
															echo $processtran['Processtran']['timenote'];
															echo ')';
														}
														
													?>
												</td>
												<td><?php echo ($processtran['Processtran']['workddetailcomment']); ?></td>
												<td>
													<?php echo $this->Html->link(('編集'), ['action' => 'edit', $processtran['Processtran']['id'], $processtran['Processtran']['ordertrans_id']], ['class' => ['btn btn-info']]); ?>
													<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $processtran['Processtran']['id'], $processtran['Processtran']['ordertrans_id']], ['confirm' => __('削除しますか？'), 'class'=>['btn btn-danger']]); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<?php // echo $this->Html->link(('過去の工程をコピー'), ['action' => 'index', 'controller' => 'Ch051s', $ordertrans['Ordertran']['id'], $ordertrans['Ordertran']['loadingmasters_id'], $ordertrans['Ordertran']['deliverymasters_id']], ['class' => ['btn btn-info']]); ?>
								更新者：<?php echo $modifiedusername; ?>　<?php echo $this->Html->link(('承認する'), ['controller' => 'Ch050s','action' => 'approve1', $ordertrans['Ordertran']['id'], $ordertrans['Ordertran']['loadingmasters_id'], $ordertrans['Ordertran']['deliverymasters_id']], ['class' => ['btn btn-info']]); ?>
								<?php echo $check1username; ?>
							</div>

						</div>
					</div>
					<div class="box col-lg-5 col-md-5 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>受注内容工程登録</h2>
							</div>

							<?php echo $this->Form->create('Processtran', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

								<br>
								<div class="form-group">
									<label class="col-sm-3 control-label">乗務員</label>
									<div class="form-inline">
										<div class="col-sm-4">
											<?php
												echo $this->Form->input('pdriver1id', [
													'label'=>false,
													'type'=>'select',
													'options'=>$pdriver1,
													'div'=>false,
													'class'=>'form-control chosen',
													'empty'=>[ 0 => '未定'],
												]);
											?>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">助手</label>
									<div class="form-inline">
										<div class="col-sm-4">
											<?php
												echo $this->Form->input('pdriver2id', [
													'label'=>false,
													'type'=>'select',
													'options'=>$pdriver2,
													'div'=>false,
													'class'=>'form-control chosen',
													'empty'=>[ 0 => '未定'],
												]);
											?>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">作業名称</label>
									<div class="form-inline">
										<div class="col-sm-4">
											<?php
												echo $this->Form->input('workdetailmasters_id', [
													'label'=>false,
													'type'=>'select',
													'div'=>false,
													'class'=>'form-control chosen',
													'empty'=>'選択してください',
												]);
											?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">作業場所</label>
									<div class="form-inline">
										<div class="col-sm-4">
											<?php
												echo $this->Form->input('workplaces_id', [
													'label'=>false,
													'type'=>'select',
													'div'=>false,
													'class'=>'form-control chosen',
													'empty'=>'選択してください',
												]);
											?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">作業日時</label>
									<div class="form-inline">
										<div class="col-sm-7">
											<?php
												echo $this->Form->input('processstartdate',[
													'label'=>false,
													'div'=>false,
													'class'=>'datepicker1 form-control',
													'type'=>'text',
													'default' => date('Y-m-d'),
												]);
											?>
											<?php
												echo $this->Form->input('processstarttime',[
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'timepicker2 form-control',
													'style' => 'width:100px',
													'default' => date('H:i'),
												]);
											?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">作業備考</label>
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

								<hr>
								<div class="form-group">
									<div class="col-sm-offset-3 col-sm-9">
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
	$(".chosen").chosen({
		search_contains:true
	});
</script>
