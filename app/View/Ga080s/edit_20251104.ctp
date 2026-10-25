<!-- 受注一覧 -->
<!-- Ga080s/edit -->
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
							$this->Html->addCrumb('出荷実績', ['controller' => 'Ga080s', 'action' => 'index']);
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
								<h2><i class="glyphicon glyphicon-info-sign"></i>出荷実績</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>荷主</th>
											<th>車両</th>
											<th>開始日時</th>
											<th>終了日時</th>
											<th>回戦</th>
											<th>開始</th>
											<th>終了</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><?php echo ($ordertrans['Shippermaster']['shippershortname']); ?></td>
											<td><?php echo ($ordertrans['Carmaster']['plateno']); ?></td>
											<td style="color:#ff0000;">
												<?php
													$loadingdate = new DateTime($ordertrans['Ordertran']['loadingdate'].$ordertrans['Ordertran']['loadingtime']);
													echo $loadingdate->format('Y-m-d H:i');
												?>
											</td>
											<td style="color:#ff0000;">
												<?php
													$deliverydate = new DateTime($ordertrans['Ordertran']['deliverydate'].$ordertrans['Ordertran']['deliverytime']);
													echo $deliverydate->format('Y-m-d H:i');
												?>
											</td>
											<td><?php echo ($ordertrans['Ordertran']['kaisen']); ?></td>
											<td><?php echo ($ordertrans['Workplace1']['workplaceshortname']); ?></td>
											<td><?php echo ($ordertrans['Workplace2']['workplaceshortname']); ?></td>
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
								<h2><i class="glyphicon glyphicon-info-sign"></i>工程内容</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>運転手・助手</th>
											<th>場所</th>
											<th>作業名称</th>
											<th>作業備考</th>
											<th>商品詳細</th>
											<th>作業日時</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($processtrans as $processtran): ?>
											<tr>
												<td><?php echo ($processtran['PDriver1']['usernameK'].'・'.$processtran['PDriver2']['usernameK']); ?></td>
												<td><?php echo ($processtran['Workplace']['workplacename']); ?></td>
												<td class="text-danger"><?php echo ($processtran['Workdetailmaster']['workname']); ?></td>
												<td><?php echo ($processtran['Processtran']['workddetailcomment']); ?></td>
												<td><?php echo ($processtran['Productcontainer']['pdconname']); ?></td>
												<td>
													<?php
														$processstart = new DateTime($processtran['Processtran']['processstartdate'].$processtran['Processtran']['processstarttime']);
														if($processstart->format('H:i')=='00:00'){
															echo $processstart->format('Y-m-d');
														}else{
															echo $processstart->format('Y-m-d H:i');
														}
													?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>

						</div>
					</div>
					<div class="box col-lg-5 col-md-5 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>出荷実績登録</h2>
							</div>
							<?php echo $this->Form->create('Ordertran', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

							<!-- 納入実績日時 -->
								<div class="box-content">
									<div class="control-group">
										<label class="control-label text-danger" for="selectError">納入実績日時</label>
										<div class="form-inline">
											<?php
												if($ordertrans['Ordertran']['correctdate'] == null){
													$correctdate = new DateTime($ordertrans['Ordertran']['deliverydate']);
												}else{
													$correctdate = new DateTime($ordertrans['Ordertran']['correctdate']);
												}
												echo $this->Form->input('cdate',[
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'datepicker1 form-control',
													'style' => 'float:left',
													'default' => $correctdate->format('Y-m-d'),
												]);

												echo $this->Form->input('ctime',[
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'timepicker form-control',
													'style' => 'width:100px',
													'default' => $correctdate->format('H:i'),
												]);

											?>
										</div>
									</div>
								</div>
							<!-- /納入実績日時 -->
							<!-- 出荷数量 -->
								<div class="box-content">
									<div class="control-group">
										<label class="control-label text-danger" for="selectError">納入数量</label>
											<div class="controls">
												<?php
													echo $this->Form->input('correctamount', [
														'label'=>false,
														'div'=>false,
														'class'=>'form-control',
														'style'=>'width:100px;',
														'default' => $ordertrans['Ordertran']['correctamount'],
													]);
												?>
										</div>
									</div>
								</div>
							<!-- /出荷数量 -->

							<div class="box-content">
								<?php
									echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' 実績登録'), [
										'type' => 'submit',
										'div' => false,
										'class' => 'btn btn-info',
										'name' => 'order',
										'escape' => false,
									]);
								?>
							</div>
						<?php echo $this->Form->end(); ?>
						</div>
					</div>
				</div>

