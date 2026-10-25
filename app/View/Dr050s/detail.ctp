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
					echo date('Y年m月',strtotime($paramsDate)).'月分';
			?>

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
												<th>平残h</th>
												<th>深増h</th>
												<th>法外h</th>
												<th>深増h</th>
												<th>法定h</th>
												<th>深増h</th>
												<th>残業h</th>
												<th>定残h</th>
												<th>定深h</th>
											</tr>
											<tr>
												<th colspan="3" style="color:#ff3399;text-align:center!important;">作業歩合時外</th>
												<th colspan="3" style="color:#ff3399;text-align:center!important;">作業歩合深夜</th>
												<th colspan="2" style="color:#ff3399;text-align:center!important;">距離歩合</th>
												<th colspan="2" style="color:#ff3399;text-align:center!important;">個別歩合</th>
												<th colspan="2" style="color:#ff3399;text-align:center!important;">夜勤</th>
												<th colspan="2" style="color:#ff3399;text-align:center!important;">通常残業</th>
												<th colspan="2" style="color:#ff3399;text-align:center!important;">乗務残業</th>
												<th colspan="1" style="color:#ff3399;text-align:center!important;">電話</th>
												<th colspan="2" style="color:#ff3399;text-align:center!important;">法定外歩合</th>
												<th colspan="2" style="color:#ff3399;text-align:center!important;">法定歩合</th>
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
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['tako_gokei_km'],0,5);
													echo '</td>';
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['heijitsuzangyo_ori'],0,5);
														$total_zangyo = $this->Format->Addtime_2($total_zangyo,$kintaitran['Kintaitran']['heijitsuzangyo_ori']);
													echo '</td>';
													echo '<td>';
														echo substr($kintaitran['Kintaitran']['heijitsushinya_ori'],0,5);
														$total_shinya = $this->Format->Addtime_2($total_shinya,$kintaitran['Kintaitran']['heijitsushinya_ori']);
													echo '</td>';
													echo '<td>';
														if($kintaitran['Kintaitran']['kyujitsuzangyo'] != '00:00:00'){
															echo substr($kintaitran['Kintaitran']['kyujitsuzangyo'],0,5);
															$total_zangyo_kyujitsu = $this->Format->Addtime_2($total_zangyo_kyujitsu,$kintaitran['Kintaitran']['kyujitsuzangyo']);
														}
													echo '</td>';
													echo '<td>';
														if($kintaitran['Kintaitran']['kyujitsushinya'] != '00:00:00'){
															echo substr($kintaitran['Kintaitran']['kyujitsushinya'],0,5);
															$total_zangyo_kyujitsu_shinya = $this->Format->Addtime_2($total_zangyo_kyujitsu_shinya,$kintaitran['Kintaitran']['kyujitsushinya']);
														}
													echo '</td>';
													echo '<td>';
														if($kintaitran['Kintaitran']['houteikyujitsu'] != '00:00:00'){
															echo substr($kintaitran['Kintaitran']['houteikyujitsu'],0,5);
															$total_zangyo_houtei = $this->Format->Addtime_2($total_zangyo_houtei,$kintaitran['Kintaitran']['houteikyujitsu']);
														}
													echo '</td>';
													echo '<td>';
														if($kintaitran['Kintaitran']['houteikyujitsushinya'] != '00:00:00'){
															echo substr($kintaitran['Kintaitran']['houteikyujitsushinya'],0,5);
															$total_zangyo_houtei_shinya = $this->Format->Addtime_2($total_zangyo_houtei_shinya,$kintaitran['Kintaitran']['houteikyujitsushinya']);
														}
													echo '</td>';
													echo '<td>';
														//残業時間の横計
														$yoko_zangyo_gokei = $this->Format->Addtime_2($yoko_zangyo_gokei,$kintaitran['Kintaitran']['heijitsuzangyo_ori']);
														$yoko_zangyo_gokei = $this->Format->Addtime_2($yoko_zangyo_gokei,$kintaitran['Kintaitran']['kyujitsuzangyo']);
														$yoko_zangyo_gokei = $this->Format->Addtime_2($yoko_zangyo_gokei,$kintaitran['Kintaitran']['houteikyujitsu']);
														echo $yoko_zangyo_gokei;
														$total_yoko_zangyo_gokei = $this->Format->Addtime_2($total_yoko_zangyo_gokei,$yoko_zangyo_gokei);
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
													//2行目
													echo '<tr style="color:#ff3399">';
														echo '<td colspan="3" align="center">';
														echo number_format($kintaitran['Kintaitran']['teigakujikangai']);
														//合計計算
														$sagyobuai = $sagyobuai + $kintaitran['Kintaitran']['teigakujikangai'];
														echo '</td>
														<td colspan="3" align="center">';
														echo number_format($kintaitran['Kintaitran']['teigakushinya']);
														//合計計算
														$sagyobuaishinya = $sagyobuaishinya + $kintaitran['Kintaitran']['teigakushinya'];
														echo '</td>
														<td colspan="2" align="center">';
														echo number_format($kintaitran['Kintaitran']['soukoukiro']);
														//合計計算
														$soukoubuai = $soukoubuai + $kintaitran['Kintaitran']['soukoukiro'];
														echo '</td>
														<td colspan="2" align="center">';
														//個別手当合計額
														echo number_format($kintaitran['Kintaitran']['sagyoteate2']);
														//合計計算
														$sagyoteate2 = $sagyoteate2 + $kintaitran['Kintaitran']['sagyoteate2'];
														echo '</td>
														<td colspan="2" align="center">';
														//夜勤歩合
														if($kintaitran['Kintaitran']['jyoumushinyaflg']){
															$yakinbuai = 0;
															$yakinbuai = $user['basetanka'] * $user['jyoumushinyahour'];
															echo number_format($yakinbuai);
															//合計計算
															$total_yakinbuai = $total_yakinbuai + $yakinbuai;
														}else{
															echo '0';
														}
														echo '</td>
														<td colspan="2" align="center">';
														//通常残業（職員用）
														if($kintaitran['Kintaitran']['zangyoflg']){
															$normalzangyo = 0;
													 		$normalzangyo = $user['basetanka'] * $user['zangyohour'];
													 		echo number_format($normalzangyo);
													 		//合計計算
													 		$total_normalzangyo = $total_normalzangyo + $normalzangyo;
													 	}else{
													 		echo '0';
													 	}
														echo '</td>
														<td colspan="2" align="center">';
														//乗務残業（職員用）
														if($kintaitran['Kintaitran']['jyoumuflg']){
															$jyoumuzangyo = 0;
													 		$jyoumuzangyo = $user['basetanka'] * $user['jyoumuhour'];
													 		echo number_format($jyoumuzangyo);
													 		//合計計算
													 		$total_jyoumuzangyo = $total_jyoumuzangyo + $jyoumuzangyo;
													 	}else{
													 		echo '0';
													 	}
														echo '</td>
														<td align="center">';
														//電話歩合（職員用）
														if($kintaitran['Kintaitran']['telflg']){
															$telbuai = 0;
															$telbuai = $user['basetanka'] * $user['telhour'];
															echo number_format($telbuai);
															//合計計算
															$total_telbuai = $total_telbuai = $telbuai;
														}else{
															echo '0';
														}
														echo '</td>
														<td colspan="2" align="center">';
														echo number_format($kintaitran['Kintaitran']['hougaikingaku']);
														$total_hougaikingaku = $total_hougaikingaku + $kintaitran['Kintaitran']['hougaikingaku'];
														echo '</td>
														<td colspan="2" align="center">';
														echo number_format($kintaitran['Kintaitran']['houteikingaku']);
														$total_houteikingaku = $total_houteikingaku + $kintaitran['Kintaitran']['houteikingaku'];
														echo '</td>
													</tr>';
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
														echo '<tr>';
														echo '<td colspan="2">（個別）</td>';
														echo '<td colspan="19">';
														foreach($kintaitranskobetsuteatemastersdata as $kkdata){
															echo $kkdata;
															echo ',';
														}
														echo '</td>';
														echo '</tr>';
													}

													$heijitsuzangyo = $this->Format->Addtime_2($heijitsuzangyo, $kintaitran['Kintaitran']['heijitsuzangyo_ori']);
