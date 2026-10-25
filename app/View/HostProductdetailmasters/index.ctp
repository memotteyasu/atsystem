<!-- 商品マスタ -->
<!-- HostProductdetailmasters -->
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

			<!-- /荷主選択 -->
 			<?php
				echo $this->element('searchForm3', [
					'model' => 'Productdetailmaster',
					'url' => ['controller'=>'HostProductdetailmasters','action'=>'index'],
					'text' => '荷主　',
					'input' => 'shippermasters_id',
					'text3' => '商品名称',
					'input3' => 'pdname',
				]);
			?>

			<!-- /荷主選択 -->

			<!-- /詳細商品一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>商品マスタ一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									    	<th><?php echo $this->Paginator->sort('shippershortname', '荷主'); ?></th>
									    	<th><?php echo $this->Paginator->sort('pdname', '商品名称'); ?></th>
									    	<th></th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($productdetailmasters as $productdetailmaster): ?>
									    	<tr>
												<td><?php echo ($productdetailmaster['Shippermaster']['shippershortname']); ?></td>
												<td><?php echo ($productdetailmaster['Productdetailmaster']['pdname']); ?></td>
									        <td>
									        	<?php echo $this->Html->link(('修正'), array('action' => 'edit',$productdetailmaster['Productdetailmaster']['id']), array('class'=>array('btn btn-info'))); ?>
									        	<?php echo $this->Form->postLink(('削除'), array('action' => 'delete',$productdetailmaster['Productdetailmaster']['id']), array('confirm' => __('削除しますか？'), 'class'=>array('btn btn-danger'))); ?>
									        	<?php echo $this->Html->link(('商品詳細'), array('controller'=>'HostProductcontainers','action' => 'index','pid'=>$productdetailmaster['Productdetailmaster']['id']), array('class'=>array('btn btn-success'))); ?>
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
				<?php echo $this->Html->link(('新規登録'), ['action' => 'add'], ['class'=>['btn btn-success']]); ?>
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
