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

			<?php
				echo $this->element('searchForm', [
					'model' => 'Areamaster',
					'url' => ['controller' => 'HostRegionmasters', 'action' => 'index'],
					'text' => '部署　',
					'input' => 'departmentmasters_id'
				]);
			?>

			<!-- /地域一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>地域マスタ一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th><?php echo $this->Paginator->sort('areacode', '地域CD'); ?></th>
									        <th><?php echo $this->Paginator->sort('areaname', '地域名称'); ?></th>
									        <th></th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($areamasters as $areamaster): ?>
									    <tr>
									    	<td><?php echo ($areamaster['Areamaster']['areacode']); ?></td>
									    	<td><?php echo ($areamaster['Areamaster']['areaname']); ?></td>
									        <td>
												<?php echo $this->Html->link(('修正'), ['action' => 'edit',$areamaster['Areamaster']['id']], ['class'=>['btn btn-info']]); ?>
												<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $areamaster['Areamaster']['id']], ['confirm' => __('削除しますか？'), 'class'=>['btn btn-danger']]); ?>
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
