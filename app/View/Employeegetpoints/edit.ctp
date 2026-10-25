<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
<?php echo $this->element('HostSidemenu'); ?>
        <!-- left menu ends -->

        <noscript>
            <div class="alert alert-block col-md-12">
                <h4 class="alert-heading">Warning!</h4>

                <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
                    enabled to use this site.</p>
            </div>
        </noscript>

		<div id="content" class="col-lg-10 col-md-10 col-sm-10">
			<!-- content starts -->
			            <div>
						    <ul class="breadcrumb">
						        <li>
						            <?php echo $this->Html->link('Home',['controller'=>'HostTops','action'=>'index']); ?>
						        </li>
						        <li>
						            <?php echo $this->Html->link('ポイント履歴マスタ',['action'=>'index']); ?>
						        </li>
			            </div>

			<!-- /積地修正 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
					    <div class="box-header well">
					        <h2>ポイント履歴修正</h2>
					    </div>

						<?php echo $this->Form->create('Employeegetpoint',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

							<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

							<br>
							<div class="form-group">
								<label class="col-sm-2 control-label">受信者</label>
								<div class="form-inline">
									<div class="col-sm-3">
										<?php echo $this->Form->input('users_id',array('label'=>false,'div'=>false, 'class'=>'form-control', 'empty'=>'選択してください')); ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">ポイント獲得日</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											/*
											echo $this->Form->input('getdate',[
												'label'=>false,
												'div'=>false,
												'class'=>'form-control',
												'type'=>'datetime',
												'dateFormat'=>'YMD',
												'monthNames'=>false,
												'timeFormat'=>'24',
												'separator'=>[' 年 ', ' 月 ', ' 日　'],
												'maxYear' => date('Y')+10,
												'minYear' => date('Y')-10,
											]);
											*/
										?>
										<?php
											echo $this->Form->input('getdate',[
												'label'=>false,
												'div'=>false,
												'class'=>'datetimepicker1 form-control',
												'type'=>'text',
											]);
										?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">送信者</label>
								<div class="form-inline">
									<div class="col-sm-3">
										<?php echo $this->Form->input('sendeduserid',array('label'=>false,'div'=>false, 'class'=>'form-control', 'options'=>$sendedusers, 'empty'=>'選択してください')); ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">送信ポイント</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											$list = array_combine(range(100, 1000, 100), range(100, 1000, 100));
											echo $this->Form->input('point',array('type' => 'select', 'options' => $list, 'label' => false, 'div' => false, 'class' => 'form-control'));
										?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">送信メッセージ</label>
								<div class="col-sm-3">
									<?php echo $this->Form->textarea('sendreason',array('label'=>false,'div'=>false, 'class'=>'form-control', 'rows'=>'3')); ?>
								</div>
							</div>

						    <hr>
						    <div class="form-group">
						      <div class="col-sm-offset-2 col-sm-10">
							    <?php echo $this->Form->submit('修正', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']); ?>
							    <?php echo $this->Html->link(('キャンセル'), ['action' => 'index'], ['class'=>['btn btn-danger']]); ?>
						      </div>
						    </div>

					    <?php echo $this->Form->end(); ?>
					</div>
			    </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<script type="text/javascript">
jQuery(function(){
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional[ "ja" ] );
	var dateFormat = 'yy/mm/dd';
	jQuery('.datepicker').datepicker({
		dateFormat: 'yy/mm/dd(DD)'
	});

	jQuery(document).ready(function(){
			jQuery.datetimepicker.setLocale('ja');
			jQuery('input.datetimepicker1').datetimepicker({
				format:'Y-m-d H:i',
				step:30,
			});
	});
});
</script>
