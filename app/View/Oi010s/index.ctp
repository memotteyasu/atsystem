<!-- 受注管理 -->
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
							$this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
							$this->Html->addCrumb('受注カレンダー', ['controller' => 'Oi000s', 'action' => 'index']);
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
								<h2><i class="glyphicon glyphicon-info-sign"></i>受注内容工程未登録</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th><?php echo $this->Paginator->sort('loadingdate', '積日'); ?></th>
											<th><?php echo $this->Paginator->sort('deliverydate', '卸日'); ?></th>
											<th><?php echo $this->Paginator->sort('productnote', 'オーダー'); ?></th>
											<th><?php echo $this->Paginator->sort('truckkinds_id', '車両'); ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody>

										<?php foreach ($ordertrans as $ordertran): ?>
											<tr>
											<?php
												//debug($ordertran);
												if (!$ordertran['Processtran']){
													echo '<td>';
														echo ($ordertran['Ordertran']['loadingdate']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Ordertran']['deliverydate']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Ordertran']['productnote']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Truckkind']['Carmaster']['plateno']).' '.($ordertran['Shippermaster']['shippername']).'/'.($ordertran['Productdetailmaster']['pdname']);
													echo '</td>';
													echo '<td>';
														echo $this->Html->link(('工程登録'), ['action' => 'add', $ordertran['Ordertran']['id']], ['class' => ['btn btn-info']]);
													echo '</td>';

												}
											 ?>
											 </tr>
										<?php endforeach; ?>
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

	<hr>

</div><!--/.fluid-container-->

<script type="text/javascript">
jQuery(function(){
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional[ "ja" ] );
	var dateFormat = 'yy/mm/dd';
	jQuery('.datepicker').datepicker({
		dateFormat: 'yy/mm/dd(DD)'
	});

	jQuery(document).ready(function(){
			jQuery.datetimepicker.setLocale('ja');
			jQuery('input.datetimepicker1').datetimepicker({
				format:'Y-m-d H:i',
				step:30,
			});
	});
});
</script>
