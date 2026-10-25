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


				<?php
					echo $this->element('searchForm', [
						'model' => 'Shippermaster',
						'url' => ['controller' => 'HostShippermasters', 'action' => 'index'],
						'text' => '部署　',
						'input' => 'departmentmasters_id'
					]);
				?>
				<?php // echo $this->element('searchForm', array('model' => 'Shippermaster', 'url' => ['controller'=>'HostShippermasters','action'=>'index'], 'desc' => '荷主名称を入力', 'input' => 'shippername')); ?>

			<!-- /荷主一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>荷主マスタ一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th><?php echo $this->Paginator->sort('displayno', '表示順'); ?></th>
									        <th><?php echo $this->Paginator->sort('shippercode', '荷主CD'); ?></th>
									        <th><?php echo $this->Paginator->sort('shippername', '荷主名称'); ?></th>
									        <th><?php echo $this->Paginator->sort('shippershortname', '荷主略称'); ?></th>
									        <th><?php echo $this->Paginator->sort('departmentmasters_id', '部署名称'); ?></th>
									        <th><?php echo $this->Paginator->sort('detailmix', '明細'); ?></th>
									        <th><?php echo $this->Paginator->sort('expensestaxflg', '諸費税'); ?></th>
									        <th><?php echo $this->Paginator->sort('surcharge', 'サーチャージ'); ?></th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($shippermasters as $shippermaster): ?>
									    <tr>
									    	<td><?php echo ($shippermaster['Shippermaster']['displayno']); ?></td>
									    	<td><?php echo ($shippermaster['Shippermaster']['shippercode']); ?></td>
									    	<td><?php echo ($shippermaster['Shippermaster']['shippername']); ?></td>
									        <td><?php echo ($shippermaster['Shippermaster']['shippershortname']); ?></td>
									        <td><?php echo ($shippermaster['Departmentmaster']['departmentname']); ?></td>
									        <td><?php 
											if($shippermaster['Shippermaster']['detailmix']==1){
											echo ($mixlist[$shippermaster['Shippermaster']['detailmix']]);
											}
											 ?></td>
									        <td><?php 
											if($shippermaster['Shippermaster']['expensestaxflg']==1){
											echo ($expenseslist[$shippermaster['Shippermaster']['expensestaxflg']]);
											}
											 ?></td>
									        <td class="text-center"><?php 
											if($shippermaster['Shippermaster']['surcharge']!='0.0'){
											echo ($shippermaster['Shippermaster']['surcharge']);
											}
											 ?></td>
									        <td>
												<?php echo $this->Html->link(('修正'), array('action' => 'edit',$shippermaster['Shippermaster']['id']), array('class'=>array('btn btn-info'))); ?>
									        	<?php echo $this->Form->postLink(('削除'), array('action' => 'delete',$shippermaster['Shippermaster']['id']), array('confirm' => __('削除しますか？'), 'class'=>array('btn btn-danger'))); ?>
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
