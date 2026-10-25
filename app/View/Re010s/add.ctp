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
							$this->Html->addCrumb('受付履歴', ['controller' => 'Re010s', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /受付編集 -->
			<div class="row">
				<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2>受付</h2>
						</div>
							<?php echo $this->Form->create('Receptionmessage',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

							<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>
						<div class="row">
							<div class="col-lg-12">
								　
							</div>
						</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">来社年月日</label>
								<div class="col-sm-2">
									<?php
										echo $this->Form->input('receptiondate',[
											'label'=>false,
											'type'=>'text',
											'div'=>false,
											'id'=>'datetimepicker1'
										]);
									?>
								</div>
							</div>
						    <div class="form-group">
						      <label class="col-sm-2 control-label">会社名1</label>
						      <div class="col-sm-5">
						      	<?php echo $this->Form->input('receptioncomment',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
						      </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">名前1</label>
						      <div class="col-sm-5">
						      	<?php echo $this->Form->input('personalname',array('label'=>false,'div'=>false, 'class'=>'form-control','style'=>'width:400px;')); ?>
						      </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">会社名2</label>
						      <div class="col-sm-5">
						      	<?php echo $this->Form->input('receptioncomment2',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
						      </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">名前2</label>
						      <div class="col-sm-5">
						      	<?php echo $this->Form->input('personalname2',array('label'=>false,'div'=>false, 'class'=>'form-control','style'=>'width:400px;')); ?>
						      </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">会社名3</label>
						      <div class="col-sm-5">
						      	<?php echo $this->Form->input('receptioncomment3',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
						      </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">名前3</label>
						      <div class="col-sm-5">
						      	<?php echo $this->Form->input('personalname3',array('label'=>false,'div'=>false, 'class'=>'form-control','style'=>'width:400px;')); ?>
						      </div>
						    </div>

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

<script>
$(function(){
	$.datetimepicker.setLocale('ja');
	$('#datetimepicker1').datetimepicker({
		step:30,
		format: 'Y-m-d H:i'
	});
});
</script>
