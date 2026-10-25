<!-- 工程未登録 -->
<!-- Co051s -->
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
							$this->Html->addCrumb('受注工程詳細', ['controller' => 'Co050s', 'action' => 'add', $this->params['pass'][0]]);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

				<!-- 受注内容工程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>過去の工程一覧</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>オーダー及び工程内容</th>
										</tr>
									</thead>
									<tbody>
										<?php
											foreach ($orderlists as $orderlist) {
												echo '<tr>';
													echo '<td>';
														echo $this->Html->link(('同じ内容で作成'), ['action' => 'copy', 'controller' => 'Co051s', $orderlist['Ordertran']['id'], $this->params['pass'][0], $this->params['pass'][1], $this->params['pass'][2]], ['class' => ['btn btn-primary']]);
														$shippername = $shippermasters[$orderlist['Ordertran']['shippermasters_id']];
														$loadingname = $loadingmasters[$orderlist['Ordertran']['loadingmasters_id']];
														$deliveryname = $deliverymasters[$orderlist['Ordertran']['deliverymasters_id']];
														$pdname = $productdetailmasters[$orderlist['Ordertran']['productdetailmasters_id']];
														$ordername = $shippername.'　積み：'.$loadingname.'　卸：'.$deliveryname.'　商品：'.$pdname;
														echo $this->Html->link(($ordername), ['action' => 'add', 'controller' => 'Co050s', $orderlist['Ordertran']['id']]);
													echo '</td>';
												echo '</tr>';
												foreach ($processtrans as $processtran) {
													if ($orderlist['Ordertran']['id'] == $processtran['Processtran']['ordertrans_id']) {
														echo '<tr>';
															echo '<td style="padding-left:20px;">';
																$processdate = new DateTime($processtran['Processtran']['processstartdate'].$processtran['Processtran']['processstarttime']);
																$processname = $processdate->format('Y-m-d H:i').'　'.($processtran['Workdetailmaster']['workname']);
																echo $this->Html->link(($processname), ['action' => 'edit', 'controller' => 'Co050s', $processtran['Processtran']['id'], $processtran['Processtran']['ordertrans_id']]);
															echo '</td>';
														echo '</tr>';
													}
												}
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

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
