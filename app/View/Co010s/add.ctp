<!-- コンテナ受注登録 -->
<!-- Co010s/add -->
<?php
	$this->Js->get('#OrdertranShippermastersId')->event(
		'change',
		$this->Js->request(
			['controller' => 'Co010s', 'action' => 'ajax_productdetail'],
			[
				'update' => '#OrdertranProductdetailmastersId',
				'dataExpression' => true,
				'data' => '$("#OrdertranShippermastersId").serialize()',
				'complete' => '$("#OrdertranProductdetailmastersId").change();',
			]
		)
	);

	$this->Js->get('#OrdertranProductdetailmastersId')->event(
		'change',
		$this->Js->request(
			['controller' => 'Co010s', 'action' => 'ajax_truckkind'],
			[
				'update' => '#OrdertranTruckkindsId',
				'dataExpression' => true,
				'data' => '$("#OrdertranProductdetailmastersId").serialize()'
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

			<div class="row">
				<!-- left start -->
					<div class="box col-md-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>受注登録 </h2>
							</div>

							<?php echo $this->Form->create('Ordertran', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

								<!-- 荷主選択 -->
									<div class="box-content">
										<div class="control-group">
											<label class="control-label" for="selectError">荷主</label>
											<div class="form-inline">
												<?php
													if ($this->params['named'] == null) {
														$v = 0;
													} else {
														$v = $this->params['named']['sid'];
													}
													echo $this->Form->input('shippermasters_id', [
														'label' => false,
														'type' => 'select',
														'div' => false,
														'class' => 'form-control',
														'empty' => '選択してください',
														//'style' => 'width:150px',
														'selected' => $v
													]);
												?>
											</div>
										</div>
									</div>
								<!-- /荷主選択 -->
								<!-- 商品選択 -->
									<div class="box-content">
										<div class="control-group">
											<label class="control-label" for="selectError">商品</label>
											<div class="form-inline">
												<?php
													if ($this->params['named'] == null) {
														$v = 0;
													} else {
														$v = $this->params['named']['pid'];
													}
													echo $this->Form->input('productdetailmasters_id', [
														'label' => false,
														'type' => 'select',
														'div' => false,
														'class' => 'form-control',
														'empty' => '選択してください',
														//'style' => 'width:150px',
														'selected' => $v
													]);
												?>
											</div>
										</div>
									</div>
								<!-- /商品選択 -->
								<!-- 商品数量 -->
									<div class="box-content">
										<div class="control-group">
											<label class="control-label" for="selectError">商品数量 (石油の場合は総量を入力してください)</label>
												<div class="controls">
													<?php
														echo $this->Form->input('amount', [
															'label'=>false,
															'div'=>false,
															'class'=>'form-control',
															//'value'=>'スーパー2K,ゼアス3K,軽油2K',
															'style'=>'width:100px;'
														]);
													?>
											</div>
										</div>
									</div>
								<!-- /商品数量 -->
								<!-- 商品備考 -->
									<div class="box-content">
										<div class="control-group">
											<label class="control-label" for="selectError">商品備考</label>
												<div class="controls">
													<?php
														echo $this->Form->input('productnote', [
															'label'=>false,
															'div'=>false,
															'class'=>'form-control',
															//'value'=>'スーパー2K,ゼアス3K,軽油2K',
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
											<label class="control-label" for="selectError">車両</label>
											<div class="form-inline">
												<?php
													if ($this->params['named'] == null) {
														$v = 0;
													} else {
														$v = $this->params['named']['id'];
													}
													echo $this->Form->input('truckkinds_id', [
														'label' => false,
														'type' => 'select',
														'div' => false,
														'class' => 'form-control',
														'empty' => '選択してください',
														//'style' => 'width:150px',
														'options' => $truckkinds,
														'selected' => $v,
													]);
												?>
											</div>
										</div>
									</div>
								<!-- /車両選択 -->
								<!-- 積み地選択 -->
									<div class="box-content">
										<div class="control-group">
											<label class="control-label" for="selectError">積地</label>
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
								<!-- /積み地選択 -->
								<!-- 積み日選択（一旦削除2019.02.19) -->
								<!--
									<div class="box-content">
										<div class="control-group">
											<label class="control-label" for="selectError">積日時</label>
											<div class="form-inline">
												<?php
													if ($this->params['named'] == null) {
														$ddate = date('Y-m-d');
													} else {
														$ddate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
													}
													echo $this->Form->input('loadingdate',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'default' => $ddate,
														'class' => 'datepicker1 form-control',
														'style' => 'float:left',
													]);
												?>
												<?php
													echo $this->Form->input('loadingtime',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
										</div>
									</div>
									 -->
								<!-- 納入先選択 -->
									<div class="box-content">
										<div class="control-group">
											<label class="control-label" for="selectError">納入先</label>
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
								<!-- /納入先選択 -->
								<!-- /納入日選択 -->
									<div class="box-content">
										<div class="control-group">
											<label class="control-label" for="selectError">納入日時</label>
											<div class="form-inline">
												<?php
													if ($this->params['named'] == null) {
														$ddate = date('Y-m-d');
													} else {
														$ddate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
													}
													echo $this->Form->input('deliverydate',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'default' => $ddate,
														'class' => 'datepicker1 form-control',
														'style' => 'float:left',
													]);
												?>
												<?php
													echo $this->Form->input('deliverytime',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
										</div>
									</div>
								<!-- 回戦選択 -->
									<div class="box-content">
										<div class="control-group">
											<label class="control-label" for="selectError">回戦</label>
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
														//'empty'=>'選択してください'
													]);
												?>
											</div>
										</div>
									</div>
								<!-- /回戦選択 -->
								<!-- 積み備考 -->
									<div class="box-content">
										<div class="control-group">
											<label class="control-label" for="selectError">積み備考</label>
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
								<!-- 卸備考 -->
								<div class="box-content">
									<div class="control-group">
										<label class="control-label" for="selectError">卸し備考</label>
										<div class="controls">
											<?php
												echo $this->Form->input('deliverynote', [
													'label'=>false,
													'div'=>false,
													'class'=>'form-control',
													'placeholder' => '備考を入力'
												]);
											?>
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
								</div>

							<?php echo $this->Form->end(); ?>

						</div>
					</div>

				<!-- left end -->


    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<script type="text/javascript">
//jQuery.noConflict();

jQuery(function(){
	jQuery(document).ready(function(){
	    jQuery('input.timepicker').timepicker({
	    	timeFormat: 'HH:mm',
	    	interval: 30,
	    	minTime: '0',
	    	defaultTime: 'now',
	    	startTime: '0',
	    	dynamic: false,
	    	scrollbar: true
	    });
	});

});

$(".chosen").chosen({
    search_contains:true
});

</script>
