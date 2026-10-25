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
									<th class="host0501">出勤1</th>
									<th class="host0501">退勤1</th>
									<th class="host0501">走行1h</th>
									<th class="host0501">作業1h</th>
									<th class="host0501">待機1h</th>
									<th class="host0502">出勤2</th>
									<th class="host0502">退勤2</th>
									<th class="host0502">走行2h</th>
									<th class="host0502">作業2h</th>
									<th class="host0502">待機2h</th>
									<th class="host0503">出勤3</th>
									<th class="host0503">退勤3</th>
									<th class="host0503">走行3h</th>
									<th class="host0503">作業3h</th>
									<th class="host0503">待機3h</th>
								</tr>
								<tr>
									<th colspan="1" style="color:#ff3399;text-align:center!important;"></th>
									<th colspan="1" style="color:#ff3399;text-align:center!important;">走行h</th>
									<th colspan="1" style="color:#ff3399;text-align:center!important;">作業h</th>
									<th colspan="1" style="color:#ff3399;text-align:center!important;">待機h</th>
									<th colspan="1" style="color:#ff3399;text-align:center!important;">勤務h</th>
									<th colspan="1" style="color:#ff3399;text-align:center!important;">km</th>
									<th colspan="1" style="color:#ff3399;text-align:center!important;">夜勤</th>
									<th colspan="1" style="color:#ff3399;text-align:center!important;">通常残業</th>
									<th colspan="1" style="color:#ff3399;text-align:center!important;">乗務残業</th>
									<th colspan="1" style="color:#ff3399;text-align:center!important;">電話</th>
									<th colspan="1" style="color:#ff3399;text-align:center!important;">4h連続</th>
									<th colspan="6" style="color:#ff3399;text-align:center!important;"></th>
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
									if(substr($userdetail['Kintaitran'][0]['timestart'],0,5) != '00:00'){
										//勤怠がない場合は出力しない
										echo '<td class="host0501">';
											echo substr($userdetail['Kintaitran'][0]['timestart'],0,5);
										echo '</td>';
										echo '<td class="host0501">';
											echo substr($userdetail['Kintaitran'][0]['timeend'],0,5);
										echo '</td>';
										echo '<td class="host0501">';
											echo substr($userdetail['Kintaitran'][0]['tako1_soukou_h'],0,5);
										echo '</td>';
										echo '<td class="host0501">';
											echo substr($userdetail['Kintaitran'][0]['tako1_sagyou_h'],0,5);
										echo '</td>';
										echo '<td class="host0501">';
											echo substr($userdetail['Kintaitran'][0]['tako1_taiki_h'],0,5);
										echo '</td>';
										echo '<td class="host0502">';
											echo substr($userdetail['Kintaitran'][0]['timestart2'],0,5);
										echo '</td>';
										echo '<td class="host0502">';
											echo substr($userdetail['Kintaitran'][0]['timeend2'],0,5);
										echo '</td>';
										echo '<td class="host0502">';
											echo substr($userdetail['Kintaitran'][0]['tako2_soukou_h'],0,5);
										echo '</td>';
										echo '<td class="host0502">';
											echo substr($userdetail['Kintaitran'][0]['tako2_sagyou_h'],0,5);
										echo '</td>';
										echo '<td class="host0502">';
											echo substr($userdetail['Kintaitran'][0]['tako2_taiki_h'],0,5);
										echo '</td>';
										echo '<td class="host0503">';
											echo substr($userdetail['Kintaitran'][0]['timestart3'],0,5);
										echo '</td>';
										echo '<td class="host0503">';
											echo substr($userdetail['Kintaitran'][0]['timeend3'],0,5);
										echo '</td>';
										echo '<td class="host0503">';
											echo substr($userdetail['Kintaitran'][0]['tako3_soukou_h'],0,5);
										echo '</td>';
										echo '<td class="host0503">';
											echo substr($userdetail['Kintaitran'][0]['tako3_sagyou_h'],0,5);
										echo '</td>';
										echo '<td class="host0503">';
											echo substr($userdetail['Kintaitran'][0]['tako3_taiki_h'],0,5);
										echo '</td>';
									}else{
										echo '<td colspan="15"></td>';
									}
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
										echo '<td colspan="1" align="center">';
										//夜勤歩合
										if($userdetail['Kintaitran'][0]['jyoumushinyaflg']){
											echo 'あり';
										}else{
											echo '';
										}
										echo '</td>
										<td colspan="1" align="center">';
										//通常残業（職員用）
										if($userdetail['Kintaitran'][0]['zangyoflg']){
											echo 'あり';
									 	}else{
									 		echo '';
									 	}
										echo '</td>
										<td colspan="1" align="center">';
										//乗務残業（職員用）
										if($userdetail['Kintaitran'][0]['jyoumuflg']){
											echo 'あり';
									 	}else{
									 		echo '';
									 	}
										echo '</td>
										<td colspan="1" align="center">';
										//電話歩合（職員用）
										if($userdetail['Kintaitran'][0]['telflg']){
											echo 'あり';
										}else{
											echo '';
										}
										echo '</td>
										<td colspan="1" align="center">';
										//4h連続（職員用）
										if($userdetail['Kintaitran'][0]['shiharaisuryo']){
											echo '4h';
										}else{
											echo '';
										}
										echo '</td>
										<td colspan="5" align="left">';
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
