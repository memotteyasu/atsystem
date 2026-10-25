<!-- Ordertran edit -->
<!-- HostProductdetailmasters -->
<style type="text/css">
.hide{
    display: none;
}
</style>

<?php
	echo $this->element('AjaxsSelects', [
		'selectid1' => '#OrdertranShippermastersId',
		'selectid2' => '#OrdertranProductdetailmastersId',
		'selectid3' => '#OrdertranTruckkindsId',
		'action' => 'ajax_productdetail',
		'action2' => 'ajax_truckkind',
		'model' => 'Ordertran',
	]);
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
							$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
							$this->Html->addCrumb('出荷実績', ['controller' => 'Oi080s', 'action' => 'index']);
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
							<h2><i class="glyphicon glyphicon-info-sign"></i>出荷実績</h2>
						</div>

						<?php echo $this->Form->create('Ordertran',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

							<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

							<!-- 荷主選択 -->
								<div class="box-content">
									<div class="control-group">
									<label class="control-label" for="selectError">荷主</label>
										<div class="form-inline">
											<?php
												echo $this->Form->input('shippermasters_id', [
													'label' => false,
													'type' => 'select',
													'div' => false,
													'class' => 'form-control',
													'empty' => '選択してください',
													//'style' => 'width:150px',
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
												echo $this->Form->input('productdetailmasters_id', [
													'label' => false,
													'type' => 'select',
													'div' => false,
													'class' => 'form-control',
													'empty' => '選択してください',
													//'style' => 'width:150px',
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
												echo $this->Form->input('truckkinds_id', [
													'label' => false,
													'type' => 'select',
													'div' => false,
													'class' => 'form-control',
													'empty' => '選択してください',
													'options' => $truckkinds,
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
												]);
											?>
										</div>
									</div>
								</div>
							<!-- /積み地選択 -->
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
												]);
											?>
										</div>
									</div>
								</div>
							<!-- /納入先選択 -->

							<!-- 積日時（一旦削除2019.02.19) -->
							<!--
								<div class="box-content">
									<div class="control-group">
										<label class="control-label" for="selectError">積日時</label>
										<div class="form-inline">
											<?php
												echo $this->Form->input('loadingdate',[
													'label' => false,
													'type' => 'text',
													'div' => false,
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
							<!-- /積日時 -->

							<!-- 卸日選択 -->
								<div class="box-content">
									<div class="control-group">
										<label class="control-label" for="selectError">納入日時</label>
										<div class="form-inline">
											<?php
												echo $this->Form->input('deliverydate',[
													'label' => false,
													'type' => 'text',
													'div' => false,
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
							<!-- /卸日選択 -->

							<!-- 回戦選択 -->
								<div class="box-content">
									<div class="control-group">
										<label class="control-label" for="selectError">回戦</label>
										<div class="form-inline">
											<?php
												$list = array_combine(range(1, 6), range(1, 6));
												echo $this->Form->input('kaisen', [
													'type' => 'select',
													'options' => $list,
													'label' => false,
													'div' => false,
													'class' => 'form-control',
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
													'placeholder' => '積み備考を入力'
												]);
											?>
										</div>
									</div>
								</div>
							<!-- 積み備考 -->

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
													'placeholder' => '卸し備考を入力'
												]);
											?>
										</div>
									</div>
								</div>
							<!-- 卸備考 -->

							<!-- コンテナNo. -->
								<div class="box-content">
									<div class="control-group">
										<label class="control-label" for="selectError">コンテナNo.</label>
										<div class="controls">
											<?php
												echo $this->Form->input('container_no', [
													'label'=>false,
													'div'=>false,
													'class'=>'form-control',
													'placeholder' => 'コンテナNo.を入力'
												]);
											?>
										</div>
									</div>
								</div>

							<!-- REF/BOOKING No. -->
								<div class="box-content">
									<div class="control-group">
										<label class="control-label" for="selectError">REF/BOOKING No.</label>
										<div class="controls">
											<?php
												echo $this->Form->input('ref_no', [
													'label'=>false,
													'div'=>false,
													'class'=>'form-control',
													'placeholder' => 'REF/BOOKING No.を入力'
												]);
											?>
										</div>
									</div>
								</div>

							<!-- 納入実績日時 -->
								<div class="box-content">
									<div class="control-group">
										<label class="control-label text-danger" for="selectError">納入実績日時</label>
										<div class="form-inline">
											<?php
												$correctdate = new DateTime($this->request->data['Ordertran']['correctdate']);
												if($this->request->data['Ordertran']['correctdate'] == '0000-00-00 00:00:00'){
													echo $this->Form->input('cdate',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'datepicker1 form-control',
														'style' => 'float:left',
													]);
													echo $this->Form->input('ctime',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												}else{
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
												}
											?>
											<?php echo $this->Form->error('Ordertran.correctdate'); ?>
										</div>
									</div>
								</div>
							<!-- /納入実績日時 -->

							<!-- 出荷数量 -->
								<div class="box-content">
									<div class="control-group">
										<label class="control-label text-danger" for="selectError">出荷数量</label>
											<div class="controls">
												<?php
													echo $this->Form->input('correctamount', [
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
							<!-- /出荷数量 -->

							<!-- 出荷金額 -->
								<div class="box-content">
									<div class="control-group">
										<label class="control-label text-danger" for="selectError">出荷金額</label>
											<div class="controls">
												<?php
													echo $this->Form->input('price', [
														'label'=>false,
														'div'=>false,
														'class'=>'form-control',
														'style'=>'width:150px;'
													]);
												?>
										</div>
									</div>
								</div>
							<!-- /出荷金額 -->

							<div class="box-content">
								<?php
									echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' 実績登録'), [
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
				</div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<script type="text/javascript">
$(function(){
	$('#makerPulldown').change(function(){

		// 選択されたオプションのkey番号を取得
		var num = $('#makerPulldown').val();

		// 選択値以外に class="hide" を追加
		$(".targetTd p[id != 'note+num']").addClass('hide');

		// 取得したkey番号の class="hide" を削除
		$("p#note"+num).removeClass('hide');
	});

	jQuery(document).ready(function(){
	    jQuery('input.timepicker').timepicker({
	    	timeFormat: 'HH:mm',
	    	interval: 30,
	    	minTime: '0',
	    	//defaultTime: 'now',
	    	startTime: '0',
	    	dynamic: false,
	    	scrollbar: true
	    });
	})
});
</script>
