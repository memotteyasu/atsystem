<div class="ch-container">
	<div class="row">

		<!-- left menu starts -->
			<?php echo $this->element('DriverSidemenu'); ?>
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
						'style'=>'width:70px',
						'default' => $paramsDate
					]);

					echo '月分';

				echo $this->Form->end();
			?>

			<div class="row">
				<div class="box col-md-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2>時間外手当</h2>
						</div>
						<div class="box-content row">
							<div class="col-lg-12 col-md-12">
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th colspan="7"><div class="text-center">時間外勤務</div></th>
												<th colspan="10"><div class="text-center">手当</div></th>
											</tr>
											<tr>
												<th>普残</th>
												<th>普深</th>
												<th>休残</th>
												<th>休深</th>
												<th>法残</th>
												<th>法深</th>
												<th>総走行</th>
												<th>長距離</th>
												<th>回次</th>
												<th>多ヶ所</th>
												<th>トレ1</th>
												<th>乗務</th>
												<th>チェーン</th>
												<th>営業キロ</th>
												<th>支払数量</th>
												<th>高稼働</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$heijitsuzangyo = $heijitsushinya = $kyujitsuzangyo = $kyujitsushinya = $houteikyujitsu = $houteikyujitsushinya =  '00:00';
												$soukoukiro = $chokyori_teate = $kaiji_teate = $takasyo_teate = $trailer_teate = $jyoumu_teate = $chain_teate = $eigyokiro = $shiharaisuryo = $koukadou_teate = 0;
												foreach ($kintaitrans as $kintaitran) {
													$heijitsuzangyo = $this->Format->Addtime_2($heijitsuzangyo, $kintaitran['Kintaitran']['heijitsuzangyo']);
													$heijitsuzangyo = $this->Format->Addtime_2($heijitsuzangyo, $kintaitran['Kintaitran']['heijitsuzangyo_nashi']);
													$heijitsushinya = $this->Format->Addtime_2($heijitsushinya, $kintaitran['Kintaitran']['heijitsushinya']);
													$kyujitsuzangyo = $this->Format->Addtime_2($kyujitsuzangyo, $kintaitran['Kintaitran']['kyujitsuzangyo']);
													$kyujitsushinya = $this->Format->Addtime_2($kyujitsushinya, $kintaitran['Kintaitran']['kyujitsushinya']);
													$houteikyujitsu = $this->Format->Addtime_2($houteikyujitsu, $kintaitran['Kintaitran']['houteikyujitsu']);
													$houteikyujitsushinya = $this->Format->Addtime_2($houteikyujitsushinya, $kintaitran['Kintaitran']['houteikyujitsushinya']);

													$soukoukiro = $soukoukiro + $kintaitran['Kintaitran']['tako_gokei_km'];
													$chokyori_teate = $chokyori_teate + $kintaitran['Kintaitran']['chokyori_teate'];
													$kaiji_teate = $kaiji_teate + $kintaitran['Kintaitran']['kaiji_teate'];
													$takasyo_teate = $takasyo_teate + $kintaitran['Kintaitran']['takasyo_teate'];
													$trailer_teate = $trailer_teate + $kintaitran['Kintaitran']['trailer_teate'];
													$jyoumu_teate = $jyoumu_teate + $kintaitran['Kintaitran']['jyoumu_teate'];
													$chain_teate = $chain_teate + $kintaitran['Kintaitran']['chain_teate'];
													$eigyokiro = $eigyokiro + $kintaitran['Kintaitran']['eigyokiro'];
													$shiharaisuryo = $shiharaisuryo + $kintaitran['Kintaitran']['shiharaisuryo'];
													$koukadou_teate = $koukadou_teate + $kintaitran['Kintaitran']['koukadou_teate'];
												}
												echo '<tr>';
													echo '<td>';
														echo $heijitsuzangyo;
													echo '</td>';
													echo '<td>';
														echo $heijitsushinya;
													echo '</td>';
													echo '<td>';
														echo $kyujitsuzangyo;
													echo '</td>';
													echo '<td>';
														echo $kyujitsushinya;
													echo '</td>';
													echo '<td>';
														echo $houteikyujitsu;
													echo '</td>';
													echo '<td>';
														echo $houteikyujitsushinya;
													echo '</td>';

													echo '<td>';
														echo $this->Format->NumberFormat($soukoukiro);
													echo '</td>';
													echo '<td>';
														echo $this->Format->NumberFormat($chokyori_teate);
													echo '</td>';
													echo '<td>';
														echo $this->Format->NumberFormat($kaiji_teate);
													echo '</td>';
													echo '<td>';
														echo $this->Format->NumberFormat($takasyo_teate);
													echo '</td>';
													echo '<td>';
														echo $this->Format->NumberFormat($trailer_teate);
													echo '</td>';
													echo '<td>';
														echo $this->Format->NumberFormat($jyoumu_teate);
													echo '</td>';
													echo '<td>';
														echo $this->Format->NumberFormat($chain_teate);
													echo '</td>';
													echo '<td>';
														echo $this->Format->NumberFormat($eigyokiro);
													echo '</td>';
													echo '<td>';
														echo $this->Format->NumberFormat($shiharaisuryo);
													echo '</td>';
													echo '<td>';
														echo $this->Format->NumberFormat($koukadou_teate);
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



		<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->


