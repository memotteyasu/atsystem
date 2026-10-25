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
							$this->Html->addCrumb('部署マスタ一覧', ['controller' => 'Departmentmasters', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /部署登録 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
					    <div class="box-header well">
					        <h2>部署マスタ登録</h2>
					    </div>

					    <?php echo $this->Form->create('Departmentmaster',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

					    	<br>
						    <div class="form-group">
						      <label class="col-sm-2 control-label">営業所</label>
						      <div class="col-sm-2">
						      	<?php echo $this->Form->input('branchmasters_id',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
						      </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">部署名</label>
						      <div class="col-sm-3">
						      	<?php echo $this->Form->input('departmentname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
						      </div>
						    </div>


						    <div class="form-group">
						      <label class="col-sm-2 control-label">距離歩合単価</label>
						      <div class="col-sm-2">
								<?php
									echo $this->Form->input('kirotanka',[
										'label' => false,
										'type' => 'text',
										'div' => false,
										'class' => 'form-control',
									]);
								?>
						      </div>
						    </div>
						    <hr>
						    <div class="form-group">
						      <div class="col-sm-offset-2 col-sm-10">
							    <?php echo $this->Form->submit('登録', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']); ?>
							    <?php echo $this->Html->link(('キャンセル'), ['action' => 'index'], ['class'=>['btn btn-danger']]); ?>
						      </div>
						    </div>

					    <?php echo $this->Form->end(); ?>
					</div>
			    </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->
