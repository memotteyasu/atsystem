<!-- コンテナ工程確認 -->
<!-- Ch530s -->
<div class="ch-container">
	<div class="row">

		<!-- left menu starts -->
			<?php echo $this->element('PartnerSidemenu'); ?>
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
				echo $this->element('searchForm_Date_No_Branch', [
					'text' => 'を表示',
				]);
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
												<th>車両</th>
												<th>乗務員</th>
												<th>助手</th>
												<th></th>
												<th>作業場所</th>
												<th>作業日時</th>
												<th>商品詳細</th>
												<th>コンテナNo.</th>
												<th>REF-No.</th>
												<th>作業備考</th>
											</tr>
										</thead>
										<tbody>
											<?php $xx = 1; ?>
											<?php foreach ($processtrans as $processtran): ?>
												<tr>
													<td><?php 
													//carmasterを読み込む
													$carmaster = ClassRegistry::init('Carmaster');
													$carmasters = $carmaster->find('first',[
														'fields'=>[
															'Carmaster.plateno'
														],
														'conditions'=>[
															'Carmaster.id'=>$processtran['Ordertran']['carmasters_id']
														],
														'recursive'=>0
													]);
													echo $carmasters['Carmaster']['plateno'];
													 ?></td>
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
																	'data-oid' => $processtran['Processtran']['ordertrans_id'],
																	'data-prid' => $processtran['Processtran']['id'],
																	'data-xxkey' => $xx,
																	'value' => $processtran['Processtran']['pdriver1id'],
																]);
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
																	'data-oid' => $processtran['Processtran']['ordertrans_id'],
																	'data-prid' => $processtran['Processtran']['id'],
																	'data-xxkey' => $xx,
																	'value' => $processtran['Processtran']['pdriver2id'],
																]);
															echo $this->Form->end();
														?>

													</td>
													<td><font class="text-danger"><?php echo $processtran['Workdetailmaster']['workname']; ?></font></td>
													<td><?php echo $processtran['Workplace']['workplaceshortname']; ?></td>
													<td><?php echo substr($processtran['Processtran']['processstarttime'],0,5); ?> <?php echo $processtran['Processtran']['timenote'] ?></td>
													<td><?php echo  $processtran['Productcontainer']['pdconname'] ?></td>
													<td><?php echo  $processtran['Processtran']['container_no'] ?></td>
													<td><?php echo  $processtran['Processtran']['ref_no'] ?></td>
													<td><?php echo $processtran['Processtran']['workddetailcomment']; ?></td>
												</tr>
												<?php $xx++; ?>
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
//						echo $this->Html->link(('確定して乗務員にメールを送信'), ['controller' => 'Ch530s', 'action' => 'confirm','tyy' => $tyy, 'tmm' => $tmm, 'tdd' => $tdd, 'bid' => $bid], ['class' => 'btn btn-info']);
						echo '　';
						echo $this->Html->link(('手配明細'), [
							'action' => 'pdftehaipro',
							$paramsDate2->format('Y-m-d'),
							2,
							$bid,
						], [
							'class' => ['btn btn-success'],
							'target'=>'_blank'
						]);
					?>
				</p>

			<!-- 車両情報・ドライバ休み -->
			<div class="row">
				    <div class="box col-lg-6 col-md-6 col-sm-6 col-xs-6">
				  	</div>
				    <div class="box col-lg-6 col-md-6 col-sm-6 col-xs-6">
					</div>
			</div>
			<!-- /車両情報・ドライバ休み -->

			<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
<script>
$(function() {

    $('.pd1chg').on('change', function() {
        var ordertranid = $(this).data('oid');
		var processtranid = $(this).data('prid');
		var xxkey = $(this).data('xxkey');
		var pd1id = $(this).val();
		$.ajax({
			url: '/Ch530s/ajax_chg',
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
			url: '/Ch530s/ajax_chg2',
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
