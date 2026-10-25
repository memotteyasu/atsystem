<!-- 担当者マスタ -->
<!-- Tantomaster -->
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
					'model' => 'Tantomaster',
					'url' => ['controller'=>'Tantomasters','action'=>'index'],
					'text' => '荷主',
					'input' => 'shippermasters_id',
					'text3' => '担当者名',
					'input3' => 'tantoname',
				]);
			?>

			<!-- /担当者一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>担当者マスタ一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th>荷主</th>
									        <th>担当者名</th>
									        <th>電話番号</th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($tantomasters as $tantomaster): ?>
									    <tr>
									        <td><?php echo($tantomaster['Shippermaster']['shippershortname']); ?></td>
									        <td><?php echo($tantomaster['Tantomaster']['tantoname']); ?></td>
									        <td><?php echo ($tantomaster['Tantomaster']['tantotel']); ?></td>
									        <td>
												<?php echo $this->Html->link(('修正'), ['action' => 'edit',$tantomaster['Tantomaster']['id']], ['class'=>['btn btn-info']]); ?>
												<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $tantomaster['Tantomaster']['id']], ['confirm' => __('削除しますか？'), 'class'=>['btn btn-danger']]); ?>
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

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->
