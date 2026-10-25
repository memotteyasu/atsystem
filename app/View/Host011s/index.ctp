<!-- 勤怠詳細 -->
<!-- Host011s -->
<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
<?php echo $this->element('HostSidemenu'); ?>
        <!-- left menu ends -->

        <div id="content" class="col-lg-10 col-sm-10">
            <!-- content starts -->
            <div>
		    <ul class="breadcrumb">
		        <li>
		        <?php
		        $this->Html->addCrumb('Top','/HostTops/index/');
		        $this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
		        $this->Html->addCrumb('勤怠管理','/Host010s/');
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
					    <div class="row">
					      <div class="col-lg-12">勤怠区分
					        <select name="a" style="margin:10px;">
					          <option value="1">出勤</option>
					          <option value="2">欠勤</option>
					          <option value="2">有給</option>
					          <option value="2">休み</option>
					          <option value="2">休出</option>
					          <option value="2">指定休</option>
					          <option value="2">特休</option>
					          <option value="2">教育</option>
					          <option value="2">代休</option>
					          <option value="2">待機</option>
					        </select>　（定時時間：7時間30分）
					      </div>
					    </div>
					    <hr>
					    <div class="row">
					      <div class="col-lg-4 col-md-4 col-sm-4">
					        始業時間　<input type="text" class="timepicker" style="width:100px;">
					      </div>
					      <div class="col-lg-4 col-md-4 col-sm-4">
					        終業時間　<input type="text" class="timepicker" style="width:100px;">
					      </div>
					      <div class="col-lg-4 col-md-4 col-sm-4">
					        休憩時間　<input type="text" class="timepicker" style="width:100px;">
					      </div>
					    </div>
<hr>
					    <div class="row">
					      <div class="col-lg-12 col-md-12 col-sm-12">
					        総勤務時間　<input type="text" class="timepicker" style="width:100px;">
					      </div>
					    </div>
<hr>
					    <div class="row">
					      <div class="col-lg-6 col-md-6 col-sm-6">
					        平日残業　<input type="text" class="timepicker" style="width:100px;">
					      </div>
					      <div class="col-lg-6 col-md-6 col-sm-6">
					        平日深夜　<input type="text" class="timepicker" style="width:100px;">
					      </div>
					    </div>
<hr>
					    <div class="row">
					      <div class="col-lg-6 col-md-6 col-sm-6">
					        <font color="#ff0000">休日残業</font>　<input type="text" class="timepicker" style="width:100px;">
					      </div>
					      <div class="col-lg-6 col-md-6 col-sm-6">
					        <font color="#ff0000">休日深夜</font>　<input type="text" class="timepicker" style="width:100px;">
					      </div>
					    </div>
<hr>
					    <div class="row">
					      <div class="col-lg-6 col-md-6 col-sm-6">
					        <font color="#ff0000">法定休日残業</font>　<input type="text" class="timepicker" style="width:100px;">
					      </div>
					      <div class="col-lg-6 col-md-6 col-sm-6">
					        <font color="#ff0000">法定休日深夜</font>　<input type="text" class="timepicker" style="width:100px;">
					      </div>
					    </div>
<hr>
					    <div class="row">
					      <div class="col-lg-4 col-md-4">
					        遅刻時間　<input type="text" class="timepicker" style="width:100px;">
					      </div>
					      <div class="col-lg-4 col-md-4">
					        早退時間　<input type="text" class="timepicker" style="width:100px;">
					      </div>
					      <div class="col-lg-4 col-md-4">
					        教育時間　<input type="text" class="timepicker" style="width:100px;">
					      </div>
					    </div>
<hr>
						<div class="row">
						  <div class="col-lg-12 col-md-12">
						  営業キロ　<input type="text" name="x" size=3> km
						  </div>
						</div>
					</div>
				    </div>
			    </div>
		    </div>


				<p>
				<?php echo $this->Html->link(('登録'), ['action' => 'add'], ['class'=>['btn btn-success']]); ?>
				</p>
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
