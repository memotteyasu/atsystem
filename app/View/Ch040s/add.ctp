<!-- 勤怠登録 -->
<!-- Ch040s/add -->
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
									$this->Html->addCrumb('勤怠管理', ['controller' => 'Ch040s', 'action' => 'index']);
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
									<p>
										<?php
										if($processtrans){
											foreach($processtrans as $processtran) {
												$week = ['日', '月', '火', '水', '木', '金', '土'];
												$date = new DateTime($this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd']);
												echo $date->format('Y年m月d日').'（'.$week[$date->format('w')].'）'.'【車両】'.$processtran['Ordertran']['Carmaster']['plateno']. '【乗務員】'.$userdata['User']['usernameK'];
												echo nl2br("\n");
												foreach($processtran['Ordertran']['Processtran'] as $dprocess) {
													$starttime = new DateTime($dprocess['processstarttime']);
													$endtime = new DateTime($dprocess['processendtime']);
													echo $dprocess['Workdetailmaster']['workname'].'　'.$starttime->format('H:i').'　'.$endtime->format('H:i').'　'.$dprocess['workddetailcomment'];
													echo nl2br("\n");
												}
											}
										}else{
											$week = ['日', '月', '火', '水', '木', '金', '土'];
											$date = new DateTime($this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd']);
											echo $date->format('Y年m月d日').'（'.$week[$date->format('w')].'）'.$userdata['User']['usernameK'];
											echo "　";
											//echo nl2br("\n");
										}
										?><?php
												echo $this->Form->input('flgmasters_id',[
												'label'=>false,
												'div'=>false,
												'type'=>'select',
												'style'=>'height:30px',
												'value'=>11,
												]);
											?>
									</p>
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
											<label class="col-sm-1 control-label">トレーラー</label>
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
										<?php echo $this->Html->link(('戻る'), ['controller' => 'Ch040s', 'action' => 'index'], ['class'=>array('btn btn-danger')]); ?>
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

<script type="text/javascript">
//jQuery.noConflict();
jQuery(function(){
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional[ "ja" ] );
	var dateFormat = 'yy/mm/dd';
	jQuery('.datepicker').datepicker({
		dateFormat: 'yy/mm/dd(DD)'
	});

	jQuery(document).ready(function(){
			jQuery.datetimepicker.setLocale('ja');
			jQuery('#datetimepicker1').datetimepicker({
				format:'Y-m-d H:i',
				step:30,
			});
			jQuery('#datetimepicker2').datetimepicker({
				format:'Y-m-d H:i',
				step:30,
			});
	});

	jQuery(document).ready(function(){
	    jQuery('input.timepicker').timepicker({
	    	timeFormat: 'HH:mm',
	    	interval: 30,
	    	minTime: '0',
	    	defaultTime: '',
	    	startTime: '0',
	    	dynamic: false,
	    	scrollbar: true
	    });
	});

});
</script>
