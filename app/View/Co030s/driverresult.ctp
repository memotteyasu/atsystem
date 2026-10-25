<!-- 運転手履歴一覧 -->
<!-- Co030s/driverresult -->
<div class="ch-container">
	<div class="row">

		<!-- left menu starts -->
			<?php echo $this->element('Sidemenu'); ?>
		<!-- left menu ends -->

		<div id="content" class="col-lg-10 col-sm-10">
			<!-- content starts -->
			<div>
				<ul class="breadcrumb">
					<li>
						<?php
							$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- 運転手一覧 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h4 class="">部署：
							<?php echo $branchmaster['Branchmaster']['branchname']; ?>
							<?php echo '　【'; ?>
							<?php echo $workplace1['Workplace']['workplacename']; ?>
							<?php echo '　→　'; ?>
							<?php echo $workplace2['Workplace']['workplacename']; ?>
							<?php echo '】'; ?>

					</h4>
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>乗務員履歴一覧</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>乗務員</th>
											<th>助手</th>
											<th>最終運転履歴</th>
										</tr>
									</thead>
									<tbody>

										<?php
											foreach($ordertrans as $ordertran){
												echo '<tr>';
													echo '<td>';
													echo $ordertran['usernameK'];
													echo '</td>';
													echo '<td>';
													if($ordertran['driver2name']){
														echo $ordertran['driver2name'];
													}
													echo '</td>';
													echo '<td>';
													if($ordertran['deliverydate']){
														echo $ordertran['deliverydate'];
													}
													echo '</td>';
												echo '</tr>';
											}
										?>
									</tbody>
								</table>
					<?php echo $this->Html->link(('戻る'), ['controller' => 'Co030s', 'action' => 'index'], ['class'=>array('btn btn-danger')]); ?>
							</div>
						</div>
					</div>
				</div>

			<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
