<div class="ch-container">
	<div class="row">

		<!-- left menu starts -->
		<?php if($user['rolemasters_id'] <= 29){ ?>
			<?php echo $this->element('ClerkSidemenu'); ?>
		<?php }else{ ?>
			<?php echo $this->element('DriverSidemenu'); ?>
			<?php } ?>
		<!-- left menu ends -->

		<div id="content" class="col-lg-10 col-sm-10">
			<!-- content starts -->
			<div>
				<ul class="breadcrumb">
					<li>
						<?php echo $this->Html->link('Top',['controller'=>$role.'Tops','action'=>'index']); ?>
					</li>
				</ul>
			</div>

			<?php
				echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);

					echo $this->Form->input('selectym1', [
						'div'=>false,
						'label'=>false,
						'type' => 'date',
						'dateFormat' => 'YM',
						'monthNames' => false,
						'minYear' => date('Y')-1,
						'maxYear' => date('Y')+1,
						'onchange'=>'this.form.submit()',
						'data-rel'=>'chosen',
						'style'=>'width:70px;',
						'default' => $paramsDate
					]);

					echo '月分';

				echo $this->Form->end();
			?>

			<div class="row">
				<div class="box col-md-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2>勤怠累計</h2>　　<span id="nowentrydate"></span>
						</div>
						<div class="box-content row">
							<div class="col-lg-12 col-md-12">
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th style="text-align:center;">走行時間</th>
												<th style="text-align:center;">作業時間</th>
												<th style="text-align:center;">待機時間</th>
												<th style="text-align:center;">点呼時間</th>
												<th style="text-align:center;">合計</th>
												<th style="text-align:center;">残業時間</th>
												<th style="text-align:center;">距離(km)</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$sou_roudou = $roudou_goukei_2 = $sou_tenko = $soukou_h = $sagyou_h = $taiki_h = $total_zangyo = $total_time = $heijitsuzangyo = $heijitsushinya = $kyujitsuzangyo = $kyujitsushinya = $houteikyujitsu = $houteikyujitsushinya =  '00:00';
												$soukoukiro = $jyoumu_teate = $chain_teate = $eigyokiro = $shiharaisuryo = $koukadou_teate = 0;
												foreach ($kintaitrans as $kintaitran) {
													$heijitsuzangyo = $this->Format->Addtime_2($heijitsuzangyo, $kintaitran['Kintaitran']['heijitsuzangyo_ori']);
//													$heijitsuzangyo = $this->Format->Addtime_2($heijitsuzangyo, $kintaitran['Kintaitran']['heijitsuzangyo_nashi']);
													$kyujitsuzangyo = $this->Format->Addtime_2($kyujitsuzangyo, $kintaitran['Kintaitran']['kyujitsuzangyo']);
													$houteikyujitsu = $this->Format->Addtime_2($houteikyujitsu, $kintaitran['Kintaitran']['houteikyujitsu']);
													$soukou_h = $this->Format->Addtime_2($soukou_h, $kintaitran['Kintaitran']['tako_gokei_soukou_h']);
													$sagyou_h = $this->Format->Addtime_2($sagyou_h, $kintaitran['Kintaitran']['tako_gokei_sagyou_h']);
													$taiki_h = $this->Format->Addtime_2($taiki_h, $kintaitran['Kintaitran']['tako_gokei_taiki_h']);
													if($kintaitran['Kintaitran']['flgmasters_id']==11){
														$sou_tenko = $this->Format->Addtime_2($sou_tenko, '00:30');
													}
													$soukoukiro = $soukoukiro + $kintaitran['Kintaitran']['tako_gokei_km'];
													$chain_teate = $chain_teate + $kintaitran['Kintaitran']['chain_teate'];
													$eigyokiro = $eigyokiro + $kintaitran['Kintaitran']['eigyokiro'];
													if($kintaitran['Kintaitran']['flgmasters_id']==11){
														$lastday = $kintaitran['Kintaitran']['kintaidate'];
														$roudou_goukei_2 = $this->Format->Addtime($kintaitran['Kintaitran']['kinmu_jikan'],'00:00:00');
														$sou_roudou = $this->Format->Addtime($sou_roudou,$roudou_goukei_2);
													}
												}
												/* 合計計算 */
//												$total_zangyo = $this->Format->Addtime_2($heijitsuzangyo, $kyujitsuzangyo);
//												$total_zangyo = $this->Format->Addtime_2($total_zangyo, $houteikyujitsu);
												$total_time = $this->Format->Addtime_2($total_time, $sou_roudou);
