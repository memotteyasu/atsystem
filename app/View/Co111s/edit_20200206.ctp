<!-- 請求書印刷 -->
<!-- Co111s -->
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
							$this->Html->addCrumb('請求書明細印刷', ['controller' => 'Co110s', 'action' => 'index']);
							$this->Html->addCrumb('請求書一覧', ['controller' => 'Co111s', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

		<?php echo $this->Form->create('Invoice', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

		<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

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
									<label class="col-sm-2 control-label">お支払い期限</label>
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
										<th class="col-sm-2">数量</th>
										<th class="col-sm-1">単位</th>
										<th class="col-sm-2">単価</th>
										<th class="col-sm-3">金額</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$count = 0;
										foreach ($this->request->data['Invoicedetail'] as $invoicedetail) {
										//debug($invoicedetail['tax_flg']);
											if ($invoicedetail['tax_flg'] == 0 && $invoicedetail['mirror_flg'] == 0) {

												echo '<tr>';
													echo $this->Form->input('Details.'.$count.'.id', [
														'type' => 'hidden',
														'value' => $invoicedetail['id'],
													]);
													echo $this->Form->input('Details.'.$count.'.tax_flg', [
														'type' => 'hidden',
														'value' => $invoicedetail['tax_flg'],
													]);
													echo $this->Form->input('Details.'.$count.'.mirror_flg', [
														'type' => 'hidden',
														'value' => $invoicedetail['mirror_flg'],
													]);
													echo '<td>';
														echo $this->Form->input('Details.'.$count.'.itemname', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control',
															'value' => $invoicedetail['itemname'],
															'placeholder' => '品番・品名',
														]);
													echo '</td>';
													echo '<td>';
														echo $this->Form->input('Details.'.$count.'.quantity', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control quantity',
															'value' => $invoicedetail['quantity'],
															'placeholder' => '数量',
															'id' => 'quantity'.$count
														]);
													echo '</td>';
													echo '<td>';
														echo $this->Form->input('Details.'.$count.'.unit', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control',
															'value' => $invoicedetail['unit'],
															'placeholder' => '単位',
														]);
													echo '</td>';
													echo '<td>';
														echo $this->Form->input('Details.'.$count.'.price', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control price',
															'value' => $invoicedetail['price'],
															'placeholder' => '単価',
															'id' => 'price'.$count
														]);
													echo '</td>';
													echo '<td>';
														echo $this->Form->input('Details.'.$count.'.payment', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control',
															'value' => $invoicedetail['payment'],
															'placeholder' => '金額',
															'readonly',
															'id' => 'payment'.$count
														]);
													echo '</td>';
												echo '</tr>';
												$count = $count + 1;
											}
										}
										for ($i = $count; $i <= 4; $i++){
											echo '<tr>';
												echo '<td>';
													echo $this->Form->input('Details.'.$i.'.itemname', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '品番・品名',
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Details.'.$i.'.quantity', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control quantity',
														'placeholder' => '数量',
														'id' => 'quantity'.$i
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Details.'.$i.'.unit', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '単位',
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Details.'.$i.'.price', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control price',
														'placeholder' => '単価',
														'id' => 'price'.$i
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Details.'.$i.'.payment', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '金額',
														'readonly',
														'id' => 'payment'.$i
													]);
												echo '</td>';
											echo '</tr>';
										}
									?>
									<tr>
										<td colspan="3"></td>
										<td align="center" valign="middle"><h4>小計</h4></td>
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
										<td colspan="3"></td>
										<td align="center" valign="middle"><h4>消費税（8％）</h4></td>
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
										<th colspan="5">非課税明細</td>
									</tr>
									<?php
										$count = 0;
										foreach ($this->request->data['Invoicedetail'] as $invoicedetail) {
											if ($invoicedetail['tax_flg'] == 1  && $invoicedetail['mirror_flg'] == 0) {
												echo '<tr>';
													echo $this->Form->input('Details2.'.$count.'.id', [
														'type' => 'hidden',
														'value' => $invoicedetail['id'],
													]);
													echo $this->Form->input('Details2.'.$count.'.tax_flg', [
														'type' => 'hidden',
														'value' => $invoicedetail['tax_flg'],
													]);
													echo $this->Form->input('Details2.'.$count.'.mirror_flg', [
														'type' => 'hidden',
														'value' => $invoicedetail['mirror_flg'],
													]);
													echo '<td>';
														echo $this->Form->input('Details2.'.$count.'.itemname', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control',
															'placeholder' => '品番・品名',
															'value' => $invoicedetail['itemname'],
														]);
													echo '</td>';
													echo '<td>';
														echo $this->Form->input('Details2.'.$count.'.quantity', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control quantity2',
															'placeholder' => '数量',
															'value' => $invoicedetail['quantity'],
															'id' => 'quantity2'.$count
														]);
													echo '</td>';
													echo '<td>';
														echo $this->Form->input('Details2.'.$count.'.unit', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control',
															'placeholder' => '単位',
															'value' => $invoicedetail['unit'],
														]);
													echo '</td>';
													echo '<td>';
														echo $this->Form->input('Details2.'.$count.'.price', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control price2',
															'placeholder' => '単価',
															'value' => $invoicedetail['price'],
															'id' => 'price2'.$count
														]);
													echo '</td>';
													echo '<td>';
														echo $this->Form->input('Details2.'.$count.'.payment', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control',
															'placeholder' => '金額',
															'value' => $invoicedetail['payment'],
															'readonly',
															'id' => 'payment2'.$count
														]);
													echo '</td>';
												echo '</tr>';
												$count = $count + 1;
											}
										}

										for ($i = $count; $i <= 1; $i++){
											echo '<tr>';
												echo '<td>';
													echo $this->Form->input('Details2.'.$i.'.itemname', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '品番・品名',
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Details2.'.$i.'.quantity', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control quantity2',
														'placeholder' => '数量',
														'id' => 'quantity2'.$i
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Details2.'.$i.'.unit', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '単位',
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Details2.'.$i.'.price', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control price2',
														'placeholder' => '単価',
														'id' => 'price2'.$i
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Details2.'.$i.'.payment', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '金額',
														'readonly',
														'id' => 'payment2'.$i
													]);
												echo '</td>';
											echo '</tr>';
										}
									?>
								</tbody>
								<tfoot>
									<?php
										echo $this->Form->input('subtotal2', [
											'label' => false,
											'type' => 'hidden',
											'div' => false,
										]);
									?>
									<tr>
										<td colspan="3"></td>
										<td align="center" valign="middle"><h4>合計</h4></td>
										<td>
											<?php
												echo $this->Form->input('total', [
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
								</tfoot>
							</table>

							<?php foreach ($this->request->data['Invoicedetail'] as $invoicedetail): ?>
								<?php if ($invoicedetail['mirror_flg'] == 1): ?>
									<?php
										//debug($invoicedetail);
										echo $this->Form->input('Mirror.id', [
											'type' => 'hidden',
											'value' => $invoicedetail['id'],
										]);
										echo $this->Form->input('Mirror.itemname', [
											'type' => 'hidden',
											'value' => $invoicedetail['itemname'],
										]);
									?>
								<?php endif; ?>
							<?php endforeach; ?>

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
<!--
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
										]);
									?>
								</div>
							</div>
