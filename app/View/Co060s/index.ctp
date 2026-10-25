<!-- コンテナ受注一覧 -->
<!-- Co060s -->
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
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>
			<?php
			echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);
				$dete = strtotime(date('Y-m-d'));
				echo $this->Form->input('selectym1', [
					'div'=>false,
					'label'=>false,
					'type' => 'date',
					'dateFormat' => 'YMD',
					'monthNames' => false,
					'minYear' => date('Y')-1,
					'maxYear' => date('Y')+1,
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'style'=>'width:70px',
				]);
			?>以降を表示


			<!-- 受注内容工程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>受注一覧（工程）</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>車両</th>
											<th>積日</th>
											<th>卸日</th>
											<th>回戦</th>
											<th>荷主</th>
											<th>積地</th>
											<th>納入先</th>
											<th>(工程数)</th>
										</tr>
									</thead>
									<tbody>

										<?php
											foreach ($ordertrans as $ordertran) {
												echo '<tr>';
													echo '<td>';
														echo ($ordertran['Carmaster']['plateno']).'（'.($ordertran['Productdetailmaster']['pdname']).'）';
													echo '</td>';
													echo '<td>';
														$loadingtime = new DateTime($ordertran['Ordertran']['loadingdate'].$ordertran['Ordertran']['loadingtime']);
														echo ($loadingtime->format('Y-m-d H:i'));
													echo '</td>';
													echo '<td>';
														$deliverytime = new DateTime($ordertran['Ordertran']['deliverydate'].$ordertran['Ordertran']['deliverytime']);
														echo ($deliverytime->format('Y-m-d H:i'));
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Ordertran']['kaisen']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Shippermaster']['shippershortname']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Loadingmaster']['loadingshortname']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Deliverymaster']['deliveryshortname']);
													echo '</td>';
													echo '<td>';
														echo $this->Html->link(('工程'), ['action' => 'add', 'controller' => 'Co060s', $ordertran['Ordertran']['id']], ['class' => ['btn btn-info']]);
														$processcount = 0;
														foreach ($ordertran['Processtran'] as $processtran) {
															if($processtran['delflg'] != 1){
																$processcount = $processcount + 1;
															}
														}
														echo '（'.$processcount.'）';
													echo '</td>';
												echo '</tr>';
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<?php //echo $this->element('pager')?>

				<p>
					<?php //echo $this->Html->link(('新規登録'), array('action' => 'add'), array('class'=>array('btn btn-success'))); ?>
				</p>

			<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
<script>
$(function(){
  $('td[data-href]', 'table.table-clickable').on('click', function(){
    location.href = $(this).data('href');
  });
});
</script>
