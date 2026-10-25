<!-- 勤怠登録 -->
<!-- Host010s/edit -->
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
									$this->Html->addCrumb('勤怠管理', ['controller' => 'Host010s', 'action' => 'index']);
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
												echo '<td colspan="8">'.$ordertran1['Ordertran']['kaisen'].'回戦　'.$ordertran1['Ordertran']['Carmaster']['plateno'].'（トレーラー手当：<b class="text-danger">'.$ordertran1['Ordertran']['Carmaster']['trailerteate'].'円</b>) 商品：'.$ordertran1['Ordertran']['Productdetailmaster']['pdshortname'].'</td>';
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
											<label class="col-sm-12 text-danger">出退勤</label>

											<div class="row">
												<label class="col-sm-4 control-label">始業</label>
												<div class="col-sm-4">
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
												<label class="col-sm-4 control-label">終業</label>
												<div class="col-sm-4">
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

										<div class="col-lg-6" id="untentako">
											<label class="col-sm-12 text-danger">タコメーター時間</label>

											<div class="row">
												<label class="col-sm-4 control-label">出庫</label>
												<div class="col-sm-4">
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
												<label class="col-sm-4 control-label">帰庫</label>
												<div class="col-sm-4">
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
<!-- 事務職用 -->
										<div class="col-lg-6" id="jimu">
											<label class="col-sm-12 text-danger">　</label>

											<div class="row">
												<label class="col-sm-3 control-label">休憩時間</label>
												<div class="col-sm-2">
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

<!-- /事務職用 -->
									</div>

									<hr>

									<div class="form-group">
										<div class="col-lg-12">
											<label class="col-sm-1 control-label"><a href="#" title="前後15分加算済">拘束時間</a></label>
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
											<label class="col-sm-1 control-label"><a href="#" title="拘束時間から休憩を引いたもの">労働時間</a></label>
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
											<label class="col-sm-1 control-label">定時時間</label>
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
											<label class="col-sm-2 control-label">平残（1.0）</label>
											<div class="col-sm-2">
												<?php
													echo $this->Form->input('heijitsuzangyo_nashi',[
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
									<div class="form-group">

										<div class="col-lg-12">
											<label class="col-sm-1 control-label">平残(1.25)</label>
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
											<label class="col-sm-1 control-label">平深(0.25)</label>
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
											<label class="col-sm-1 control-label">休残(1.25)</label>
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
											<label class="col-sm-1 control-label">休深(0.25)</label>
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
											<label class="col-sm-1 control-label">法残(1.35)</label>
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
											<label class="col-sm-1 control-label">法深(0.25)</label>
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
									<div class="form-group" id="untenteate1">
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
											<label class="col-sm-1 control-label"><a href="#" title="出光運転手↔化学品運転手の場合、1500円">乗務</a></label>
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
													if(isset($ordertran1['Ordertran']['Carmaster']['trailerteate'])){
														echo $this->Form->input('trailer_teate',[
															'label' => false,
															'div' => false,
															'class' => 'form-control',
															'style' => 'width:100px',
															'default'=>$ordertran1['Ordertran']['Carmaster']['trailerteate']
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
										<?php echo $this->Html->link(('戻る'), ['controller' => 'Host010s', 'action' => 'index'], ['class'=>array('btn btn-danger')]); ?>
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
<?php
$did = $userdata['User']['departmentmasters_id'];
?>
<script>
let bar = <?php echo $did; ?>;
if(bar==5){
	document.getElementById("untentako").style.display = "none";
	document.getElementById("untenteate1").style.display = "none";
}else{
}
</script>