-->
						</div>

						<div class="box-content">
							<?php
								echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' 請求書編集内容確認'), [
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
	var total3 = 0;
	$('.price').on('change', function() {
		var pg = [];
		$('.price').each(function(i) {
			var output = $('#payment' + i);
			var q = $('#quantity' + i).val();
			var price = $('#price' + i).val();
			$('#price' + i).val(price);
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
				total3 = total + total2;
			} else {
				total3 = total;
			}
			$('#InvoiceTotal').val(total3);
		});
	});
	$('.price2').on('change', function() {
		var pg = [];
		$('.price2').each(function(i) {
			var output = $('#payment2' + i);
			var q = $('#quantity2' + i).val();
			var price = $('#price2' + i).val();
			$('#price2' + i).val(price);
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
				total3 = total + total2;
			} else {
				total3 = total2;
			}
			$('#InvoiceTotal').val(total3);
		});
	});
	$('.quantity').on('change', function() {
		var pg = [];
		$('.quantity').each(function(i) {
			var output = $('#payment' + i);
			var q = $('#quantity' + i).val();
			var price = $('#price' + i).val();
			$('#price' + i).val(price);
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
				total3 = total + total2;
			} else {
				total3 = total;
			}
			$('#InvoiceTotal').val(total3);
		});
	});
	$('.quantity2').on('change', function() {
		var pg = [];
		$('.quantity2').each(function(i) {
			var output = $('#payment2' + i);
			var q = $('#quantity2' + i).val();
			var price = $('#price2' + i).val();
			$('#price2' + i).val(price);
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
				total3 = total + total2;
			} else {
				total3 = total2;
			}
			$('#InvoiceTotal').val(total3);
		});
	});
});

$(".chosen").chosen({
    search_contains:true
});

</script>
