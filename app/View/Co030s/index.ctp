<!-- コンテナ工程確認 -->
<!-- Co030s -->
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
				echo $this->element('searchForm_Date_Branch', [
					'branchmasters_id' => $branchmasters_id,	
					'text' => 'を表示',
				]);
			?>
				<p style="padding-top:10px;">
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
//						echo $this->Html->link(('確定して乗務員にメールを送信'), ['controller' => 'Co030s', 'action' => 'confirm','tyy' => $tyy, 'tmm' => $tmm, 'tdd' => $tdd, 'bid' => $bid], ['class' => 'btn btn-info']);
						echo '　';
						echo $this->Html->link(('手配明細（営業所内）'), [
							'action' => 'pdftehai',
							$paramsDate2->format('Y-m-d'),
							4,
							$bid,
						], [
							'class' => ['btn btn-success'],
							'target'=>'_blank'
						]);
						echo '　';
						echo $this->Html->link(('手配明細（荷主順）'), [
							'action' => 'pdftehaini',
							$paramsDate2->format('Y-m-d'),
							4,
							$bgid,
						], [
							'class' => ['btn btn-warning'],
							'target'=>'_blank'
						]);
						echo '　';
						echo $this->Html->link(('手配明細（運転手）'), [
							'action' => 'pdftehaipro',
							$paramsDate2->format('Y-m-d'),
							4,
							$bid,
						], [
							'class' => ['btn btn-primary'],
							'target'=>'_blank'
						]);
						echo '　';
						echo $this->Html->link(('乗務員を確定する'), ['controller' => 'Ch030s', 'action' => 'confirm','tyy' => $tyy, 'tmm' => $tmm, 'tdd' => $tdd, 'bid' => $bid], ['class' => 'btn btn-info']);
					?>
				</p>

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
												<th>乗務員</th>
												<th>助手</th>
												<th>作業場所（<span style="color:#ff0000">作業名称</span>）</th>
												<th>作業日時</th>
												<th>商品詳細</th>
												<th>コンテナNo.</th>
												<th></th>
												<th>作業備考</th>
											</tr>
										</thead>
										<tbody>
											<?php $i = 1; ?>
											<?php foreach ($ordertrans as $ordertran): ?>
												<tr class="info">
													<td colspan="3">
														<?php															$plateno = $ordertran['Carmaster']['plateno'];
															$carname = $plateno.' ('.$ordertran['Carmaster']['Trailermaster']['trailerkind'].')';
															//ここに営業所を印字
															$eigyousyo = '--<small class="text-danger">'.$ordertran['Branchmaster']['branchname'].'</small>';
															$carname = $carname.$eigyousyo;
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
														<td>
														<?php
															echo $this->Form->create('Processtran', ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);
																echo $this->Form->input('pdriver1id', [
																	'div'=>false,
																	'label'=>false,
																	'type' => 'select',
																	'empty'=>'未定',
																	'options' => $userlist,
																	'data-rel'=>'chosen',
																	'class' => 'pd1chg',
																	'data-oid' => $ordertran['Ordertran']['id'],
																	'data-prid' => $processtran['id'],
																	'data-xxkey' => $xx,
																	'value' => $processtran['pdriver1id'],
																	//'onchange'=>'this.form.submit()',
																]);
																//echo $this->Form->input('driver2id', ['type' => 'hidden', 'value' => $ordertran['Ordertran']['driver2id']]);
															echo $this->Form->end();
														?>
														</td>
														<td>
														<?php
															echo $this->Form->create('Processtran', ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);
																echo $this->Form->input('pdriver2id', [
																	'div'=>false,
																	'label'=>false,
																	'type' => 'select',
																	'empty'=>'未定',
																	'options' => $userlist,
																	'data-rel'=>'chosen',
																	'class' => 'pd2chg',
																	'data-oid' => $ordertran['Ordertran']['id'],
																	'data-prid' => $processtran['id'],
																	'data-xxkey' => $xx,
																	'value' => $processtran['pdriver2id'],
																]);
															echo $this->Form->end();
														?>
														</td>
														<td>
															<?php
																if($processtran['workplaces_id']){
																	$workplace = $processtran['Workplace']['workplaceshortname'];
																	$workname = '（<span style="color:#ff0000"><strong>'.$processtran['Workdetailmaster']['workname'].'</strong></span>）';
																	echo $workplace.$workname;
																}else{
																	$workname = '（<span style="color:#ff0000"><strong>'.$processtran['Workdetailmaster']['workname'].'</strong></span>）';
																	echo $workname;
																}
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
														<td>
															<?php
																if (isset($processtran['Productcontainer']['pdconname'])) {
																	echo $processtran['Productcontainer']['pdconname'];
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
												<?php $i++; ?>
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
						echo '　';
						echo $this->Html->link(('手配明細（営業所内）'), [
							'action' => 'pdftehai',
							$paramsDate2->format('Y-m-d'),
							4,
							$bid,
						], [
							'class' => ['btn btn-success'],
							'target'=>'_blank'
						]);
						echo '　';
						echo $this->Html->link(('手配明細（荷主順）'), [
							'action' => 'pdftehaini',
							$paramsDate2->format('Y-m-d'),
							4,
							$bid,
						], [
							'class' => ['btn btn-warning'],
							'target'=>'_blank'
						]);
						echo '　';
						echo $this->Html->link(('手配明細（運転手）'), [
							'action' => 'pdftehaipro',
							$paramsDate2->format('Y-m-d'),
							4,
							$bid,
						], [
							'class' => ['btn btn-primary'],
							'target'=>'_blank'
						]);
						echo '　';
						echo $this->Html->link(('乗務員を確定する'), ['controller' => 'Ch030s', 'action' => 'confirm','tyy' => $tyy, 'tmm' => $tmm, 'tdd' => $tdd, 'bid' => $bid], ['class' => 'btn btn-info']);
					?>
				</p>

			<!-- 車両情報・ドライバ休み -->
			<div class="row">
				    <div class="box col-lg-6 col-md-6 col-sm-6 col-xs-6">
					    <div class="box-inner">
						    <div class="box-header well">空き乗務員
						    </div>
						    <div class="box-content">
								<ul>
										<?php
											foreach($vacancyusers2 as $vacancyuser) {
												echo '<li>';
														echo $vacancyuser;
												echo '</li>';
											}
										?>
								</ul>
							</div>
				  	    </div>
				  	</div>
				    <div class="box col-lg-6 col-md-6 col-sm-6 col-xs-6">
					    <div class="box-inner">
						    <div class="box-header well">乗務員休み
						    </div>
						    <div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<tbody>
										<?php
											foreach($driversrest as $driver) {
												echo '<tr>';
													echo '<td>';
														echo ($driver['User']['usernameK']);
														echo '/';
														echo ($driver['Branchmaster']['branchname']);
													echo '</td>';
													echo '<td>';
														$date = new DateTime($driver['Kintaitran']['kintaidate']);
														echo ($date->format('Y年m月d日')).($driver['Flgmaster']['flgname']);
													echo '</td>';
												echo '</tr>';
											}
										?>
									</tbody>
								</table>
							</div>
				  		</div>
					    <div class="box-inner">
							<div class="box-header well">乗務員要望
							</div>
							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
										<tbody>
											<?php
												foreach($employeeholidays as $employeeholiday) {
													echo '<tr>';
														echo '<td>';
															echo ($employeeholiday['User']['usernameK']);
														echo '</td>';
														echo '<td>';
															$date = new DateTime($employeeholiday['Employeeholiday']['applicationdate']);
															echo ($date->format('Y年m月d日')).' '.$employeeholiday['Flgmaster2']['flgname'].' ('.($employeeholiday['Flgmaster']['flgname'].')');
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
			<!-- /車両情報・ドライバ休み -->

			<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
<!-- オーダーの２件目の１つ目を強制にセット　-->
<script>
$(function() {

    $('.pd1chg').on('change', function() {
        var ordertranid = $(this).data('oid');
		var processtranid = $(this).data('prid');
		var xxkey = $(this).data('xxkey');
		var pd1id = $(this).val();
		$.ajax({
			url: '/Co030s/ajax_chg',
            type: 'POST',
			dataType: 'text',
			data: {
				'pdriverid': pd1id,
				'oid': ordertranid,
				'prid': processtranid,
				'xxkey': xxkey
			},
            dataType: 'json',
            success: function(response) {
                // 成功時の処理
				console.log(response);
				return json_encode('ok', JSON_UNESCAPED_UNICODE);
            },
			error: function(xhr, status, error) {
				console.log('通信エラー発生！');
				console.log('ステータス:', status);     // → "error", "timeout" など
				console.log('HTTPコード:', xhr.status); // → 404, 500 など
				console.log('レスポンス:', xhr.responseText); // サーバーからのメッセージ
				console.log('エラー内容:', error);     // 例: "Internal Server Error"
			}
        });
    });

    $('.pd2chg').on('change', function() {
        var ordertranid = $(this).data('oid');
		var processtranid = $(this).data('prid');
		var xxkey = $(this).data('xxkey');
		var pd2id = $(this).val();
		$.ajax({
			url: '/Co030s/ajax_chg2',
            type: 'POST',
			dataType: 'text',
			data: {
				'pdriverid': pd2id,
				'oid': ordertranid,
				'prid': processtranid,
				'xxkey': xxkey
			},
            dataType: 'json',
            success: function(response) {
                // 成功時の処理
				console.log(response);
				return json_encode('ok', JSON_UNESCAPED_UNICODE);
            },
			error: function(xhr, status, error) {
				console.log('通信エラー発生！');
				console.log('ステータス:', status);     // → "error", "timeout" など
				console.log('HTTPコード:', xhr.status); // → 404, 500 など
				console.log('レスポンス:', xhr.responseText); // サーバーからのメッセージ
				console.log('エラー内容:', error);     // 例: "Internal Server Error"
			}
        });
    });
});
</script>
