<!-- コンテナ運転手割振 -->
<!-- Ch090s -->
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
				echo $this->element('searchForm_Date_Branchgroup', [
					'branchmasters_id' => $branchgroups_id,	
					'text' => 'を表示',
				]);
			?>

			<!-- 受注内容工程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>未確認者一覧</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>車両</th>
											<th>確認状態</th>
											<th></th>
										</tr>
									</thead>
									<tbody>

										<?php foreach ($ordertrans as $ordertran): ?>
											<tr class="info">
												<th colspan="3">
													<?php echo ($ordertran['Carmaster']['plateno']).'（'.($ordertran['Productdetailmaster']['pdname']).'）'; ?>
												</td>
											</tr>
											<tr>
												<td>
												乗務員：<?php echo $ordertran['Driver1']['usernameK']; ?>
												</td>
												<td>
													<?php
														if ($ordertran['Ordertran']['dr1confirmflg'] == 0) {
															echo '<font color="#ff0000">未確認</font>';
														} else {
															$time = new DateTime ($ordertran['Ordertran']['dr1confirmdate']);
															echo '確認済（'.$time->format('Y年m月d日　H:i').'）';
														}
													?>
												</td>
												<td>
													<?php
														if ($ordertran['Ordertran']['dr1confirmflg'] == 0) {
															$paramsDate2 = new DateTime($paramsDate);
															$tyy = $paramsDate2->format('Y');
															$tmm = $paramsDate2->format('m');
															$tdd = $paramsDate2->format('d');
															echo $this->Form->postLink('<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>'.(' メールを送信'), [
																'action' => 'sendmail',
																$ordertran['Ordertran']['driver1id'],
																$tyy,
																$tmm,
																$tdd,
															],
															[
																'escape' => false,
																'class' => 'btn btn-primary',
																'confirm' => __('メールを送信しますか？')
															]);
														}
													?>
												</td>
											</tr>
											<tr>
												<td>
													助手　：<?php echo $ordertran['Driver2']['usernameK']; ?>
												</td>
												<td>
													<?php
														if ($ordertran['Ordertran']['dr2confirmflg'] == 0) {
															echo '<font color="#ff0000">未確認</font>';
														} else {
															$time = new DateTime ($ordertran['Ordertran']['dr2confirmdate']);
															echo '確認済（'.$time->format('Y年m月d日　H:i').'）';
														}
													?>
												</td>
												<td>
													<?php
														if ($ordertran['Ordertran']['dr2confirmflg'] == 0) {
															$paramsDate2 = new DateTime($paramsDate);
															$tyy = $paramsDate2->format('Y');
															$tmm = $paramsDate2->format('m');
															$tdd = $paramsDate2->format('d');
															echo $this->Form->postLink('<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>'.(' メールを送信'), [
																'action' => 'sendmail',
																$ordertran['Ordertran']['driver2id'],
																$tyy,
																$tmm,
																$tdd,
															],
															[
																'escape' => false,
																'class' => 'btn btn-primary',
																'confirm' => __('メールを送信しますか？')
															]);
														}
													?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

			<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
