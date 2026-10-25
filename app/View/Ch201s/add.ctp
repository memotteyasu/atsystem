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
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /会社マスタ修正 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				    <div class="box-inner">
				    <div class="box-header well">
				        <h2>個別経費登録</h2>
				    </div>

						<?php echo $this->Form->create('Expensestran',['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'enctype'=>'multipart/form-data', 'novalidate' => true]); ?>

						<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

						<br>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">個別経費名称</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('expensesname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">請求月</label>
					      <div class="col-sm-2">
					      	<?php 
								echo $this->Form->input('billconfirmdate',[
									'label' => false,
									'type' => 'text',
									'div' => false,
									'default' => $date,
									'class' => 'datepicker1 form-control',
									'style' => 'float:left',
								]);
							 ?>
					      </div>
					    </div>

						<div class="form-group">
					      <label class="col-sm-2 control-label">経費金額</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('expensesprice',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>円（税抜き）
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">荷主</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('shippermasters_id',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
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