/* 2021-09-19
												$total_time = $this->Format->Addtime_2($total_time, $soukou_h);
												$total_time = $this->Format->Addtime_2($total_time, $sagyou_h);
												$total_time = $this->Format->Addtime_2($total_time, $taiki_h);
*/
												echo '<tr>';
													echo '<td style="text-align:center;">';
														echo $soukou_h;
													echo '</td>';
													echo '<td style="text-align:center;">';
														echo $sagyou_h;
													echo '</td>';
													echo '<td style="text-align:center;">';
														echo $taiki_h;
													echo '</td>';
													echo '<td style="text-align:center;">';
														echo $sou_tenko;
													echo '</td>';
													echo '<td style="text-align:center;">';
														echo $total_time;
													echo '</td>';
													echo '<td style="font-weight:600;font-size:1.3em;text-align:center;">';
														//echo $total_zangyo;
														echo $heijitsuzangyo;
													echo '</td>';
													echo '<td style="font-weight:600;font-size:1.3em;text-align:center;">';
														echo $this->Format->NumberFormat($soukoukiro);
													echo '</td>';
												echo '</tr>';
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="box col-md-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2>勤怠実績詳細</h2>
						</div>
						<div class="box-content row">
							<div class="col-lg-12 col-md-12">
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>日</th>
												<th>出勤1</th>
												<th>退勤1</th>
												<th>出勤2</th>
												<th>退勤2</th>
												<th>出勤3</th>
												<th>退勤3</th>
												<th>走行h</th>
												<th>作業h</th>
												<th>待機h</th>
												<th>勤務h</th>
												<th>km</th>
												<th>残業h</th>
												<th>深増h</th>
												<th>定残h</th>
												<th>定深h</th>
											</tr>
											<tr>
												<th colspan="2" style="color:#ff3399;text-align:center!important;"></th>
												<th colspan="7" style="color:#ff3399;text-align:center!important;"></th>
												<th colspan="2" style="color:#ff3399;text-align:center!important;">夜勤</th>
												<th colspan="2" style="color:#ff3399;text-align:center!important;">通常残業</th>
												<th colspan="2" style="color:#ff3399;text-align:center!important;">乗務残業</th>
												<th colspan="1" style="color:#ff3399;text-align:center!important;">電話</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$total_yoko_zangyo_gokei = $total_zangyo_houtei = $total_zangyo_houtei_shinya = $total_zangyo_kyujitsu = $total_zangyo_kyujitsu_shinya = $soukou_h = $sagyou_h = $taiki_h = $total_zangyo = $total_teigaku_zangyo = $total_shinya = $total_teigaku_shinya = $total_time = $heijitsuzangyo = $heijitsushinya = $kyujitsuzangyo = $kyujitsushinya = $houteikyujitsu = $houteikyujitsushinya =  '00:00';
												$total_hougaikingaku = $total_houteikingaku = $total_yakinbuai = $total_telbuai = $total_jyoumuzangyo = $total_normalzangyo = $sagyoteate2 = $soukoubuai = $sagyobuai = $sagyobuaishinya = $soukoukiro = $jyoumu_teate = $chain_teate = $eigyokiro = $shiharaisuryo = $koukadou_teate = 0;
												foreach ($kintaitrans as $kintaitran) {
													$yoko_zangyo_gokei = '00:00';
													echo '<tr>';
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['kintaidate'],-2);
													echo '</td>';
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['timestart'],0,5);
													echo '</td>';
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['timeend'],0,5);
													echo '</td>';
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['timestart2'],0,5);
													echo '</td>';
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['timeend2'],0,5);
													echo '</td>';
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['timestart3'],0,5);
													echo '</td>';
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['timeend3'],0,5);
													echo '</td>';
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['tako_gokei_soukou_h'],0,5);
													echo '</td>';
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['tako_gokei_sagyou_h'],0,5);
													echo '</td>';
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['tako_gokei_taiki_h'],0,5);
													echo '</td>';
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['kinmu_jikan'],0,5);
														$total_time = $this->Format->Addtime_2($total_time,$kintaitran['Kintaitran']['kinmu_jikan']);
													echo '</td>';
													echo '<td align="right"><b>';
														echo substr($kintaitran['Kintaitran']['tako_gokei_km'],0,5);
													echo '</b></td>';
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['heijitsuzangyo_ori'],0,5);
														$total_zangyo = $this->Format->Addtime_2($total_zangyo,$kintaitran['Kintaitran']['heijitsuzangyo_ori']);
													echo '</td>';
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['heijitsushinya_ori'],0,5);
														$total_shinya = $this->Format->Addtime_2($total_shinya,$kintaitran['Kintaitran']['heijitsushinya_ori']);
													echo '</td>';
													echo '<td style="font-weight:600;">';
														if($kintaitran['Kintaitran']['tako_gokei_km']>0){
													 		$workprice2 = ClassRegistry::init('Workprice2');
													 		$workprice2data = [];
													 		$workprice2data = $workprice2->find('first',[
													 			'fields'=>[
													 				'Workprice2.zangyojikan',
													 				'Workprice2.shinyajikan'
													 			],
													 			'conditions'=>[
													 				'AND'=>[
														 				'Workprice2.startkilo <=' => $kintaitran['Kintaitran']['tako_gokei_km'],
														 				'Workprice2.endkilo >=' => $kintaitran['Kintaitran']['tako_gokei_km']
													 				]
													 			],
													 			'recursive'=>0
													 		]);
															echo substr($workprice2data['Workprice2']['zangyojikan'],0,5);
															$total_teigaku_zangyo = $this->Format->Addtime_2($total_teigaku_zangyo,$workprice2data['Workprice2']['zangyojikan']);
														}
													echo '</td>';
													echo '<td style="font-weight:600;">';
														if($kintaitran['Kintaitran']['tako_gokei_km']>0){
															echo substr($workprice2data['Workprice2']['shinyajikan'],0,5);
															$total_teigaku_shinya = $this->Format->Addtime_2($total_teigaku_shinya,$workprice2data['Workprice2']['shinyajikan']);
														}
													echo '</td>';
													echo '</tr>';
								//　■■■■■■■2行目
													echo '<tr style="color:#ff3399">';
														//個別手当の明細
												 		$kintaitranskobetsuteatemasters = ClassRegistry::init('KintaitranKobetsuteatemaster');
												 		$kintaitranskobetsuteatemastersdata = [];
												 		$kintaitranskobetsuteatemastersdata = $kintaitranskobetsuteatemasters->find('list',[
												 			'fields'=>[
												 				'KintaitranKobetsuteatemaster.id',
												 				'Kobetsuteatemaster.teatename'
												 			],
												 			'conditions'=>[
												 				'KintaitranKobetsuteatemaster.kintaitrans_id'=>$kintaitran['Kintaitran']['id']
												 			],
												 			'recursive'=>0
												 		]);
														if($kintaitranskobetsuteatemastersdata){
															echo '<td colspan="2">（個別）</td>';
															echo '<td colspan="7">';
															foreach($kintaitranskobetsuteatemastersdata as $kkdata){
																echo $kkdata;
																echo ',';
															}
															echo '</td>';
														}else{
															echo '<td colspan="2"></td>';
															echo '<td colspan="7"></td>';
														}
														echo '<td colspan="2" align="center">';
														//夜勤歩合
														if($kintaitran['Kintaitran']['jyoumushinyaflg']){
															echo 'あり';
														}else{
															echo '　';
														}
														echo '</td>
														<td colspan="2" align="center">';
														//通常残業（職員用）
														if($kintaitran['Kintaitran']['zangyoflg']){
															echo 'あり';
														}else{
															echo '　';
													 	}
														echo '</td>
														<td colspan="2" align="center">';
														//乗務残業（職員用）
														if($kintaitran['Kintaitran']['jyoumuflg']){
															echo 'あり';
														}else{
															echo '　';
													 	}
														echo '</td>
														<td align="center">';
														//電話歩合（職員用）
														if($kintaitran['Kintaitran']['telflg']){
															echo 'あり';
														}else{
															echo '　';
														}
														echo '</td>
													</tr>';

													$heijitsuzangyo = $this->Format->Addtime_2($heijitsuzangyo, $kintaitran['Kintaitran']['heijitsuzangyo_ori']);
													$kyujitsuzangyo = $this->Format->Addtime_2($kyujitsuzangyo, $kintaitran['Kintaitran']['kyujitsuzangyo']);
													$houteikyujitsu = $this->Format->Addtime_2($houteikyujitsu, $kintaitran['Kintaitran']['houteikyujitsu']);
													$soukou_h = $this->Format->Addtime_2($soukou_h, $kintaitran['Kintaitran']['tako_gokei_soukou_h']);
													$sagyou_h = $this->Format->Addtime_2($sagyou_h, $kintaitran['Kintaitran']['tako_gokei_sagyou_h']);
													$taiki_h = $this->Format->Addtime_2($taiki_h, $kintaitran['Kintaitran']['tako_gokei_taiki_h']);

													$soukoukiro = $soukoukiro + $kintaitran['Kintaitran']['tako_gokei_km'];
													$chain_teate = $chain_teate + $kintaitran['Kintaitran']['chain_teate'];
												}
												/* 合計出力 */
												echo '<tr style="color:#ff3300">';
													echo '<td colspan="7">計</td>';
													echo '<td>';
														echo $soukou_h;
													echo '</td>';
													echo '<td>';
														echo $sagyou_h;
													echo '</td>';
													echo '<td>';
														echo $taiki_h;
													echo '</td>';
													echo '<td>';
														echo $total_time;
													echo '</td>';
													echo '<td>';
														echo $this->Format->NumberFormat($soukoukiro);
													echo '</td>';
													echo '<td>';
														echo $total_zangyo;
													echo '</td>';
													echo '<td>';
														echo $total_shinya;
													echo '</td>';
													echo '<td>';
														echo $total_teigaku_zangyo;
													echo '</td>';
													echo '<td>';
														echo $total_teigaku_shinya;
													echo '</td>';
												echo '</tr>';
											?>
										</tbody>
									</table>
								</div>
									<?php echo ' ';
									if(isset($lastday)){
										echo $lastday;
									}else{
										echo '当月はデータがまだありません';
									}
									echo ' 現在　';
									?>
							</div>
						</div>
					</div>
				</div>
			</div>


		<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
<script>
var ned = document.getElementById('nowentrydate');
ned.textContent = '(<?php echo $lastday; ?> 現在)';
</script>

