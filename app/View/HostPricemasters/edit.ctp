<?php
	$selectid1 = '#PricemasterDepartmentmastersId';
	$selectid2 = '#PricemasterShippermastersId';
	$selectid3 = '#PricemasterProductdetailmastersId';
	$selectid4 = '#PricemasterProductcontainersId';
	$selectid5 = '#PricemasterLoadingmastersId';
	$selectid6 = '#PricemasterDeliverymastersId';
	$action1 = 'ajax_shipper';
	$action2 = 'ajax_productdetail';
	$action3 = 'ajax_productcontainer';
	$action4 = 'ajax_loading';
	$action5 = 'ajax_delivery';
	$model = 'Pricemaster';

	$this->Js->get($selectid1)->event(
		'change',
		$this->Js->request(
			['controller' => 'AjaxsSelects', 'action' => $action1, $model],
			[
				'update' => $selectid2,
				'dataExpression' => true,
				'data' => '$("'.$selectid1.'").serialize()',
				'complete' => '$("'.$selectid2.'").change();',
			]
		)
	);

	$this->Js->get($selectid1)->event(
		'change',
		$this->Js->request(
			['controller' => 'AjaxsSelects', 'action' => $action4, $model],
			[
				'update' => $selectid5,
				'dataExpression' => true,
				'data' => '$("'.$selectid1.'").serialize()',
			]
		)
	);

	$this->Js->get($selectid1)->event(
		'change',
		$this->Js->request(
			['controller' => 'AjaxsSelects', 'action' => $action5, $model],
			[
				'update' => $selectid6,
				'dataExpression' => true,
				'data' => '$("'.$selectid1.'").serialize()',
			]
		)
	);

	$this->Js->get($selectid2)->event(
		'change',
		$this->Js->request(
			['controller' => 'AjaxsSelects', 'action' => $action2, $model],
			[
				'update' => $selectid3,
				'dataExpression' => true,
				'data' => '$("'.$selectid2.'").serialize()',
				'complete' => '$("'.$selectid3.'").change();',
			]
		)
	);

	$this->Js->get($selectid3)->event(
		'change',
		$this->Js->request(
			['controller' => 'AjaxsSelects', 'action' => $action3, $model],
			[
				'update' => $selectid4,
				'dataExpression' => true,
				'data' => '$("'.$selectid3.'").serialize()',
			]
		)
	);

	echo $this->Js->writeBuffer();
?>

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
								$this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
								$this->Html->addCrumb('単価マスタ一覧', ['controller' => 'HostPricemasters', 'action' => 'index']);
								echo $this->element('breadcrumbs');
							?>
							<?php echo $this->Flash->render(); ?>
						</li>
					</ul>
				</div>

			<!-- /単価登録修正 -->
			<div class="row">
				<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2>価格マスタ登録</h2>
						</div>

						<?php echo $this->Form->create('Pricemaster', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>

							<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

							<br>
							<div class="form-group">
								<label class="col-sm-2 control-label">価格名</label>
								<div class="col-sm-7">
									<?php echo $this->Form->input('pricename', ['label' => false, 'div' => false, 'class' => 'form-control']); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">価格</label>
								<div class="col-sm-2">
									<div class="input-group">
										<?php echo $this->Form->input('price', ['label' => false, 'div' => false, 'class' => 'form-control', 'step' => '100', 'min' => '0']); ?>
										<span class="input-group-addon" id="basic-addon2">円</span>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">部署</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('departmentmasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												//'data-rel' => 'chosen',
											]);
										?>
									</div>
								</div>
							</div>
<!--
							<div class="form-group">
								<label class="col-sm-2 control-label">荷主</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('shippermasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												//'data-rel' => 'chosen',
											]);
										?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">積地</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('loadingmasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												//'data-rel' => 'chosen',
											]);
										?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">納入先</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('deliverymasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												//'data-rel' => 'chosen',
											]);
										?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">商品</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('productdetailmasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												//'data-rel' => 'chosen',
											]);
										?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">商品詳細</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('productcontainers_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												//'data-rel' => 'chosen',
											]);
										?>
									</div>
								</div>
							</div>
-->
							<div class="form-group">
								<label class="col-sm-2 control-label">諸費扱い</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('keihiflg', [
												'label' => false,
												'type' => 'checkbox',
												'div' => false,
												'class' => 'form-control',
											]);
										?>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<?php echo $this->Form->submit('登録', array('div'=>false, 'class' => 'btn btn-info', 'name' => 'submit')); ?>
									<?php echo $this->Html->link(('キャンセル'), array('action' => 'index'), array('class'=>array('btn btn-danger'))); ?>
								</div>
							</div>

						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>

		</div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
