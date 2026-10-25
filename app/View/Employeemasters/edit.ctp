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
						            <?php echo $this->Html->link('社員マスタ',['action'=>'index']); ?>
						        </li>
			            </div>

			<!-- /積地修正 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				    <div class="box-header well">
				        <h2>社員マスタ修正</h2>
				    </div>

						<?php echo $this->Form->create('Employeemaster',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

						<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

						<br>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">名前</label>
					      <div class="col-sm-3">
					      	<?php echo $this->Form->input('employeename',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">読み仮名</label>
					      <div class="col-sm-3">
					      	<?php echo $this->Form->input('namecana',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
							<label class="col-sm-2 control-label">入社日</label>
							<div class="col-sm-3">
								<?php
									if ($this->params['pass'] == null) {
										$ddate = date('Y-m-d');
									} else {
										$ddate = $this->params['pass'];
									}
									echo $this->Form->input('entrydate',[
										'label'=>false,
										'type'=>'text',
										//'dateFormat'=>'YMD',
										//'monthNames'=>false,
										//'separator'=>['年','月','日'],
										//'maxYear' => date('Y') + 1,
										//'minYear' => date('Y') - 1,
										'div'=>false,
										'default'=>$ddate,
										//'class'=>'form-control'
										'class'=>'datepicker1 form-control'
									]);
								?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">適用開始日</label>
							<div class="col-sm-3">
								<?php
									if ($this->params['pass'] == null) {
										$ddate = date('Y-m-d');
									} else {
										$ddate = $this->params['pass'];
									}
									echo $this->Form->input('startdate',[
										'label'=>false,
										'type'=>'text',
										//'dateFormat'=>'YMD',
										//'monthNames'=>false,
										//'separator'=>['年','月','日'],
										//'maxYear' => date('Y') + 1,
										//'minYear' => date('Y') - 1,
										'div'=>false,
										'default'=>$ddate,
										//'class'=>'form-control'
										'class'=>'datepicker2 form-control'
									]);
								?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">適用終了日</label>
							<div class="col-sm-3">
								<?php
									if ($this->params['pass'] == null) {
										$ddate = date('Y-m-d');
									} else {
										$ddate = $this->params['pass'];
									}
									echo $this->Form->input('enddate',[
										'label'=>false,
										'type'=>'text',
										//'dateFormat'=>'YMD',
										//'monthNames'=>false,
										//'separator'=>['年','月','日'],
										//'maxYear' => date('Y') + 1,
										//'minYear' => date('Y') - 1,
										'div'=>false,
										'default'=>$ddate,
										//'class'=>'form-control'
										'class'=>'datepicker3 form-control'
									]);
								?>
							</div>
						</div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">部門</label>
					      <div class="form-inline">
						      <div class="col-sm-6">
						      	<div class="radio">
						      		<?php echo $this->Form->radio('branchmasters_id', $branchmasters, array('legend'=>false, 'separator'=>' ', 'div'=>false, 'class'=>'radio')); ?>
						      	</div>
						      </div>
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

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->

<script type="text/javascript">
$(function () {
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional["ja"]);
    var Format = 'yy-mm-dd';
    $('input.datepicker1').datepicker({
        dateFormat: 'yy-mm-dd',
        autoSize: 'true',
        showOtherMonths: true,
        selectOtherMonths: true,
        showButtonPanel: true,
    });
    $('input.datepicker2').datepicker({
        dateFormat: 'yy-mm-dd',
        autoSize: 'true',
        showOtherMonths: true,
        selectOtherMonths: true,
        showButtonPanel: true,
        onClose: function(selectedDate) {
        $("input.datepicker3").datepicker("option", "minDate", selectedDate);
      }
    });
    $('input.datepicker3').datepicker({
        dateFormat: 'yy-mm-dd',
        autoSize: 'true',
        showOtherMonths: true,
        selectOtherMonths: true,
        showButtonPanel: true,
        onClose: function(selectedDate) {
        $("input.datepicker2").datepicker("option", "maxDate", selectedDate);
      }
    });
});
</script>