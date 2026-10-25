<!-- 直近２ｗ休み -->
<!-- Host013s -->
<style>
	table.table-clickable td[data-href]{
		cursor: pointer;
	}
	table.table-clickable td:hover{
		background-color:#e8e8e8 !important;
	}
</style>

<div class="ch-container">
	<div class="row">

	<!-- left menu starts -->
		<?php echo $this->element('Sidemenu'); ?>
	<!-- left menu ends -->

	<div id="content" class="col-lg-10 col-md-10 col-sm-10">
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

		<?php echo $this->element('Host013sForm'); ?>
		<!-- /一覧 -->
		<div class="row">
			<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="box-inner">
					<div class="box-header well">
						<h2><i class="glyphicon glyphicon-info-sign"></i>直近2週間カレンダー</h2>
					</div>
					<div class="box-content">
						<!--<div class="table-responsive">-->
						<table class="table table-striped table-bordered table-clickable">
							<thead>
								<tr>
									<th width="90px">社員名</th>
									<th align="center">km</th>
										<?php
											$date = new DateTime($paramsDate);
											$datenext = new DateTime($paramsDate);
											$baseymd=$date->format('Y-m').'-01';
											$basenext = $datenext->modify('+1 month')->format('Y-m').'-01';
											$bndate = explode('-',$basenext);

											for ($i = 1; $i <= 14; $i++) {
												echo '<th>';

												if ($i == 1) {
													$date2 = $date->format('m月d日');
												} else {
													$date2 = $date->modify('+1 day')->format('m月d日');
												}

												if ($week[$date->format('w')] == '日') {
													echo $date2.'<font color="#ff0000">('.$week[$date->format('w')].')</font>';
												} else {
													echo $date2.'('.$week[$date->format('w')].')';
												}

												echo '</th>';
											}
										?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($userlists as $userlist): ?>
								<?//php debug($userlist);?>
									<tr>
										<td>
											<?php
												echo $userlist['User']['usernameK'];
											?>
										</td>
										<td align="center">
											<?php
												$kmtext = '0km';
												if ($selectkintais) {
													foreach ($selectkintais as $selectkintai) {
														if ($userlist['User']['id'] == $selectkintai['Kintaitran']['users_id']) {
															$latestDate = new \DateTime($selectkintai['Kintaitran']['latestdate']);
															$kmtext = number_format($selectkintai['Kintaitran']['sum']).'km'.$latestDate->format('（Y.m.d）');
														}
													}
												}
												echo $kmtext;
											?>
											<!-- 1000km (2021.04.23) -->
										</td>
										<?php
									 		//calendarusermaseterからデータを取得
									 		$calendarusermaster = ClassRegistry::init('Calendarusermaster');
									 		$clumdata = [];
									 		$clumdata2 = [];
									 		$clumdata = $calendarusermaster->find('first',[
									 			'conditions'=>[
									 				'Calendarusermaster.calendartype'=>$userlist['User']['calendarflg'],
													'Calendarusermaster.month' => $baseymd
									 			],
									 			'recursive'=>-1
									 		]);
									 		// 翌月のカレンダーを取得
									 		$clumdata2 = $calendarusermaster->find('first',[
									 			'conditions'=>[
									 				'Calendarusermaster.calendartype'=>$userlist['User']['calendarflg'],
													'Calendarusermaster.month' => $basenext
									 			],
									 			'recursive'=>-1
									 		]);
											$date = new DateTime($paramsDate);
											for ($i = 1; $i <= 14; $i++):
										?>
											<td align="center" style="vertical-align:middle;">
												<?php
													$wday = substr('00'.$date->format('d'),-2);
													$wmonth = substr('00'.$date->format('m'),-2);
													if($wmonth != $bndate[1]){
														$wdayflg = $clumdata['Calendarusermaster']['day'.$wday];
													}else{
														$wdayflg = $clumdata2['Calendarusermaster']['day'.$wday];
													}
													if($wdayflg==1){
														echo '法定外';
													}
													if($wdayflg==2){
														echo '法定休日';
													}
													if($userlist['Kintaitran']){
														foreach($userlist['Kintaitran'] as $kintaitrans){
															if($kintaitrans['kintaidate']==$date->format('Y-m-d')){
																switch($kintaitrans['flgmasters_id']){
																	case 13:
																		echo '有休';
																		break;
																	case 14:
																		echo '休業';
																		break;
																	case 17:
																		echo '特休';
																		break;
																	case 19:
																		echo '代休';
																		break;
																}
															}
														}
													}
												?>
											</td>
										<?php
											$date->modify('+1 day');
											endfor;
										?>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<!--</div>-->
					</div>
				</div>
			</div>
		</div>

		<!-- CarandPerson 呼び出し -->

	<!-- content ends -->
	</div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<script>
$(function(){
  $('td[data-href]', 'table.table-clickable').on('click', function(){
    location.href = $(this).data('href');
  });
});
</script>
