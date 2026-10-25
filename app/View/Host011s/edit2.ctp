<!-- 勤怠詳細 -->
<!-- Host011s -->
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
		        $this->Html->addCrumb('勤怠管理', ['controller' => 'Host010s', 'action' => 'index']);
		        echo $this->element('breadcrumbs');
		        ?><?php echo $this->Flash->render(); ?>
		        </li>
		    </ul>
		    </div>
<!--
検索窓はここに記述
 -->
		    <div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				    <div class="box-inner">
					    <div class="box-header well">
					        <h2>勤怠詳細（<?php echo $userdata['User']['usernameK']; ?>）<?php echo $this->params['named']['kyy']; ?>年<?php echo $this->params['named']['kmm']; ?>月<?php echo $this->params['named']['kdd']; ?>日</h2>
					    </div>
					    <div class="box-content">
<?php echo $this->Form->create('Kintaitran'); ?>
	<fieldset>
					    <div class="row">
					      <div class="col-lg-12">
<?php
		echo $this->Form->input('flgmasters_id',['label'=>'勤怠区分　']);
?>　定時：<?php
echo $userdata['Departmentmaster']['departmentname'].'　';
echo $teiji; ?>
					      </div>
					    </div>
					    <hr>
					    <div class="row">
					      <div class="col-lg-4 col-md-4 col-sm-4">
					      <?php echo $this->Form->input('datestart',['id'=>'datetimepicker1','label'=>'始業時間　','type'=>'text']); ?>
					      </div>
					      <div class="col-lg-4 col-md-4 col-sm-4">
					      <?php echo $this->Form->input('dateend',['id'=>'datetimepicker2','label'=>'終業時間　','type'=>'text']); ?>
					      </div>
					      <div class="col-lg-4 col-md-4 col-sm-4">
					      <?php echo $this->Form->input('kyukeitime',['class'=>'timepicker','type'=>'text','style'=>'width:100px;','label'=>'休憩時間　']); ?>
					      </div>
					    </div>
<hr>
					    <div class="row">
					      <div class="col-lg-6 col-md-6 col-sm-6">
					      <?php echo $this->Form->input('heijitsuzangyo',['class'=>'timepicker','type'=>'text','style'=>'width:100px;','label'=>'平日残業　']); ?>
					      </div>
					      <div class="col-lg-6 col-md-6 col-sm-6">
					      <?php echo $this->Form->input('heijitsushinya',['class'=>'timepicker','type'=>'text','style'=>'width:100px;','label'=>'平日深夜　']); ?>
					      </div>
					    </div>
<hr>
					    <div class="row">
					      <div class="col-lg-6 col-md-6 col-sm-6">
					      <?php echo $this->Form->input('kyujitsuzangyo',['class'=>'timepicker','type'=>'text','style'=>'width:100px;','label'=>'休日残業　']); ?>
					      </div>
					      <div class="col-lg-6 col-md-6 col-sm-6">
					      <?php echo $this->Form->input('kyujitsushinya',['class'=>'timepicker','type'=>'text','style'=>'width:100px;','label'=>'休日深夜　']); ?>
					      </div>
					    </div>
<hr>
					    <div class="row">
					      <div class="col-lg-6 col-md-6 col-sm-6">
					      <?php echo $this->Form->input('houteikyujitsu',['class'=>'timepicker','type'=>'text','style'=>'width:100px;','label'=>'法定休日残業　']); ?>
					      </div>
					      <div class="col-lg-6 col-md-6 col-sm-6">
					      <?php echo $this->Form->input('houteikyujitsushinya',['class'=>'timepicker','type'=>'text','style'=>'width:100px;','label'=>'法定休日深夜　']); ?>
					      </div>
					    </div>
<hr>
					    <div class="row">
					      <div class="col-lg-6 col-md-6">
					      <?php echo $this->Form->input('latetime',['class'=>'timepicker','type'=>'text','style'=>'width:100px;','label'=>'遅刻時間　']); ?>
					      </div>
					    </div>
<hr>
						<div class="row">
						  <div class="col-lg-12 col-md-12">
						  <?php echo $this->Form->input('eigyokiro',['type'=>'text','style'=>'width:100px;','label'=>'営業キロ(km)　']); ?>　<small>※運転手で有休の場合50km、待機の場合30kmつける。</small>
						  </div>
						</div>
						<?php echo $this->Form->hidden('id'); ?>
						<?php echo $this->Form->hidden('users_id'); ?>
					</div>
				    </div>
			    </div>
		    </div>
	</fieldset>
<?php echo $this->Form->end('保存する',[
	'class'=>['btn btn-success btn-sm']
	]); ?>
			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

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
