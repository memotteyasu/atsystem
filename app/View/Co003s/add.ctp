<?php
	$selectid1 = '#OrdertranShippermastersId';
	$selectid3 = '#OrdertranTruckkindsId';
	$selectid4 = '#OrdertranTantomastersId';
	$action2 = 'ajax_tanto';
	$model = 'Ordertran';
	$model2 = 'Tantomaster';
	$cid = $this->params['named']['cid'];


	$this->Js->get($selectid1)->event(
		'change',
		$this->Js->request(
			['controller' => 'AjaxsSelects', 'action' => $action2, $model],
			[
				'update' => $selectid4,
				'dataExpression' => true,
				'data' => '$("'.$selectid1.'").serialize()',
			]
		)
	);

	echo $this->Js->writeBuffer();
?>
<style>
.ui-timepicker-container {z-index: 9999 !important;}
.ui-timepicker-standard {z-index: 9999 !important;}
</style>
<!-- Oi010s -->
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
							$this->Html->addCrumb('受注カレンダー', ['controller' => 'Co003s', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<?php echo $this->Form->create('Ordertran', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

				<div class="row">

					<div class="box col-md-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i> 受注新規登録</h2>
							</div>

							<?php
								if (!$this->params['named']) {
									$tid = $sid = $pid = 0;
									$date = new DateTime();
								} else {
									$date = new DateTime($this->params['named']['date']);
								}
							?>

							<!-- 荷主選択 -->
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">荷主</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('shippermasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty'=>'選択してください'
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /荷主選択 -->

					<div class="box-content">
<?php echo $this->Html->link('過去のデータをコピー',['controller'=>'Co005s','action'=>'index','id'=>1,'sid'=>1,'pid'=>1,'cid'=>$this->params['named']['cid'],'page'=>'Co003s','haidate'=>$this->params['named']['date']],['class'=>['btn btn-info']]); ?>
</div>

							<!-- 車両選択 -->
							<div class="box-content">
								<div class="control-group">
								<label class="control-label">車両</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('carmasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'options' => $carmasters,
												'selected' => $cid
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /車両選択 -->

							<!-- 回戦選択 -->
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">回戦</label>
									<div class="form-inline">
										<?php
											if (isset($this->params['named']['k'])) {
												$k = $this->params['named']['k'];
											} else {
												$k = 1;
											}
											$list = array_combine(range(1, 6), range(1, 6));
											echo $this->Form->input('kaisen', [
												'type' => 'select',
												'options' => $list,
												'label' => false,
												'div' => false,
												'class' => 'form-control',
												'default' => $k,
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /回戦選択 -->
							<!-- 課税非課税選択 -->
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">課税対象</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('taxflg', [
												'type' => 'select',
												'options' => $taxflgs,
												'label' => false,
												'div' => false,
												'class' => 'form-control',
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /課税非課税選択 -->

							<!-- 担当者名 -->
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">担当者名</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('tantomasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control'
											]);
										?>
									</div>
								</div>
							</div>
							<!-- 担当者名 -->


						</div>
					</div>

				</div>

				<div class="row">

					<div class="box col-md-12">
						<div class="box-inner">

							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i> 工程登録</h2>
							</div>

							<div class="box-content">

								<?php for ($i = 0; $i <= 7; $i++): ?>
									<br>

									<div class="form-inline">
											<?php
												echo $this->Form->input('Processtran.'.$i.'.users_id', [
													'type' => 'hidden',
													'value' => 0,
												]);
											?>
										<div class="input-group">
											<span class="input-group-addon">作業日時</span>
											<?php
												echo $this->Form->input('Processtran.'.$i.'.processstartdate',[
													'label' => false,
													'type' => 'text',
													'div' => false,
													'default' => $date->format('Y-m-d'),
													'class' => 'datepicker1 form-control',
													'style' => 'width:150px',
												]);
											?>
											<?php
												echo $this->Form->input('Processtran.'.$i.'.processstarttime',[
													'label' => false,
													'type' => 'text',
													'div' => false,
													// 'default' => date('H:i'),
													'default' => '',
													'class' => 'timepicker2 form-control',
													'style' => 'width:100px',
												]);
											?>
											<?php
												echo $this->Form->input('Processtran.'.$i.'.timenote',[
													'label'=>false,
													'type'=>'text',
													'div'=>false,
													'class'=>'form-control',
													'style'=>'width:100px',
													'placeholder'=>'AM PM'
												]);
											?>
										</div>
									</div>

									<div class="form-inline">

										<div class="input-group input-group-ex">
											<span class="input-group-addon">作業名称</span>
											<?php
												echo $this->Form->input('Processtran.'.$i.'.workdetailmasters_id', [
													'label' => false,
													'type' => 'select',
													'div' => false,
													'class' => 'form-control',
													'empty' => '選択してください',
													//'style' => 'width:150px',
												]);
											?>
										</div>

										<div class="input-group input-group-ex">
										<span class="input-group-addon"><a class="btn btn-primary" data-toggle="modal" data-target="#testModal<?php echo $i ?>">作業場所</a></span>
										<div class="modal fade" id="testModal<?php echo $i ?>" tabindex="-1">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<h4 class="modal-title" id="myModalLabel">作業場所新規追加</h4>
													</div>
													<div class="modal-body">
														<div class="row">
															<div class="form-group">
															<label class="col-sm-5 control-label">作業場所名称</label>
															<div class="col-sm-7">
															<input name="data[Workplace][workplacename][<?php echo $i ?>]" class="form-control" maxlength="45" type="text" id="WorkplaceWorkplacename<?php echo $i ?>"/>
															</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
															<label class="col-sm-5 control-label">略名称</label>
															<div class="col-sm-7">
															<input name="data[Workplace][workplaceshortname][<?php echo $i ?>]" class="form-control" maxlength="45" type="text" id="WorkplaceWorkplaceshortname<?php echo $i ?>"/>
															</div>
															</div>
														</div>
													</div>
													<div class="modal-footer">
														<button id="chgDataSub<?php echo $i ?>" type="button" class="btn btn-primary" data-dismiss="modal">追加</button>
													</div>
												</div>
											</div>
										</div>
										<?php
												echo $this->Form->input('Processtran.'.$i.'.workplaces_id', [
													'label' => false,
													'type' => 'select',
													'div' => false,
													'class' => 'form-control chosen',
													'empty' => '選択してください',
													//'style'=>'width:100px;',
												]);
											?>	
										</div>


										<div class="input-group input-group-ex">
										<span class="input-group-addon"><a class="btn btn-primary" data-toggle="modal" data-target="#testModal2<?php echo $i ?>">商品</a></span>
										<div class="modal fade" id="testModal2<?php echo $i ?>" tabindex="-1">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<h4 class="modal-title" id="myModalLabel">商品新規追加</h4>
													</div>
													<div class="modal-body">
														<div class="row">
															<div class="form-group">
															<label class="col-sm-5 control-label">商品名称</label>
															<div class="col-sm-7">
															<input name="data[Productcontainer][pdconname][<?php echo $i ?>]" class="form-control" maxlength="45" type="text" id="ProductcontainerPdconname<?php echo $i ?>"/>
															</div>
															</div>
														</div>
													</div>
													<div class="modal-footer">
														<button id="chgDataSub2<?php echo $i ?>" type="button" class="btn btn-primary" data-dismiss="modal">追加</button>
													</div>
												</div>
											</div>
										</div>

										<?php
											echo $this->Form->input('Processtran.'.$i.'.productcontainers_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control chosen',
												'empty' => '選択してください',
												//'style'=>'width:100px;',
											]);
										?>

									</div>

									<div class="form-inline">

										<div class="input-group">
											<span class="input-group-addon">コンテナNo.</span>
											<?php
												echo $this->Form->input('Processtran.'.$i.'.container_no', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control',
													'placeholder' => 'コンテナNo.を入力'
													//'style'=>'width:100px;',
												]);
											?>
										</div>

										<div class="input-group">
											<span class="input-group-addon">REF/BOOKING No.</span>
											<?php
												echo $this->Form->input('Processtran.'.$i.'.ref_no', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control',
													'placeholder' => 'REF/BOOKING No.を入力'
													//'style'=>'width:100px;',
												]);
											?>
										</div>

										<div class="input-group">
											<?php if ($i != 0): ?>
												<input type="button" id="copybutton<?= $i ?>" value="同上" class="btn btn-default" onclick="copybutton(<?= $i-1 ?>,<?= $i ?>)">
											<?php endif; ?>
										</div>

									</div>

									<div class="form-inline">
										<div class="input-group">
											<span class="input-group-addon">作業備考</span>
											<?php
												echo $this->Form->input('Processtran.'.$i.'.workddetailcomment', [
													'label'=>false,
													'type'=>'textarea',
													'rows' => 3,
													'div'=>false,
													'class'=>'form-control',
													'placeholder' => '作業備考を入力'
												]);
											?>
											<span class="input-group-addon"><?php echo $carmasters[$cid] ?></span>
										</div>
									</div>

								<?php endfor; ?>

								<hr></hr>


							</div>

						</div>
					</div>
				</div>

				<div class="box-content">
					<input type="submit" name="submit" value="登録">
					<?php
					/*
						echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' 登録'), [
							'type' => 'submit',
							'div' => false,
							'class' => 'btn btn-info',
							'name' => 'submit',
							'escape' => false,
						]);
						*/
					?>
				</div>

				<?php $bid = SessionHelper::read('BackData2.bid'); ?>
				<?php echo $this->Form->input('branchmasters_id',[
					'type'=>'hidden',
					'value'=>$bid
				]); ?>
			<?php echo $this->Form->end(); ?>


    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<script type="text/javascript">
	//jQuery.noConflict();
	$(".chosen").chosen({
		search_contains:true
	});

	// 工程登録フォームのコンテナNo.及びREFNo.の一つ上からのコピー
	function copybutton(a,b) {
		const ContainerNo = $("#Processtran"+a+"ContainerNo").val();
		$("#Processtran"+b+"ContainerNo").val(ContainerNo);
		const RefNo = $("#Processtran"+a+"RefNo").val();
		$("#Processtran"+b+"RefNo").val(RefNo);
		const ProductName1 = $("#Processtran"+a+"Productdetailmastername").val();
		$("#Processtran"+b+"Productdetailmastername").val(ProductName1);
	}
	//workplace追加用
	$("#chgDataSub0").click(function(){
		var workplacename = $('#WorkplaceWorkplacename0').val();
		var workplaceshortname = $('#WorkplaceWorkplaceshortname0').val();
		if(workplacename){
			$.ajax({
				url: '/Co003s/ajax_add',
				type: 'POST',
				dataType: 'text',
				data: {
					'wpn': workplacename,
					'wpsn': workplaceshortname
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const wpsname = first[1].split('"');
					$('#Processtran0WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})
	$("#chgDataSub1").click(function(){
		var workplacename = $('#WorkplaceWorkplacename1').val();
		var workplaceshortname = $('#WorkplaceWorkplaceshortname1').val();
		if(workplacename){
			$.ajax({
				url: '/Co003s/ajax_add',
				type: 'POST',
				dataType: 'text',
				data: {
					'wpn': workplacename,
					'wpsn': workplaceshortname
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const wpsname = first[1].split('"');
					$('#Processtran0WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})
	$("#chgDataSub2").click(function(){
		var workplacename = $('#WorkplaceWorkplacename2').val();
		var workplaceshortname = $('#WorkplaceWorkplaceshortname2').val();
		if(workplacename){
			$.ajax({
				url: '/Co003s/ajax_add',
				type: 'POST',
				dataType: 'text',
				data: {
					'wpn': workplacename,
					'wpsn': workplaceshortname
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const wpsname = first[1].split('"');
					$('#Processtran0WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})
	$("#chgDataSub3").click(function(){
		var workplacename = $('#WorkplaceWorkplacename3').val();
		var workplaceshortname = $('#WorkplaceWorkplaceshortname3').val();
		if(workplacename){
			$.ajax({
				url: '/Co003s/ajax_add',
				type: 'POST',
				dataType: 'text',
				data: {
					'wpn': workplacename,
					'wpsn': workplaceshortname
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const wpsname = first[1].split('"');
					$('#Processtran0WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})
	$("#chgDataSub4").click(function(){
		var workplacename = $('#WorkplaceWorkplacename4').val();
		var workplaceshortname = $('#WorkplaceWorkplaceshortname4').val();
		if(workplacename){
			$.ajax({
				url: '/Co003s/ajax_add',
				type: 'POST',
				dataType: 'text',
				data: {
					'wpn': workplacename,
					'wpsn': workplaceshortname
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const wpsname = first[1].split('"');
					$('#Processtran0WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})
	$("#chgDataSub5").click(function(){
		var workplacename = $('#WorkplaceWorkplacename5').val();
		var workplaceshortname = $('#WorkplaceWorkplaceshortname5').val();
		if(workplacename){
			$.ajax({
				url: '/Co003s/ajax_add',
				type: 'POST',
				dataType: 'text',
				data: {
					'wpn': workplacename,
					'wpsn': workplaceshortname
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const wpsname = first[1].split('"');
					$('#Processtran0WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})
	$("#chgDataSub6").click(function(){
		var workplacename = $('#WorkplaceWorkplacename6').val();
		var workplaceshortname = $('#WorkplaceWorkplaceshortname6').val();
		if(workplacename){
			$.ajax({
				url: '/Co003s/ajax_add',
				type: 'POST',
				dataType: 'text',
				data: {
					'wpn': workplacename,
					'wpsn': workplaceshortname
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const wpsname = first[1].split('"');
					$('#Processtran0WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})
	$("#chgDataSub7").click(function(){
		var workplacename = $('#WorkplaceWorkplacename7').val();
		var workplaceshortname = $('#WorkplaceWorkplaceshortname7').val();
		if(workplacename){
			$.ajax({
				url: '/Co003s/ajax_add',
				type: 'POST',
				dataType: 'text',
				data: {
					'wpn': workplacename,
					'wpsn': workplaceshortname
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const wpsname = first[1].split('"');
					$('#Processtran0WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7WorkplacesId').append($('<option>').html(wpsname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})

	//productcontainer追加用
	$("#chgDataSub20").click(function(){
		var pdconname = $('#ProductcontainerPdconname0').val();
		if(pdconname){
			$.ajax({
				url: '/Co003s/ajax_add2',
				type: 'POST',
				dataType: 'text',
				data: {
					'pdc': pdconname,
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const pdcname = first[1].split('"');
					$('#Processtran0ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})
	$("#chgDataSub21").click(function(){
		var pdconname = $('#ProductcontainerPdconname1').val();
		if(pdconname){
			$.ajax({
				url: '/Co003s/ajax_add2',
				type: 'POST',
				dataType: 'text',
				data: {
					'pdc': pdconname,
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const pdcname = first[1].split('"');
					$('#Processtran0ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})
	$("#chgDataSub22").click(function(){
		var pdconname = $('#ProductcontainerPdconname2').val();
		if(pdconname){
			$.ajax({
				url: '/Co003s/ajax_add2',
				type: 'POST',
				dataType: 'text',
				data: {
					'pdc': pdconname,
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const pdcname = first[1].split('"');
					$('#Processtran0ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})
	$("#chgDataSub23").click(function(){
		var pdconname = $('#ProductcontainerPdconname3').val();
		if(pdconname){
			$.ajax({
				url: '/Co003s/ajax_add2',
				type: 'POST',
				dataType: 'text',
				data: {
					'pdc': pdconname,
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const pdcname = first[1].split('"');
					$('#Processtran0ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})
	$("#chgDataSub24").click(function(){
		var pdconname = $('#ProductcontainerPdconname4').val();
		if(pdconname){
			$.ajax({
				url: '/Co003s/ajax_add2',
				type: 'POST',
				dataType: 'text',
				data: {
					'pdc': pdconname,
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const pdcname = first[1].split('"');
					$('#Processtran0ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})
	$("#chgDataSub25").click(function(){
		var pdconname = $('#ProductcontainerPdconname5').val();
		if(pdconname){
			$.ajax({
				url: '/Co003s/ajax_add2',
				type: 'POST',
				dataType: 'text',
				data: {
					'pdc': pdconname,
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const pdcname = first[1].split('"');
					$('#Processtran0ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})
	$("#chgDataSub26").click(function(){
		var pdconname = $('#ProductcontainerPdconname6').val();
		if(pdconname){
			$.ajax({
				url: '/Co003s/ajax_add2',
				type: 'POST',
				dataType: 'text',
				data: {
					'pdc': pdconname,
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const pdcname = first[1].split('"');
					$('#Processtran0ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})
	$("#chgDataSub27").click(function(){
		var pdconname = $('#ProductcontainerPdconname7').val();
		if(pdconname){
			$.ajax({
				url: '/Co003s/ajax_add2',
				type: 'POST',
				dataType: 'text',
				data: {
					'pdc': pdconname,
				},
				success: function(key){
					const first = key.split(',');
					const keyid = first[0].split('"');
					const pdcname = first[1].split('"');
					$('#Processtran0ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran1ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran2ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran3ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran4ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran5ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran6ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
					$('#Processtran7ProductcontainersId').append($('<option>').html(pdcname[1]).val(keyid[1])).trigger('chosen:updated');
				},
				error: function(){
					alert('faild');
				}
			});
		}
	})
</script>
