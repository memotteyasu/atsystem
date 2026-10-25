<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
			<?php echo $this->element('Sidemenu'); ?>
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
						<?php
							$this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
							$this->Html->addCrumb('納入先マスタ一覧', ['controller' => 'HostDeliverymasters', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /納入先修正 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
				    <div class="box-header well">
				        <h2>納入先マスタ登録</h2>
				    </div>
				    <?php echo $this->Form->create(
				    		'Deliverymaster',
				    		[
				    			'label'=>false,
				    			'div'=>false,
				    			'class'=>'form-horizontal',
				    			'enctype'=>'multipart/form-data',
				    			'novalidate' => true,
				    			'onsubmit'=>'return confirm("登録します。よろしいですか？");'
				    		]
				    	);
				     ?>

				    	<br>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">部署</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('departmentmasters_id',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">納入先名称</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('deliveryname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">納入先略名称</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('deliveryshortname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">名称カナ（全角）</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('deliverynamekana',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">郵便番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('deliverypost',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">住所</label>
						      <div class="col-sm-4">
						      	<?php echo $this->Form->input('deliveryadd',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
						      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">電話番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('deliverytel',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">FAX番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('deliveryfax',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">地域</label>
					      <div class="col-sm-1">
					      	<?php echo $this->Form->input('areamasters_id',array('label'=>false, 'type'=>'select', 'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">納入先CD</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('deliverycode',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">手当１</label>
					      <div class="form-inline">
						      <div class="col-sm-4">
						      	<?php echo $this->Form->input('longdistancefee1',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?><label>　円</label>
						      </div>
						  </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">手当２</label>
					      <div class="form-inline">
						      <div class="col-sm-4">
						      	<?php echo $this->Form->input('longdistancefee2',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?><label>　円</label>
						      </div>
						  </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">営業キロ（名）</label>
					      <div class="form-inline">
						      <div class="col-sm-4">
						      	<?php echo $this->Form->input('kilometer1',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?><label>　km</label>
						      </div>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">営業キロ（四）</label>
					      <div class="form-inline">
						      <div class="col-sm-4">
						      	<?php echo $this->Form->input('kilometer2',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?><label>　km</label>
						      </div>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">所要時間</label>
					      <div class="col-sm-2">
							<?php
								echo $this->Form->input('processtime',[
									'label' => false,
									'type' => 'text',
									'div' => false,
									'class' => 'timepicker form-control',
								]);
							?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">詳細情報及び注意事項</label>
					      <div class="col-sm-9">
					      	<?php echo $this->Form->input('checkcomment',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label"><a href="https://www.google.com/maps/" target="_blank">google map</a></label>
					      <div class="col-sm-9">
					      	<?php echo $this->Form->input('googlemapurl',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">地図の詳細情報</label>
					      <div class="col-sm-9">
					      	<?php echo $this->Form->input('mapcomment',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?>
					      </div>
					    </div>

					    <div class="form-group">
							<label class="col-sm-2 control-label">画像1</label>
							<div class="col-sm-2">
								<?php echo $this->Form->input('Image.0.img',['label'=>false,'div'=>false, 'class'=>false, 'type'=>'file']); ?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">画像コメント1</label>
							<div class="col-sm-2">
								<?php echo $this->Form->input('Image.0.imgcomment',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">画像2</label>
							<div class="col-sm-2">
								<?php echo $this->Form->input('Image.1.img',['label'=>false,'div'=>false, 'class'=>false, 'type'=>'file']); ?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">画像コメント2</label>
							<div class="col-sm-2">
								<?php echo $this->Form->input('Image.1.imgcomment',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">画像3</label>
							<div class="col-sm-2">
								<?php echo $this->Form->input('Image.2.img',['label'=>false,'div'=>false, 'class'=>false, 'type'=>'file']); ?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">画像コメント3</label>
							<div class="col-sm-2">
								<?php echo $this->Form->input('Image.2.imgcomment',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
							</div>
						</div>

<!--
					    <div class="form-group">
					      <label class="col-sm-2 control-label">画像コメント</label>
					      <div class="col-sm-2">
					      	<?php //echo $this->Form->input('Deliverymasterimage.0.imgcomment',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
					      	<?php echo $this->Form->input('Deliverymasterimage.imgcomment',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">画像</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('Deliverymasterimage.img',['label'=>false,'div'=>false, 'class'=>false, 'type'=>'file', 'multiple']); ?>
					      </div>
					    </div>
-->

					    <hr>
					    <div class="form-group">
					      <div class="col-sm-offset-2 col-sm-10">
						    <?php echo $this->Form->submit('登録', array('div'=>false, 'class' => 'btn btn-info', 'name' => 'submit')); ?>
						    <?php echo $this->Html->link(('キャンセル'), array('action' => 'index'), array('class'=>array('btn btn-danger'))); ?>
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

