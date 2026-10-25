<div class="ch-container">

	<div class="row">

	<!-- left menu starts -->
		<?php echo $this->element('DriverSidemenu'); ?>
	<!-- left menu ends -->

	<div id="content" class="col-lg-10 col-sm-10">

		<!-- content starts -->
			<div>
				<ul class="breadcrumb">
					<li>
         			   <?php
							$this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<?php if(!$ordertrans1 && !$ordertrans2): ?>
				<div class="alert alert-warning" role="alert">データが存在しません。</div>

			<?php else: ?>

			<!-- 運行日程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<?php
									$today = new DateTime($paramsDate->format('Y-m-d'));
									echo $today->format('Y年m月d日').'('.$week[$today->format('w')].')';
									echo '　運転手';
								?>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>状態</th>
											<th>車両</th>
											<th>荷主</th>
											<th>積地</th>
											<th>卸地</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($ordertrans1 as $ordertran): ?>
											<tr>
												<td>
													<?php
														if ($ordertran['Ordertran']['dr1confirmflg'] == 0) {
															echo '<font color="#ff0000">未確認</font>';
														} else {
															echo '確認済';
														}
													?>
												</td>
												<td>
													<?php echo ($ordertran['Carmaster']['plateno']).'（'.($ordertran['Productdetailmaster']['pdname']).'）'; ?>
												</td>
												<td>
													<?php echo ($ordertran['Shippermaster']['shippershortname']); ?>
												</td>
												<td>
													<?php echo ($ordertran['Loadingmaster']['loadingname']); ?>
												</td>
												<td>
													<?php echo ($ordertran['Deliverymaster']['deliveryname']); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<?php
									$today = new DateTime($paramsDate->format('Y-m-d'));
									echo $today->format('Y年m月d日').'('.$week[$today->format('w')].')';
									echo '　助手';
								?>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>状態</th>
											<th>車両</th>
											<th>荷主</th>
											<th>積地</th>
											<th>卸地</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($ordertrans2 as $ordertran): ?>
											<tr>
												<td>
													<?php
														if ($ordertran['Ordertran']['dr2confirmflg'] == 0) {
															echo '<font color="#ff0000">未確認</font>';
														} else {
															echo '確認済';
														}
													?>
												</td>
												<td>
													<?php echo ($ordertran['Carmaster']['plateno']).'（'.($ordertran['Productdetailmaster']['pdname']).'）'; ?>
												</td>
												<td>
													<?php echo ($ordertran['Shippermaster']['shippershortname']); ?>
												</td>
												<td>
													<?php echo ($ordertran['Loadingmaster']['loadingname']); ?>
												</td>
												<td>
													<?php echo ($ordertran['Deliverymaster']['deliveryname']); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<p>
					<?php
						if(!$confirmorder1 || !$confirmorder2) {
							echo $this->Html->link('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'.(' 確認する'), [
								'action' => 'drconfirm'
							],
							[
								'escape' => false,
								'class' => 'btn btn-primary',
							]);
						} else {
							echo '<button type="button" class="btn btn-primary" disabled="disabled"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> 確認済</button>';
						}
					?>
				</p>

				<?php endif; ?>

				<!-- /運行日程 -->
			</div>
    	</div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
