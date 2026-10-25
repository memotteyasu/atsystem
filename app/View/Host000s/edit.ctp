<!-- 勤怠登録 -->
<!-- Host000s/edit -->
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
									$this->Html->addCrumb('勤怠管理（川崎）', ['controller' => 'Host000s', 'action' => 'index']);
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
									echo $today->format('Y年m月d日').'('.$week[$today->format('w')].')'.' 勤怠実績';
									if($calen != null){
										switch($calen['Calendarusermaster']['restflg']){
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
								$totaleigyokilo = 0;
								$totallongteate = 0;
								$i=0;
								echo '<div class="box-content">';
								echo '<table class="table table-striped table-bordered bootstrap-datatable">';
									echo '<thead>';
										echo '<tr>';
											echo '<th>';
												echo '運転手';
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
								$w_car = '';
								foreach($processtrandriver1 as $ordertran1) {
									if($ordertran1['Processtran']){
											if($w_car == $ordertran1['Ordertran']['Carmaster']['id']){
											}else{
												$totalloadingaccount = $totalloadingaccount + $ordertran1['Ordertran']['Carmaster']['loadingaccount'];
												$w_car = $ordertran1['Ordertran']['Carmaster']['id'];
												echo '<tr>';
												echo '<td colspan="8">'.$ordertran1['Ordertran']['kaisen'].'回戦　'.$ordertran1['Ordertran']['Carmaster']['plateno'].'（トレーラー手当：<b class="text-danger">'.$ordertran1['Ordertran']['Carmaster']['trailerteate'].'円</b>)</td>';
												echo '</tr>';
											}
											switch($ordertran1['Ordertran']['Carmaster']['trailermasters_id']){
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
											echo '<tr>';
												echo '<td>';
													echo ($ordertran1['PDriver1']['usernameK']);
												echo '</td>';
												echo '<td>';
													echo ($ordertran1['PDriver2']['usernameK']);
												echo '</td>';
												echo '<td>';
													echo ($ordertran1['Workplace']['workplacename']);
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
															'workplaces_id' => $ordertran1['Processtran']['workplaces_id'],
															'Workplaceteate.branchmasters_id' => $ordertran1['Ordertran']['Carmaster']['branchmasters_id'],
														]
													]);
													// 冬かそれ以外か、かつトレーラーか単車か
													$season = $this->Session->read('BackData1.season');
													$w_longteate = 0;
													if($workplaceteates){
														switch(true){
															case $season=='winter' && $ordertran1['Ordertran']['Carmaster']['trailermasters_id']==1:
																$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tan'].'円';
																echo $w_longteate;
																break;
															case $season=='winter' && $ordertran1['Ordertran']['Carmaster']['trailermasters_id']==2:
																$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tol'].'円';
																echo $w_longteate;
																break;
															case $season=='winter' && $ordertran1['Ordertran']['Carmaster']['trailermasters_id']==3:
																$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tol'].'円';
																echo $w_longteate;
																break;
															case $season=='winter' && $ordertran1['Ordertran']['Carmaster']['trailermasters_id']==4:
																$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tol'].'円';
																echo $w_longteate;
																break;
															case $season=='summer' && $ordertran1['Ordertran']['Carmaster']['trailermasters_id']==1:
																$w_longteate = $workplaceteates['Workplaceteate']['longteate_summer_tan'].'円';
																echo $w_longteate;
																break;
															case $season=='summer' && $ordertran1['Ordertran']['Carmaster']['trailermasters_id']==2:
																$w_longteate = $workplaceteates['Workplaceteate']['longteate_summer_tol'].'円';
																echo $w_longteate;
																break;
															case $season=='summer' && $ordertran1['Ordertran']['Carmaster']['trailermasters_id']==3:
																$w_longteate = $workplaceteates['Workplaceteate']['longteate_summer_tol'].'円';
																echo $w_longteate;
																break;
															case $season=='summer' && $ordertran1['Ordertran']['Carmaster']['trailermasters_id']==4:
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
													echo ($ordertran1['Workdetailmaster']['workname']);
												echo '</td>';
												echo '<td>';
													echo ($ordertran1['Processtran']['workddetailcomment']);
												echo '</td>';
												echo '<td>';
													if($ordertran1['Processtran']['processstarttime']){
														$starttime = new DateTime($ordertran1['Processtran']['processstartdate'].$ordertran1['Processtran']['processstarttime']);
														echo $starttime->format('Y-m-d H:i');
													}else{
														$starttime = new DateTime($ordertran1['Processtran']['processstartdate']);
														echo $starttime->format('Y-m-d');
													}
												echo '</td>';
											echo '</tr>';

									}
									$i++;
								}
										echo '</tbody>';
									echo '</table>';

								echo '</div>';
							?>
							<?php // 助手用
							if($processtrandriver2){
								echo '<div class="box-content">';
								echo '<table class="table table-striped table-bordered bootstrap-datatable">';
									echo '<thead>';
										echo '<tr>';
											echo '<th>';
												echo '運転手';
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
								$w_car = '';
								foreach($processtrandriver2 as $ordertran2) {
									if($ordertran2['Processtran']){
											if($w_car == $ordertran2['Ordertran']['Carmaster']['id']){
											}else{
												$totalloadingaccount = $totalloadingaccount + $ordertran2['Ordertran']['Carmaster']['loadingaccount'];
												$w_car = $ordertran2['Ordertran']['Carmaster']['id'];
											}
											switch($ordertran2['Ordertran']['Carmaster']['trailermasters_id']){
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
											echo '<tr>';
												echo '<td>';
													echo ($ordertran2['PDriver1']['usernameK']);
												echo '</td>';
												echo '<td>';
													echo ($ordertran2['PDriver2']['usernameK']);
												echo '</td>';
												echo '<td>';
													echo ($ordertran2['Workplace']['workplacename']);
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
															'workplaces_id' => $ordertran2['Processtran']['workplaces_id'],
															'Workplaceteate.branchmasters_id' => $ordertran2['Ordertran']['Carmaster']['branchmasters_id'],
														]
													]);
													// 冬かそれ以外か、かつトレーラーか単車か
													$season = $this->Session->read('BackData1.season');
													$w_longteate = 0;
													if($workplaceteates){
														switch(true){
															case $season=='winter' && $ordertran2['Ordertran']['Carmaster']['trailermasters_id']==1:
																$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tan'].'円';
																echo $w_longteate;
																break;
															case $season=='winter' && $ordertran2['Ordertran']['Carmaster']['trailermasters_id']==2:
																$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tol'].'円';
																echo $w_longteate;
																break;
															case $season=='winter' && $ordertran2['Ordertran']['Carmaster']['trailermasters_id']==3:
																$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tol'].'円';
																echo $w_longteate;
																break;
															case $season=='winter' && $ordertran2['Ordertran']['Carmaster']['trailermasters_id']==4:
																$w_longteate = $workplaceteates['Workplaceteate']['longteate_winter_tol'].'円';
																echo $w_longteate;
																break;
															case $season=='summer' && $ordertran2['Ordertran']['Carmaster']['trailermasters_id']==1:
																$w_longteate = $workplaceteates['Workplaceteate']['longteate_summer_tan'].'円';
																echo $w_longteate;
																break;
															case $season=='summer' && $ordertran2['Ordertran']['Carmaster']['trailermasters_id']==2:
																$w_longteate = $workplaceteates['Workplaceteate']['longteate_summer_tol'].'円';
																echo $w_longteate;
																break;
															case $season=='summer' && $ordertran2['Ordertran']['Carmaster']['trailermasters_id']==3:
																$w_longteate = $workplaceteates['Workplaceteate']['longteate_summer_tol'].'円';
																echo $w_longteate;
																break;
															case $season=='summer' && $ordertran2['Ordertran']['Carmaster']['trailermasters_id']==4:
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
													echo ($ordertran2['Workdetailmaster']['workname']);
												echo '</td>';
												echo '<td>';
													echo ($ordertran2['Processtran']['workddetailcomment']);
												echo '</td>';
												echo '<td>';
													if($ordertran2['Processtran']['processstarttime']){
														$starttime = new DateTime($ordertran2['Processtran']['processstartdate'].$ordertran2['Processtran']['processstarttime']);
														echo $starttime->format('Y-m-d H:i');
													}else{
														$starttime = new DateTime($ordertran2['Processtran']['processstartdate']);
														echo $starttime->format('Y-m-d');
													}
												echo '</td>';
											echo '</tr>';

									}
									$i++;
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
							?>
						</div>
<hr>
							<table>
							<tr>
							<td>
								<h4><?php echo $userdata['User']['usernameK']; ?>　：
									<?php
											echo $this->Form->input('flgmasters_id',[
											'label'=>false,
											'div'=>false,
											'type'=>'select',
											'style'=>'height:30px',
											'tabindex'=>-1
											]);
										?>
								</h4>
								</td>
								<td>　カレンダー区分：<?php
									echo $this->Form->input('rinjicalendarflg',[
										'label'=>false,
										'div'=>false,
										'type'=>'select',
										'style'=>'height:30px',
										'options'=>$rinjicalendar,
										'default'=>$calen['Calendarusermaster']['restflg']
									]);
								?>
								</td>
								</tr>
								</table>
								<div class="box-inner">
									<div class="box-header well">
										<h2>勤怠登録（川崎バージョン）</h2>
									</div>
<!-- 勤怠移動 -->
									<div class="form-group">
<?php
$tabindex = 1;
$did = $userdata['User']['departmentmasters_id'];
 ?>
<!-- タコメータ1枚目 -->
										<div class="col-lg-6" id="untentako">
											<label class="col-sm-12 text-danger">タコメーター時間１枚目</label>

											<div class="row">
												<label class="col-sm-4 control-label">出庫</label>
												<div class="col-sm-4">
													<?php
														echo $this->Form->input('tako_datestart',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'tabindex'=>-1,
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
															'tabindex'=>$tabindex,
															'class' => 'timepicker form-control',
														]);
														$tabindex += 1;
													?>
												</div>
											</div>

											<div class="row">
												<label class="col-sm-4 control-label">帰庫</label>
												<div class="col-sm-4">
													<?php
														echo $this->Form->input('tako_dateend',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'tabindex'=>-1,
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
															'tabindex'=>$tabindex,
															'class' => 'timepicker form-control',
														]);
														$tabindex += 1;
													?>
												</div>
											</div>
										</div>
										<div class="col-lg-6">
											<label class="col-sm-12 text-danger">出退勤１</label>
											<div class="row">
												<label class="col-sm-4 control-label">始業</label>
												<div class="col-sm-4">
													<?php
														echo $this->Form->input('datestart',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'tabindex'=>-1,
															'type'=>'text',
														]);
													?>
												</div>
												<div class="col-sm-3">
													<?php
														$jimuindex = 1;
														if($did==5){
															echo $this->Form->input('timestart',[
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'timepicker form-control',
																'tabindex'=>$jimuindex,
																'timeFormat'=>24,
															]);
														}else{
															echo $this->Form->input('timestart',[
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'timepicker form-control',
																'tabindex'=>-1,
																'timeFormat'=>24,
															]);
														}
													?>
												</div>
											</div>

											<div class="row">
												<label class="col-sm-4 control-label">終業</label>
												<div class="col-sm-4">
													<?php
														echo $this->Form->input('dateend',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'tabindex'=>-1,
															'type'=>'text',
														]);
													?>
												</div>
												<div class="col-sm-3">
													<?php
														$jimuindex += 1;
														if($did==5){
															echo $this->Form->input('timeend',[
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'timepicker form-control',
																'tabindex'=>$jimuindex,
																'timeFormat'=>24,
															]);
														}else{
															echo $this->Form->input('timeend',[
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'timepicker form-control',
																'tabindex'=>-1,
																'timeFormat'=>24,
															]);
														}
													?>
												</div>
											</div>

										</div>
