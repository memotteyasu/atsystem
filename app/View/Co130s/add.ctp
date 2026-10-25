<!-- 請求書印刷 -->
<!-- Co130s -->
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
							$this->Html->addCrumb('請求書明細一覧', ['controller' => 'Co130s', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

		<?php echo $this->Form->create('Invoice', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

		<?php if(!$ordertrans): ?>
			<div class="box-content">
				<div class="alert alert-warning" role="alert">
					<strong>
						選択した条件に一致するデータが存在していないため、請求書を作成することができません。</br>
						もう一度やり直してください。
					</strong>
				</div>
			</div>
		<?php else: ?>

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
												'options' => $orderlist,
												//'empty' => '請求先を選択',
												//'data-rel'=>'chosen',
											]);
										?>
									</div>
									<div class="col-sm-2">
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
							<h2><i class="glyphicon glyphicon-info-sign"></i>請求書明細</h2>
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
								<!--
									<?php
										$count = 0;
										$subtotal = 0;
										//$tax = 0;
										$taxtotal = 0;
										$total = 0;
										$count2 = 0;
										foreach ($ordertrans as $ordertran):
									?>
										<tr>
											<td>
												<?php
													$itemname = $ordertran['Productcontainer']['pdconname'].'（No:'.$ordertran['Ordertran']['container_no'].'/REF:'.$ordertran['Ordertran']['ref_no'].'）';
													$quantity = $ordertran['Ordertran']['correctamount'];
													$price = $ordertran['Ordertran']['price'];
													$payment = $quantity * $price;
													$subtotal = $subtotal + $payment;
													$taxtotal = floor($subtotal * $tax);
													$total = $subtotal + $taxtotal;
												?>
												<?php
													echo $this->Form->input('Details.'.$count.'.itemname', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '品番・品名',
														'readonly',
														'value' => $itemname
													]);
												?>
											</td>
											<td>
												<?php
													echo $this->Form->input('Details.'.$count.'.quantity', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control quantity',
														'placeholder' => '数量',
														'id' => 'quantity'.$count,
														'style' => 'text-align: right',
														'readonly',
														'value' => $quantity
													]);
												?>
											</td>
											<td>
												<?php
													echo $this->Form->input('Details.'.$count.'.unit', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'readonly',
														'placeholder' => '単位',
													]);
												?>
											</td>
											<td>
												<?php
													echo $this->Form->input('Details.'.$count.'.price', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control price',
														'placeholder' => '単価',
														'id' => 'price'.$count,
														'style' => 'text-align: right',
														'readonly',
														//'value' => $this->Number->currency($price,'JPY',['wholeSymbol'=>"", 'places' => 0])
														'value' => $price
													]);
												?>
											</td>
											<td>
												<?php
													echo $this->Form->input('Details.'.$count.'.payment', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '金額',
														'readonly',
														'id' => 'payment'.$count,
														'style' => 'text-align: right',
														'readonly',
														//'value' => $this->Number->currency($payment,'JPY',['wholeSymbol'=>"", 'places' => 0])
														'value' => $payment
													]);
												?>
											</td>
										</tr>
										-->
										<?php
											if (isset($ordertran['Price'])):
										?>
											<?php
												foreach ($ordertran['Price'] as $price):
											?>
												<tr class="info">
													<?php
														$orderprice_itemname = $price['pricename'];
														$orderprice_quantity = 1;
														$orderprice_price = $price['price'];
														$orderprice_payment = $orderprice_quantity * $orderprice_price;
													?>
													<td>
														<!--
														<div class="input-group">
														<span class="input-group-addon"></span>
														-->
														<?php
															echo $this->Form->input('DetailsOrderPrice.'.$count2.'.itemname', [
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'form-control',
																'placeholder' => '品番・品名',
																'readonly',
																'value' => $orderprice_itemname
															]);
														?>
														<!--</div>-->
													</td>
													<td>
														<?php
															echo $this->Form->input('DetailsOrderPrice.'.$count2.'.quantity', [
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'form-control quantity',
																'placeholder' => '数量',
																'id' => 'orderquantity'.$count,
																'style' => 'text-align: right',
																'readonly',
																'value' => $orderprice_quantity
															]);
														?>
													</td>
													<td>
														<?php
															echo $this->Form->input('DetailsOrderPrice.'.$count2.'.unit', [
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'form-control',
																'readonly',
																'placeholder' => '単位',
															]);
														?>
													</td>
													<td>
														<?php
															echo $this->Form->input('DetailsOrderPrice.'.$count2.'.price', [
																'label' => false,
																//'type' => 'text',
																'div' => false,
																'class' => 'form-control orderprice',
																'placeholder' => '単価',
																'readonly',
																'id' => 'orderprice'.$count,
																'style' => 'text-align: right',
																//'value' => $this->Number->currency($price2,'JPY',['wholeSymbol'=>"", 'places' => 0]),
																'value' => $orderprice_price
															]);
														?>
													</td>
													<td>
														<?php
															echo $this->Form->input('DetailsOrderPrice.'.$count2.'.payment', [
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'form-control',
																'placeholder' => '金額',
																'readonly',
																'id' => 'orderpayment'.$count,
																'style' => 'text-align: right',
																//'value' => $this->Number->currency($payment2,'JPY',['wholeSymbol'=>"", 'places' => 0])
																'value' => $orderprice_payment
															]);
														?>
													</td>
												</tr>
											<?php
												$count2 = $count2 + 1;
												endforeach;
											?>
										<?php endif; ?>
									<?php
										$count = $count + 1;
										endforeach;
									?>
									<?php for ($i = $count; $i <= 4; $i++): ?>
										<tr>
											<td>
												<?php
													echo $this->Form->input('Details.'.$i.'.itemname', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '品番・品名',
													]);
												?>
											</td>
											<td>
												<?php
													echo $this->Form->input('Details.'.$i.'.quantity', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control quantity',
														'placeholder' => '数量',
														'style' => 'text-align: right',
														'id' => 'quantity'.$i,
													]);
												?>
											</td>
											<td>
												<?php
													echo $this->Form->input('Details.'.$i.'.unit', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '単位',
													]);
												?>
											</td>
											<td>
												<?php
													echo $this->Form->input('Details.'.$i.'.price', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control price',
														'placeholder' => '単価',
														'style' => 'text-align: right',
														'id' => 'price'.$i,
													]);
												?>
											</td>
											<td>
												<?php
													echo $this->Form->input('Details.'.$i.'.payment', [
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'form-control',
														'placeholder' => '金額',
														'readonly',
														'style' => 'text-align: right',
														'id' => 'payment'.$i,
													]);
												?>
											</td>
										</tr>
									<?php endfor; ?>
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
													'style' => 'text-align: right',
													'value' => $subtotal
												]);
											?>
										</td>
									</tr>
									<tr>
										<td colspan="3"></td>
										<?php $percent = $tax * 100; ?>
										<td align="center" valign="middle"><h4>消費税（<?php echo $percent; ?>％）</h4></td>
										<td>
											<?php
												echo $this->Form->input('consumptiontax', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control',
													'placeholder' => '金額',
													'readonly',
													'style' => 'text-align: right',
													'value' => $taxtotal
												]);
											?>
										</td>
									</tr>
									<tr>
										<th colspan="5">非課税明細</td>
									</tr>
									<?php
										for ($count = 0; $count <= 1; $count++){
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
														'style' => 'text-align: right',
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
														'style' => 'text-align: right',
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
														'style' => 'text-align: right',
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
													'style' => 'text-align: right',
													'value' => $total
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

		<?php endif; ?>

	</div>

    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->

</div><!--/.fluid-container-->
<?php echo $this->Html->script(['invoicessum.js']); ?>
<script type="text/javascript">
	$(".chosen").chosen({
		search_contains:true
	});
</script>
