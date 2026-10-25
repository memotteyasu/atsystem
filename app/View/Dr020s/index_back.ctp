<div class="ch-container">

	<div class="row">

	<!-- left menu starts -->
		<?php echo $this->element('DriverSidemenu'); ?>
	<!-- left menu ends -->

	<div id="content" class="col-lg-10 col-sm-10">

		<!-- content starts -->
			<div>
				<ul class="breadcrumb">
					<li>
         			   <?php
							$this->Html->addCrumb('Top', ['controller' => 'DrTops', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>
			<!-- 運行日程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						    	<?php
									$today = new DateTime($date);
									if ($dr1confirm1 == $dr1confirm2 || $dr2confirm1 == $dr2confirm2) {
										if ($dr1confirm1 == 0 || $dr2confirm1 == 0) {
											$text = '';
										} else {
											$text = '（<font color="#ff0000">確定済</font>）';
										}
									} else {
										$text = '（未確定）';
									}
									echo $today->format('Y年m月d日').'('.$week[$today->format('w')].')'.' 運行日程';
									echo $text;
								?>
							</div>

							<?php if (!$ordertrans): ?>
								<div class="box-content">
									データが存在しません。
								</div>
							<?php endif; ?>

							<?php foreach ($ordertrans as $ordertran): ?>

								<?php if ($ordertran['Ordertran']['driver1id'] == $user['id']): ?>

									<div class="box-content">
										<table class="table table-bordered bootstrap-datatable">
											<tr>
												<?php
													if ($ordertran['Driver2']) {
														$driver2name = $ordertran['Driver2']['usernameK'];
													} else {
														$driver2name = '';
													}
													$text1 = $ordertran['Ordertran']['kaisen'].'回戦　(運転手)'.$ordertran['Driver1']['usernameK'].'　(助手)'.$driver2name;
												?>
												<th colspan="2"><?php echo $text1; ?></th>
<!--
												<tr>
													<th>荷主</th>
													<td><?php echo $ordertran['Shippermaster']['shippershortname']; ?></td>
												</tr>
												<tr>
													<th>積地</th>
													<td>
														<?php
															$url = $ordertran['Loadingmaster']['googlemapurl'];
															$loadingname = $ordertran['Loadingmaster']['loadingname'];
															$loadingnote = $ordertran['Ordertran']['loadingnote'];
														?>
														<a href='<?php echo $url; ?>' target="_blank"><?php echo $loadingname; ?></a>(<?php echo $loadingnote; ?>)
													</td>
												</tr>
												<tr>
													<th>画像</th>
													<td>
														<?php
															foreach ($ordertran['Loadingmaster']['Loadingmasterimage'] as $image) {
																echo $this->Html->link($image['imgcomment'],
																	'/loadingimages/resize/'.$image['id'].'.jpg',
																	[
																		'target' => '_self'
																	]
																);
																echo ',';
															}
														?>
													</td>
												</tr>
												<tr>
													<th>卸地</th>
														<td>
															<?php
																$url = $ordertran['Deliverymaster']['googlemapurl'];
																$deliveryname = $ordertran['Deliverymaster']['deliveryname'];
																$deliverynote = $ordertran['Ordertran']['deliverynote'];
															?>
															<a href="<?php echo $url; ?>" target="_blank"><?php echo $deliveryname; ?></a>(<?php echo $deliverynote; ?>)
														</td>
												</tr>
												<tr>
													<th>画像</th>
													<td>
														<?php
															foreach ($ordertran['Deliverymaster']['Deliverymasterimage'] as $image) {
																echo $this->Html->link($image['imgcomment'],
																	'/deliveryimages/resize/'.$image['id'].'.jpg',
																	[
																		'target' => '_self'
																	]
																);
																echo ',';
															}
														?>
													</td>
												</tr>
												<tr>
													<td><?php echo $ordertran['Productdetailmaster']['pdname']; ?></td>
													<td>（<?php echo $ordertran['Ordertran']['productnote']; ?>）</td>
												</tr>
-->
											</tr>
										</table>

										<table class="table table-striped table-bordered bootstrap-datatable">
											<thead>
												<tr>
													<th>作業者</th>
													<th>作業名称</th>
													<th>作業場所</th>
													<th>作業日時</th>
													<?php
													if($user['departmentmasters_id'] == 4){
														echo '<th>商品詳細</th>';
														echo '<th>コンテナNo.</th>';
														echo '<th>REF/BOOKING No.</th>';
														echo '<th>作業備考</th>';
													}else{
														echo '<th colspan="4">作業備考</th>';
													}
													?>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($ordertran['Processtran'] as $dprocess): ?>
													<tr>
														<td>
															<?php
																if (isset($dprocess['User']['usernameK'])) {
																	echo $dprocess['User']['usernameK'];
																} else {
																	echo '未定';
																}
															?>
														</td>
														<td><?php echo $dprocess['Workdetailmaster']['workname']; ?></td>
														<td><?php echo $dprocess['Workplace']['workplacename']; ?></td>
														<td>
															<?php
																$processstart = new DateTime($dprocess['processstartdate'].$dprocess['processstarttime']);
																echo $processstart->format('d日 H:i');
															?>
														</td>
														<?php
														if($user['departmentmasters_id']==4){
															echo '<td>';
															if (isset($dprocess['Productcontainer']['pdconname'])) {
																echo $dprocess['Productcontainer']['pdconname'];
																echo '<td>';
																echo $dprocess['container_no'];
																echo '</td>';
																echo '<td>';
																echo $dprocess['ref_no'];
																echo '</td>';
																echo '<td>';
																echo $dprocess['workddetailcomment'];
																echo '</td>';
															}
															echo '</td>';
														}else{
																echo '<td colspan="4">';
																echo $dprocess['workddetailcomment'];
																echo '</td>';
														}
														?>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>

								<?php endif; ?>

							<?php endforeach; ?>
						</div>



					<!-- 助手用 -->
					<?php
						if ($dr2confirm1 != 0):
							echo '<h2><span class="label label-info">助手</span></h2>';
					?>

						<div class="box-inner">
							<?php foreach ($ordertrans as $ordertran): ?>

								<?php if ($ordertran['Ordertran']['driver2id'] == $user['id']): ?>

									<div class="box-content">
										<table class="table table-bordered bootstrap-datatable">
											<tr>
												<?php
													if ($ordertran['Driver1']) {
														$driver1name = $ordertran['Driver1']['usernameK'];
													} else {
														$driver1name = '未定';
													}
													$text1 = $ordertran['Ordertran']['kaisen'].'回戦　(運転手)'.$driver1name.'　(助手)'.$ordertran['Driver2']['usernameK'];
												?>
												<th colspan="2"><?php echo $text1; ?></th>
												<tr>
													<th>荷主</th>
													<td><?php echo $ordertran['Shippermaster']['shippershortname']; ?></td>
												</tr>
												<tr>
													<th>積地</th>
													<td>
														<?php
															$url = $ordertran['Loadingmaster']['googlemapurl'];
															$loadingname = $ordertran['Loadingmaster']['loadingname'];
															$loadingnote = $ordertran['Ordertran']['loadingnote'];
														?>
														<a href='<?php echo $url; ?>' target="_blank"><?php echo $loadingname; ?></a>(<?php echo $loadingnote; ?>)
													</td>
												</tr>
												<tr>
													<th>画像</th>
													<td>
														<?php
															foreach ($ordertran['Loadingmaster']['Loadingmasterimage'] as $image) {
																echo $this->Html->link($image['imgcomment'],
																	'/loadingimages/resize/'.$image['id'].'.jpg',
																	[
																		'target' => '_self'
																	]
																);
																echo ',';
															}
														?>
													</td>
												</tr>
												<tr>
													<th>卸地</th>
														<td>
															<?php
																$url = $ordertran['Deliverymaster']['googlemapurl'];
																$deliveryname = $ordertran['Deliverymaster']['deliveryname'];
																$deliverynote = $ordertran['Ordertran']['deliverynote'];
															?>
															<a href="<?php echo $url; ?>" target="_blank"><?php echo $deliveryname; ?></a>(<?php echo $deliverynote; ?>)
														</td>
												</tr>
												<tr>
													<th>画像</th>
													<td>
														<?php
															foreach ($ordertran['Deliverymaster']['Deliverymasterimage'] as $image) {
																echo $this->Html->link($image['imgcomment'],
																	'/deliveryimages/resize/'.$image['id'].'.jpg',
																	[
																		'target' => '_self'
																	]
																);
																echo ',';
															}
														?>
													</td>
												</tr>
												<tr>
													<td><?php echo $ordertran['Productdetailmaster']['pdname']; ?></td>
													<td>（<?php echo $ordertran['Ordertran']['productnote']; ?>）</td>
												</tr>
											</tr>
										</table>

										<table class="table table-striped table-bordered bootstrap-datatable">
											<thead>
												<tr>
													<th>作業者</th>
													<th>作業名称</th>
													<th>作業場所</th>
													<th>作業日時</th>
													<th>商品詳細</th>
													<th>コンテナNo.</th>
													<th>REF/BOOKING No.</th>
													<th>作業備考</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($ordertran['Processtran'] as $dprocess): ?>
													<tr>
														<td>
															<?php
																if (isset($dprocess['PDriver1']['usernameK'])) {
																	echo $dprocess['PDriver1']['usernameK'];
																} else {
																	echo '未定';
																}
															?>
														</td>
														<td><?php echo $dprocess['Workdetailmaster']['workname']; ?></td>
														<td><?php echo $dprocess['Workplace']['workplacename']; ?></td>
														<td>
															<?php
																$processstart = new DateTime($dprocess['processstartdate'].$dprocess['processstarttime']);
																echo $processstart->format('d日 H:i');
															?>
														</td>
														<td>
															<?php
																if (isset($dprocess['Productcontainer']['pdconname'])) {
																	echo $dprocess['Productcontainer']['pdconname'];
																}
															?>
														</td>
														<td><?php echo $dprocess['container_no']; ?></td>
														<td><?php echo $dprocess['ref_no']; ?></td>
														<td><php echo $dprocess['workddetailcomment']; ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>

								<?php endif; ?>

							<?php endforeach; ?>

						</div>

					<?php endif; ?>

						<div class="box-content">
							<?php
							if($ordertrans){
								echo $this->Form->postLink('<i class="glyphicon glyphicon-ok icon-white"></i>'.(' 確認したらクリックしてください'), [
									'action' => 'confirm',
									$date,
									$user['id']
								], [
									'class' => 'btn btn-success',
									'escape' => false,
								]);
							}
							?>
							<?php
							if(isset($ordertran['Ordertran']['driver1id'])){
								if($user['id'] == $ordertran['Ordertran']['driver1id']){
									if($ordertran['Ordertran']['dr1confirmdate']){
										echo '確認済み：'.$ordertran['Ordertran']['dr1confirmdate'];
									}
								}
								if($user['id'] == $ordertran['Ordertran']['driver2id']){
									if($ordertran['Ordertran']['dr2confirmdate']){
										echo '確認済み：'.$ordertran['Ordertran']['dr2confirmdate'];
									}
								}
							}
							?>
						</div>

					</div>
				<!-- /運行日程 -->
			</div>
    	</div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