<!-- /タコメータ1枚目 -->
<!-- 事務職用 -->
										<div class="col-lg-6" id="jimu">
											<label class="col-sm-12 text-danger">事務職用</label>

											<div class="row">
												<label class="col-sm-3 control-label">休憩時間</label>
												<div class="col-sm-2">
													<?php
														$jimuindex += 1;
														echo $this->Form->input('kyukeitime',[
															'label' => false,
															'type' => 'text',
															'div' => false,
															'default'=>'01:00',
															'tabindex'=>$jimuindex,
															'class' => 'timepicker form-control',
														]);
													?>
												</div>
											</div>

										</div>

<!-- /事務職用 -->
									</div>
<!-- /勤怠移動 -->

									<!-- 運転手用 -->
									<div id="unten">
									<div class="form-group" style="padding-top:20px;">
										<div class="col-lg-12">
											<label class="col-sm-12 text-danger">タコ１枚目</label>
											<label class="col-sm-1 control-label text-right">走行時間</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako1_soukou_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label">作業時間</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako1_sagyou_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label">待機</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako1_taiki_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label">休憩</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako1_kyukei_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
										</div>
										<div class="col-lg-12">
											<label class="col-sm-1 control-label text-right">始業km</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako1_km_s',[
														'label' => false,
														'div' => false,
														'class' => 'form-control KmS',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label text-right">終業km</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako1_km_e',[
														'label' => false,
														'div' => false,
														'class' => 'form-control KmE',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
													$km_init1 = 0;
													if(isset($this->request->data['Kintaitran']['tako1_km_e'])){
														$km_init1 = $this->request->data['Kintaitran']['tako1_km_e'] - $this->request->data['Kintaitran']['tako1_km_s'];
													}
												?>
											</div>
											<div class="col-sm-5">
												<span id="Km1"><?php echo $km_init1; ?>km ←　</span>
												<?php
													echo $this->Form->input('tako1flg',[
														'legend' => false,
														'div' => false,
														'type'=>'radio',
														'options'=>$flglist,
														'default'=>0,
														'style'=>'margin-top:15px;',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
										</div>
									</div>
									<hr>
									<div class="form-group">
<!-- タコメータ2枚目 -->
										<div class="col-lg-6" id="untentako2">
											<label class="col-sm-12 text-danger">タコメーター時間２枚目</label>

											<div class="row">
												<label class="col-sm-4 control-label">出庫</label>
												<div class="col-sm-4">
													<?php
														echo $this->Form->input('tako_datestart2',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'tabindex'=>-1,
															'type'=>'text',
														]);
													?>
												</div>
												<div class="col-sm-3">
													<?php
														echo $this->Form->input('tako_timestart2',[
															'label' => false,
															'type' => 'text',
															'div' => false,
															'tabindex'=>$tabindex,
															'class' => 'timepicker form-control',
														]);
														$tabindex += 1;
													?>
												</div>
											</div>

											<div class="row">
												<label class="col-sm-4 control-label">帰庫</label>
												<div class="col-sm-4">
													<?php
														echo $this->Form->input('tako_dateend2',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'tabindex'=>-1,
															'type'=>'text',
														]);
													?>
												</div>
												<div class="col-sm-3">
													<?php
														echo $this->Form->input('tako_timeend2',[
															'label' => false,
															'type' => 'text',
															'div' => false,
															'tabindex'=>$tabindex,
															'class' => 'timepicker form-control',
														]);
														$tabindex += 1;
													?>
												</div>
											</div>
										</div>
										<div class="col-lg-6" id="kintai2">
											<label class="col-sm-12 text-danger">出退勤２</label>
											<div class="row">
												<label class="col-sm-4 control-label">始業</label>
												<div class="col-sm-4">
													<?php
														echo $this->Form->input('datestart2',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'tabindex'=>-1,
															'type'=>'text',
														]);
													?>
												</div>
												<div class="col-sm-3">
													<?php
														$jimuindex = 1;
														if($did==5){
															echo $this->Form->input('timestart2',[
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'timepicker form-control',
																'tabindex'=>$jimuindex,
																'timeFormat'=>24,
															]);
														}else{
															echo $this->Form->input('timestart2',[
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'timepicker form-control',
																'tabindex'=>-1,
																'timeFormat'=>24,
															]);
														}
													?>
												</div>
											</div>

											<div class="row">
												<label class="col-sm-4 control-label">終業</label>
												<div class="col-sm-4">
													<?php
														echo $this->Form->input('dateend2',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'tabindex'=>-1,
															'type'=>'text',
														]);
													?>
												</div>
												<div class="col-sm-3">
													<?php
														$jimuindex += 1;
														if($did==5){
															echo $this->Form->input('timeend2',[
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'timepicker form-control',
																'tabindex'=>$jimuindex,
																'timeFormat'=>24,
															]);
														}else{
															echo $this->Form->input('timeend2',[
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'timepicker form-control',
																'tabindex'=>-1,
																'timeFormat'=>24,
															]);
														}
													?>
												</div>
											</div>

										</div>
