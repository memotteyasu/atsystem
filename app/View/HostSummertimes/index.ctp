<!-- 就業時間マスタ -->
<!-- HostSummertimes -->
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
				echo $this->element('searchForm2', [
					'model' => 'Summertime',
					'url' => ['controller'=>'HostSummertimes','action'=>'index'],
					'text' => '部署　',
					'input' => 'departmentmasters_id',
					'text2' => '営業所　',
					'input2' => 'branchmasters_id'
				]);
			?>

			<!-- /就業時間一覧 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2>就業時間マスタ一覧</h2>
							</div>
							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th><?php echo $this->Paginator->sort('departmentmasters_id', '部署'); ?></th>
											<th><?php echo $this->Paginator->sort('branchmasters_id', '営業所'); ?></th>
											<th><?php echo $this->Paginator->sort('summerstartmonth', '開始月'); ?></th>
											<th><?php echo $this->Paginator->sort('summerendmonth', '終了月'); ?></th>
											<th><?php echo $this->Paginator->sort('summertermtime', 'サマー就業'); ?></th>
											<th><?php echo $this->Paginator->sort('summerstarttime', 'サマー出社'); ?></th>
											<th><?php echo $this->Paginator->sort('summerendtime', 'サマー退社'); ?></th>
											<th><?php echo $this->Paginator->sort('termtime', '通樹就業'); ?></th>
											<th><?php echo $this->Paginator->sort('starttime', '通常出社'); ?></th>
											<th><?php echo $this->Paginator->sort('endtime', '通常退社'); ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($summertimes as $summertime): ?>
											<tr>
												<td><?php echo ($summertime['Departmentmaster']['departmentname']); ?></td>
												<td><?php echo ($summertime['Branchmaster']['branchname']); ?></td>
												<td><?php echo ($summertime['Summertime']['summerstartmonth']); ?></td>
												<td><?php echo ($summertime['Summertime']['summerendmonth']); ?></td>
												<td><?php echo ($summertime['Summertime']['summertermtime']); ?></td>
												<td><?php echo ($summertime['Summertime']['summerstarttime']); ?></td>
												<td><?php echo ($summertime['Summertime']['summerendtime']); ?></td>
												<td><?php echo ($summertime['Summertime']['termtime']); ?></td>
												<td><?php echo ($summertime['Summertime']['starttime']); ?></td>
												<td><?php echo ($summertime['Summertime']['endtime']); ?></td>
												<td>
													<?php echo $this->Html->link(('修正'), ['action' => 'edit',$summertime['Summertime']['id']], ['class' => 'btn btn-info']); ?>
													<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $summertime['Summertime']['id']], ['confirm' => __('削除しますか？'), 'class' => 'btn btn-danger']); ?>
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
