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
							$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
							$this->Html->addCrumb('請求書作成', ['controller' => 'Co130s', 'action' => 'index']);
							$this->Html->addCrumb('請求書一覧', ['controller' => 'Co130s', 'action' => 'index2']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

		<?php echo $this->Form->create(false, ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>

			<fieldset disabled>

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
												echo $this->Form->input('shippername', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control',
													'value' => $invoice['Shippermaster']['shippername']
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
													'value' => $invoice['Invoice']['honorific'],
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
													'class' => 'form-control',
													'value' => $invoice['Invoice']['billingdate'],
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
													'value' => $invoice['Invoice']['invoicenumber']
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
													'value' => $invoice['Invoice']['subject']
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
													'class' => 'form-control',
													'value' => $invoice['Invoice']['paymentdate']
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
										<?php foreach ($invoice['Invoicedetail'] as $invoicedetail): ?>
											<?php if ($invoicedetail['tax_flg'] == 0): ?>
											<tr>
												<td>
													<?php
														echo $this->Form->input('itemname', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control',
															'value' => $invoicedetail['itemname']
														]);
													?>
												</td>
												<td>
													<?php
														echo $this->Form->input('quantity', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control',
															'placeholder' => '数量',
															'style' => 'text-align: right',
															'value' => $invoicedetail['quantity']
														]);
													?>
												</td>
												<td>
													<?php
														echo $this->Form->input('unit', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control',
															'value' => $invoicedetail['unit']
														]);
													?>
												</td>
												<td>
													<?php
														echo $this->Form->input('price', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control',
															'style' => 'text-align: right',
															'value' => $invoicedetail['price']
														]);
													?>
												</td>
												<td>
													<?php
														echo $this->Form->input('payment', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'form-control',
															'style' => 'text-align: right',
															'value' => $invoicedetail['payment']
														]);
													?>
												</td>
											</tr>
											<?php endif; ?>
										<?php endforeach; ?>
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
														'style' => 'text-align: right',
														'value' => $invoice['Invoice']['subtotal']
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
														'style' => 'text-align: right',
														'value' => $invoice['Invoice']['consumptiontax']
													]);
												?>
											</td>
										</tr>
										<tr>
											<th colspan="5">非課税明細</td>
										</tr>
										<?php foreach ($invoice['Invoicedetail'] as $invoicedetail): ?>
											<?php if ($invoicedetail['tax_flg'] == 1): ?>
												<tr>
													<td>
														<?php
															echo $this->Form->input('itemname', [
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'form-control',
																'value' => $invoicedetail['itemname']
															]);
														?>
													</td>
													<td>
														<?php
															echo $this->Form->input('quantity', [
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'form-control',
																'style' => 'text-align: right',
																'value' => $invoicedetail['quantity']
															]);
														?>
													</td>
													<td>
														<?php
															echo $this->Form->input('unit', [
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'form-control',
																'value' => $invoicedetail['unit']
															]);
														?>
													</td>
													<td>
														<?php
															echo $this->Form->input('price', [
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'form-control',
																'style' => 'text-align: right',
																'value' => $invoicedetail['price']
															]);
														?>
													</td>
													<td>
														<?php
															echo $this->Form->input('payment', [
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'form-control',
																'style' => 'text-align: right',
																'value' => $invoicedetail['payment']
															]);
														?>
													</td>
												</tr>
											<?php endif; ?>
										<?php endforeach; ?>
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
														'style' => 'text-align: right',
														'value' => $invoice['Invoice']['total']
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
												'value' => $invoice['Invoice']['billingnote']
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
												'value' => $invoice['Invoice']['payee']
											]);
										?>
									</div>
								</div>

							</div>

			</fieldset>

		<?php echo $this->Form->end(); ?>

							<div class="box-content">
								<?php
									echo $this->Html->link('<span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span>'.(' 一覧に戻る'), [
										'action' => 'index2',
									], [
										'escape' => false,
										'class' => 'btn btn-danger',
									]);
								?>
							</div>

						</div>
					</div>
				</div>

	</div>

    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->

</div><!--/.fluid-container-->
