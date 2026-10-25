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
							$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
							$this->Html->addCrumb('納入先マスタ一覧', ['controller' => 'Host2Deliverymasters', 'action' => 'index']);
							$deliveryname = $deliveryname['Deliverymaster']['deliveryname'];
							$this->Html->addCrumb('納入先【'.$deliveryname.'】', ['controller' => 'Host2Deliverymasters', 'action' => 'edit', $this->params['pass'][0]]);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /納入先一覧 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2>画像詳細一覧　＜<?php echo $deliveryname; ?>＞</h2>
							</div>
							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>画像</th>
											<th class="col-sm-3">コメント</th>
											<th class="col-sm-2"></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($deliverymasterimages as $image): ?>
											<tr>
												<td>
													<div class="col-sm-5">
														<?php
															echo $this->Html->link(
																$this->Html->image('/deliveryimages/resize/'.$image['Deliverymasterimage']['id'].'.jpg', [
																	'alt' => $image['Deliverymasterimage']['imgcomment'],
																	'class' => 'img-responsive img-thumbnail'
																]),
																'/deliveryimages/original/'.$image['Deliverymasterimage']['id'].'.jpg',
																['escape' => false, 'target'=>'_blank']
															);
														?>
													</div>
												</td>
												<td>
													<?php echo ($image['Deliverymasterimage']['imgcomment']); ?>
												</td>
												<td>
													<?php
														echo $this->Html->link(('コメント編集'), [
															'action' => 'edit',
															$image['Deliverymasterimage']['id']
														], [
															'class' => 'btn btn-info'
														]);
													?>
													<?php
														echo $this->Form->postLink(('削除'), [
															'action' => 'delete',
															$image['Deliverymasterimage']['id'],
															$image['Deliverymaster']['id']
														], [
															'confirm' => __('削除しますか？'),
															'class' => 'btn btn-danger'
														]);
													?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
					    </div>
				    </div>
			    </div>
				<p>
				<?php echo $this->Html->link(('新規登録'), array('action' => 'add',$deliverymasterid), array('class'=>array('btn btn-success'))); ?>
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
