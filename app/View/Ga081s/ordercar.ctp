<!-- 請求データ出力（車両別） -->
<!-- Ga081s -->
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
			<h4>
			<?php
				echo "<b>".$date->format('Y-m-d')."</b>";

				echo '　から　';

				echo $date2->format('Y-m-d');
	
				echo 'までの請求日　(荷主)　';

				echo $shippershortname['Shippermaster']['shippershortname'];

				echo '　(営業所)　';

				echo $branchgroups['Branchgroup']['branchgroupname'];
			?>
			</h4>

			<!-- 受注内容工程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>請求データ作成</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>車両</th>
											<th>金額</th>
										</tr>
									</thead>
									<tbody>

										<?php
											foreach ($ordertrans as $ordertran) {
												echo '<tr>';
												echo '<td>';
												echo $ordertran['Carmaster']['plateno'];
												echo '</td>';
												echo '<td>';
												echo number_format($ordertran[0]['sumprice']);
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
				<?php echo $this->Html->link(('戻る'), ['controller' => 'Ga081s', 'action' => 'index'], ['class'=>array('btn btn-danger')]); ?>


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
$(".chosen").chosen({
    search_contains:true
});
</script>
