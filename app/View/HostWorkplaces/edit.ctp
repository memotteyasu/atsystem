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
							$this->Html->addCrumb('作業場所一覧', ['controller' => 'HostWorkplaces', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /作業場所修正 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
				    <div class="box-header well">
				        <h2>作業場所<?php if ($this->action == 'edit') {
								echo '修正';
							} else {
								echo '登録';
							} ?>
						</h2>
				    </div>
				    <?php echo $this->Form->create('Workplace', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>

				    	<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>

				    	<br>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">部署</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('departmentmasters_id',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">作業場所名称</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('workplacename',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">略名称</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('workplaceshortname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">住所</label>
						      <div class="col-sm-4">
						      	<?php echo $this->Form->input('placeadd',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
						      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">電話番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('placetel',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">FAX番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('placefax',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
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


					    <hr>
					    <div class="form-group">
					      <div class="col-sm-offset-2 col-sm-10">
						    <?php echo $this->Form->submit('登録', array('div'=>false, 'class' => 'btn btn-info', 'name' => 'submit')); ?>
						    <?php echo $this->Html->link(('画像詳細画面'), ['controller'=>'HostWorkplaceimages','action' => 'index', $this->Form->value('Workplace.id')], ['class' => 'btn btn-success']); ?>
						    <?php if (isset($this->params['named']['page'])) {
									$return = ['controller' => $this->params['named']['page'], 'action' => 'index'];
							} else {
								$return = ['action' => 'index'];
							}
								echo $this->Html->link('キャンセル', $return, [
									'class' => 'btn btn-danger',
								]);
							?>
					      </div>
					    </div>

				    <?php echo $this->Form->end(); ?>

				    </div>
			    </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
