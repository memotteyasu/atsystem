<!-- 受注一覧 -->
<!-- Co060s/add -->
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
							$this->Html->addCrumb('受注一覧', ['controller' => 'Co060s', 'action' => 'index']);
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
											<th>商品詳細</th>
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
											<td><?php echo ($ordertrans['Productcontainer']['pdconname']); ?></td>
											<td><?php echo ($ordertrans['Ordertran']['productnote']); ?></td>
											<td><?php echo ($ordertrans['Carmaster']['plateno']); ?></td>
											<td style="color:#ff0000;">
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
					<div class="box col-lg-7 col-md-7 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>登録済価格内容</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>価格名称</th>
											<th>価格</th>
											<th>部署</th>
											<th>荷主</th>
											<th>商品</th>
											<th>商品詳細</th>
											<th>積地</th>
											<th>納入先</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($pricemasters as $pricemaster): ?>
											<tr>
												<?php
													$department = $shipper = $pdname = $pdconname = $loading = $delivery = null;
													if ($pricemaster['Pricemaster']['departmentmasters_id']) {
														$department = $departmentmasters[$pricemaster['Pricemaster']['departmentmasters_id']];
													}
													if ($pricemaster['Pricemaster']['shippermasters_id']) {
														$shipper = $shippermasters[$pricemaster['Pricemaster']['shippermasters_id']];
													}
													if ($pricemaster['Pricemaster']['shippermasters_id']) {
														$shipper = $shippermasters[$pricemaster['Pricemaster']['shippermasters_id']];
													}
													if ($pricemaster['Pricemaster']['productdetailmasters_id']) {
														$pdname = $productdetailmasters[$pricemaster['Pricemaster']['productdetailmasters_id']];
													}
													if ($pricemaster['Pricemaster']['productcontainers_id']) {
														$pdconname = $productcontainers[$pricemaster['Pricemaster']['productcontainers_id']];
													}
													if ($pricemaster['Pricemaster']['loadingmasters_id']) {
														$loading = $loadingmasters[$pricemaster['Pricemaster']['loadingmasters_id']];
													}
													if ($pricemaster['Pricemaster']['deliverymasters_id']) {
														$delivery = $deliverymasters[$pricemaster['Pricemaster']['deliverymasters_id']];
													}
												?>
												<td><?php echo $pricemaster['Pricemaster']['pricename']; ?></td>
												<td><?php echo $pricemaster['Pricemaster']['price']; ?></td>
												<td><?php echo $department; ?></td>
												<td><?php echo $shipper; ?></td>
												<td><?php echo $pdname; ?></td>
												<td><?php echo $pdconname; ?></td>
												<td><?php echo $loading; ?></td>
												<td><?php echo $delivery; ?></td>
												<td>
													<?php echo $this->Html->link(('編集'), ['controller' => 'Co060s', 'action' => 'edit', $pricemaster['OrdertranPricemaster']['id'], $pricemaster['OrdertranPricemaster']['ordertrans_id']], ['class' => ['btn btn-info']]); ?>
													<?php //echo $this->Form->postLink(('削除'), ['action' => 'delete', $pricemaster['OrdertranPricemaster']['id'], $pricemaster['OrdertranPricemaster']['ordertrans_id']], ['confirm' => __('削除しますか？'), 'class'=>['btn btn-danger']]); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<?php //echo $this->Html->link(('過去の工程をコピー'), ['action' => 'index', 'controller' => 'Co051s', $ordertrans['Ordertran']['id'], $ordertrans['Ordertran']['loadingmasters_id'], $ordertrans['Ordertran']['deliverymasters_id']], ['class' => ['btn btn-info']]); ?>
							</div>

						</div>
					</div>
					<div class="box col-lg-5 col-md-5 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>価格内容登録</h2>
							</div>

							<?php echo $this->Form->create('OrdertranPricemaster', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>

								<br>
								<div class="form-group">
									<label class="col-sm-3 control-label">価格内容</label>
									<div class="form-inline">
										<div class="col-sm-4">
											<?php
												echo $this->Form->input('pricemasters_id', [
													'label'=>false,
													'type'=>'select',
													'div'=>false,
													'class'=>'form-control',
													'empty'=>'選択してください',
													'data-rel' => 'chosen',
													'options' => $pricemasters2
												]);
											?>
										</div>
									</div>
								</div>

								<!--
								<div class="form-group">
									<label class="col-sm-3 control-label">価格名称</label>
									<div class="form-inline">
										<div class="col-sm-4">
											<?php
												echo $this->Form->input('pricename', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control',
													'placeholder' => '価格名称を入力',
												]);
											?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">価格</label>
									<div class="form-inline">
										<div class="col-sm-4">
											<?php
												echo $this->Form->input('price', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control',
													'step' => 100,
													'min' => 0,
													'placeholder' => '価格を入力',
												]);
											?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">部署</label>
									<div class="form-inline">
										<div class="col-sm-4">
											<?php
												echo $this->Form->input('departmentmasters_id', [
													'label'=>false,
													'type'=>'select',
													'div'=>false,
													'class'=>'form-control',
													'empty'=>'選択してください',
													'data-rel' => 'chosen',
												]);
											?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">荷主</label>
									<div class="form-inline">
										<div class="col-sm-4">
											<?php
												echo $this->Form->input('shippermasters_id', [
													'label'=>false,
													'type'=>'select',
													'div'=>false,
													'class'=>'form-control',
													'empty'=>'選択してください',
													'data-rel' => 'chosen',
												]);
											?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">商品</label>
									<div class="form-inline">
										<div class="col-sm-4">
											<?php
												echo $this->Form->input('productdetailmasters_id', [
													'label'=>false,
													'type'=>'select',
													'div'=>false,
													'class'=>'form-control',
													'empty'=>'選択してください',
													'data-rel' => 'chosen',
												]);
											?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">商品詳細</label>
									<div class="form-inline">
										<div class="col-sm-4">
											<?php
												echo $this->Form->input('productcontainers_id', [
													'label'=>false,
													'type'=>'select',
													'div'=>false,
													'class'=>'form-control',
													'empty'=>'選択してください',
													'data-rel' => 'chosen',
												]);
											?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">積地</label>
									<div class="form-inline">
										<div class="col-sm-4">
											<?php
												echo $this->Form->input('loadingmasters_id', [
													'label'=>false,
													'type'=>'select',
													'div'=>false,
													'class'=>'form-control',
													'empty'=>'選択してください',
													'data-rel' => 'chosen',
												]);
											?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">納入先</label>
									<div class="form-inline">
										<div class="col-sm-4">
											<?php
												echo $this->Form->input('deliverymasters_id', [
													'label'=>false,
													'type'=>'select',
													'div'=>false,
													'class'=>'form-control',
													'empty'=>'選択してください',
													'data-rel' => 'chosen',
												]);
											?>
										</div>
									</div>
								</div>
								-->

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
