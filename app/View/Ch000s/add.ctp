<?php
	$selectid1 = '#OrdertranShippermastersId';
	$selectid2 = '#OrdertranProductdetailmastersId';
	$selectid3 = '#OrdertranTruckkindsId';
	//$selectid4 = '#OrdertranProductcontainersId';
	$action = 'ajax_productdetail';
	$action2 = 'ajax_truckkind';
	//$action3 = 'ajax_productcontainer';
	$model = 'Ordertran';

	$this->Js->get($selectid1)->event(
		'change',
		$this->Js->request(
			['controller' => 'AjaxsSelects', 'action' => $action, $model],
			[
				'update' => $selectid2,
				'dataExpression' => true,
				'data' => '$("'.$selectid1.'").serialize()',
				'complete' => '$("'.$selectid2.'").change();',
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
			]
		)
	);

	echo $this->Js->writeBuffer();
?>
<style>
.ui-timepicker-container {z-index: 9999 !important;}
.ui-timepicker-standard {z-index: 9999 !important;}
</style>

<!-- Oi010s -->
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
							$this->Html->addCrumb('受注カレンダー', ['controller' => 'Ch000s', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<?php echo $this->Form->create('Ordertran', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

				<div class="row">

					<div class="box col-md-12">
						<div class="box-inner">

							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i> 受注登録</h2>
							</div>

							<?php
								if (!$this->params['named']) {
									$tid = $sid = $pid = 0;
									$date = new DateTime();
								} else {
									$tid = $this->params['named']['id'];
									$sid = $this->params['named']['sid'];
									$pid = $this->params['named']['pid'];
									$date = new DateTime($this->params['named']['date']);
								}
							?>

							<!-- 荷主選択 -->
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">荷主</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('shippermasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'readonly',
												'class' => 'form-control',
												'empty' => '選択してください',
												//'style' => 'width:150px',
												'selected' => $sid
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /荷主選択 -->

							<!-- 商品選択 -->
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">商品</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('productdetailmasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'readonly',
												'class' => 'form-control',
												'empty' => '選択してください',
												//'style' => 'width:150px',
												'selected' => $pid
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /商品選択 -->

							<!-- 商品詳細選択 -->
							<!-- 2019.12.03 削除
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">商品詳細</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('productcontainers_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												//'style' => 'width:150px',
												//'selected' => $pid
											]);
										?>
									</div>
								</div>
							</div>
							-->
							<!-- /商品詳細選択 -->
							<!-- 商品数量 -->
							<!--
							<div class="box-content">
								<div class="control-group">
								<label class="control-label">商品数量</label>
									<div class="controls">
										<?php
											echo $this->Form->input('amount', [
												'label' => false,
												'div' => false,
												'class' => 'form-control',
												'style' => 'width:100px;',
												'min' => 0,
											]);
										?>
									</div>
								</div>
							</div>
							-->
							<!-- /商品数量 -->
							<!-- 商品備考 -->
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">商品備考</label>
									<div class="controls">
										<?php
											echo $this->Form->input('productnote', [
												'label' => false,
												'div' => false,
												'class' => 'form-control',
												'placeholder' => '商品備考を入力'
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /商品備考 -->

							<!-- 車両選択 -->
							<div class="box-content">
								<div class="control-group">
								<label class="control-label">車両</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('truckkinds_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												//'style' => 'width:150px',
												'options' => $truckkinds,
												'selected' => $tid,
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /車両選択 -->

							<!-- 積み地選択 -->
							<!--
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">積地</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('loadingmasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												'data-rel'=>'chosen',
												//'style' => 'width:150px'
											]);
										?>
									</div>
								</div>
							</div>
							-->
							<!-- /積み地選択 -->

							<!-- 納入先選択 -->
							<!--
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">納入先</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('deliverymasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												'data-rel'=>'chosen',
												//'style' => 'width:150px'
											]);
										?>
									</div>
								</div>
							</div>
							-->
							<!-- /納入先選択 -->

							<!-- 積み日選択-->
							<!--
							<div class="box-content">
								<div class="control-group">
								<label class="control-label">開始日時</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('loadingdate',[
												'label' => false,
												'type' => 'text',
												'div' => false,
												'default' => $date->format('Y-m-d'),
												'class' => 'datepicker1 form-control',
												'style' => 'float:left',
											]);
										?>
										<?php
											echo $this->Form->input('loadingtime',[
												'label' => false,
												'type' => 'text',
												'div' => false,
												'default' => date('H:i'),
												'class' => 'timepicker form-control',
												'style' => 'width:100px',
											]);
										?>
									</div>
								</div>
							</div>
							-->
							<!-- /納入日選択 -->
							<!--
							<div class="box-content">
								<div class="control-group">
								<label class="control-label">終了日時</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('deliverydate',[
												'label' => false,
												'type' => 'text',
												'div' => false,
												'default' => $date->format('Y-m-d'),
												'class' => 'datepicker1 form-control',
												'style' => 'float:left',
											]);
										?>
										<?php
											echo $this->Form->input('deliverytime',[
												'label' => false,
												'type' => 'text',
												'div' => false,
												'default' => date('H:i'),
												'class' => 'timepicker form-control',
												'style' => 'width:100px',
											]);
										?>
									</div>
								</div>
							</div>
							-->
							<!-- /納入日選択 -->

							<!-- 回戦選択 -->
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">回戦</label>
									<div class="form-inline">
										<?php
											if (isset($this->params['named']['k'])) {
												$k = $this->params['named']['k'];
											} else {
												$k = 1;
											}
											$list = array_combine(range(1, 6), range(1, 6));
											echo $this->Form->input('kaisen', [
												'type' => 'select',
												'options' => $list,
												'label' => false,
												'div' => false,
												'class' => 'form-control',
												'default' => $k,
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /回戦選択 -->

							<!-- 積み備考 -->
<!--
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">積み備考</label>
									<div class="controls">
										<?php
											echo $this->Form->input('loadingnote', [
												'label'=>false,
												'div'=>false,
												'class'=>'form-control',
												'placeholder' => '備考を入力'
											]);
										?>
									</div>
								</div>
							</div>
-->
							<!-- 積み備考 -->

							<!-- 卸備考 -->
<!--
							<div class="box-content">
								<div class="control-group">
								<label class="control-label">卸し備考</label>
									<div class="controls">
										<?php
											echo $this->Form->input('deliverynote', [
												'label' => false,
												'div' => false,
												'class' => 'form-control',
												'placeholder' => '備考を入力'
											]);
										?>
									</div>
								</div>
							</div>
-->
							<!-- 卸備考 -->

							<!-- コンテナNo. -->
							<!--
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">コンテナNo.</label>
									<div class="controls">
										<?php
											echo $this->Form->input('container_no', [
												'label' => false,
												'div' => false,
												'class' => 'form-control',
												'placeholder' => 'コンテナNo.を入力'
											]);
										?>
									</div>
								</div>
							</div>
							-->
							<!-- コンテナNo. -->

							<!-- REF/BOOKING No. -->
							<!--
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">REF/BOOKING No.</label>
									<div class="controls">
										<?php
											echo $this->Form->input('ref_no', [
												'label' => false,
												'div' => false,
												'class' => 'form-control',
												'placeholder' => 'REF/BOOKING No.を入力'
											]);
										?>
									</div>
								</div>
							</div>
							-->
							<!-- REF/BOOKING No. -->

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

								<?php for ($i = 0; $i <= 7; $i++): ?>
								<br>

								<div class="form-inline">
								<!--
									<div class="input-group">
										<span class="input-group-addon">作業者</span>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.users_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => [ 0 => '未定'],
											]);
										?>
									</div>
								 -->
										<?php
											echo $this->Form->input('Processtran.'.$i.'.users_id', [
												'type' => 'hidden',
												'value' => 0,
											]);
										?>
									<div class="input-group">
										<span class="input-group-addon">作業日時</span>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.processstartdate',[
												'label' => false,
												'type' => 'text',
												'div' => false,
												'default' => $date->format('Y-m-d'),
												'class' => 'datepicker1 form-control',
												'style' => 'width:150px',
											]);
										?>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.processstarttime',[
												'label' => false,
												'type' => 'text',
												'div' => false,
