<!-- 勤怠登録 -->
<!-- Co040s/edit -->
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
									$this->Html->addCrumb('走行実績', ['controller' => 'Co040s', 'action' => 'index']);
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

							<?php // 運転手用
								$totalloadingaccount = 0; //支払い数量用
								$totaleigyokilo = 0;
								$totallongteate = 0;

								foreach($ordertrans1 as $ordertran1) {
									if($ordertran1['Processtran']){
										echo '<div class="box-content">';
											echo '<table class="table table-bordered bootstrap-datatable">';
											echo '<tr>';
											switch($ordertran1['Carmaster']['trailermasters_id']){
												case 1:
													$trailerkubun = '単車';
													break;
												case 2:
													$trailerkubun = 'トレーラー';
													break;
												case 3:
													$trailerkubun = '16〜20kトレーラー';
													break;
												case 4:
													$trailerkubun = '24k以上トレーラー';
													break;
											}
											$totalloadingaccount = $totalloadingaccount + $ordertran1['Carmaster']['loadingaccount'];
											echo '<th colspan="2">'.$ordertran1['Ordertran']['kaisen'].'回戦　'.$ordertran1['Carmaster']['plateno'].'（区分：<b class="text-danger">'.$trailerkubun.'</b>　トレーラー手当：<b class="text-danger">'.$ordertran1['Carmaster']['trailerteate'].'円</b>)</th>';
											echo '</tr>';
											echo '<tr>';
											echo '<td colspan="2">荷主：'.$ordertran1['Shippermaster']['shippershortname'].'　開始場所：<b class="text-danger">'.$ordertran1['Workplace1']['workplacename'].'</b>　終了場所：<b class="text-danger">'.$ordertran1['Workplace2']['workplacename'].'</b>　商品：'.$ordertran1['Productdetailmaster']['pdname'].'</td>';
											echo '</tr>';
											echo '</table>';
											echo '<table class="table table-striped table-bordered bootstrap-datatable">';
												echo '<thead>';
													echo '<tr>';
														echo '<th>';
															echo '乗務員';
														echo '</th>';
														echo '<th>';
															echo '助手';
														echo '</th>';
														echo '<th>';
															echo '作業場所';
														echo '</th>';
														echo '<th>';
															echo '長距離';
														echo '</th>';
														echo '<th>';
															echo '営業キロ';
														echo '</th>';
														echo '<th>';
															echo '作業名称';
														echo '</th>';
														echo '<th>';
															echo '備考';
														echo '</th>';
														echo '<th>';
															echo '日時';
														echo '</th>';
													echo '</tr>';
												echo '</thead>';
												echo '<tbody>';
													$totaleigyokilo = 0;
													$totallongteate = 0;
													foreach($processtran1 as $dprocess) {
														echo '<tr>';
															echo '<td>';
																echo ($dprocess['PDriver1']['usernameK']);
															echo '</td>';
															echo '<td>';
																echo ($dprocess['PDriver2']['usernameK']);
															echo '</td>';
															echo '<td>';
																echo ($dprocess['Workplace']['workplacename']);
															echo '</td>';
															echo '<td>';
																$workplaceteate = ClassRegistry::init('Workplaceteate');
																$workplaceteates = $workplaceteate->find('first',[
																	'fields'=>[
																		'id',
																		'longteate_summer_tan',
																		'longteate_summer_tol',
																		'longteate_winter_tan',
																		'longteate_winter_tol',
																		'eigyokilo',
																		'eigyokilogas',
																		'eigyokiloytc'
																	],
																	'conditions'=>[
																		'workplaces_id' => $dprocess['Processtran']['workplaces_id'],
																		'Workplaceteate.branchmasters_id' => $ordertran1['Carmaster']['branchmasters_id'],
																	]
																]);
																// 冬かそれ以外か、かつトレーラーか単車か
																$season = $this->Session->read('BackData1.season');
																$w_longteate = 0;
																if($workplaceteates){
																	switch(true){
																		case $season=='winter' && $ordertran1['Carmaster']['trailermasters_id']==1:
																			$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tan'].'円';
																			echo $w_longteate;
																			break;
																		case $season=='winter' && $ordertran1['Carmaster']['trailermasters_id']==2:
																			$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tol'].'円';
																			echo $w_longteate;
																			break;
																		case $season=='winter' && $ordertran1['Carmaster']['trailermasters_id']==3:
																			$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tol'].'円';
																			echo $w_longteate;
																			break;
																		case $season=='winter' && $ordertran1['Carmaster']['trailermasters_id']==4:
																			$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tol'].'円';
																			echo $w_longteate;
																			break;
																		case $season=='summer' && $ordertran1['Carmaster']['trailermasters_id']==1:
																			$w_longteate = $workplaceteates['Workplaceteate']['longteate_summer_tan'].'円';
																			echo $w_longteate;
																			break;
																		case $season=='summer' && $ordertran1['Carmaster']['trailermasters_id']==2:
																			$w_longteate = $workplaceteates['Workplaceteate']['longteate_summer_tol'].'円';
																			echo $w_longteate;
																			break;
																		case $season=='summer' && $ordertran1['Carmaster']['trailermasters_id']==3:
																			$w_longteate = $workplaceteates['Workplaceteate']['longteate_summer_tol'].'円';
																			echo $w_longteate;
																			break;
																		case $season=='summer' && $ordertran1['Carmaster']['trailermasters_id']==4:
																			$w_longteate = $workplaceteates['Workplaceteate']['longteate_summer_tol'].'円';
																			echo $w_longteate;
																			break;
																	}
																}
																$totallongteate = $totallongteate + $w_longteate;
															echo '</td>';
															echo '<td>';
																if($workplaceteates){
																	echo $workplaceteates['Workplaceteate']['eigyokilo'].'km';
																	$totaleigyokilo = $totaleigyokilo + $workplaceteates['Workplaceteate']['eigyokilo'];
																}
															echo '</td>';
															echo '<td>';
																echo ($dprocess['Workdetailmaster']['workname']);
															echo '</td>';
															echo '<td>';
																echo ($dprocess['Processtran']['workddetailcomment']);
															echo '</td>';
															echo '<td>';
																if($dprocess['Processtran']['processstarttime']){
																	$starttime = new DateTime($dprocess['Processtran']['processstartdate'].$dprocess['Processtran']['processstarttime']);
																	echo $starttime->format('Y-m-d H:i');
																}else{
																	$starttime = new DateTime($dprocess['Processtran']['processstartdate']);
																	echo $starttime->format('Y-m-d');
																}
															echo '</td>';
														echo '</tr>';
													}

												echo '</tbody>';
											echo '</table>';

										echo '</div>';
									}
								}
							?>
							<?php // 助手用
								foreach($ordertrans2 as $ordertran2) {
									if($ordertran2['Processtran']){
										echo '<div class="box-content">';
										echo '<table class="table table-bordered bootstrap-datatable">';
										echo '<tr>';
										switch($ordertran2['Carmaster']['trailermasters_id']){
											case 1:
												$trailerkubun = '単車';
												break;
											case 2:
												$trailerkubun = 'トレーラー';
												break;
											case 3:
												$trailerkubun = '16〜20kトレーラー';
												break;
											case 4:
												$trailerkubun = '24k以上トレーラー';
												break;
										}
										echo '<th colspan="2">'.$ordertran2['Ordertran']['kaisen'].'回戦　'.$ordertran2['Carmaster']['plateno'].'（区分：<b class="text-danger">'.$trailerkubun.'</b>　トレーラー手当：<b class="text-danger">'.$ordertran2['Carmaster']['trailerteate'].'円</b>)</th>';
										echo '</tr>';
										echo '<tr>';
										echo '<td colspan="2">荷主：'.$ordertran2['Shippermaster']['shippershortname'].'　開始場所：<b class="text-danger">'.$ordertran2['Workplace1']['workplacename'].'</b>　終了場所：<b class="text-danger">'.$ordertran2['Workplace2']['workplacename'].'</b>　商品：'.$ordertran2['Productdetailmaster']['pdname'].'</td>';
										echo '</tr>';
										echo '</table>';
										echo '<table class="table table-striped table-bordered bootstrap-datatable">';
											echo '<thead>';
												echo '<tr>';
													echo '<th>';
														echo '乗務員';
													echo '</th>';
													echo '<th>';
														echo '助手';
													echo '</th>';
													echo '<th>';
														echo '作業場所';
													echo '</th>';
													echo '<th>';
														echo '長距離';
													echo '</th>';
													echo '<th>';
														echo '営業キロ';
													echo '</th>';
													echo '<th>';
														echo '作業名称';
													echo '</th>';
													echo '<th>';
														echo '備考';
													echo '</th>';
													echo '<th>';
														echo '日時';
													echo '</th>';
												echo '</tr>';
											echo '</thead>';
											echo '<tbody>';
												$totaleigyokilo = 0;
												$totallongteate = 0;
												foreach($processtran2 as $dprocess) {
													echo '<tr>';
														echo '<td>';
															echo ($dprocess['PDriver1']['usernameK']);
														echo '</td>';
														echo '<td>';
															echo ($dprocess['PDriver2']['usernameK']);
														echo '</td>';
														echo '<td>';
															echo ($dprocess['Workplace']['workplacename']);
														echo '</td>';
														echo '<td>';
															$workplaceteate = ClassRegistry::init('Workplaceteate');
															$workplaceteates = $workplaceteate->find('first',[
																'fields'=>[
																	'id',
																	'longteate_summer_tan',
																	'longteate_summer_tol',
																	'longteate_winter_tan',
																	'longteate_winter_tol',
																	'eigyokilo',
																	'eigyokilogas',
																	'eigyokiloytc'
																],
																'conditions'=>[
																	'workplaces_id' => $dprocess['Processtran']['workplaces_id'],
																	'Workplaceteate.branchmasters_id' => $ordertran2['Carmaster']['branchmasters_id'],
																]
															]);
															// 冬かそれ以外か、かつトレーラーか単車か
															$season = $this->Session->read('BackData1.season');
															$w_longteate = 0;
															if($workplaceteates){
																switch(true){
																	case $season=='winter' && $ordertran2['Carmaster']['trailermasters_id']==1:
																		$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tan'].'円';
																		echo $w_longteate;
																		break;
																	case $season=='winter' && $ordertran2['Carmaster']['trailermasters_id']==2:
																		$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tol'].'円';
																		echo $w_longteate;
																		break;
																	case $season=='winter' && $ordertran2['Carmaster']['trailermasters_id']==3:
																		$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tol'].'円';
																		echo $w_longteate;
																		break;
																	case $season=='winter' && $ordertran2['Carmaster']['trailermasters_id']==4:
																		$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tol'].'円';
																		echo $w_longteate;
																		break;
																	case $season=='summer' && $ordertran2['Carmaster']['trailermasters_id']==1:
																		$w_longteate = $workplaceteates['Workplaceteate']['longteate_summer_tan'].'円';
																		echo $w_longteate;
																		break;
																	case $season=='summer' && $ordertran2['Carmaster']['trailermasters_id']==2:
																		$w_longteate = $workplaceteates['Workplaceteate']['longteate_summer_tol'].'円';
																		echo $w_longteate;
																		break;
																	case $season=='summer' && $ordertran2['Carmaster']['trailermasters_id']==3:
																		$w_longteate = $workplaceteates['Workplaceteate']['longteate_summer_tol'].'円';
																		echo $w_longteate;
																		break;
																	case $season=='summer' && $ordertran2['Carmaster']['trailermasters_id']==4:
																		$w_longteate = $workplaceteates['Workplaceteate']['longteate_summer_tol'].'円';
																		echo $w_longteate;
																		break;
																}
															}
															$totallongteate = $totallongteate + $w_longteate;
														echo '</td>';
														echo '<td>';
															if($workplaceteates){
																echo $workplaceteates['Workplaceteate']['eigyokilo'].'km';
																$totaleigyokilo = $totaleigyokilo + $workplaceteates['Workplaceteate']['eigyokilo'];
															}
														echo '</td>';
														echo '<td>';
															echo ($dprocess['Workdetailmaster']['workname']);
														echo '</td>';
														echo '<td>';
															echo ($dprocess['Processtran']['workddetailcomment']);
														echo '</td>';
														echo '<td>';
															if($dprocess['Processtran']['processstarttime']){
																$starttime = new DateTime($dprocess['Processtran']['processstartdate'].$dprocess['Processtran']['processstarttime']);
																echo $starttime->format('Y-m-d H:i');
															}else{
																$starttime = new DateTime($dprocess['Processtran']['processstartdate']);
																echo $starttime->format('Y-m-d');
															}
														echo '</td>';
													echo '</tr>';
												}

											echo '</tbody>';
										echo '</table>';

										echo '</div>';
										if($totaleigyokilo){
											//助手の場合は半分にする
											$totaleigyokilo = round($totaleigyokilo / 2);
										}
										if($totallongteate){
											//助手の場合は半分にする
											$totallongteate = round($totallongteate / 2);
										}

									}
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
												<div class="col-sm-3">
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
												<div class="col-sm-3">
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
												<div class="col-sm-3">
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
												<div class="col-sm-3">
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
														'default'=>$totaleigyokilo,
													]);
												?>
											</div>
											<label class="col-sm-1 control-label"><a href="#" title="回次 x 積載数">支払数量</a></label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('shiharaisuryo',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
														'default'=> $totalloadingaccount
													]);
												?>
											</div>
											<label class="col-sm-1 control-label"><a href="#" title="長距離手当が付く場合は残業は0にする">長距離</a></label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('chokyori_teate',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
														'default'=>$totallongteate,
													]);
												?>
											</div>
										</div>

										<div class="col-lg-12">
											<label class="col-sm-1 control-label"><a href="#" title="3回転:200円加算、4回転:300円加算">回次</a></label>
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
											<label class="col-sm-1 control-label"><a href="#" title="2件:300円加算,3件:400円加算,4件:500円加算">多箇所</a></label>
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
											<label class="col-sm-1 control-label"><a href="#" title="出光乗務員↔化学品乗務員の場合、1500円">乗務</a></label>
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
											<label class="col-sm-1 control-label"><a href="#" title="1500円">チェーン</a></label>
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
													if(isset($ordertran1['Carmaster']['trailerteate'])){
														echo $this->Form->input('trailer_teate',[
															'label' => false,
															'div' => false,
															'class' => 'form-control',
															'style' => 'width:100px',
															'default'=>$ordertran1['Carmaster']['trailerteate']
														]);
													}else{
														echo $this->Form->input('trailer_teate',[
															'label' => false,
															'div' => false,
															'class' => 'form-control',
															'style' => 'width:100px',
														]);
													}
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
											<label class="col-sm-1 control-label"><a href="#" title="高稼働の人に300円（季節は付けない）">電話代</a></label>
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
<!--
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
-->
										</div>

									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-12">
										<?php echo $this->Form->submit('計算して登録', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']); ?>
										<?php echo $this->Html->link(('戻る'), ['controller' => 'Co040s', 'action' => 'index'], ['class'=>array('btn btn-danger')]); ?>
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
