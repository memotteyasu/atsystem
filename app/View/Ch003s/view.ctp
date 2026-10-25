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
								<h2><i class="glyphicon glyphicon-info-sign"></i> 受注内容</h2>　
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
												if($processtran['workplaces_id']){
													$workplace = ClassRegistry::init('Workplace');
													$workplaces = $workplace->findById($processtran['workplaces_id']);
													echo $workplaces['Workplace']['workplacename'];
												}
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
	<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="box-inner bg-info">
			<div class="box-header well">
				<h2><i class="glyphicon glyphicon-info-sign"></i>請求内容</h2>
			</div>

			<div class="box-content">
				<table class="table table-striped table-bordered bootstrap-datatable">
					<thead>
						<tr>
							<th>工程</th>
							<th>価格</th>
							<th>備考</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($ordertranpricemasters as $opmaster): ?>
						<tr>
							<td><?php echo $opmaster['Pricemaster']['pricename']; ?></td>
							<?php 
							if($opmaster['Pricemaster']['updateflg']==1){
								echo '<td style="background-color:lightpink;">'.$opmaster['Pricemaster']['price'].'</td>';
							}else{
								echo '<td>'.$opmaster['Pricemaster']['price'].'</td>';
							} ?>
							<td><?php echo $opmaster['Pricemaster']['pricecomment']; ?></td>
						</tr>
					<?php endforeach; ?>
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
											<th class="col-sm-2">登録日</th>
											<th class="col-sm-1"></th>
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
//															$w_area = '/ordertranimages/resize/'.$kakutyoushi[0].'.'.$kakutyoushi[1];
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
															//$w_area = '/ordertranimages/resize/'.$image['id'].'.'.$kakutyoushi[1];
														?>
													</div>
												</td>
												<td>
												<?php echo date('Y-m-d H:i',strtotime($image['created'])) ?>
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
								<?php
									if($ordertran['Ordertran']['printcompflg']==0){
										echo $this->Form->postLink(('印刷済みにする'), [
											'action' => 'printcomp',
											$ordertran['Ordertran']['id']
										], [
											'confirm' => __('印刷済みにしますか？'),
											'class' => 'btn btn-success'
										]);
									}else{
										echo '<span class="text-success">【印刷済み】</span>';
									}
								?>
							</div>

					</div>
				</div>



			<?= $this->Form->create('Ordertranimage', [
						'label' => false,
						'div' => false,
						'class' => 'form-horizontal',
						'enctype' => 'multipart/form-data',
						'novalidate' => true,
					]); ?>
			<div class="row">
				<div class="box col-md-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2><i class="glyphicon glyphicon-info-sign"></i> ImageUpload</h2>
						</div>

						<br>

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
									<span class="align-center text-danger"><small>※ファイルを選択した後、「ファイル登録」ボタンをクリック</small></span>
								</div>
							</div>

					</div>
				</div>
			</div>
			<?php echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.('ファイルを登録します'), [
							'type' => 'submit',
							'div' => false,
							'class' => 'btn btn-info',
							'name' => 'submit',
							'escape' => false,
						]);
			?>


			<div class="row">
				<div class="col-lg-12">
				<div class="box-content">
					<div class="control-group">
							<label class="control-label">備考（メール内容等必要事項を記入してください）</label>
							<div class="controls">
								<?= $this->Form->input('bikocomment', [
									'label' => false,
									'div' => false,
									'type' => 'textarea',
									'class' => 'form-control',
									'placeholder' => '備考',
									'default' => $ordertran['Ordertran']['bikocomment']
								]); ?>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="row">
			<div class="col-lg-6">
			<?php echo $this->Html->link(('編集'), ['controller'=>'Ch003s','action' => 'edit', $ordertran['Ordertran']['id'],0], ['class' => ['btn btn-info']]); ?>

			<?php echo $this->Html->link(('価格変更'), ['controller'=>'Ch003s','action' => 'priceview', $ordertran['Ordertran']['id'],0], ['class' => ['btn btn-primary']]); ?>


			<?php echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.('備考を登録'), [
							'type' => 'submit',
							'div' => false,
							'class' => 'btn btn-success',
							'name' => 'submit',
							'escape' => false,
						]);
			?>
			<?= $this->Html->link(' 戻る',[
				'action' => 'index#'.$ordertran['Carmaster']['plateno'],
				'controller' => 'Ch003s'
			], [
				'escape' => false,
				'class' => 'btn btn-info'
			]); ?>

			</div>
			<div class="col-lg-6 text-right">
			<?php echo $this->Html->link(('キャンセル扱い'), ['controller'=>'Ch003s','action' => 'ordercancel', $ordertran['Ordertran']['id'],0], ['class' => ['btn btn-warning']]); ?>
			<?php echo $this->Html->link(('削除'), ['controller'=>'Ch003s','action' => 'delete', $ordertran['Ordertran']['id'],0], ['class' => ['btn btn-danger']]); ?>
			</div>

			<?= $this->Form->end(); ?>

		</div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<?= $this->Html->script('drag-drop-file-input'); ?>
