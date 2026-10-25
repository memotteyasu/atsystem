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
							$this->Html->addCrumb('作業名称マスタ一覧', ['controller' => 'HostWorkdetailmasters', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /営業所修正 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
					    <div class="box-header well">
					        <h2>作業名称マスタ修正</h2>
					    </div>

							<?php echo $this->Form->create('Workdetailmaster',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

							<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

							<br>
						    <div class="form-group">
						      <label class="col-sm-2 control-label">作業名称</label>
						      <div class="col-sm-3">
						      	<?php echo $this->Form->input('workname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
						      </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">部署</label>
						      <div class="col-sm-3">
						      	<?php echo $this->Form->input('departmentmasters_id',array('label'=>false, 'type'=>'select', 'div'=>false, 'class'=>'form-control', 'empty'=>'選択してください')); ?>
						      </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">表示順</label>
						      <div class="col-sm-3">
						      	<?php echo $this->Form->input('displayno',array('label'=>false,'div'=>false, 'class'=>'form-control','default'=>0)); ?>
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

</div><!--/.fluid-container-->
