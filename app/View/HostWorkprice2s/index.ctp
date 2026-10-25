<!-- 作業歩合マスタ2 -->
<!-- HostWorkprice2s -->
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

			<!-- /作業歩合一覧 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2>作業歩合マスタ2一覧</h2>
							</div>
							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th><?php echo $this->Paginator->sort('startkilo', 'スタートキロ'); ?></th>
											<th><?php echo $this->Paginator->sort('endkilo', 'エンドキロ'); ?></th>
											<th><?php echo $this->Paginator->sort('zangyojikan', '時間外'); ?></th>
											<th><?php echo $this->Paginator->sort('shinyajikan', '深夜割増'); ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($workprice2s as $workprice2): ?>
											<tr>
												<td><?php echo ($workprice2['Workprice2']['startkilo']); ?></td>
												<td><?php echo ($workprice2['Workprice2']['endkilo']); ?></td>
												<td class="text-right"><?php echo substr($workprice2['Workprice2']['zangyojikan'],0,5); ?></td>
												<td class="text-right"><?php echo substr($workprice2['Workprice2']['shinyajikan'],0,5); ?></td>
												<td>
													<?php echo $this->Html->link(('修正'), ['action' => 'edit',$workprice2['Workprice2']['id']], ['class' => 'btn btn-info']); ?>
													<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $workprice2['Workprice2']['id']], ['confirm' => __('削除しますか？'), 'class' => 'btn btn-danger']); ?>
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