//													$heijitsuzangyo = $this->Format->Addtime_2($heijitsuzangyo, $kintaitran['Kintaitran']['heijitsuzangyo_nashi']);
													$kyujitsuzangyo = $this->Format->Addtime_2($kyujitsuzangyo, $kintaitran['Kintaitran']['kyujitsuzangyo']);
													$houteikyujitsu = $this->Format->Addtime_2($houteikyujitsu, $kintaitran['Kintaitran']['houteikyujitsu']);
													$soukou_h = $this->Format->Addtime_2($soukou_h, $kintaitran['Kintaitran']['tako_gokei_soukou_h']);
													$sagyou_h = $this->Format->Addtime_2($sagyou_h, $kintaitran['Kintaitran']['tako_gokei_sagyou_h']);
													$taiki_h = $this->Format->Addtime_2($taiki_h, $kintaitran['Kintaitran']['tako_gokei_taiki_h']);

													$soukoukiro = $soukoukiro + $kintaitran['Kintaitran']['tako_gokei_km'];
													$chain_teate = $chain_teate + $kintaitran['Kintaitran']['chain_teate'];
													$lastday = $kintaitran['Kintaitran']['kintaidate'];
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
														if($total_zangyo_kyujitsu > '00:00'){
															echo $total_zangyo_kyujitsu;
														}
													echo '</td>';
													echo '<td>';
														echo $total_zangyo_kyujitsu_shinya;
													echo '</td>';
													echo '<td>';
														echo $total_zangyo_houtei;
													echo '</td>';
													echo '<td>';
														echo $total_zangyo_houtei_shinya;
													echo '</td>';
													echo '<td>';
														echo $total_yoko_zangyo_gokei;
													echo '</td>';
													echo '<td>';
														echo $total_teigaku_zangyo;
													echo '</td>';
													echo '<td>';
														echo $total_teigaku_shinya;
													echo '</td>';
												echo '</tr>';
												/* 合計出力2行目 */
												echo '<tr style="color:#ff3300">';
													echo '<td colspan="3" align="center">';
													echo number_format($sagyobuai).'円';
													echo '</td>
													<td colspan="3" align="center">';
													echo number_format($sagyobuaishinya).'円';
													echo '</td>
													<td colspan="2" align="center">';
													echo number_format($soukoubuai).'円';
													echo '</td>';
													echo '<td colspan="2" align="center">';
													echo $sagyoteate2.'円';
													echo '</td>';
													echo '<td colspan="2" align="center">';
													echo number_format($total_yakinbuai).'円';
													echo '</td>';
													echo '<td colspan="2" align="center">';
													echo number_format($total_normalzangyo).'円';
													echo '</td>';
													echo '<td colspan="2" align="center">';
													echo number_format($total_jyoumuzangyo).'円';
													echo '</td>';
													echo '<td align="center">';
													echo number_format($total_telbuai).'円';
													echo '</td>';
													echo '<td colspan="2" align="center">';
													echo number_format($total_hougaikingaku).'円';
													echo '</td>';
													echo '<td colspan="2" align="center">';
													echo number_format($total_houteikingaku).'円';
													echo '</td>';
												echo '</tr>';
											?>
										</tbody>
									</table>
									<?php echo ' ';
									if(isset($lastday)){
										echo $lastday;
									}else{
										echo '当月はデータがまだありません';
									}
									echo ' 現在'; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>



		<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->


