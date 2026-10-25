<!-- 請求書印刷 -->
<!-- Co100s -->
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
							$this->Html->addCrumb('月別車両別請求書修正', ['controller' => 'Co111s', 'action' => 'index']);
							echo $this->element('breadcrumbs');
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
							<h2><i class="glyphicon glyphicon-info-sign"></i>請求情報</h2>
						</div>
						<div class="box-content">
							<div class="row">
								<br>
								<div class="form-group">
									<label class="col-sm-2 text-right">請求先</label>
									<div class="col-sm-4">
										<?php
											echo $this->request->data['Shippermaster']['shippername'];
										?>
									</div>
									<div class="col-sm-1">
										<?php
											echo '御中';
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
												'default'=>$this->request->data['Invoice']['subject'],
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
							<h2><i class="glyphicon glyphicon-info-sign"></i>請求書明細</h2>
						</div>

						<div class="box-content">
							<br>
							<table class="table table-striped table-bordered bootstrap-datatable">
								<thead>
									<tr>
										<th class="col-sm-1 text-center">日付</th>
										<th class="col-sm-5 text-center">納入先・諸費</th>
										<th class="col-sm-1 text-center">ｺﾝﾃﾅNo</th>
										<th class="col-sm-1 text-center">REF</th>
										<th class="col-sm-2 text-center">品名</th>
										<th class="col-sm-2 text-center">金額</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$count = 0;
										$subtotal = 0;
										$zei = 0.1;  //消費税１０％
										$total = 0;
										$syokei = 0;
										foreach ($this->request->data['Invoicedetail'] as $invoicedetails){
											$w_product = [];
											//小計加算
											$syokei = $syokei + $invoicedetails['price'];
												echo '<tr>';
												echo '<td>';
												$date = new DateTime($invoicedetails['detaildate']);
												echo $this->Form->input('Details.'.$count.'.detaildatedummy', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control',
													'value' => $date->format('m/d'),
													'readonly',
												]);
												echo $this->Form->input('Details.'.$count.'.detaildate', [
													'label' => false,
													'type' => 'hidden',
													'div' => false,
													'class' => 'form-control',
													'value' => $date->format('Y/m/d'),
													'readonly',
												]);
												echo $this->Form->input('Details.'.$count.'.id', [
													'label' => false,
													'type' => 'hidden',
													'div' => false,
													'class' => 'form-control',
													'value' => $invoicedetails['id'],
													'readonly',
												]);
												echo '</td>';
												echo '<td>';
												echo $this->Form->input('Details.'.$count.'.itemname', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control',
													'value' => $invoicedetails['itemname']
												]);
												echo '</td>';
												echo '<td>';
												echo $this->Form->input('Details.'.$count.'.containerno', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control',
													'value' => $invoicedetails['containerno']
												]);
												echo '</td>';
												echo '<td>';
												echo $this->Form->input('Details.'.$count.'.refno', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control',
													'value' => $invoicedetails['refno']
												]);
												echo '</td>';
												echo '<td>';
														echo $this->Form->input('Details.'.$count.'.productdetailname', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control',
															'value' => $invoicedetails['productdetailname']
														]);
												echo '</td>';
												echo '<td class="text-right">';
													echo $this->Form->input('Details.'.$count.'.price', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control text-right',
														'value' => $invoicedetails['price'],
													]);
												echo '</td>';
												echo '</tr>';
												$count = $count + 1;
										}
										?>
									<tr>
										<td colspan="4"></td>
										<td align="center" valign="middle"><h4>小計</h4></td>
										<td class="text-right h3">
											<?php
												echo $this->Form->input('subtotal', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control text-right',
													'placeholder' => '金額',
													'value' => $syokei
												]);
											?>
										</td>
									</tr>
									<tr>
										<td colspan="4"></td>
										<td align="center" valign="middle"><h4>消費税（10％）</h4></td>
										<td class="text-right h3">
											<?php
												$tax = $syokei * $zei;
												echo $this->Form->input('consumptiontax', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control text-right',
													'placeholder' => '金額',
													'value' => $tax
												]);
												$sougoukei = $tax + $syokei;
											?>
										</td>
									</tr>
									<tr>
										<th colspan="6">非課税明細</td>
									</tr>
									<?php
										for ($count = 0; $count <= 1; $count++){
											echo '<tr>';
												echo '<td></td>';
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
													echo $this->Form->input('Details2.'.$count.'.quantity', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control quantity2 text-right',
														'placeholder' => '数量',
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
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Details2.'.$count.'.price', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control price2 text-right',
														'placeholder' => '単価',
														'id' => 'price2'.$count
													]);
												echo '</td>';
												echo '<td>';
													echo $this->Form->input('Details2.'.$count.'.payment', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control text-right',
														'placeholder' => '金額',
														'readonly',
														'id' => 'payment2'.$count
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
										<td colspan="4"></td>
										<td align="center" valign="middle"><h4>合計</h4></td>
										<td>
											<?php
												echo $this->Form->input('total', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control text-right',
													'placeholder' => '金額',
													'readonly',
													'value' => $total+$sougoukei
												]);
											?>
										</td>
									</tr>
								</tfoot>
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
											'default' => $user['Kaisya']['bank'],
										]);
									?>
								</div>
							</div>
-->
							<?php //echo $this->Form->hidden('mode',['value' => 'confirm']); ?>

						</div>

						<div class="box-content">
							<?php
								echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' 修正を登録する'), [
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
	var tax = 0.1;
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
				console.log(payment);
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
