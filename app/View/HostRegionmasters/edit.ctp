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
							$this->Html->addCrumb('地域マスタ一覧', ['controller' => 'HostRegionmasters', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /地域修正 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
				    <div class="box-header well">
				        <h2>地域マスタ修正</h2>
				    </div>

				    <?php echo $this->Form->create('Areamaster',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

				    	<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

					    <br>
						<div class="form-group">
							<label class="col-sm-2 control-label">部署</label>
							<div class="form-inline">
								<div class="col-sm-4">
									<?php echo $this->Form->input('departmentmasters_id',array('label'=>false, 'type'=>'select', 'div'=>false, 'class'=>'form-control', 'empty'=>'選択してください', 'id'=>'makerPulldown')); ?>
								</div>
							</div>
						</div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">地域CD</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('areacode',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">地域名称</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('areaname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">手当１</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php echo $this->Form->input('teate_1',['label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0']); ?><label>　円</label>
					      	</div>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">手当２</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php echo $this->Form->input('teate_2',['label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0']); ?><label>　円</label>
					      	</div>
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
