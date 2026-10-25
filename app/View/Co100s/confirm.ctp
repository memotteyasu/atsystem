<!-- 請求書印刷 -->
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
							$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>
<!--
			<div class="row">-->
				<!-- left start --><!--
					<div class="box col-md-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>請求書印刷確認</h2>
							</div>-->

		<div class="alert alert-info" role="alert">内容・計算結果が正しいか確認して下さい。</div>

		<?php echo $this->Form->create('Invoice', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

			<div class="row">
				<div class="box col-sm-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2><i class="glyphicon glyphicon-info-sign"></i>請求情報</h2>
						</div>
						<div class="box-content">
							<div class="row">
								<br>
								<div class="form-group">
									<label class="col-sm-2 control-label">請求先</label>
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('seikyusaki', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'placeholder' => '山田風太郎',
												//'style' => 'width:150px',
											]);
										?>
									</div>
									<div class="col-sm-1">
										<?php
											echo $this->Form->input('keisyou', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'default' => '様',
												//'style' => 'width:60px',
											]);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">請求日</label>
									<div class="col-sm-2">
										<?php
											$date = new DateTime();
											echo $this->Form->input('seikyubi', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control datepicker1',
												'default' => $date->format('Y-m-d'),
												//'style' => 'width:150px',
											]);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">請求書番号</label>
									<div class="col-sm-2">
										<?php
											echo $this->Form->input('seikyubangou', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'default' => $date->format('Ymd'),
												//'style' => 'width:150px',
											]);
										?>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label">件名</label>
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('kenmei', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'placeholder' => '○×案件',
												//'style' => 'width:150px',
											]);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">お支払い期限</label>
									<div class="col-sm-2">
										<?php
											echo $this->Form->input('kigen', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control datepicker1',
												'placeholder' => $date->format('Y-m-d'),
												//'style' => 'width:150px',
											]);
										?>
									</div>
								</div>
							</div>
						</div>

						<div class="box-header well">
							<h2><i class="glyphicon glyphicon-info-sign"></i>請求元情報</h2>
						</div>

						<div class="box-content">
							<div class="row">
								<br>
								<div class="form-group">
									<label class="col-sm-2 control-label">請求者名</label>
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('seikyusyamei1', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'placeholder' => '山田風太郎',
												//'style' => 'width:150px',
											]);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"></label>
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('seikyusyamei2', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'placeholder' => '山田風太郎',
												//'style' => 'width:150px',
											]);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"></label>
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('seikyusyamei3', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'placeholder' => '山田風太郎',
												//'style' => 'width:150px',
											]);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"></label>
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('seikyusyamei4', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'placeholder' => '山田風太郎',
												//'style' => 'width:150px',
											]);
										?>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>

			<div class="row">
				<div class="box col-sm-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2><i class="glyphicon glyphicon-info-sign"></i>請求書内容</h2>
						</div>

						<div class="box-content">
							<br>
							<table class="table table-striped table-bordered bootstrap-datatable">
								<thead>
									<tr>
										<th class="col-sm-4">品番・品名</th>
										<th class="col-sm-2">数量</th>
										<th class="col-sm-1">単位</th>
										<th class="col-sm-2">単価</th>
										<th class="col-sm-3">金額</th>
									</tr>
								</thead>
								<tbody>
									<?php
										for ($count = 0; $count <= 4; $count++){
											echo '<tr>';
												echo '<td>';
													echo $this->Form->input('Test.'.$count.'.sinamei', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => 'インフィニティ・ストーン',
														//'style' => 'width:150px',
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Test.'.$count.'.suryo', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '数量',
														//'style' => 'width:150px',
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Test.'.$count.'.tani', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '単位',
														//'style' => 'width:150px',
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Test.'.$count.'.tanka', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '単価',
														//'style' => 'width:150px',
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Test.'.$count.'.kingaku', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '金額',
														'readonly',
														//'style' => 'width:150px',
													]);
												echo '</td>';
											echo '</tr>';
										}
									?>
									<?php
										for ($count = 1; $count <= 3; $count++){
											echo '<tr>';
												echo '<td colspan="3"></td>';
												if ($count == 1) {
													$text = '小計';
													$input = 'syoukei';
												} elseif ($count == 2) {
													$text = '消費税（8％）';
													$input = 'syouhizei';
												} else {
													$text = '合計';
													$input = 'goukei';
												}
												echo '<td align="center" valign="middle"><h4>'.$text.'</h4></td>';
												echo '<td>';
													echo $this->Form->input($input, [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '金額',
														'readonly',
														//'style' => 'width:150px',
													]);
												echo '</td>';
											echo '</tr>';
										}
									?>
								</tbody>
							</table>

							<div class="box-content">
								<div class="form-group">
									<label class="control-label h4">備考</label>
									<?php
										echo $this->Form->input('bikou', [
											'label' => false,
											'type' => 'textarea',
											'div' => false,
											'class' => 'form-control',
											'placeholder' => '備考',
											//'style' => 'width:150px',
										]);
									?>
								</div>
							</div>

							<div class="box-content">
								<div class="form-group">
									<label class="control-label h4">お振込先</label>
									<?php
										echo $this->Form->input('hurikomisaki', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control',
											'placeholder' => '○×銀行',
											//'style' => 'width:150px',
										]);
									?>
								</div>
							</div>

							<?php //echo $this->Form->hidden('mode',['value' => 'complete']); ?>

						</div>

						<div class="box-content">
							<?php
								echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' 請求書作成'), [
									'type' => 'submit',
									'div' => false,
									'class' => 'btn btn-success',
									//'name' => 'submit',
									'escape' => false,
								]);
							?>
						</div>

					</div>
				</div>
			</div>

		<?php echo $this->Form->end(); ?>

	</div>

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
