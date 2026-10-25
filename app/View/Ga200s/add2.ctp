<!-- free請求書印刷 -->
<!-- 数量、単価なしバージョン -->
<!-- Ga200s -->
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
							$this->Html->addCrumb('請求書一覧', ['controller' => 'Ga200s', 'action' => 'index']);
							echo $this->element('breadcrumbs')
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

		<?php echo $this->Form->create('Invoice', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

			<div class="row">
				<div class="box col-sm-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2><i class="glyphicon glyphicon-info-sign"></i>free請求書作成</h2>
						</div>
						<div class="box-content">
							<div class="row">
								<br>
								<div class="form-group">
									<label class="col-sm-2 control-label">請求先</label>
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('shippermasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'options' => $shippermasters,
												'empty' => '請求先を選択',
												//'data-rel'=>'chosen',
											]);
										?>
									</div>
									<div class="col-sm-1">
										<?php
											echo $this->Form->input('honorific', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'default' => '御中',
											]);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label text-danger">請求元</label>
									<div class="col-sm-2">
										<?php
											echo $this->Form->input('kaisyas_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'options' => $kaisyas,
											]);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">請求日</label>
									<div class="col-sm-2">
										<?php
											$date = new DateTime();
											echo $this->Form->input('billingdate', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control datepicker1',
												'default' => $date->format('Y-m-d'),
											]);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">請求書番号</label>
									<div class="col-sm-2">
										<?php
											echo $this->Form->input('invoicenumber', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'default' => $date->format('Ymd'),
											]);
										?>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label">件名</label>
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('subject', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'placeholder' => '件名',
											]);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">明細日付</label>
									<div class="col-sm-2">
										<?php
											echo $this->Form->input('paymentdate', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control datepicker1',
												'placeholder' => $date->format('Y-m-d'),
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
										<th class="col-sm-2">金額</th>
									</tr>
								</thead>
								<tbody>
									<?php
										for ($count = 0; $count <= 4; $count++){
											echo '<tr>';
												echo '<td>';
													echo $this->Form->input('Details.'.$count.'.itemname', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '品番・品名',
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Details.'.$count.'.payment', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control payment',
														'placeholder' => '金額',
														'id' => 'payment'.$count,
													]);
												echo '</td>';
											echo '</tr>';
										}
									?>
									<tr>
										<td align="right" valign="middle"><h4>小計</h4></td>
										<td>
											<?php
												echo $this->Form->input('subtotal', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control',
													'placeholder' => '金額',
													'readonly',
												]);
											?>
										</td>
									</tr>
									<tr>
										<td align="right" valign="middle"><h4>消費税（10％）</h4></td>
										<td>
											<?php
												echo $this->Form->input('consumptiontax', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control',
													'placeholder' => '金額',
													'readonly',
												]);
											?>
										</td>
									</tr>
									<tr>
										<th colspan="2">免税</td>
									</tr>
									<?php
										for ($count = 0; $count <= 4; $count++){
											echo '<tr>';
												echo '<td>';
													echo $this->Form->input('Details2.'.$count.'.itemname', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '品番・品名',
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Details2.'.$count.'.payment', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control payment2',
														'placeholder' => '金額',
														'id' => 'payment2'.$count
													]);
												echo '</td>';
											echo '</tr>';
										}
									?>
									<tr>
										<th colspan="2">高速代<small>（税込金額で入力）</small></td>
									</tr>
									<?php
										for ($count = 0; $count <= 0; $count++){
											echo '<tr>';
												echo '<td colspan="0">';
													echo $this->Form->input('Details3.'.$count.'.itemname', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '名称',
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Details3.'.$count.'.payment', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control payment3',
														'placeholder' => '金額',
														'id' => 'payment3'.$count
													]);
												echo '</td>';
											echo '</tr>';
										}
									?>
									<tr>
										<th colspan="2">サーチャージ<small>（税抜金額で入力）</small></td>
									</tr>
									<?php
										for ($count = 0; $count <= 0; $count++){
											echo '<tr>';
												echo '<td colspan="0">';
													echo $this->Form->input('Details4.'.$count.'.itemname', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '名称',
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Details4.'.$count.'.payment', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control payment4',
														'placeholder' => '金額',
														'id' => 'payment4'.$count
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
										echo $this->Form->input('billingnote', [
											'label' => false,
											'type' => 'textarea',
											'div' => false,
											'class' => 'form-control',
											'placeholder' => '備考',
										]);
									?>
								</div>
							</div>

							<div class="box-content">
								<div class="form-group">
									<label class="control-label h4">お振込先</label>
									<?php
										echo $this->Form->input('payee', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control',
											'placeholder' => '振込先',
											'default' => $user['Kaisya']['bank'],
										]);
									?>
								</div>
							</div>

							<?php //echo $this->Form->hidden('mode',['value' => 'confirm']); ?>

						</div>

						<div class="box-content">
							<?php
								echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' free請求書作成'), [
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
$(function(){
	var tax = 0.10;
	var test = parseInt($('#InvoiceSubtotal').val());
	if (Number.isNaN(test)) {
		var total = 0;
	} else {
		var total = parseInt($('#InvoiceSubtotal').val()) + parseInt($('#InvoiceConsumptiontax').val());
	}
	var test2 = parseInt($('#InvoiceSubtotal2').val());
	if (Number.isNaN(test2)) {
		var total2 = 0;
	} else {
		var total2 = parseInt($('#InvoiceSubtotal2').val());
	}
	var test3 = parseInt($('#InvoiceSubtotal3').val());
	if (Number.isNaN(test3)) {
		var total3 = 0;
	} else {
		var total3 = parseInt($('#InvoiceSubtotal3').val());
	}
	var total4 = 0;
	$('.payment').on('change', function() {
		var pg = [];
		$('.payment').each(function(i) {
			var output = $('#payment' + i);
			var q = 1;
			var price = $('#payment' + i).val();
			if (price != 0) {
				var payment = price * q;
				output.val(payment);
				pg.push(payment);
			}
			var subtotal = 0;
			for(var j = 0; j < pg.length; j++) {
				subtotal += pg[j];
			}
			$('#InvoiceSubtotal').val(subtotal);
			var consumptiontax = Math.floor(subtotal * tax);
			$('#InvoiceConsumptiontax').val(consumptiontax);
			total = subtotal + consumptiontax;
			if (total2 != 0) {
				total4 = total + total2;
			} else {
				total4 = total;
			}
			$('#InvoiceTotal').val(total4);
		});
	});
	$('.payment2').on('change', function() {
		var pg = [];
		$('.payment2').each(function(i) {
			var output = $('#payment2' + i);
			var q = 1;
			var price = $('#payment2' + i).val();
			if (price != 0) {
				var payment = price * q;
				output.val(payment);
				pg.push(payment);
			}
			var subtotal2 = 0;
			for(var j = 0; j < pg.length; j++) {
				subtotal2 += pg[j];
			}
			$('#InvoiceSubtotal2').val(subtotal2);
			total2 = subtotal2;
			if (total != 0) {
				total4 = total + total2;
			} else {
				total4 = total2;
			}
			$('#InvoiceTotal').val(total4);
		});
	});
	$('.payment3').on('change', function() {
		var pg = [];
		$('.payment3').each(function(i) {
			var output = $('#payment3' + i);
			var q = 1;
			var price = $('#payment3' + i).val();
			if (price != 0) {
				var payment = price * q;
				output.val(payment);
				pg.push(payment);
			}
			var subtotal3 = 0;
			for(var j = 0; j < pg.length; j++) {
				subtotal3 += pg[j];
			}
			$('#InvoiceSubtotal3').val(subtotal3);
			total3 = subtotal3;
			if (total != 0) {
				total4 = total + total2 + total3;
			} else {
				total4 = total3;
			}
			$('#InvoiceTotal').val(total4);
		});
	});
});

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
