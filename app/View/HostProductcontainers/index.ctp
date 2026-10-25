<!-- 商品コンテナマスタ -->
<!-- HostProductcontainers -->
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
							$this->Html->addCrumb('商品マスタ', ['controller' => 'HostProductdetailmasters', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>
			<?php
				echo $this->element('searchForm1', [
					'model' => 'Productcontainer',
					'url' => ['controller'=>'HostProductcontainers','action'=>'index','pid'=>$pid],
					'text3' => '詳細名称',
					'input3' => 'pdconname',
				]);
			?>
			<!-- /商品一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>詳細商品マスタ一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									    	<th><?php echo $this->Paginator->sort('pdconname', '商品詳細名称'); ?></th>
									    	<th></th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($productcontainers as $productcontainer): ?>
									    	<tr>
												<td><?php echo ($productcontainer['Productcontainer']['pdconname']); ?></td>
									        <td>
									        	<?php echo $this->Html->link(('修正'), array('action' => 'edit',$productcontainer['Productcontainer']['id'],'pid'=>$pid), array('class'=>array('btn btn-info'))); ?>
									        	<?php echo $this->Form->postLink(('削除'), array('action' => 'delete',$productcontainer['Productcontainer']['id'],'pid'=>$pid), array('confirm' => __('削除しますか？'), 'class'=>array('btn btn-danger'))); ?>
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
				<?php echo $this->Html->link(('新規登録'), ['action' => 'add','pid'=>$pid], ['class'=>['btn btn-success']]); ?>
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

