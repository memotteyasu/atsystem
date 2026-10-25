<!-- 作業歩合マスタ -->
<!-- HostWorkprices -->
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
				echo $this->element('searchForm4', [
					'model' => 'Workprice',
					'url' => ['controller'=>'HostWorkprices','action'=>'index'],
					'text' => '部署　',
					'input' => 'departmentmasters_id',
					'text2' => '営業所　',
					'input2' => 'branchmasters_id',
					'text3' => '労働区分　',
					'input3' => 'roudouflg',
					'text4' => '歩合ランク　',
					'input4' => 'buairank'
				]);
			?>

			<!-- /作業歩合一覧 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2>作業歩合マスタ一覧</h2>
							</div>
							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th><?php echo $this->Paginator->sort('startkilo', 'スタートキロ'); ?></th>
											<th><?php echo $this->Paginator->sort('endkilo', 'エンドキロ'); ?></th>
											<th><?php echo $this->Paginator->sort('', '内訳（距離歩合）'); ?></th>
											<th><?php echo $this->Paginator->sort('zangyotanka', '定額時間外'); ?></th>
											<th><?php echo $this->Paginator->sort('shinyatanka', '定額深夜割増'); ?></th>
											<th><?php echo $this->Paginator->sort('price', '合計'); ?></th>
											<!-- <th><?php echo $this->Paginator->sort('departmentmasters_id', '部署'); ?></th> -->
											<th><?php echo $this->Paginator->sort('branchmasters_id', '営業所'); ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($workprices as $workprice): ?>
											<tr>
												<td><?php echo ($workprice['Workprice']['startkilo']); ?></td>
												<td><?php echo ($workprice['Workprice']['endkilo']); ?></td>
												<td>残業：<?php echo substr($workprice['Workprice']['zangyojikan'],0,5); ?>／
													深夜：<?php echo substr($workprice['Workprice']['shinyajikan'],0,5); ?>／
													<?php echo ($workprice['Workprice']['untinbuai']); ?>
												</td>
												<td class="text-right"><?php echo ($workprice['Workprice']['zangyotanka']); ?></td>
												<td class="text-right"><?php echo ($workprice['Workprice']['shinyatanka']); ?></td>
												<td class="text-right"><?php echo ($workprice['Workprice']['price']); ?></td>
												<!-- <td><?php echo ($workprice['Departmentmaster']['departmentname']); ?></td> -->
												<td><?php echo ($workprice['Branchmaster']['branchname']); ?></td>
												<td>
													<?php echo $this->Html->link(('修正'), ['action' => 'edit',$workprice['Workprice']['id']], ['class' => 'btn btn-info']); ?>
													<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $workprice['Workprice']['id']], ['confirm' => __('削除しますか？'), 'class' => 'btn btn-danger']); ?>
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
