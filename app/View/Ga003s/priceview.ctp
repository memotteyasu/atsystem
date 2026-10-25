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

							$this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
							$this->Html->addCrumb('受注カレンダー', ['controller' => 'Ga003s', 'action' => 'index']);
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
											<th>税</th>
											<th></th>
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
											<td><?php 
											if($opmaster['OrdertranPricemaster']['keihitaxflg']==1){
												echo "免税";
											}
											 ?></td>
											<td><?php echo $this->Form->postLink(('削除'), array('action' => 'pricedelete',$opmaster['OrdertranPricemaster']['ordertrans_id'],$opmaster['OrdertranPricemaster']['id']), array('confirm' => __('削除しますか？'), 'class'=>array('btn btn-danger'))); ?></td>
										</tr>
									<?php endforeach; ?>
									<?php foreach($orderepensestrans as $oet): ?>
										<tr>
											<td><?php echo $oet['Orderexpensestran']['expensesname'] ?></td>
											<td><?php echo $oet['Orderexpensestran']['expensesprice'] ?></td>
											<td></td>
											<td></td>
											<td><?php echo $this->Form->postLink(('削除'), array('action' => 'expensesdelete',$oet['Orderexpensestran']['ordertrans_id'],$oet['Orderexpensestran']['id']), array('confirm' => __('削除しますか？'), 'class'=>array('btn btn-danger'))); ?></td>
										</tr>
									<?php endforeach ?>
									</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="box-header well">
								<h2 class="text-danger"><i class="glyphicon glyphicon-info-sign"></i>同工程価格マスタ一覧</h2>
							</div>

							<div class="box-content">
								<table class="table">
									<tbody>
									<?php foreach ($pricemasters2 as $pmaster): ?>
										<tr>
											<td><?php echo $pmaster['Pricemaster']['pricename']; ?></td>
											<?php 
											if($pmaster['Pricemaster']['updateflg']==1){
												echo '<td style="background-color:lightpink;">'.$pmaster['Pricemaster']['price'].'</td>';
											}else{
												echo '<td>'.$pmaster['Pricemaster']['price'].'</td>';
											} ?>
											<td><?php // echo $ordertran['Productcontainer']['pdconname']; ?></td>
											<td><?php echo $pmaster['Pricemaster']['pricecomment']; ?></td>
											<td><?php echo $this->Form->postLink(('選択'), array('action' => 'priceview',$ordertran['Ordertran']['id'],$pmaster['Pricemaster']['id']), array('class'=>array('btn btn-warning'))); ?></td>
										</tr>
									<?php endforeach; ?>
									</tbody>
								</table>
							</div>
					</div>
				</div>
				<div class="row">
					<div class="box col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2 class="text-danger"><i class="glyphicon glyphicon-info-sign"></i>価格マスタ登録</h2>
							</div>
							<?php echo $this->Form->create('Pricemaster', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>

							<!-- 価格内容 -->
							<!-- /価格内容 -->
							<div class="box-content">
							<small class="text-danger">※上記に無い場合は、価格を登録してください</small>
								<div class="control-group">
									<div class="controls">価格
										<?php
											echo $this->Form->input('price', [
												'label'=>false,
												'div'=>false,
												'type'=>'number',
												'class'=>'form-control',
											]);
										?>
									</div>
								</div>
								<div class="control-group">
									<div class="controls">備考
										<?php
											echo $this->Form->input('pricecomment', [
												'label'=>false,
												'div'=>false,
												'type'=>'text',
												'class'=>'form-control',
											]);
										?>
									</div>
								</div>
								<?php
									echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' 価格登録及び請求登録'), [
										'type' => 'submit',
										'div' => false,
										'class' => 'btn btn-info',
										'name' => 'price',
										'escape' => false,
									]);
								?>
							</div>
						<?php echo $this->Form->end(); ?>
						</div>
					</div>
					<div class="box col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>諸費選択</h2>
							</div>
							<?php echo $this->Form->create('OrdertranPricemaster', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>

							<!-- 価格内容 -->
								<div class="box-content">
									<div class="control-group">
										<label class="control-label text-danger" for="selectError">諸費内容</label>
										<?php
											echo $this->Form->input('pricemasters_id', [
												'label'=>false,
												'type'=>'select',
												'div'=>false,
												'class'=>'form-control chosen',
												'empty'=>'選択してください',
												'options' => $pricemasters
											]);
										?>
									</div>
									<div class="control-group">
									<?php
										echo $this->Form->input('keihitaxflg', [
											'label'=>false,
											'type'=>'checkbox',
											'div'=>false,
											'class'=>'input-control',
										]);
										?>免税対象
									</div>
								</div>
							<!-- /価格内容 -->
							<div class="box-content">
								<?php
									echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' 請求内容に追加'), [
										'type' => 'submit',
										'div' => false,
										'class' => 'btn btn-info',
										'name' => 'keihi',
										'escape' => false,
									]);
								?>
							</div>
							<?php echo $this->Form->end(); ?>
						</div>
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>諸費登録</h2><small class="text-danger">　※諸費マスタには反映しません</small>
							</div>
							<?php echo $this->Form->create('Orderexpensestran', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>

							<!-- 諸費追加内容 -->
								<div class="box-content">
									<div class="control-group">
										<div class="controls">内容
											<?php
												echo $this->Form->input('expensesname', [
													'label'=>false,
													'div'=>false,
													'type'=>'text',
													'class'=>'form-control',
												]);
											?>
										</div>
									</div>
								</div>
								<div class="box-content">
									<div class="control-group">
										<div class="controls">価格
											<?php
												echo $this->Form->input('expensesprice', [
													'label'=>false,
													'div'=>false,
													'type'=>'number',
													'class'=>'form-control',
												]);
											?>
										</div>
									</div>
								</div>
							<!-- /諸費追加内容 -->
							<div class="box-content">
								<?php
									echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.('諸費を登録'), [
										'type' => 'submit',
										'div' => false,
										'class' => 'btn btn-info',
										'name' => 'shohi',
										'escape' => false,
									]);
								?>
							</div>
							<?php echo $this->Form->end(); ?>
						</div>
					</div>

				</div>
					<?php echo $this->Html->link(('編集'), ['controller'=>'Ga003s','action' => 'edit', $ordertran['Ordertran']['id'],0], ['class' => ['btn btn-info']]); ?>
					<?php echo $this->Html->link(('添付ファイル'), ['controller'=>'Ga003s','action' => 'view', $ordertran['Ordertran']['id'],0], ['class' => ['btn btn-warning']]); ?>

					<?= $this->Html->link('<span class="glyphicon glyphicon-backward icon-white" aria-hidden="true"></span>'.(' 登録'),[
						'action' => 'index#'.$ordertran['Carmaster']['plateno'],
						'controller' => 'Ga003s'
					], [
						'escape' => false,
						'class' => 'btn btn-success'
					]); ?>

		</div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
<script type="text/javascript">
	//jQuery.noConflict();
	$(".chosen").chosen({
		search_contains:true
	});
</script>