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
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

				<?php //echo $this->element('searchForm', array('model' => 'Deliverymaster', 'url' => ['controller'=>'HostDestinationmasters','action'=>'index'], 'desc' => '納入先名称を入力', 'input' => 'deliveryname')); ?>

			<?php
				echo $this->element('searchForm3', [
					'model' => 'Deliverymaster',
					'url' => ['controller'=>'HostDeliverymasters','action'=>'index'],
					'text' => '部署　',
					'input' => 'departmentmasters_id',
					'text2' => '地域　',
					'input2' => 'areamasters_id',
					'text3' => '納入先名称',
					'input3' => 'deliveryname',
				]);
			?>

			<!-- /納入先一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>納入先マスタ一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th><?php echo $this->Paginator->sort('deliverycode', '納入先CD'); ?></th>
									        <th><?php echo $this->Paginator->sort('deliveryname', '納入先名称'); ?></th>
									        <th><?php echo $this->Paginator->sort('deliveryshortname', '納入先略名称'); ?></th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($deliverymasters as $deliverymaster): ?>
									    <tr>
									        <td><?php echo ($deliverymaster['Deliverymaster']['deliverycode']); ?></td>
									        <td><?php echo ($deliverymaster['Deliverymaster']['deliveryname']); ?></td>
									        <td><?php echo ($deliverymaster['Deliverymaster']['deliveryshortname']); ?></td>
									        <td>
												<?php echo $this->Html->link(('修正'), array('action' => 'edit',$deliverymaster['Deliverymaster']['id']), array('class'=>array('btn btn-info'))); ?>
												<?php echo $this->Form->postLink(('削除'), array('action' => 'delete', $deliverymaster['Deliverymaster']['id']), array('confirm' => __('削除しますか？'), 'class'=>array('btn btn-danger'))); ?>
												<?php echo $this->Html->link(('画像詳細'), ['action' => 'image',$deliverymaster['Deliverymaster']['id']], ['class' => 'btn btn-success']); ?>
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
