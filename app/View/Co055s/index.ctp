<!-- コンテナ車両未確定受注一覧 -->
<!-- Co055s -->
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
				echo $this->element('searchForm_Date_Branch_Conte', [
					'branchmasters_id' => $branchgroups_id,	
					'text' => '以降を表示',
				]);
			?>


			<!-- 車両未確定受注内容工程 -->
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
											<th>開始</th>
											<th>場所</th>
											<th>終了</th>
											<th>場所</th>
											<th>回戦</th>
											<th>荷主</th>
											<th>(工程数)</th>
											<th>承認</th>
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
														if($ordertran['Ordertran']['loadingtime']){
															$loadingtime = new DateTime($ordertran['Ordertran']['loadingdate'].$ordertran['Ordertran']['loadingtime']);
															echo ($loadingtime->format('Y-m-d H:i'));
														}else{
															$loadingtime = new DateTime($ordertran['Ordertran']['loadingdate'].$ordertran['Ordertran']['loadingtime']);
															echo ($loadingtime->format('Y-m-d'));
														}
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Workplace1']['workplaceshortname']);
													echo '</td>';
													echo '<td>';
														if($ordertran['Ordertran']['deliverytime']){
															$deliverytime = new DateTime($ordertran['Ordertran']['deliverydate'].$ordertran['Ordertran']['deliverytime']);
															echo ($deliverytime->format('Y-m-d H:i'));
														}else{
															$deliverytime = new DateTime($ordertran['Ordertran']['deliverydate'].$ordertran['Ordertran']['deliverytime']);
															echo ($deliverytime->format('Y-m-d'));
														}
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Workplace2']['workplaceshortname']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Ordertran']['kaisen']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Shippermaster']['shippershortname']);
													echo '</td>';
													echo '<td>';
														echo $this->Html->link(('工程'), ['action' => 'add', 'controller' => 'Co050s', $ordertran['Ordertran']['id']], ['class' => ['btn btn-info']]);
														$processcount = 0;
														foreach ($ordertran['Processtran'] as $processtran) {
															if($processtran['delflg'] != 1){
																$processcount = $processcount + 1;
															}
														}
														echo '（'.$processcount.'）';
													echo '</td>';
													echo '<td class="text-danger text-center">';
														if($ordertran['Ordertran']['check1flg']==0){
															echo ' ';
														}else{
															echo '済';
														}
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

				<?php
					$paramsDate2 = new DateTime($paramsDate);
					$tyy = $paramsDate2->format('Y');
					$tmm = $paramsDate2->format('m');
					$tdd = $paramsDate2->format('d');
					if (!$branchmasters_id) {
						$bid = 0;
					} else {
						$bid = $branchmasters_id;
					}
					echo $this->Html->link(('一覧を印刷'), [
						'controller' => 'Co050s',
						'action' => 'pdf',
						'tyy' => $tyy,
						'tmm' => $tmm,
						'tdd' => $tdd,
						'bid' => $bid
					], [
						'class' => 'btn btn-success',
						'target'=>'_blank'
					]);
					echo '　';
					echo $this->Html->link(('手配明細'), [
						'action' => 'pdftehai',
						$paramsDate2->format('Y-m-d'),
						4,
						$bid,
					], [
						'class' => ['btn btn-success'],
						'target'=>'_blank'
					]);

				?>

					<?php // echo $this->Html->link(('新規登録'), array('action' => 'add'), array('class'=>array('btn btn-success'))); ?>

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
