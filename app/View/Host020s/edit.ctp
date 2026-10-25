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
							$this->Html->addCrumb('ガソリン使用量一覧', ['action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>
			<!-- ガソリン使用量登録 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>ガソリン使用量変更</h2>
						    </div>
						    <div class="box-content">
							<?php echo $this->Form->create('Gasusetran', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>
								<!-- 運転手選択 -->
									<div class="box-content">
										<div class="control-group">
											<label class="control-label">従業員名</label>
											<div class="form-inline">
												<?php
													echo $this->Form->input('users_id',[
														'label' => false,
														'type' => 'select',
														'div' => false,
														'class' => 'form-control',
														'options' => $drivers,
														'empty' => '選択して下さい',
													]);
												?>
											</div>
										</div>
									</div>
								<!-- /運転手選択 -->
								<!-- 油種選択 -->
									<div class="box-content">
										<div class="control-group">
											<label class="control-label" for="selectError">油種</label>
											<div class="form-inline">
												<?php
													echo $this->Form->input('gaskind', [
														'label' => false,
														'type' => 'select',
														'div' => false,
														'class' => 'form-control',
														'options' => $flgmasters,
														'empty' => '選択してください',
													]);
												?>
											</div>
										</div>
									</div>
								<!-- /荷主選択 -->

								<!-- 給油日選択 -->
									<div class="box-content">
										<div class="control-group">
											<label class="control-label">給油日時</label>
											<div class="form-inline">
												<?php
													echo $this->Form->input('chargedate',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'default' => '',
														'class' => 'datepicker1 form-control',
													]);
												?>
											</div>
										</div>
									</div>
								<!-- /給油日選択 -->
								<!-- 数量 -->
									<div class="box-content">
										<div class="control-group">
											<label class="control-label">数量</label>
											<div class="form-inline">
												<?php
													echo $this->Form->input('amount', [
														'label'=>false,
														'div'=>false,
														'class'=>'form-control',
														'placeholder' => '数量を入力',
														'size'=>3
													]);
												?>
											</div>
										</div>
									</div>
								<!-- /数量 -->
								<div class="box-content">
									<?php
										echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' 登録'), [
											'type' => 'submit',
											'div' => false,
											'class' => 'btn btn-info',
											'name' => 'submit',
											'escape' => false,
										]);
									?>
								</div>

							<?php echo $this->Form->end(); ?>

							</div>
					    </div>
				    </div>
			    </div>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
<?php echo $this->Html->script(['jQuery.datepicker.js']); ?>

<script type="text/javascript">
//jQuery.noConflict();

jQuery(function(){
/*
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional[ "ja" ] );
	var dateFormat = 'yy/mm/dd';
	jQuery('.datepicker').datepicker({
		dateFormat: 'yy/mm/dd(DD)'
	});

	jQuery(document).ready(function(){
			jQuery.datetimepicker.setLocale('ja');
			jQuery('#datetimepicker1').datetimepicker({
				step:30,
			});
	});
*/

	jQuery(document).ready(function(){
	    jQuery('input.timepicker').timepicker({
	    	timeFormat: 'HH:mm',
	    	interval: 30,
	    	minTime: '0',
	    	defaultTime: 'now',
	    	startTime: '0',
	    	dynamic: false,
	    	scrollbar: true
	    });
	});

});
</script>
