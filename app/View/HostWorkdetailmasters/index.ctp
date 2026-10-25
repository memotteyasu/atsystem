<!-- 作業内容マスタ -->
<!-- Branchmasters -->
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
						'model' => 'Workdetailmaster',
						'url' => ['controller' => 'HostWorkdetailmasters', 'action' => 'index'],
						'text' => '部署　',
						'input' => 'departmentmasters_id',
					]);
				?>

			<!-- /営業所一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>作業名称マスタ一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th>作業名称</th>
									        <th>表示順</th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($workdetailmasters as $workdetailmaster): ?>
									    <tr>
									        <td><?php echo ($workdetailmaster['Workdetailmaster']['workname']); ?></td>
									        <td><?php echo ($workdetailmaster['Workdetailmaster']['displayno']); ?></td>
									        <td>
												<?php echo $this->Html->link(('修正'), ['action' => 'edit',$workdetailmaster['Workdetailmaster']['id']], ['class'=>['btn btn-info']]); ?>
												<?php echo $this->Form->postlink(('削除'), ['action' => 'delete',$workdetailmaster['Workdetailmaster']['id']], ['class'=>'btn btn-danger'],'削除しますか？'); ?>
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
