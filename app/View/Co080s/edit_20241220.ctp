<!-- 受注一覧 -->
<!-- Co080s/edit -->
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
							$this->Html->addCrumb('出荷実績', ['controller' => 'Co080s', 'action' => 'index']);
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
											<th>回戦</th>
											<th>担当</th>
											<th>税</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><?php echo ($ordertrans['Shippermaster']['shippershortname']); ?></td>
											<td><?php echo ($ordertrans['Carmaster']['plateno']); ?></td>
											<td><?php echo ($ordertrans['Ordertran']['kaisen']); ?></td>
											<td><?php 
											if($tanto){
											echo ($tanto['Tantomaster']['tantoname']);
											} ?></td>
											<td>
											<?php if($ordertrans['Ordertran']['taxflg']==1){ 
												echo "免税";
											}else{
												echo "";
											} ?></td>
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
								<h2><i class="glyphicon glyphicon-info-sign"></i>工程内容</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>場所</th>
											<th>作業名称</th>
											<th>ｺﾝﾃﾅNo.</th>
											<th>REFNo.</th>
											<th>商品</th>
											<th>作業日時</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($processtrans as $processtran): ?>
											<tr>
												<td><?php echo ($processtran['Workplace']['workplacename']); ?></td>
												<td class="text-danger"><?php echo ($processtran['Workdetailmaster']['workname']); ?></td>
												<td><?php echo ($processtran['Processtran']['container_no']); ?></td>
												<td><?php echo ($processtran['Processtran']['ref_no']); ?></td>
												<td>
													<?php echo ($processtran['Productcontainer']['pdconname']); ?></td>
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
				</div>

				<div class="row" id="confirmedarea">
					<div class="box col-lg-7 col-md-7 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>同工程価格マスタ</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>工程</th>
											<th>価格</th>
											<th>商品</th>
											<th>備考</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($pricemasters2 as $pricemaster2): ?>
											<?php echo $this->Form->create('OrdertranPricemaster', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>
											<tr>
												<td><?php echo $pricemaster2['Pricemaster']['pricename']; ?></td>
												<td><?php echo $pricemaster2['Pricemaster']['price']; ?></td>
												<td><?php echo $ordertrans['Productcontainer']['pdconname']; ?></td>
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
											<?php echo $this->Form->end(); ?>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>

						</div>
					</div>
					<!-- 元価格マスタ -->
					<div class="box col-lg-5 col-md-5 col-sm-12 col-xs-12">
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
								<h2><i class="glyphicon glyphicon-info-sign"></i>諸費登録</h2>
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
					<!-- /元価格マスタ -->
				</div>

				<div class="row">
					<div class="box col-lg-7 col-md-7 col-sm-12 col-xs-12">
						<div class="box-inner bg-info">
						<?php echo $this->Form->create(); ?>
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>請求内容</h2><small class="text-danger">　※締日</small>
								<?php
									if(isset($ordertrans['Ordertran']['billconfirmdate'])){
										$billconfirmdateymd = new DateTime($ordertrans['Ordertran']['billconfirmdate']);
										echo $this->Form->input('billconfirmdate',[
											'label'=>false,
											'div'=>false,
											'class'=>'datepicker1',
											'tabindex'=>-1,
											'type'=>'text',
											'default' => $billconfirmdateymd->format('Y-m-d'),
										]);
									}else{
										echo $this->Form->input('billconfirmdate',[
											'label'=>false,
											'div'=>false,
											'class'=>'datepicker1',
											'tabindex'=>-1,
											'type'=>'text',
											'default'=>$lastday
										]);
									}
								?><small class="text-danger">　※請求日</small>
								<?php
									if(isset($ordertrans['Ordertran']['detailbilldate'])){
										$detailbilldate = new DateTime($ordertrans['Ordertran']['detailbilldate']);
										echo $this->Form->input('detailbilldate',[
											'label'=>false,
											'div'=>false,
											'class'=>'datepicker1',
											'tabindex'=>-1,
											'type'=>'text',
											'default' => $detailbilldate->format('Y-m-d'),
										]);
									}else{
										echo $this->Form->input('detailbilldate',[
											'label'=>false,
											'div'=>false,
											'class'=>'datepicker1',
											'tabindex'=>-1,
											'type'=>'text',
											'default'=>$ordertrans['Ordertran']['loadingdate']
										]);
									}
								?>
								<?php
									if($ordertrans['Ordertran']['billconfirmflg']==1){
										echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.('確定取消'), [
											'type' => 'submit',
											'div' => false,
											'class' => 'btn btn-danger',
											'name' => 'orderpayout',
											'escape' => false,
										]);
									}else{
										echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.('確定'), [
											'type' => 'submit',
											'div' => false,
											'class' => 'btn btn-success',
											'name' => 'orderpay',
											'escape' => false,
										]);
									}
								?>
							</div>
						<?php echo $this->Form->end(); ?>
							<div class="box-content">
								<table class="table  table-bordered" style="background-color:white;">
									<thead>
										<tr>
											<th>名称</th>
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
										<?php foreach($orderepensestrans as $oet): ?>
										<tr>
											<td><?php echo $oet['Orderexpensestran']['expensesname'] ?></td>
											<td><?php echo $oet['Orderexpensestran']['expensesprice'] ?></td>
											<td></td>
											<td><?php echo $this->Form->postLink(('削除'), array('action' => 'expensesdelete',$oet['Orderexpensestran']['ordertrans_id'],$oet['Orderexpensestran']['id']), array('confirm' => __('削除しますか？'), 'class'=>array('btn btn-danger'))); ?></td>
										</tr>
										<?php endforeach ?>
									</tbody>
								</table>
								<!--高速代処理 -->
								<?php echo $this->Form->create('Ordertrankousokudai', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>
								<table class="table  table-bordered" style="background-color:white;">
								<tbody>
									<tr>
										<td>高速代(税込)</td>
										<td>
										<div class="controls">
											<?php
												echo $this->Form->input('kousokuprice', [
													'label'=>false,
													'div'=>false,
													'type'=>'number',
													'class'=>'input-control',
													'default' => $ordertrankousokudais['Ordertrankousokudai']['kousokuprice']
												]);
											?>円　
											<?php 
												echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.('高速代登録'), [
													'type' => 'submit',
													'div' => false,
													'class' => 'btn btn-info',
													'name' => 'kousokudaipay',
													'escape' => false,
												]);
												?>
										</div>
										</td>
									</tr>
								</tbody>
								</table>
								<?php echo $this->Form->end(); ?>

							</div>

						</div>
					</div>
					<!-- 諸費選択 -->
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
					<!-- /諸費選択 -->
				</div>

			<!-- content ends -->
			<?= $this->Html->link('<span class="glyphicon glyphicon-backward icon-white" aria-hidden="true"></span>'.(' 戻る'),[
				'action' => 'index',
				'controller' => 'Co080s'
			], [
				'escape' => false,
				'class' => 'btn btn-danger'
			]); ?>
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
<script type="text/javascript">
	$(".chosen").chosen({
		search_contains:true
	});
</script>