//												'default' => date('H:i'),
												'default' => '',
												'class' => 'timepicker form-control',
												'style' => 'width:100px',
											]);
										?>
									</div>
									<?= $this->Html->link('作業場所追加', [
										'controller' => 'HostWorkplaces',
										'action' => 'index',
										'page' => 'Ch000s',
									], [
										'class' => 'btn btn-primary',
									]); ?>

									<?= $this->Html->link('商品詳細追加', [
										'controller' => 'HostProductdetailmasters',
										'action' => 'index',
										'page' => 'Ch000s',
									], [
										'class' => 'btn btn-primary',
									]); ?>

								</div>

								<div class="form-inline">

									<div class="input-group input-group-ex">
										<span class="input-group-addon">作業名称</span>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.workdetailmasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												//'style' => 'width:150px',
											]);
										?>
									</div>

									<div class="input-group input-group-ex">
										<span class="input-group-addon">作業場所</span>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.workplaces_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control chosen',
												'empty' => '選択してください',
												//'style'=>'width:100px;',
											]);
										?>
									</div>
									<div class="input-group  input-group-ex">
										<span class="input-group-addon">商品詳細</span>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.productcontainers_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control chosen',
												'empty' => '',
												//'style'=>'width:100px;',
											]);
										?>
									</div>

								</div>

								<div class="form-inline">
									<div class="input-group">
										<span class="input-group-addon">作業備考</span>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.workddetailcomment', [
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

							<?php endfor; ?>

							</div>

						</div>
					</div>
				</div>

				<div class="box-content">
					<?php
						echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' 登録'), [
							'type' => 'submit',
							'div' => false,
							'class' => 'btn btn-info',
							'name' => 'submit',
							'escape' => false,
						]);
					?>
					<?php echo $this->Html->link(('戻る'), ['controller' => 'Ch000s', 'action' => 'index'], ['class'=>array('btn btn-danger')]); ?>
				</div>


			<?php echo $this->Form->end(); ?>


    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<script type="text/javascript">
//jQuery.noConflict();
$(".chosen").chosen({
    search_contains:true
});

</script>
