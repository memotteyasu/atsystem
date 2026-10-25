<!-- 勤怠確認 -->
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

		<?php
			echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);

				echo $this->Form->input('selectym1', [
					'div' => false,
					'label' => false,
					'type' => 'text',
					'class' => 'datepicker1',
					'onchange'=>'this.form.submit()',
					'default' => $paramsDate
				]);

				echo '　　営業所　';
				echo $this->Form->input('branchmasters_id',[
					'label'=>false,
					'type'=>'select',
					// 'empty'=>'未選択',
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'default' => $branchmasters_id
				]);
				echo '　　';

				echo '部署　';
				echo $this->Form->input('departmentmasters_id',[
					'label'=>false,
					'type'=>'select',
					'empty' => [0 =>'未選択'],
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'default' => $departmentmasters_id
				]);

			echo $this->Form->end();
		?>
		<!-- /勤怠実績 -->
		<div class="row">
			<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="box-inner">
					<div class="box-header well">
						<h2><i class="glyphicon glyphicon-info-sign"></i>勤怠実績</h2>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable table-clickable">
							<thead>
								<tr>
									<th>名前</th>
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
									<th colspan="1" style="color:#ff3399;text-align:center!important;"></th>
									<th colspan="2" style="color:#ff3399;text-align:center!important;">夜勤</th>
									<th colspan="2" style="color:#ff3399;text-align:center!important;">通常残業</th>
									<th colspan="2" style="color:#ff3399;text-align:center!important;">乗務残業</th>
									<th colspan="2" style="color:#ff3399;text-align:center!important;">電話</th>
									<th colspan="12" style="color:#ff3399;text-align:center!important;"></th>
								</tr>
							</thead>
							<tbody>
							<?php
								$total_yoko_zangyo_gokei = $total_zangyo_houtei = $total_zangyo_houtei_shinya = $total_zangyo_kyujitsu = $total_zangyo_kyujitsu_shinya = $soukou_h = $sagyou_h = $taiki_h = $total_zangyo = $total_teigaku_zangyo = $total_shinya = $total_teigaku_shinya = $total_time = $heijitsuzangyo = $heijitsushinya = $kyujitsuzangyo = $kyujitsushinya = $houteikyujitsu = $houteikyujitsushinya =  '00:00';
								$total_hougaikingaku = $total_houteikingaku = $total_yakinbuai = $total_telbuai = $total_jyoumuzangyo = $total_normalzangyo = $sagyoteate2 = $soukoubuai = $sagyobuai = $sagyobuaishinya = $soukoukiro = $jyoumu_teate = $chain_teate = $eigyokiro = $shiharaisuryo = $koukadou_teate = 0;
								foreach ($users as $userdetail) {
									$yoko_zangyo_gokei = '00:00';
									echo '<tr>';
									echo '<td>';
										echo $userdetail['User']['usernameK'];
									echo '</td>';
								if($userdetail['Kintaitran']){
									echo '<td>';
										echo substr($userdetail['Kintaitran'][0]['timestart'],0,5);
									echo '</td>';
									echo '<td>';
										echo substr($userdetail['Kintaitran'][0]['timeend'],0,5);
									echo '</td>';
									echo '<td>';
										echo substr($userdetail['Kintaitran'][0]['timestart2'],0,5);
									echo '</td>';
									echo '<td>';
										echo substr($userdetail['Kintaitran'][0]['timeend2'],0,5);
									echo '</td>';
									echo '<td>';
										echo substr($userdetail['Kintaitran'][0]['timestart3'],0,5);
									echo '</td>';
									echo '<td>';
										echo substr($userdetail['Kintaitran'][0]['timeend3'],0,5);
									echo '</td>';
									echo '<td>';
										echo substr($userdetail['Kintaitran'][0]['tako_gokei_soukou_h'],0,5);
									echo '</td>';
									echo '<td>';
										echo substr($userdetail['Kintaitran'][0]['tako_gokei_sagyou_h'],0,5);
									echo '</td>';
									echo '<td>';
										echo substr($userdetail['Kintaitran'][0]['tako_gokei_taiki_h'],0,5);
									echo '</td>';
									echo '<td>';
										echo substr($userdetail['Kintaitran'][0]['kinmu_jikan'],0,5);
										$total_time = $this->Format->Addtime_2($total_time,$userdetail['Kintaitran'][0]['kinmu_jikan']);
									echo '</td>';
									echo '<td>';
										echo substr($userdetail['Kintaitran'][0]['tako_gokei_km'],0,5);
									echo '</td>';
									echo '<td>';
										if($userdetail['Kintaitran'][0]['heijitsuzangyo'] != '00:00:00'){
											echo substr($userdetail['Kintaitran'][0]['heijitsuzangyo'],0,5);
										}
										// $total_zangyo = $this->Format->Addtime_2($total_zangyo,$userdetail['Kintaitran'][0]['heijitsuzangyo_ori']);
									echo '</td>';
									echo '<td>';
										if($userdetail['Kintaitran'][0]['heijitsushinya'] != '00:00:00'){
											echo substr($userdetail['Kintaitran'][0]['heijitsushinya'],0,5);
										}
										// $total_shinya = $this->Format->Addtime_2($total_shinya,$userdetail['Kintaitran'][0]['heijitsushinya_ori']);
									echo '</td>';
									echo '<td>';
										if($userdetail['Kintaitran'][0]['kyujitsuzangyo'] != '00:00:00'){
											echo substr($userdetail['Kintaitran'][0]['kyujitsuzangyo'],0,5);
											$total_zangyo_kyujitsu = $this->Format->Addtime_2($total_zangyo_kyujitsu,$userdetail['Kintaitran'][0]['kyujitsuzangyo']);
										}
									echo '</td>';
									echo '<td>';
										if($userdetail['Kintaitran'][0]['kyujitsushinya'] != '00:00:00'){
											echo substr($userdetail['Kintaitran'][0]['kyujitsushinya'],0,5);
											$total_zangyo_kyujitsu_shinya = $this->Format->Addtime_2($total_zangyo_kyujitsu_shinya,$userdetail['Kintaitran'][0]['kyujitsushinya']);
										}
									echo '</td>';
									echo '<td>';
										if($userdetail['Kintaitran'][0]['houteikyujitsu'] != '00:00:00'){
											echo substr($userdetail['Kintaitran'][0]['houteikyujitsu'],0,5);
											$total_zangyo_houtei = $this->Format->Addtime_2($total_zangyo_houtei,$userdetail['Kintaitran'][0]['houteikyujitsu']);
										}
									echo '</td>';
									echo '<td>';
										if($userdetail['Kintaitran'][0]['houteikyujitsushinya'] != '00:00:00'){
											echo substr($userdetail['Kintaitran'][0]['houteikyujitsushinya'],0,5);
											$total_zangyo_houtei_shinya = $this->Format->Addtime_2($total_zangyo_houtei_shinya,$userdetail['Kintaitran'][0]['houteikyujitsushinya']);
										}
									echo '</td>';
									echo '<td>';
										//残業時間の横計
										$yoko_zangyo_gokei = $this->Format->Addtime_2($yoko_zangyo_gokei,$userdetail['Kintaitran'][0]['heijitsuzangyo_ori']);
										$yoko_zangyo_gokei = $this->Format->Addtime_2($yoko_zangyo_gokei,$userdetail['Kintaitran'][0]['kyujitsuzangyo']);
										$yoko_zangyo_gokei = $this->Format->Addtime_2($yoko_zangyo_gokei,$userdetail['Kintaitran'][0]['houteikyujitsu']);
										echo $yoko_zangyo_gokei;
										$total_yoko_zangyo_gokei = $this->Format->Addtime_2($total_yoko_zangyo_gokei,$yoko_zangyo_gokei);
									echo '</td>';
									echo '<td style="font-weight:600;">';
								 		$workprice2 = ClassRegistry::init('Workprice2');
								 		$workprice2data = [];
								 		$workprice2data = $workprice2->find('first',[
								 			'fields'=>[
								 				'Workprice2.zangyojikan',
								 				'Workprice2.shinyajikan'
								 			],
								 			'conditions'=>[
								 				'AND'=>[
									 				'Workprice2.startkilo <=' => $userdetail['Kintaitran'][0]['tako_gokei_km'],
									 				'Workprice2.endkilo >=' => $userdetail['Kintaitran'][0]['tako_gokei_km']
								 				]
								 			],
								 			'recursive'=>0
								 		]);
								 		if($workprice2data){
											echo substr($workprice2data['Workprice2']['zangyojikan'],0,5);
											$total_teigaku_zangyo = $this->Format->Addtime_2($total_teigaku_zangyo,$workprice2data['Workprice2']['zangyojikan']);
										}
									echo '</td>';
									echo '<td style="font-weight:600;">';
									if($workprice2data){
										echo substr($workprice2data['Workprice2']['shinyajikan'],0,5);
										$total_teigaku_shinya = $this->Format->Addtime_2($total_teigaku_shinya,$workprice2data['Workprice2']['shinyajikan']);
									}
									echo '</td>';
									echo '</tr>';
						//　　2行目
									echo '<tr style="color:#ff3399">';
										echo '<td align="center">';
										//出勤か否か
										switch($userdetail['Kintaitran'][0]['flgmasters_id']){
											case 11:
												echo '出勤';
												break;
											case 12:
												echo '<font color="#4169e1">欠勤</font>';
												break;
											case 13:
												echo '有休';
												break;
											case 14:
												echo '休業';
												break;
											case 15:
												echo '待機';
												break;
											case 17:
												echo '特休';
												break;
											case 18:
												echo '教育';
												break;
											case 19:
												echo '代休';
												break;
											case 20:
												echo '指定休';
												break;
											case 21:
												echo '遅早';
												break;
										}
										if($userdetail['Kintaitran'][0]['rinjicalendarflg']==1){
											echo '(法定外)';
										}
										if($userdetail['Kintaitran'][0]['rinjicalendarflg']==2){
											echo '(法定)';
										}
										echo '</td>
										<td colspan="2" align="center">';
										//夜勤歩合
										if($userdetail['Kintaitran'][0]['jyoumushinyaflg']){
											echo 'あり';
										}else{
											echo '';
										}
										echo '</td>
										<td colspan="2" align="center">';
										//通常残業（職員用）
										if($userdetail['Kintaitran'][0]['zangyoflg']){
											echo 'あり';
									 	}else{
									 		echo '';
									 	}
										echo '</td>
										<td colspan="2" align="center">';
										//乗務残業（職員用）
										if($userdetail['Kintaitran'][0]['jyoumuflg']){
											echo 'あり';
									 	}else{
									 		echo '';
									 	}
										echo '</td>
										<td colspan="2" align="center">';
										//電話歩合（職員用）
										if($userdetail['Kintaitran'][0]['telflg']){
											echo 'あり';
										}else{
											echo '';
										}
										echo '</td>
										<td colspan="12" align="left">';
									//個別手当の明細
							 		$kintaitranskobetsuteatemasters = ClassRegistry::init('KintaitranKobetsuteatemaster');
							 		$kintaitranskobetsuteatemastersdata = [];
							 		$kintaitranskobetsuteatemastersdata = $kintaitranskobetsuteatemasters->find('list',[
							 			'fields'=>[
							 				'KintaitranKobetsuteatemaster.id',
							 				'Kobetsuteatemaster.teatename'
							 			],
							 			'conditions'=>[
							 				'KintaitranKobetsuteatemaster.kintaitrans_id'=>$userdetail['Kintaitran'][0]['id']
							 			],
							 			'recursive'=>0
							 		]);
									if($kintaitranskobetsuteatemastersdata){
										foreach($kintaitranskobetsuteatemastersdata as $kkdata){
											echo $kkdata;
											echo ',';
										}
										echo '</td>';
									}else{
										echo '</td>';
									}
								}else{ // if
									echo '<td colspan="21"></td>';
								}
								} // foreach
							?>
						</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>


	<!-- content ends -->
	</div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