<!-- /タコメータ2枚目 -->
									</div>
									<div class="form-group">
										<div class="col-lg-12">
											<label class="col-sm-12 text-danger">タコ２枚目</label>
											<label class="col-sm-1 control-label text-right">走行時間</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako2_soukou_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label">作業時間</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako2_sagyou_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label">待機</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako2_taiki_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label">休憩</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako2_kyukei_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
										</div>
										<div class="col-lg-12">
											<label class="col-sm-1 control-label text-right">始業km</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako2_km_s',[
														'label' => false,
														'div' => false,
														'class' => 'form-control KmS',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label text-right">終業km</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako2_km_e',[
														'label' => false,
														'div' => false,
														'class' => 'form-control KmE',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
													$km_init2 = 0;
													if(isset($this->request->data['Kintaitran']['tako2_km_e'])){
														$km_init2 = $this->request->data['Kintaitran']['tako2_km_e'] - $this->request->data['Kintaitran']['tako2_km_s'];
													}
												?>
											</div>
											<div class="col-sm-5">
												<span id="Km2"><?php echo $km_init2; ?>km ←　</span>
												<?php
													echo $this->Form->input('tako2flg',[
														'legend' => false,
														'div' => false,
														'type'=>'radio',
														'options'=>$flglist,
														'default'=>0,
														'style'=>'margin-top:15px;',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
										</div>
									</div>
									<hr>
									<div class="form-group">
<!-- タコメータ3枚目 -->
										<div class="col-lg-6" id="untentako3">
											<label class="col-sm-12 text-danger">タコメーター時間３枚目</label>

											<div class="row">
												<label class="col-sm-4 control-label">出庫</label>
												<div class="col-sm-4">
													<?php
														echo $this->Form->input('tako_datestart3',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'tabindex'=>-1,
															'type'=>'text',
														]);
													?>
												</div>
												<div class="col-sm-3">
													<?php
														echo $this->Form->input('tako_timestart3',[
															'label' => false,
															'type' => 'text',
															'div' => false,
															'tabindex'=>$tabindex,
															'class' => 'timepicker form-control',
														]);
														$tabindex += 1;
													?>
												</div>
											</div>

											<div class="row">
												<label class="col-sm-4 control-label">帰庫</label>
												<div class="col-sm-4">
													<?php
														echo $this->Form->input('tako_dateend3',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'tabindex'=>-1,
															'type'=>'text',
														]);
													?>
												</div>
												<div class="col-sm-3">
													<?php
														echo $this->Form->input('tako_timeend3',[
															'label' => false,
															'type' => 'text',
															'div' => false,
															'tabindex'=>$tabindex,
															'class' => 'timepicker form-control',
														]);
														$tabindex += 1;
													?>
												</div>
											</div>
										</div>
										<div class="col-lg-6" id="kintai3">
											<label class="col-sm-12 text-danger">出退勤３</label>
											<div class="row">
												<label class="col-sm-4 control-label">始業</label>
												<div class="col-sm-4">
													<?php
														echo $this->Form->input('datestart3',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'tabindex'=>-1,
															'type'=>'text',
														]);
													?>
												</div>
												<div class="col-sm-3">
													<?php
														$jimuindex = 1;
														if($did==5){
															echo $this->Form->input('timestart3',[
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'timepicker form-control',
																'tabindex'=>$jimuindex,
																'timeFormat'=>24,
															]);
														}else{
															echo $this->Form->input('timestart3',[
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'timepicker form-control',
																'tabindex'=>-1,
																'timeFormat'=>24,
															]);
														}
													?>
												</div>
											</div>

											<div class="row">
												<label class="col-sm-4 control-label">終業</label>
												<div class="col-sm-4">
													<?php
														echo $this->Form->input('dateend3',[
															'label'=>false,
															'div'=>false,
															'class'=>'datepicker1 form-control',
															'tabindex'=>-1,
															'type'=>'text',
														]);
													?>
												</div>
												<div class="col-sm-3">
													<?php
														$jimuindex += 1;
														if($did==5){
															echo $this->Form->input('timeend3',[
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'timepicker form-control',
																'tabindex'=>$jimuindex,
																'timeFormat'=>24,
															]);
														}else{
															echo $this->Form->input('timeend3',[
																'label' => false,
																'type' => 'text',
																'div' => false,
																'class' => 'timepicker form-control',
																'tabindex'=>-1,
																'timeFormat'=>24,
															]);
														}
													?>
												</div>
											</div>

										</div>
