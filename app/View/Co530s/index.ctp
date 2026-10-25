<!-- コンテナ工程確認 -->
<!-- Co530s -->
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
							$this->Html->addCrumb('Top', ['controller' => 'PartnerTops', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>
			<?php
			echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);
					echo $this->Form->input('selectym1', [
						'div' => false,
						'label' => false,
						'type' => 'text',
						'class' => 'datepicker1',
						'onchange'=>'this.form.submit()',
						'default' => $paramsDate
					]);

				echo 'を表示　　営業所　';

				echo $this->Form->input('branchmasters_id',[
					'label'=>false,
					'type'=>'select',
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'default' => $branchmasters_id,
				]);

			echo $this->Form->end();
			?>

			<!-- 工程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>工程確認</h2>
							</div>

							<div class="box-content">
								<div class="table-responsive">
									<table class="table table-striped table-bordered">
										<thead>
											<tr>
												<th colspan="4">車両</th>
												<th></th>
												<th>卸日</th>
												<th>荷主</th>
												<th>終了場所</th>
											</tr>
											<tr>
												<th>運転手</th>
												<th>助手</th>
												<th>作業場所（<span style="color:#ff0000">作業名称</span>）</th>
												<th>作業日時</th>
												<th>商品詳細</th>
												<th>コンテナNo.</th>
												<th>REF/BOOKING No.</th>
												<th>作業備考</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($ordertrans as $ordertran): ?>
												<tr class="info">
													<td colspan="3" class="h4">
														<?php
															$plateno = $ordertran['Carmaster']['plateno'];
															$carname = $plateno.' ('.$ordertran['Carmaster']['Trailermaster']['trailerkind'].')';
															if($ordertran['Ordertran']['confirmflg']==1){
																//確定済み
																$confirmcheck = '確定済み  '.substr($ordertran['Ordertran']['confirmdate'],0,16);
															}else{
																$confirmcheck = '';
															}
															echo $carname.' <span class="text-danger"> '.$confirmcheck.' </span>';
														?>
													</td>
													<td>
													</td>
													<td>
													</td>
													<td class="h4">
														<?php
															if($ordertran['Ordertran']['deliverytime']){
																$deliverytime = new DateTime($ordertran['Ordertran']['deliverydate'].$ordertran['Ordertran']['deliverytime']);
																echo $deliverytime->format('Y-m-d H:i');
															}else{
																$deliverytime = new DateTime($ordertran['Ordertran']['deliverydate'].$ordertran['Ordertran']['deliverytime']);
																echo $deliverytime->format('Y-m-d');
															}
														?>
													</td>
													<td><?php echo $ordertran['Shippermaster']['shippershortname']; ?></td>
													<td><?php echo $ordertran['Workplace2']['workplaceshortname']; ?></td>
												</tr>
												<?php
												$xx = 1; //オーダー内でのカウンター
												foreach($ordertran['Processtran'] as $processtran): ?>
													<tr>
														<td>
														<?php
															if($processtran['PDriver1']){
																echo $processtran['PDriver1']['usernameK'];
															}else{
																echo '';
															}
														?>
														</td>
														<td>
														<?php
															if($processtran['PDriver2']){
																echo $processtran['PDriver2']['usernameK'];
															}else{
																echo '';
															}
														?>
														</td>
														<td class="h4">
															<?php
																$workplace = $processtran['Workplace']['workplaceshortname'];
																$workname = '（<span style="color:#ff0000"><strong>'.$processtran['Workdetailmaster']['workname'].'</strong></span>）';
																echo $workplace.$workname;
															?>
														</td>
														<td class="h4">
															<?php
																if($processtran['processstarttime']){
																	$workdate = new DateTime($processtran['processstartdate'].$processtran['processstarttime']);
																	echo $workdate->format('Y-m-d H:i');
																}else{
																	$workdate = new DateTime($processtran['processstartdate'].$processtran['processstarttime']);
																	echo $workdate->format('Y-m-d');
																}
															?>
														</td>
														<td class="h4">
															<?php
																if (isset($processtran['Productcontainer']['pdconname'])) {
																	echo $processtran['Productcontainer']['pdconname'];
																}
															?>
														</td>
														<td class="text-right h4"><?php echo $processtran['container_no']; ?></td>
														<td class="text-right h4"><?php echo $processtran['ref_no']; ?></td>
														<td><?php echo $processtran['workddetailcomment']; ?></td>
													</tr>
												<?php
												$xx++;
												endforeach; ?>
												<!-- //プロセスの最後でファイルを表示 -->
												<!-- ファイル名一覧 -->
												<?php foreach ($ordertran['Ordertranimage'] as $image): ?>
													<tr>
														<td colspan="4">
															<div class="col-sm-5">
																<?php
																	$kakutyoushi = [];
																	$kakutyoushi = explode('.',$image['img_file_name']);
																	$w_area = '/ordertranimages/resize/'.$image['id'].'.'.$kakutyoushi[1];
																	echo $this->Html->link($image['img_file_name'],$w_area,['class'=>'button','target'=>'_blank']);
																?>
															</div>
														</td>
														<td colspan="4">
															<?php echo ($image['imgcomment']); ?>
														</td>
													</tr>
												<?php endforeach; ?>

											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- /row -->

				<p>
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
						// echo $this->Html->link(('確定して運転手にメールを送信'), ['controller' => 'Co530s', 'action' => 'confirm','tyy' => $tyy, 'tmm' => $tmm, 'tdd' => $tdd, 'bid' => $bid], ['class' => 'btn btn-success']);
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
