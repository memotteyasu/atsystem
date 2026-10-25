<!-- 個別手当マスタ -->
<!-- HostKobetsuteates -->
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

			<!-- /個別手当マスタ一覧 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2>個別手当マスタ一覧</h2>
							</div>
							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th><?php echo $this->Paginator->sort('kobetsukilo', '時間（分）'); ?></th>
											<th><?php echo $this->Paginator->sort('teatename', '手当名'); ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($kobetsuteatemasters as $kobetsuteate): ?>
											<tr>
												<td><?php echo ($kobetsuteate['Kobetsuteatemaster']['kobetsukilo']); ?></td>
												<td><?php echo ($kobetsuteate['Kobetsuteatemaster']['teatename']); ?></td>
												<td>
													<?php echo $this->Html->link(('修正'), ['action' => 'edit',$kobetsuteate['Kobetsuteatemaster']['id']], ['class' => 'btn btn-info']); ?>
													<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $kobetsuteate['Kobetsuteatemaster']['id']], ['confirm' => __('削除しますか？'), 'class' => 'btn btn-danger']); ?>
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
