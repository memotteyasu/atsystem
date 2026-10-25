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
							$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
							$this->Html->addCrumb('評価履歴一覧', ['controller' => 'UsersEvaluationmasters', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- 評価履歴 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				    <div class="box-inner">
				    <div class="box-header well">
				        <h2><?php echo $selectuser['User']['usernameK']; ?>　評価登録</h2>
				    </div>

					<?php echo $this->Form->create('UsersEvaluationmaster',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

					    <br>
						<div class="form-group">
							<label class="col-sm-2 control-label">キロ単価（円）</label>
							<div class="form-inline">
								<div class="col-sm-4">
									<?php echo $this->Form->input('evaluationkeisu',array(
									'label'=>false,
									'type'=>'text',
									'div'=>false,
									'class'=>'form-control',
									)); ?>
								</div>
							</div>
						</div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">適用開始</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('startdate',[
								'label'=>false,
								'type'=>'text',
								'div'=>false,
								'class'=>'datepicker1 form-control'
					      	]); ?>
					      </div>
					    </div>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">適用終了</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('enddate',[
								'label'=>false,
								'type'=>'text',
								'div'=>false,
								'class'=>'datepicker1 form-control'
					      	]); ?>
					      </div>
					    </div>

					    <hr>
					    <div class="form-group">
					      <div class="col-sm-offset-2 col-sm-10">
						    <?php echo $this->Form->submit('登録', array('div'=>false, 'class' => 'btn btn-info', 'name' => 'submit')); ?>
						    <?php echo $this->Html->link(('キャンセル'), array('action' => 'index',$selectuser['User']['id']), array('class'=>array('btn btn-danger'))); ?>
						    <?php //echo $this->Html->link(('キャンセル'), array('action' => 'index'), array('class'=>array('btn btn-danger'))); ?>
					      </div>
					    </div>

				    <?php echo $this->Form->end(); ?>
				    </div>

			    </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
<?php echo $this->Html->script(['jQuery.datepicker.js']); ?>

