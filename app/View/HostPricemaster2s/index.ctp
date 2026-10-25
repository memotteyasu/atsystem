<!-- 価格マスタ -->
<!-- HostPricemaster2s -->
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
				echo $this->element('searchForm5', [
					'model' => 'Pricemaster',
					'url' => ['controller'=>'HostPricemaster2s','action'=>'index'],
					'text3' => '価格名',
					'input3' => 'pricename',
					'text4' => '価格名',
					'input4' => 'pricename2',
				]);
			?>

			<!-- /商品一覧 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2>価格マスタ一覧2（2026.07改定状況）</h2>
							</div>
							<div class="box-content">
								<table class="table table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th><?php echo '価格名'; ?></th>
											<th><?php echo '部署名'; ?></th>
											<th><?php echo '荷主名'; ?></th>
											<th><?php echo '備考'; ?></th>
											<th><?php echo '価格'; ?></th>
											<th><?php echo '諸費'; ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($pricemasters as $pricemaster): ?>
											<?php 
											if($pricemaster['Pricemaster']['updateflg']==2){ 
												echo '<tr style="background-color:#ffc0cb;">';
											}else{
												echo '<tr style="background-color:#ffffff;">';
											}
											?>
												<td><?php echo h($pricemaster['Pricemaster']['pricename']); ?></td>
												<td><?php echo h(isset($pricemaster['Departmentmaster']['departmentname']) ? $pricemaster['Departmentmaster']['departmentname'] : ''); ?></td>
												<td><?php echo h(isset($pricemaster['Shippermaster']['shippershortname']) ? $pricemaster['Shippermaster']['shippershortname'] : ''); ?></td>
												<td><?php echo h($pricemaster['Pricemaster']['pricecomment']); ?></td>
												<td><?php echo h($pricemaster['Pricemaster']['price']); ?></td>
												<td><?php
												if($pricemaster['Pricemaster']['keihiflg']==0){
													echo ' ';
												}else{
													echo '諸費';
												}
												?></td>
												<td>
													<?php if(isset($this->request->params['named']['page'])){ ?>
														<?php echo $this->Html->link(('修正'), ['action' => 'edit',$pricemaster['Pricemaster']['id'],$this->request->params['named']['page']], ['class' => 'btn btn-info']); ?>
													<?php }else{ ?>
														<?php echo $this->Html->link(('修正'), ['action' => 'edit',$pricemaster['Pricemaster']['id']], ['class' => 'btn btn-info']); ?>
													<?php } ?>
													<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $pricemaster['Pricemaster']['id']], ['confirm' => __('削除しますか？'), 'class' => 'btn btn-danger']); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<?php if (!empty($searchSql)): ?>
					<div class="well well-sm">
						<strong>検索条件SQL</strong>
						<pre><?php echo h($searchSql); ?></pre>
					</div>
				<?php endif; ?>



			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
