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
							$workplacename = $workplaces['Workplace']['workplacename'];
							$this->Html->addCrumb('場所【'.$workplacename.'】', ['controller' => 'HostWorkplaces', 'action' => 'index', $this->params['pass'][0]]);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /作業場所手当一覧 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2>手当一覧</h2>
							</div>
							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th class="col-sm-1">営業所</th>
											<th class="col-sm-1">長距離単車（夏）</th>
											<th class="col-sm-1">長距離トレーラー（冬）</th>
											<th class="col-sm-1">長距離単車（夏）</th>
											<th class="col-sm-1">長距離トレーラー（冬）</th>
											<th class="col-sm-1">営業キロ</th>
											<!--
											<th class="col-sm-1">営業キロ（ガス）</th>
											<th class="col-sm-1">営業キロ（YTC)</th>
											-->
											<th class="col-sm-1"></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($workplaceteates as $workplaceteate): ?>
											<tr>
												<td>
													<?php echo $workplaceteate['Branchmaster']['branchname']; ?>
												</td>
												<td>
													<?php echo $workplaceteate['Workplaceteate']['longteate_summer_tan']; ?>
												</td>
												<td>
													<?php echo $workplaceteate['Workplaceteate']['longteate_summer_tol']; ?>
												</td>
												<td>
													<?php echo $workplaceteate['Workplaceteate']['longteate_winter_tan']; ?>
												</td>
												<td>
													<?php echo $workplaceteate['Workplaceteate']['longteate_winter_tol']; ?>
												</td>
												<td>
													<?php echo $workplaceteate['Workplaceteate']['eigyokilo']; ?>
												</td>
												<!--
												<td>
													<?php echo $workplaceteate['Workplaceteate']['eigyokilogas']; ?>
												</td>
												<td>
													<?php echo $workplaceteate['Workplaceteate']['eigyokiloytc']; ?>
												</td>
												-->
												<td>
												<?php echo $this->Html->link(('修正'), array('action' => 'edit',$workplaceteate['Workplaceteate']['id'],$this->params['pass'][0]), array('class'=>array('btn btn-info'))); ?>
												<?php echo $this->Form->postLink(('削除'), array('action' => 'delete', $workplaceteate['Workplaceteate']['id']), array('confirm' => __('削除しますか？'), 'class'=>array('btn btn-danger'))); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
					    </div>
				    </div>
			    </div>
				<p>
				<?php echo $this->Html->link(('新規登録'), array('action' => 'add',$workplaces['Workplace']['id']), array('class'=>array('btn btn-success'))); ?>
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
