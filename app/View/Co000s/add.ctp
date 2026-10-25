<?php
	$selectid1 = '#OrdertranShippermastersId';
	$selectid2 = '#OrdertranProductdetailmastersId';
	$selectid3 = '#OrdertranTruckkindsId';
	$selectid4 = '#OrdertranTantomastersId';
	$action = 'ajax_productdetail';
	$action2 = 'ajax_truckkind2';
	$action3 = 'ajax_tanto';
	$model = 'Ordertran';
	$model2 = 'Tantomaster';
	$cid = $this->params['named']['cid'];

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
			['controller' => 'AjaxsSelects', 'action' => $action2, $model, $cid],
			[
				'update' => $selectid3,
				'dataExpression' => true,
				'data' => '$("'.$selectid2.'").serialize()',
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
							$this->Html->addCrumb('受注カレンダー', ['controller' => 'Co000s', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<?php echo $this->Form->create('Ordertran', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

				<div class="row">

					<div class="box col-md-12">
					<p>
<?php echo $this->Html->link('過去のデータをコピー',['controller'=>'Co005s','action'=>'index','id'=>$this->params['named']['id'],'sid'=>$this->params['named']['sid'],'pid'=>$this->params['named']['pid'],'cid'=>$this->params['named']['cid'],'page'=>'Co000s','haidate'=>$this->params['named']['date']],['class'=>['btn btn-info']]); ?>
</p>
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i> 受注新規登録</h2>
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
												'class' => 'form-control',
												'selected' => $sid,
												// 'readonly',
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
												'class' => 'form-control',
												//'style' => 'width:150px',
												'selected' => $pid,
												// 'readonly'
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /商品選択 -->

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
												'options' => $truckkinds,
												'selected' => $tid,
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /車両選択 -->

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
							<!-- 課税非課税選択 -->
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">課税対象</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('taxflg', [
												'type' => 'select',
												'options' => $taxflgs,
												'label' => false,
												'div' => false,
												'class' => 'form-control',
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /課税非課税選択 -->

							<!-- 担当者名 -->
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">担当者名</label>
									<div class="controls">
										<?php
											echo $this->Form->input('tantomasters_id', [
												'type'=>'select',
												'label' => false,
												'div' => false,
												'class' => 'input-control',
												'options' => $tantomasters,
												'default'=>0
											]);
										?>
									</div>
								</div>
							</div>
							<!-- 担当者名 -->

							<!-- 卸備考
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
							 卸備考 -->

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
												'class' => 'timepicker2 form-control',
												'style' => 'width:100px',
											]);
										?>
									</div>
									<?= $this->Html->link('作業場所追加', [
										'controller' => 'HostWorkplaces',
										'action' => 'index',
										'page' => 'Co000s',
									], [
										'class' => 'btn btn-primary',
									]); ?>

									<?= $this->Html->link('商品詳細追加', [
										'controller' => 'HostProductdetailmasters',
										'action' => 'index',
										'page' => 'Co000s',
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
												'empty' => '選択してください',
												//'style'=>'width:100px;',
											]);
										?>
									</div>

								</div>

								<div class="form-inline">

									<div class="input-group">
										<span class="input-group-addon">コンテナNo.</span>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.container_no', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'placeholder' => 'コンテナNo.を入力'
												//'style'=>'width:100px;',
											]);
										?>
									</div>

									<div class="input-group">
										<span class="input-group-addon">REF/BOOKING No.</span>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.ref_no', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'placeholder' => 'REF/BOOKING No.を入力'
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

							<hr></hr>


							</div>

						</div>
					</div>
				</div>

				<!--
				<p>
					<?php
						echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' 登録'), [
							'type' => 'submit',
							'div' => false,
							'class' => 'btn btn-info',
							'name' => 'submit',
							'escape' => false,
						]);
					?>
				</p>
				-->

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
				</div>

<?php $bid = SessionHelper::read('BackData2.bid'); ?>
<?php echo $this->Form->input('branchmasters_id',[
'type'=>'hidden',
'value'=>$bid
]); ?>
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
