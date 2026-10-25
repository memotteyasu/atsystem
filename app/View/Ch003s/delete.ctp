<style>
	#dragDropArea {
		background-color: #f4f4f4;
		margin: 10px;
		padding: 10px;
		border: 1px dotted rgba(0, 0, 0, .4);
		min-height: 200px;
		text-align: center;
	}
	#dragDropArea i {
		font-size: 120px;
		opacity: .1;
		text-align: center;
	}
	#dragDropArea p {
		color: #999;
		font-weight: bold;
		font-size: 14px;
		font-size: 1.4em;
	}
	#dragDropArea .drag-drop-buttons {
		margin-top: 20px;
		font-size: 12px;
		font-size: 1.2em;
	}
	.drag-drop-buttons input {
		margin: auto;
	}
	#previewArea #previewImage {
			max-width: 450px;
	}
/* .ui-timepicker-container {z-index: 9999 !important;}*/
/* .ui-timepicker-standard {z-index: 9999 !important;}*/
</style>

<div class="ch-container">
	<div class="row">

		<!-- left menu starts -->
		<?php echo $this->element('Sidemenu'); ?>
		<!-- left menu ends -->

		<div id="content" class="col-lg-10 col-md-10 col-sm-10">
			<!-- content starts -->
			 <div>
				<ul class="breadcrumb">
					<li>
						<?php
							$id = $this->request->pass[0];
//							$flg = $this->request->pass[1];

							$this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
							$this->Html->addCrumb('受注カレンダー', ['controller' => 'Ch003s', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>


				<div class="row">

					<div class="box col-md-12">
						<div class="box-inner">
				            <div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i> 受注内容<span class="text-danger">（削除処理）</span></h2>　
								<!-- <button name="a" value="" class="btn btn-danger">車両を未確定(999)にする</button> -->
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>荷主</th>
											<th>車両</th>
											<th>回戦</th>
											<th>課税</th>
											<th>担当者</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><?php echo $ordertran['Shippermaster']['shippername']?></td>
											<td><?php echo $ordertran['Carmaster']['plateno']?></td>
											<td><?php echo $ordertran['Ordertran']['kaisen']?></td>
											<td><?php
											$taxwork = ['0'=>'課税','1'=>'免税・非課税'];
											echo $taxwork[$ordertran['Ordertran']['taxflg']];
											?></td>
											<td><?php echo $ordertran['Tantomaster']['tantoname']; ?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="row">

					<div class="box col-md-12">
						<div class="box-inner">

							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i> 工程登録</h2>
							</div>

							<div class="box-content">

								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>作業日時</th>
											<th></th>
											<th>作業名称</th>
											<th>作業場所</th>
											<th>商品</th>
											<th>コンテナNo.</th>
											<th>REF/Booking</th>
											<th>作業備考</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$i = 0;
										foreach ($ordertran['Processtran'] as $processtran):
									?>
										<tr>
											<td><?php echo $processtran['processstartdate']; ?></td>
											<td><?php echo substr($processtran['processstarttime'],0,5); ?>
											<?php 
											if($processtran['timenote']){
												echo $processtran['timenote']; 
											}
											?></td>
											<td>
												<?php
												$workdetailmaster = ClassRegistry::init('Workdetailmaster');
												$workdetailmasters = $workdetailmaster->findById($processtran['workdetailmasters_id']);
												echo $workdetailmasters['Workdetailmaster']['workname'];
												?>
											</td>
											<td>
												<?php
												$workplace = ClassRegistry::init('Workplace');
												$workplaces = $workplace->findById($processtran['workplaces_id']);
												echo $workplaces['Workplace']['workplacename'];
												?>
											</td>
											<td>
												<?php
												if($processtran['productcontainers_id']){
													$productcontainer = ClassRegistry::init('Productcontainer');
													$productcontainers = $productcontainer->findById($processtran['productcontainers_id']);
													echo $productcontainers['Productcontainer']['pdconname'];
												}
												?>
											</td>
											<td><?php echo $processtran['container_no']; ?></td>
											<td><?php echo $processtran['ref_no']; ?></td>
											<td><?php echo $processtran['workddetailcomment']; ?></td>
										</tr>
										<?php
											$i = $i + 1;
											endforeach;
										?>
									</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>


			<div class="row">
			<div class="col-lg-6">
			<?php
					echo $this->Form->postLink('<span class="glyphicon glyphicon-trash icon-white" aria-hidden="true"></span>'.('このオーダーを削除'), [
					'action' => 'delete',
					$ordertran['Ordertran']['id'],
					1
				], [
					'escape' => false, 'class' => 'btn btn-danger', 'role' => 'button',
				]);
			?>
			</div>

			<?= $this->Form->end(); ?>

		</div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<?= $this->Html->script('drag-drop-file-input'); ?>
