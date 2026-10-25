<!-- 荷主マスタ -->
<!-- HostShippermasters -->
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

			<!-- /会社一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>会社マスタ一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th><?php echo $this->Paginator->sort('companyname', '会社名'); ?></th>
									        <th><?php echo $this->Paginator->sort('companyshortname', '会社略称'); ?></th>
									        <th><?php echo $this->Paginator->sort('kaisyakey', '会社ID'); ?></th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($kaisyas as $kaisya): ?>
									    <tr>
									    	<td><?php echo ($kaisya['Kaisya']['companyname']); ?></td>
									    	<td><?php echo ($kaisya['Kaisya']['companyshortname']); ?></td>
									        <td><?php echo ($kaisya['Kaisya']['kaisyakey']); ?></td>
									        <td>
												<?php echo $this->Html->link(('修正'), array('action' => 'edit',$kaisya['Kaisya']['id']), array('class'=>array('btn btn-info'))); ?>
									        	<?php echo $this->Form->postLink(('削除'), array('action' => 'delete',$kaisya['Kaisya']['id']), array('confirm' => __('削除しますか？'), 'class'=>array('btn btn-danger'))); ?>
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
