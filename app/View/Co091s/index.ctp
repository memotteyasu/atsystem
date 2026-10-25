<!-- コンテナ運転手確認 -->
<!-- Co091s -->
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
					'empty'=>'未選択',
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
								<h2><i class="glyphicon glyphicon-info-sign"></i>乗務員確認状況</h2>
							</div>

							<div class="box-content">
								<div class="table-responsive">
									<table class="table table-striped table-bordered">
										<thead>
											<tr>
												<th></th>
												<th colspan="4">車両</th>
												<th>卸日</th>
												<th>荷主</th>
												<th>終了場所</th>
											</tr>
											<tr>
												<th>確認</th>
												<th>運転手</th>
												<th>助手</th>
												<th>作業場所（作業名称）</th>
												<th>作業日時</th>
												<th>コンテナNo.</th>
												<th>REF/BOOKING No.</th>
												<th>作業備考</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($ordertrans as $ordertran): ?>
												<tr class="info">
													<td></td>
													<td colspan="3">
														<?php
															$plateno = $ordertran['Carmaster']['plateno'];
															$carname = $plateno;
															if($ordertran['Ordertran']['confirmflg']==1){
																//確定済み
																$confirmcheck = '確定済み  '.substr($ordertran['Ordertran']['confirmdate'],0,16);
															}else{
																$confirmcheck = '';
															}
															echo $carname.' <span class="text-danger">('.$confirmcheck.')</span>';
														?>
													</td>
													<td>
													</td>
													<td>
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
														<td align="center"><?php
														if($processtran['confirmdate']){
															echo '済';
														}
														?></td>
														<td>
														<?php
															echo $this->Form->input('ordertranid', ['type' => 'hidden', 'value' => $ordertran['Ordertran']['id']]);
															echo $this->Form->input('id', ['type' => 'hidden', 'value' => $processtran['id']]);
															echo $this->Form->input('pdriver2id', ['type' => 'hidden', 'value' => $processtran['pdriver2id']]);
															if(isset($processtran['PDriver1']['usernameK'])){
																echo $processtran['PDriver1']['usernameK'];
															}else{
																echo '';
															}
														?>
														</td>
														<td>
														<?php
															echo $this->Form->input('ordertranid', ['type' => 'hidden', 'value' => $ordertran['Ordertran']['id']]);
															echo $this->Form->input('id', ['type' => 'hidden', 'value' => $processtran['id']]);
															echo $this->Form->input('pdriver2id', ['type' => 'hidden', 'value' => $processtran['pdriver2id']]);
															if(isset($processtran['PDriver2']['usernameK'])){
																echo $processtran['PDriver2']['usernameK'];
															}else{
																echo '';
															}
														?>
														</td>
														<td>
															<?php
																$workplace = $processtran['Workplace']['workplaceshortname'];
																$workname = '（<strong>'.$processtran['Workdetailmaster']['workname'].'</strong>）';
																echo $workplace.$workname;
															?>
														</td>
														<td>
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
														<td class="text-right"><?php echo $processtran['container_no']; ?></td>
														<td class="text-right"><?php echo $processtran['ref_no']; ?></td>
														<td><?php echo $processtran['workddetailcomment']; ?></td>
													</tr>
												<?php
												$xx++;
												endforeach; ?>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

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
//						echo $this->Html->link(('確定して運転手にメールを送信'), ['controller' => 'Co030s', 'action' => 'confirm','tyy' => $tyy, 'tmm' => $tmm, 'tdd' => $tdd, 'bid' => $bid], ['class' => 'btn btn-success']);
						echo '戻る';
//						echo '　';
/*
						echo $this->Html->link(('手配明細'), [
							'action' => 'pdftehai',
							$paramsDate2->format('Y-m-d'),
							4,
							$bid,
						], [
							'class' => ['btn btn-success'],
							'target'=>'_blank'
						]);
*/
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