<div class="row">
	<div class="box col-lg-7 col-md-7 col-sm-12 col-xs-12">
		<div class="box-inner">
			<div class="box-header well">
				<h2><i class="glyphicon glyphicon-info-sign"></i>価格マスタ</h2>
			</div>

			<div class="box-content">
				<table class="table table-striped table-bordered bootstrap-datatable">
					<thead>
						<tr>
							<th>名称</th>
							<th>価格</th>
							<th>営業所</th>
							<th>備考</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php echo $this->Form->create('OrdertranPricemaster', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>
						<?php foreach ($pricemasters2 as $pricemaster2): ?>
							<tr>
								<td><?php echo $pricemaster2['Pricemaster']['pricename']; ?></td>
								<td><?php echo $pricemaster2['Pricemaster']['price']; ?></td>
								<td><?php echo $pricemaster2['Branchmaster']['branchname']; ?></td>
								<td><?php echo $pricemaster2['Pricemaster']['pricecomment']; ?></td>
								<td>
								<?php echo $this->Form->input('pricemasters_id',[
									'type'=>'hidden',
									'value'=>$pricemaster2['Pricemaster']['id'],
								]); ?>
								<?php echo $this->Form->input('ordertrans_id',[
									'type'=>'hidden',
									'value'=>$ordertrans['Ordertran']['id'],
								]); ?>
								<?php
									echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.('請求登録'), [
										'type' => 'submit',
										'div' => false,
										'class' => 'btn btn-info',
										'name' => 'orderprice',
										'escape' => false,
									]);
								?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php echo $this->Form->end(); ?>
					</tbody>
				</table>
			</div>

		</div>
	</div>
	<div class="box col-lg-5 col-md-5 col-sm-12 col-xs-12">
		<div class="box-inner">
			<div class="box-header well">
				<h2><i class="glyphicon glyphicon-info-sign"></i>価格マスタ登録</h2>
			</div>
			<?php echo $this->Form->create('Pricemaster', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>

			<!-- 価格内容 -->
			<!-- /価格内容 -->
			<div class="box-content">
			<small class="text-danger">※左記に無い場合は、価格を登録してください</small>
				<div class="control-group">
					<div class="controls">名称
						<?php
							echo $this->Form->input('pricename', [
								'label'=>false,
								'div'=>false,
								'type'=>'text',
								'class'=>'form-control',
							]);
						?>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">価格
						<?php
							echo $this->Form->input('price', [
								'label'=>false,
								'div'=>false,
								'type'=>'text',
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
</div>

				<div class="row">
					<div class="box col-lg-7 col-md-7 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>請求内容</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>価格名称</th>
											<th>価格</th>
											<th>備考</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($orderpricemasters as $orderpricemaster): ?>
											<tr>
												<td><?php echo $orderpricemaster['Pricemaster']['pricename']; ?></td>
												<td><?php echo $orderpricemaster['Pricemaster']['price']; ?></td>
												<td><?php echo $orderpricemaster['Pricemaster']['pricecomment']; ?></td>
												<td>
													<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $orderpricemaster['OrdertranPricemaster']['id'], $orderpricemaster['OrdertranPricemaster']['ordertrans_id'], $orderpricemaster['OrdertranPricemaster']['pricemasters_id']], ['confirm' => __('削除しますか？'), 'class'=>['btn btn-danger']]); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>

						</div>
					</div>
					<div class="box col-lg-5 col-md-5 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>諸費登録</h2>
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
					</div>
				</div>

			<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
<script type="text/javascript">
	$(".chosen").chosen({
		search_contains:true
	});
</script>
