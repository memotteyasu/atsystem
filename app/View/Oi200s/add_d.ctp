<!-- 請求書印刷 -->
<!-- add_d -->
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
							$this->Html->addCrumb('請求一覧', ['controller' => 'Oi200s', 'action' => 'index']);
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
							<h2><i class="glyphicon glyphicon-info-sign"></i>請求情報(Type:D)</h2>
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
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'default' => $shippermasters['Shippermaster']['shippername'],
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
								<!--
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
								-->
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
										<th class="col-sm-4">日付</th>
										<th class="col-sm-4">内容</th>
										<th class="col-sm-4">コンテナNo.</th>
										<th class="col-sm-4">REFNo.</th>
										<th class="col-sm-4">商品</th>
										<th class="col-sm-3">金額</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$count = 0;
										$subtotal = 0;
										foreach($ordertrans as $ot){
											//品名、コンテナNo.REFNo.を取得。
											//processtranをforeachして入って入れば変数へ代入
											$pdconname = $hinmei = $conno = $refno = '';
											foreach($ot['Processtran'] as $otpt){
												if(isset($otpt['productcontainers_id'])){
													$productcontainers = ClassRegistry::init('Productcontainer');
													$pdcon = $productcontainers->find('first',[
														'fields'=>[
															'pdconname'
														],
														'conditions'=>[
															'Productcontainer.id' => $otpt['productcontainers_id']
														]
													]);
													$pdconname = $pdcon['Productcontainer']['pdconname'];
												}
												if($otpt['productdetailmastername']){
													$hinmei = $otpt['productdetailmastername'];
												}
												if($otpt['container_no']){
													$conno = $otpt['container_no'];
												}
												if($otpt['ref_no']){
													$refno = $otpt['ref_no'];
												}
											}
											//ordertranのIDでordertran_pricemasterを読み込む
											$orderpricemasters = ClassRegistry::init('ordertrans_pricemaster');
											$ops = $orderpricemasters->find('all',[
												'fields' => [
													'id', 'ordertrans_id', 'pricemasters_id'
												],
												'conditions' => [
													'ordertrans_pricemaster.ordertrans_id' => $ot['Ordertran']['id'],
													'ordertrans_pricemaster.delflg' => 0
												],
												'order'=> [
													'ordertrans_pricemaster.created'=>'asc'
												]									
											]);
											//キャンセルするオーダーは価格が決まったものしかしない前提で作成（未確認）
											foreach($ops as $op){
												if($ot['Ordertran']['delflg']==3){
													//キャンセルデータの場合
													echo '<tr class="table-warning">';
													echo '<td>';
//20240706													echo $ot['Ordertran']['loadingdate'];
													echo $ot['Ordertran']['detailbilldate'];
													echo '</td>';
													echo '<td>';
													$pricemasters = ClassRegistry::init('Pricemaster');
													$pm = $pricemasters->find('first',[
														'fields'=>[
															'Pricemaster.pricename',
															'Pricemaster.price'
														],
														'conditions'=>[
															'Pricemaster.id' => $op['ordertrans_pricemaster']['pricemasters_id']
														]
													]);
													echo $pm['Pricemaster']['pricename'];
													echo '</td>';
													echo '<td>';
													echo $conno;
													echo '</td>';
													echo '<td>';
													echo $refno;
													echo '</td>';
													echo '<td>';
													echo $pdconname;
													echo '</td>';
													echo '<td class="text-right">';
													echo number_format($ot['Ordertran']['cancelprice']);
													$subtotal = $subtotal + $ot['Ordertran']['cancelprice'];
													echo '</td>';
													echo '</tr>';
													$count++;

												}else{
													echo '<tr>';
													echo '<td>';
//20240706													echo $ot['Ordertran']['loadingdate'];
													echo $ot['Ordertran']['detailbilldate'];
													echo '</td>';
													echo '<td>';
													$pricemasters = ClassRegistry::init('Pricemaster');
													$pm = $pricemasters->find('first',[
														'fields'=>[
															'Pricemaster.pricename',
															'Pricemaster.price'
														],
														'conditions'=>[
															'Pricemaster.id' => $op['ordertrans_pricemaster']['pricemasters_id']
														]
													]);
													echo $pm['Pricemaster']['pricename'];
													echo '</td>';
													echo '<td>';
													echo $conno;
													echo '</td>';
													echo '<td>';
													echo $refno;
													echo '</td>';
													echo '<td>';
													echo $pdconname;
													echo '</td>';
													echo '<td class="text-right">';
													echo number_format($pm['Pricemaster']['price']);
													$subtotal = $subtotal + $pm['Pricemaster']['price'];
													echo '</td>';
													echo '</tr>';
													$count++;
												}
											}
											//ordertranのIDでorderexpensestranを読み込む
											$orderexpensestrans = ClassRegistry::init('Orderexpensestran');
											$oet = $orderexpensestrans->find('all',[
												'fields' => [
													'id', 'expensesname', 'expensesprice'
												],
												'conditions' => [
													'ordertrans_id' => $ot['Ordertran']['id'],
												]
											]);
											foreach($oet as $oe){
												echo '<tr>';
												echo '<td>';
												echo $ot['Ordertran']['deliverydate'];
												echo '</td>';
												echo '<td>';
												echo $oe['Orderexpensestran']['expensesname'];
												echo '</td>';
												echo '<td>';
												echo '</td>';
												echo '<td>';
												echo '</td>';
												echo '<td>';
												echo '</td>';
												echo '<td class="text-right">';
												echo number_format($oe['Orderexpensestran']['expensesprice']);
												$subtotal = $subtotal + $oe['Orderexpensestran']['expensesprice'];
												echo '</td>';
												echo '</tr>';
												$count++;
											}
										}
									?>
									<tr>
										<td colspan="4"></td>
										<td class="text-center" valign="middle"><h4>小計</h4></td>
										<td class="text-right">
											<?php
											echo number_format($subtotal);
											?>
										</td>
									</tr>
									<tr>
										<td colspan="4"></td>
										<td align="center" valign="middle"><h4>消費税（10％）</h4></td>
										<td class="text-right">
											<?php
											$subtotal_tax = round($subtotal * 0.1);
											echo number_format($subtotal_tax);
											?>
										</td>
									</tr>
									</tbody>
									</table>

									<table class="table table-striped table-bordered bootstrap-datatable">
										<tbody>
									<tr>
										<th colspan="5">非課税明細</td>
									</tr>
									<?php
										for ($count = 0; $count <= 6; $count++){
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
													echo $this->Form->input('Details2.'.$count.'.quantity', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control quantity2',
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
														'class' => 'form-control price2',
														'placeholder' => '単価',
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
														'readonly',
														'id' => 'payment2'.$count
													]);
												echo '</td>';
											echo '</tr>';
										}
									?>
									<tr>
										<th colspan="5">高速代<small>（税込金額で入力）</small></td>
									</tr>
									<?php
										for ($count = 0; $count <= 0; $count++){
											echo '<tr>';
												echo '<td colspan="4">';
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
										<th colspan="5">サーチャージ<small>（税抜金額で入力）</small></td>
									</tr>
									<?php
										for ($count = 0; $count <= 0; $count++){
											echo '<tr>';
												echo '<td colspan="4">';
													echo $this->Form->input('Details4.'.$count.'.itemname', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'default' => '燃料サーチャージ('.$shippermasters['Shippermaster']['surcharge'].'%)',
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
								<tfoot>
									<?php
										echo $this->Form->input('subtotal2', [
											'label' => false,
											'type' => 'hidden',
											'div' => false,
										]);
										echo $this->Form->input('paramsDate',[
											'label' => false,
											'type' => 'hidden',
											'div' => false,
											'value' => $this->params['pass'][0]
										]);
										echo $this->Form->input('paramsDate2',[
											'label' => false,
											'type' => 'hidden',
											'div' => false,
											'value' => $this->params['pass'][1]
										]);
										echo $this->Form->input('shippermasters_id',[
											'label' => false,
											'type' => 'hidden',
											'div' => false,
											'value' => $this->params['pass'][2]
										]);
/*
										echo $this->Form->input('did',[
											'label' => false,
											'type' => 'hidden',
											'div' => false,
											'value' => $this->params['pass'][3]
										]);
*/
										if(!empty($this->params['pass'][3])){
											echo $this->Form->input('cid',[
												'label' => false,
												'type' => 'hidden',
												'div' => false,
												'value' => $this->params['pass'][3]
											]);
										}
									?>
<!-- 
<tr>
										<td colspan="3"></td>
										<td align="center" valign="middle"><h4>小計</h4></td>
										<td>
											<?php
												echo $this->Form->input('total', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control',
													'id' => 'InvoiceTotal',
													'placeholder' => '金額',
													'readonly',
												]);
											?>
										</td>
									</tr>
-->
									<?php
										/*
										for ($count = 1; $count <= 3; $count++){
											echo '<tr>';
												echo '<td colspan="3"></td>';
												if ($count == 1) {
													$text = '小計';
													$input = 'subtotal';
												} elseif ($count == 2) {
													$text = '消費税（10％）';
													$input = 'consumptiontax';
												} else {
													$text = '合計';
													$input = 'total';
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
													]);
												echo '</td>';
											echo '</tr>';
										}
										*/
									?>
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
							<?php //echo $this->Form->hidden('mode',['value' => 'confirm']); ?>
-->
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
	var total4 = 0;
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
			total = subtotal2;
			$('#InvoiceTotal').val(total);
		});
	});
	$('.quantity2').on('change', function() {
		var pg = [];
		$('.quantity2').each(function(i) {
			var output = $('#payment' + i);
			var q = $('#quantity2' + i).val();
			var price = $('#price2' + i).val();
			$('#price' + i).val(price);
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
			total = subtotal2;
			$('#InvoiceTotal').val(total);
		});
	});
	$('.payment3').on('change', function() {
		var pg = [];
		$('.payment3').each(function(i) {
			var output = $('#payment3' + i);
			var q = 1;
			var price = $('#payment3' + i).val();
			total = total + price * 1;
			$('#InvoiceTotal').val(total);
		});
	});
	$('.payment4').on('change', function() {
		var pg = [];
		$('.payment4').each(function(i) {
			var output = $('#payment4' + i);
			var q = 1;
			var price = $('#payment4' + i).val();
			total = total + price * 1;
			$('#InvoiceTotal').val(total);
		});
	});
});

$(".chosen").chosen({
    search_contains:true
});

</script>
