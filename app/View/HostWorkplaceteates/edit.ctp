<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
<?php echo $this->element('HostSidemenu'); ?>
        <!-- left menu ends -->

		<div id="content" class="col-lg-10 col-md-10 col-sm-10">
			<!-- content starts -->
            <div>
				<ul class="breadcrumb">
					<li>
						<?php
							$this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
							$this->Html->addCrumb('作業場所一覧', ['controller' => 'HostWorkplaces', 'action' => 'index']);
							$workplacename = $workplaces['Workplace']['workplacename'];
							$this->Html->addCrumb('場所【'.$workplacename.'】', ['controller' => 'HostWorkplaces', 'action' => 'index', $this->params['pass'][0]]);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
            </div>

			<!-- /手当修正 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
				    <div class="box-header well">
				        <h2>手当修正</h2>
				    </div>

				    <?php echo $this->Form->create('Workplaceteate',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

						<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

						<br>
					    <div class="form-group">
					      <label class="col-sm-3 control-label">営業所</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('branchmasters_id',
					      	[
						      	'type'=>'select',
						      	'label'=>false,
						      	'div'=>false,
						      	'class'=>'form-control',
						      	'option'=>$branchmasters,
					      	]); ?>
					      </div>
					    </div>
						<div class="form-group">
							<label class="col-sm-3 control-label">長距離手当単車（夏）</label>
							<div class="col-sm-2">
								<?php echo $this->Form->input('longteate_summer_tan',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?>
							</div>
							<label class="col-sm-3 control-label">長距離手当単車（冬）</label>
							<div class="col-sm-2">
								<?php echo $this->Form->input('longteate_summer_tol',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">長距離手当トレーラー（夏）</label>
							<div class="col-sm-2">
								<?php echo $this->Form->input('longteate_winter_tan',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?>
							</div>
							<label class="col-sm-3 control-label">長距離手当トレーラー（冬）</label>
							<div class="col-sm-2">
								<?php echo $this->Form->input('longteate_winter_tol',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?>
							</div>
						</div>
<hr>
						<div class="form-group">
							<label class="col-sm-3 control-label">営業キロ</label>
							<div class="col-sm-2">
								<?php echo $this->Form->input('eigyokilo',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?>
							</div>
							<!--
							<label class="col-sm-3 control-label">営業キロ（ガス）</label>
							<div class="col-sm-2">
								<?php echo $this->Form->input('eigyokilogas',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?>
							</div>
							-->
						</div>
						<!--
						<div class="form-group">
							<label class="col-sm-3 control-label">営業キロYTC</label>
							<div class="col-sm-2">
								<?php echo $this->Form->input('eigyokiloytc',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?>
							</div>
						</div>
						-->
					    <hr>
					    <div class="form-group">
					      <div class="col-sm-offset-2 col-sm-10">
						    <?php echo $this->Form->submit('登録', array('div'=>false, 'class' => 'btn btn-info', 'name' => 'submit')); ?>
						    <?php echo $this->Html->link(('キャンセル'), array('action' => 'index',$workplaces['Workplace']['id']), array('class'=>array('btn btn-danger'))); ?>
					      </div>
					    </div>

				    <?php echo $this->Form->end(); ?>
			    </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->
