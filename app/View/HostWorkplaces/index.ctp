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

			<?php
				echo $this->element('searchForm3', [
					'model' => 'Workplace',
					'url' => ['controller'=>'HostWorkplaces','action'=>'index'],
					'text' => '部署　',
					'input' => 'departmentmasters_id',
					'text3' => '作業場所',
					'input3' => 'workplacename',
				]);
			?>

			<!-- /作業場所一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>作業場所一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th><?php echo $this->Paginator->sort('workplacename', '作業場所名称'); ?></th>
									        <th><?php echo $this->Paginator->sort('workplaceshortname', '略名称'); ?></th>
									        <th><?php echo $this->Paginator->sort('placetel', 'TEL'); ?></th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($workplaces as $workplace): ?>
									    <tr>
									        <td><?php echo ($workplace['Workplace']['workplacename']); ?></td>
									        <td><?php echo ($workplace['Workplace']['workplaceshortname']); ?></td>
									        <td><?php echo ($workplace['Workplace']['placetel']); ?></td>
									        <td>
												<?php echo $this->Html->link(('修正'), array('action' => 'edit',$workplace['Workplace']['id']), array('class'=>array('btn btn-info'))); ?>
												<?php echo $this->Form->postLink(('削除'), array('action' => 'delete', $workplace['Workplace']['id']), array('confirm' => __('削除しますか？'), 'class'=>array('btn btn-danger'))); ?>
												<?php // echo $this->Html->link(('手当'), array('controller'=>'HostWorkplaceteates','action' => 'index',$workplace['Workplace']['id']), array('class'=>array('btn btn-warning'))); ?>
												<?php echo $this->Html->link(('画像詳細'), ['controller'=>'HostWorkplaceimages','action' => 'index',$workplace['Workplace']['id']], ['class' => 'btn btn-success']); ?>
											</td>
									    </tr>
									    <?php endforeach; ?>
								    </tbody>
								</table>
							</div>
					    </div>
				    </div>
			    </div>

			    <?php echo $this->element('pager')?>

				<p>
				<?php echo $this->Html->link(('新規登録'), array('action' => 'add'), array('class'=>array('btn btn-success'))); ?>
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
