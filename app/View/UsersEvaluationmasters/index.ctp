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
							$this->Html->addCrumb('社員マスタ', ['controller' => 'Users', 'action' => 'index']);
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
						        <h2><?php echo $selectuser['User']['usernameK']; ?>　評価履歴</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th>キロ単価</th>
									        <th>適用開始</th>
									        <th>適用終了</th>
									        <th></th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($usersevaluationmasters as $usersevaluationmaster): ?>
									    <tr>
									    	<td><?php echo ($usersevaluationmaster['UsersEvaluationmaster']['evaluationkeisu']); ?></td>
									    	<td><?php echo ($usersevaluationmaster['UsersEvaluationmaster']['startdate']); ?></td>
									    	<td><?php echo ($usersevaluationmaster['UsersEvaluationmaster']['enddate']); ?></td>
									        <td>
												<?php echo $this->Html->link(('修正'), ['action' => 'edit',$usersevaluationmaster['UsersEvaluationmaster']['id'],$selectuserid], ['class'=>['btn btn-info']]); ?>
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
				<?php echo $this->Html->link(('新規登録'), array('action' => 'add',$selectuserid), array('class'=>array('btn btn-success'))); ?>
				<?php echo $this->Html->link(('社員マスタに戻る'), array('controller'=>'Users'), array('class'=>array('btn btn-danger'))); ?>
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