<!-- /タコメータ3枚目 -->
									</div>
									<div class="form-group">
										<div class="col-lg-12">
											<label class="col-sm-12 text-danger">タコ３枚目</label>
											<label class="col-sm-1 control-label text-right">走行時間</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako3_soukou_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label">作業時間</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako3_sagyou_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label">待機</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako3_taiki_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label">休憩</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako3_kyukei_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
										</div>
										<div class="col-lg-12">
											<label class="col-sm-1 control-label text-right">始業km</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako3_km_s',[
														'label' => false,
														'div' => false,
														'class' => 'form-control KmS',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label text-right">終業km</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako3_km_e',[
														'label' => false,
														'div' => false,
														'class' => 'form-control KmE',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
													$km_init3 = 0;
													if(isset($this->request->data['Kintaitran']['tako3_km_e'])){
														$km_init1 = $this->request->data['Kintaitran']['tako3_km_e'] - $this->request->data['Kintaitran']['tako3_km_s'];
													}
												?>
											</div>
											<div class="col-sm-5">
												<span id="Km3"><?php echo $km_init3; ?>km ←　</span>
												<?php
													echo $this->Form->input('tako3flg',[
														'legend' => false,
														'div' => false,
														'type'=>'radio',
														'options'=>$flglist,
														'default'=>0,
														'style'=>'margin-top:15px;',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
										</div>
									</div>
									<hr>
<!-- ４枚目は外す
									<div class="form-group">
										<div class="col-lg-12">
											<label class="col-sm-12 text-danger">タコ４枚目</label>
											<label class="col-sm-1 control-label text-right">走行時間</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako4_soukou_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label">作業時間</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako4_sagyou_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label">待機</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako4_taiki_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label">休憩</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako4_kyukei_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
										</div>
										<div class="col-lg-12">
											<label class="col-sm-1 control-label text-right">始業km</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako4_km_s',[
														'label' => false,
														'div' => false,
														'class' => 'form-control KmS',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label text-right">終業km</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako4_km_e',[
														'label' => false,
														'div' => false,
														'class' => 'form-control KmE',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<div class="col-sm-5">
												<span id="Km4">0km ←　</span>
												<?php
													echo $this->Form->input('tako4flg',[
														'legend' => false,
														'div' => false,
														'type'=>'radio',
														'options'=>$flglist,
														'default'=>0,
														'style'=>'margin-top:15px;',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
										</div>
									</div>
<hr>
-->
									<div class="form-group" style="padding-top:20px;">
										<div class="col-lg-12">
											<label class="col-sm-12 text-danger">合計</label>
											<label class="col-sm-1 control-label text-right">走行合計</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako_gokei_soukou_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'disabled',
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">作業合計</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako_gokei_sagyou_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'disabled',
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">待機合計</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako_gokei_taiki_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'disabled',
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">休憩合計</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako_gokei_kyukei_h',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'disabled',
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
										</div>
										<div class="col-lg-12">
											<label class="col-sm-1 control-label text-right">合計km</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('tako_gokei_km',[
														'label' => false,
														'div' => false,
														'disabled',
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
										</div>
									</div>
									<hr>

									</div>
									<!-- /運転手用 -->

									<div class="form-group">
										<div class="col-lg-12">
<!-- 走行時間、作業時間、待機・休憩を足した時間
											<label class="col-sm-1 control-label"><a href="#" title="前後15分加算済" tabindex="-1">拘束時間</a></label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('so_kinmu_jikan',[
														'label' => false,
														'div' => false,
														'type' => 'text',
														'disabled',
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
 -->
											<label class="col-sm-1 control-label"><a href="#" title="走行時間＋作業時間＋待機時間＋30分" tabindex="-1">労働時間</a></label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('kinmu_jikan',[
														'label' => false,
														'div' => false,
														'type' => 'text',
														'disabled',
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label"><a href="#" title="労務管理上の出退勤" tabindex="-1">拘束時間</a></label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('roumu_kousoku',[
														'label' => false,
														'div' => false,
														'type' => 'text',
														'disabled',
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label"></label>
											<div class="col-sm-2">
												<!-- ブランク -->
											</div>
											<label class="col-sm-1 control-label text-success">定時時間</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('teiji',[
														'label' => false,
														'div' => false,
														'type' => 'text',
														'disabled',
														'default'=>substr($teiji,0,5),
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
											<label class="col-sm-12 text-danger">残業時間</label>
											<label class="col-sm-1 control-label">平残（1.0）</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('heijitsuzangyo_nashi',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'disabled',
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<!--
											<label class="col-sm-1">含残業<br>含深夜</label>
											<div class="col-sm-2">
												<?php
													echo substr($pin_zangyojikan,0,5);
													echo '<br>';
													echo substr($pin_shinyajikan,0,5);
												?>
											</div>
											-->
										</div>
									</div>
									<div class="form-group">

										<div class="col-lg-12">
											<label class="col-sm-1 control-label">平残(1.25)</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('heijitsuzangyo',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'disabled',
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">平深(0.25)</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('heijitsushinya',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
										</div>
										<div class="col-lg-12">
											<label class="col-sm-1 control-label">休残(1.25)</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('kyujitsuzangyo',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'disabled',
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">休深(0.25)</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('kyujitsushinya',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
										</div>

										<div class="col-lg-12">
											<label class="col-sm-1 control-label">法残(1.35)</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('houteikyujitsu',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'disabled',
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">法深(0.25)</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('houteikyujitsushinya',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'timepicker form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
										</div>

									</div>

									<hr>
									<div class="form-group" id="untenteate1">
										<div class="col-lg-12">
											<label class="col-sm-12 text-danger">手当</label>
											<label class="col-sm-1 control-label"><a href="#" title="走行した距離で作業歩合マスタ*評価係数をセット" tabindex="-1">合計</a></label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('sagyoteate',[
														'label' => false,
														'div' => false,
														'disabled',
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">定額時間外</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('teigakujikangai',[
														'label' => false,
														'div' => false,
														'disabled',
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">定額深夜割</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('teigakushinya',[
														'label' => false,
														'div' => false,
														'disabled',
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
											<label class="col-sm-1 control-label">距離歩合</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('untinbuai',[
														'label' => false,
														'div' => false,
														'disabled',
														'class' => 'form-control',
														'style' => 'width:100px',
													]);
												?>
											</div>
										</div>
									</div>
									<div class="form-group" id="untenteate2">
										<div class="col-lg-12">
											<label class="col-sm-1 control-label">走行歩合</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('soukoukiro',[
														'label' => false,
														'div' => false,
														'disabled',
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
													if(isset($ordertran1['Ordertran']['Carmaster']['trailerteate'])){
														echo $this->Form->input('trailer_teate',[
															'label' => false,
															'div' => false,
															'class' => 'form-control',
															'style' => 'width:100px',
															'default'=>$ordertran1['Ordertran']['Carmaster']['trailerteate'],
															'tabindex'=>$tabindex,
														]);
														$tabindex += 1;
													}else{
														echo $this->Form->input('trailer_teate',[
															'label' => false,
															'div' => false,
															'class' => 'form-control',
															'style' => 'width:100px',
															'tabindex'=>$tabindex,
														]);
														$tabindex += 1;
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
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label"><a href="#" title="1500円" tabindex="-1">チェーン</a></label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('chain_teate',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
											<label class="col-sm-1 control-label"><a href="#" title="1500円" tabindex="-1">乗務手当</a></label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('jyoumu_teate',[
														'label' => false,
														'div' => false,
														'class' => 'form-control',
														'style' => 'width:100px',
														'tabindex'=>$tabindex,
													]);
													$tabindex += 1;
												?>
											</div>
										</div>

									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-12">
										<?php echo $this->Form->submit('登録', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit','tabindex'=>-1]); ?>
										<?php
										/* 一旦削除
										$today->modify('+1 days');
										if(isset($keyid)){
											echo $this->Html->link(('翌日を続けて入力'), ['controller' => 'Host000s', 'action' => 'add','uid'=>$userdata['User']['id'],'kyy'=>$today->format('Y'),'kmm'=>$today->format('m'),'kdd'=>$today->format('d')], ['class'=>array('btn btn-warning')]);
										}
										*/
										 ?>
										<?php echo $this->Html->link(('戻る'), ['controller' => 'Host000s', 'action' => 'index'], ['class'=>array('btn btn-primary'),'tabindex'=>-1]); ?>
										<?php
										if(isset($keyid)){
											echo $this->Html->link(('削除'), ['controller' => 'Host000s', 'action' => 'delete',$keyid], ['class'=>array('btn btn-danger'),'tabindex'=>-1],'このデータを削除してもいいですか？');
										}
										?>
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
<script>
let bar = <?php echo $did; ?>;
if(bar==5){
	document.getElementById("unten").style.display = "none";
	document.getElementById("untentako").style.display = "none";
	document.getElementById("untentako2").style.display = "none";
	document.getElementById("kintai2").style.display = "none";
	document.getElementById("untentako3").style.display = "none";
	document.getElementById("kintai3").style.display = "none";
	document.getElementById("untenteate1").style.display = "none";
	document.getElementById("untenteate2").style.display = "none";
}else{
	document.getElementById("jimu").style.display = "none";
}

function calculation(a,b) {
	if (Number.isNaN(a)) {
		var a = 0;
	} else {
		var a = a;
	}
	if (Number.isNaN(b)) {
		var b = 0;
	} else {
		var b = b;
	}
	var result = b - a;

	return result;
}

$(function(){
	$('.KmS').on('change', function() {
		$('.KmS').each(function(i) {
			// eachのindexは0から始まるため、1から始まるように加算している
			i = i + 1;
			var output = $('#Km' + i);
			var KmS = $('#KintaitranTako' + i + 'KmS').val();
			var KmE = $('#KintaitranTako' + i + 'KmE').val();
			var answer = calculation(KmS,KmE);
			if (answer < 0) {
				output.html('<font color="red">×　</font>');
			} else {
				output.text(answer + 'km ←　');
			}
		});
	});

	$('.KmE').on('change', function() {
		$('.KmE').each(function(i) {
			// eachのindexは0から始まるため、1から始まるように加算している
			i = i + 1;
			var output = $('#Km' + i);
			var KmS = $('#KintaitranTako' + i + 'KmS').val();
			var KmE = $('#KintaitranTako' + i + 'KmE').val();
			var answer = calculation(KmS,KmE);
			if (answer < 0) {
				output.html('<font color="red">×　</font>');
			} else {
				output.text(answer + 'km ←　');
			}
		});
	});
});
</script>
