<!-- 走行実績 -->
<!-- Oi040s/edit -->
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
									$this->Html->addCrumb('走行実績', ['controller' => 'Oi040s', 'action' => 'index']);
									echo $this->element('breadcrumbs');
								?>
								<?php echo $this->Flash->render(); ?>
							</li>
						</ul>
					</div>

					<!--
					検索窓はここに記述
					 -->

					<!-- コンテンツ -->
						<div class="row">

							<!--<form class="form-horizontal">-->
							<?php echo $this->Form->create('Kintaitran', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

								<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						    	<?php
									$today = new DateTime($date);
									echo $today->format('Y年m月d日').'('.$week[$today->format('w')].')'.' 走行実績';
									if($calen != null){
										switch($calen['Calendarmaster']['restflg']){
											case 1:
												echo ' <b class="text-info">(法定外休日)</b>';
												break;
											case 2:
												echo ' <b class="text-danger">(法定休日)</b>';
												break;
										}
									}
						    	?>
						    </div>

							<?php
								foreach($ordertrans1 as $ordertran1) {
									echo '<div class="box-content">';
										echo '<table class="table table-bordered bootstrap-datatable">';
										echo '<tr>';
										echo '<th colspan="2">'.$ordertran1['Ordertran']['kaisen'].'回戦　( 運転手 ) '.$ordertran1['Driver1']['usernameK'].' 　( 助手 ) '.$ordertran1['Driver2']['usernameK'].'</th>';
										echo '</tr>';
										echo '<tr>';
										echo '<td colspan="2">荷主：'.$ordertran1['Shippermaster']['shippershortname'].'　( 開始 )'.$ordertran1['Workplace1']['workplaceshortname'].'　( 終了 )'.$ordertran1['Workplace2']['workplaceshortname'].'　商品：'.$ordertran1['Productdetailmaster']['pdname'].'</td>';
										echo '</tr>';
										echo '</table>';
										echo '<table class="table table-striped table-bordered bootstrap-datatable">';
											echo '<thead>';
												echo '<tr>';
													echo '<th>';
														echo '作業名称';
													echo '</th>';
													echo '<th>';
														echo '作業場所';
													echo '</th>';
													echo '<th>';
														echo '長距離手当';
													echo '</th>';
													echo '<th>';
														echo '営業キロ';
													echo '</th>';
													echo '<th>';
														echo '備考';
													echo '</th>';
													echo '<th>';
														echo '開始日時';
													echo '</th>';
												echo '</tr>';
											echo '</thead>';
											echo '<tbody>';

												foreach($ordertran1['Processtran'] as $dprocess) {
													echo '<tr>';
														echo '<td>';
															echo ($dprocess['Workdetailmaster']['workname']);
														echo '</td>';
														echo '<td>';
															echo ($dprocess['Workplace']['workplaceshortname']);
														echo '</td>';
														echo '<td>';
															echo ($dprocess['Workplace']['longteate_summer_tan']);
														echo '</td>';
														echo '<td>';
															echo ($dprocess['Workplace']['eigyokilo']);
														echo '</td>';
														echo '<td>';
															echo ($dprocess['workddetailcomment']);
														echo '</td>';
														echo '<td>';
															$starttime = new DateTime($dprocess['processstartdate'].$dprocess['processstarttime']);
															echo $starttime->format('Y-m-d H:i');
														echo '</td>';
													echo '</tr>';
												}

											echo '</tbody>';
										echo '</table>';

									echo '</div>';
								}
							?>
							<?php
								foreach($ordertrans2 as $ordertran2) {
									echo '<div class="box-content">';
										echo '<table class="table table-bordered bootstrap-datatable">';
										echo '<tr>';
										echo '<th colspan="2">'.$ordertran2['Ordertran']['kaisen'].'回戦　(運転手) '.$ordertran2['Driver1']['usernameK'].' 　(助手) '.$ordertran2['Driver2']['usernameK'].'</th>';
										echo '</tr>';
										echo '<tr>';
										echo '<td colspan="2">荷主：'.$ordertran2['Shippermaster']['shippershortname'].'　積地：'.$ordertran2['Loadingmaster']['loadingname'].'　卸地：'.$ordertran2['Deliverymaster']['deliveryname'].'　商品：'.$ordertran2['Productdetailmaster']['pdname'].'</td>';
										echo '</tr>';
										echo '</table>';
										echo '<table class="table table-striped table-bordered bootstrap-datatable">';
											echo '<thead>';
												echo '<tr>';
													echo '<th>';
														echo '作業名称';
													echo '</th>';
													echo '<th>';
														echo '備考';
													echo '</th>';
													echo '<th>';
														echo '開始日時';
													echo '</th>';
													echo '<th>';
														echo '終了日時';
													echo '</th>';
												echo '</tr>';
											echo '</thead>';
											echo '<tbody>';
												foreach($ordertran2['Processtran'] as $dprocess) {
													echo '<tr>';
														echo '<td>';
															echo ($dprocess['Workdetailmaster']['workname']);
														echo '</td>';
														echo '<td>';
															echo ($dprocess['workddetailcomment']);
														echo '</td>';
														echo '<td>';
															$starttime = new DateTime($dprocess['processstarttime']);
															echo $starttime->format('H:i');
														echo '</td>';
														echo '<td>';
															$endtime = new DateTime($dprocess['processendtime']);
															echo $endtime->format('H:i');
														echo '</td>';
													echo '</tr>';
												}
											echo '</tbody>';
										echo '</table>';

									echo '</div>';
								}
							?>
						</div>
<hr>
									<h4><?php echo $userdata['User']['usernameK']; ?>　：
										<?php
												echo $this->Form->input('flgmasters_id',[
												'label'=>false,
												'div'=>false,
												'type'=>'select',
												'style'=>'height:30px',
												'value'=>11,
												]);
											?>
									</h4>
									<div class="box-inner">
										<div class="box-header well">
											<h2>勤怠登録</h2>
										</div>
									<div class="form-group">
										<div class="col-lg-6">
											<label class="col-sm-12 text-danger">タイムレコーダー</label>

											<div class="row">
												<label class="col-sm-3 control-label">始業</label>
												<div class="col-sm-3">
													<?php
														echo $this->Form->input('datestart',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'type'=>'text',
														]);
													?>
												</div>
												<div class="col-sm-2">
													<?php
														echo $this->Form->input('timestart',[
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'timepicker form-control',
															'timeFormat'=>24,
														]);
													?>
												</div>
											</div>

											<div class="row">
												<label class="col-sm-3 control-label">終業</label>
												<div class="col-sm-3">
													<?php
														echo $this->Form->input('dateend',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'type'=>'text',
														]);
													?>
												</div>
												<div class="col-sm-2">
													<?php
														echo $this->Form->input('timeend',[
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'timepicker form-control',
															'timeFormat'=>24,
														]);
													?>
												</div>
											</div>

										</div>

										<div class="col-lg-6">
											<label class="col-sm-12 text-danger">タコメーター時間</label>

											<div class="row">
												<label class="col-sm-3 control-label">出庫</label>
												<div class="col-sm-3">
													<?php
														echo $this->Form->input('tako_datestart',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'type'=>'text',
														]);
													?>
												</div>
												<div class="col-sm-2">
													<?php
														echo $this->Form->input('tako_timestart',[
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'timepicker form-control',
														]);
													?>
												</div>
											</div>

											<div class="row">
												<label class="col-sm-3 control-label">帰庫</label>
												<div class="col-sm-3">
													<?php
														echo $this->Form->input('tako_dateend',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'type'=>'text',
														]);
													?>
												</div>
												<div class="col-sm-2">
													<?php
														echo $this->Form->input('tako_timeend',[
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'timepicker form-control',
														]);
													?>
												</div>
											</div>

										</div>

										<div class="col-lg-11">
											<div class="row">
												<label class="col-sm-5 control-label">休憩</label>
												<div class="col-sm-3">
													<?php
														echo $this->Form->input('kyukeitime',[
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'timepicker form-control',
														]);
													?>
												</div>
											</div>
										</div>

									</div>

									<hr>
									<div class="form-group">
										<div class="col-lg-11">
											<div class="row">
												<label class="col-sm-2 control-label">拘束時間</label>
												<div class="col-sm-2">
													<?php
														echo $this->Form->input('so_kinmu_jikan',[
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'timepicker form-control',
															'style' => 'width:100px',
														]);
													?>
												</div>
												<label class="col-sm-2 control-label">運転時間</label>
												<div class="col-sm-2">
													<?php
														echo $this->Form->input('drivingtime',[
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'timepicker form-control',
															'style' => 'width:100px',
														]);
													?>
												</div>
												<label class="col-sm-2 control-label">アイドル</label>
												<div class="col-sm-2">
													<?php
														echo $this->Form->input('idlingtime',[
															'label' => false,
															'type' => 'text',
															'div' => false,
															'class' => 'timepicker form-control',
															'style' => 'width:100px',
														]);
													?>
												</div>
											</div>
										</div>
									</div>

									<hr>
									<div class="form-group">

										<div class="col-lg-12">
											<label class="col-sm-12 text-danger">残業時間</label>
											<label class="col-sm-1 control-label">平日残業</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('heijitsuzangyo',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">平日深夜</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('heijitsushinya',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">休日残業</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('kyujitsuzangyo',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">休日深夜</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('kyujitsushinya',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
										</div>

										<div class="col-lg-12">
											<label class="col-sm-1 control-label">法定残業</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('houteikyujitsu',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">法定深夜</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('houteikyujitsushinya',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
										</div>

									</div>

									<hr>
									<div class="form-group">
										<div class="col-lg-12">
											<label class="col-sm-12 text-danger">手当</label>
											<label class="col-sm-1 control-label">走行キロ</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('soukoukiro',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">営業キロ</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('eigyokiro',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">支払数量</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('shiharaisuryo',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">長距離</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('chokyori_teate',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
										</div>

										<div class="col-lg-12">
											<label class="col-sm-1 control-label">回次</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('kaiji_teate',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">多箇所</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('takasyo_teate',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">乗務</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('jyoumu_teate',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">チェーン</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('chain_teate',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
										</div>

										<div class="col-lg-12">
											<label class="col-sm-1 control-label">トレーラ</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('trailer_teate',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">高速代</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('kousokudai',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">プレート</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('plate_teate',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">軽油</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('keiyu',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
										</div>

										<div class="col-lg-12">
											<label class="col-sm-1 control-label">電話代</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('denwa_teate',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-12">
										<?php echo $this->Form->submit('計算して登録', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']); ?>
										<?php echo $this->Html->link(('戻る'), ['controller' => 'Oi040s', 'action' => 'index'], ['class'=>array('btn btn-danger')]); ?>
									</div>
								</div>

							<!--</form>-->
							<?php echo $this->Form->end(); ?>

						</div>
					<!-- /コンテンツ -->
			</div>

		</div>
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
