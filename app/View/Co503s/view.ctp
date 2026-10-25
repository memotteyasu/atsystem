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
							$this->Html->addCrumb('受注カレンダー', ['controller' => 'Co003s', 'action' => 'index']);
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
								<h2><i class="glyphicon glyphicon-info-sign"></i> 受注内容</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>荷主</th>
											<th>商品</th>
											<th>商品備考</th>
											<th>車両</th>
											<th>回戦</th>
											<th>課税</th>
											<th>担当者</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><?php echo $ordertran['Shippermaster']['shippername']?></td>
											<td><?php echo $ordertran['Productdetailmaster']['pdname']?></td>
											<td><?php echo $ordertran['Ordertran']['productnote']?></td>
											<td><?php echo $ordertran['Carmaster']['plateno']?></td>
											<td><?php echo $ordertran['Ordertran']['kaisen']?></td>
											<td><?php
											$taxwork = ['0'=>'課税','1'=>'非課税'];
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
											<th>作業名称</th>
											<th>作業場所</th>
											<th>商品詳細</th>
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
											<td><?php echo $processtran['processstartdate']; ?> <?php echo $processtran['processstarttime']; ?></td>
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
												}else{
													//何もしない
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
					<div class="box col-md-12">
							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>ファイル名</th>
											<th class="col-sm-3">コメント</th>
											<th class="col-sm-2"></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($ordertran['Ordertranimage'] as $image): ?>
											<tr>
												<td>
													<div class="col-sm-5">
														<?php
															$kakutyoushi = [];
															$kakutyoushi = explode('.',$image['img_file_name']);
															$w_area = '/ordertranimages/resize/'.$image['id'].'.'.$kakutyoushi[1];
															echo $this->Html->link($image['img_file_name'],$w_area,['class'=>'button','target'=>'_blank']);

/*
															echo $this->Html->link(
																$this->Html->image('/ordertranimages/resize/'.$image['id'].'.jpg', [
																	'alt' => $image['imgcomment'],
																	'class' => 'img-responsive img-thumbnail'
																]),
																'/ordertranimages/original/'.$image['id'].'.jpg',
																['escape' => false, 'target'=>'_blank']
															);
*/
														?>
													</div>
												</td>
												<td>
													<?php echo ($image['imgcomment']); ?>
												</td>
												<td>
													<?php
														echo $this->Form->postLink(('削除'), [
															'action' => 'imagedelete',
															$image['id'],
															$image['ordertrans_id']
														], [
															'confirm' => __('削除しますか？'),
															'class' => 'btn btn-danger'
														]);
													?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>

					</div>
				</div>

<!--

			<div class="row">
				<div class="box col-md-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2><i class="glyphicon glyphicon-info-sign"></i> ImageUpload</h2>
						</div>

						<br>
						<?= $this->Form->create('Ordertranimage', [
							'label' => false,
							'div' => false,
							'class' => 'form-horizontal',
							'enctype' => 'multipart/form-data',
							'novalidate' => true,
							'onsubmit' => 'return confirm("画像をアップロードします。よろしいですか？");'
						]); ?>

							<div class="box-content">
								<div id="dragDropArea">
									<div class="drag-drop-inside">
										<i class="glyphicon glyphicon-upload icon-white"></i>
										<p class="drgg-drop-info">ここにファイルをドロップ</p>
										<p>または</p>
										<p class="drag-drop-buttons">
											<?= $this->Form->input('img', [
												'label' => false,
												'div' => false,
												'class' => false,
												'type' => 'file',
												'onChange' => 'photoPreview(event)'
											]); ?>
										</p>
										<div id="previewArea"></div>
									</div>
								</div>
							</div>

							<div class="box-content">
								<div class="control-group">
									<label class="control-label">画像備考</label>
									<div class="controls">
										<?= $this->Form->input('imgcomment', [
											'label' => false,
											'div' => false,
											'class' => 'form-control',
											'placeholder' => '備考'
										]); ?>
									</div>
								</div>
							</div>

							<hr>
							<div class="box-content">
								<?= $this->Form->submit('画像登録', [
									'div' => false,
									'class' => 'btn btn-warning',
									'name' => 'submit'
								]); ?>
							</div>

						<?= $this->Form->end(); ?>
					</div>
				</div>
			</div>
							-->
			<?php //echo $this->Html->link(('編集'), ['controller'=>'Co003s','action' => 'edit', $ordertran['Ordertran']['id'],0], ['class' => ['btn btn-info']]); ?>

			<?= $this->Html->link('<span class="glyphicon glyphicon-backward icon-white" aria-hidden="true"></span>'.(' 戻る'),[
				'action' => 'index',
				'controller' => 'Co503s'
			], [
				'escape' => false,
				'class' => 'btn btn-success'
			]); ?>

		</div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<?= $this->Html->script('drag-drop-file-input'); ?>
