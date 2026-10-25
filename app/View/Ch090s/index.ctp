<!-- コンテナ運転手割振 -->
<!-- Ch090s -->
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
							$this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
							$this->Html->addCrumb('乗務員割振', ['controller' => 'Ch090s', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>
			<?php
			echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);

				echo $this->Form->input('selectym1', [
					'div'=>false,
					'label'=>false,
					'type' => 'date',
					'dateFormat' => 'YMD',
					'monthNames' => false,
					'minYear' => date('Y')-1,
					'maxYear' => date('Y')+1,
					'onchange'=>'this.form.submit()',
					'style'=>'width:70px',
					'data-rel'=>'chosen',
					'default' => $paramsDate,
				]);

				echo 'のデータを表示　';
				echo '営業所　';

				echo $this->Form->input('branchmasters_id',[
					'label'=>false,
					'type'=>'select',
					'empty'=>'未選択',
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'default' => $branchmasters_id,
				]);

			echo $this->Form->end();
		?>

			<!-- 受注内容工程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>乗務員割振（オーダー別）</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>車両</th>
											<th>卸日</th>
											<th>回戦</th>
											<th>荷主</th>
											<th>納入先</th>
											<th>乗務員</th>
											<th>助手</th>
										</tr>
									</thead>
									<tbody>

										<?php
											foreach ($ordertrans as $ordertran) {
												echo '<tr class="">';
													echo '<td>';
														echo ($ordertran['Carmaster']['plateno']).'（'.($ordertran['Productdetailmaster']['pdname']).'）';
													echo '</td>';
													echo '<td>';
														$deliverytime = new DateTime($ordertran['Ordertran']['deliverydate'].$ordertran['Ordertran']['deliverytime']);
														echo ($deliverytime->format('Y-m-d H:i'));
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Ordertran']['kaisen']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Shippermaster']['shippershortname']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Deliverymaster']['deliveryshortname']);
													echo '</td>';

													echo '<td>';
														echo $this->Form->create('Ordertran', ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);
															echo $this->Form->input('id', ['type' => 'hidden', 'value' => $ordertran['Ordertran']['id']]);
															echo $this->Form->input('driver1id', [
																'div'=>false,
																'label'=>false,
																'type' => 'select',
																'empty'=>'未定',
																'options' => $userlist,
																'data-rel'=>'chosen',
																'value' => $ordertran['Ordertran']['driver1id'],
																'onchange'=>'this.form.submit()',
															]);
															echo $this->Form->input('driver2id', ['type' => 'hidden', 'value' => $ordertran['Ordertran']['driver2id']]);
														echo $this->Form->end();
													echo '</td>';

													echo '<td>';
														echo $this->Form->create('Ordertran', ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);
															echo $this->Form->input('id', ['type' => 'hidden', 'value' => $ordertran['Ordertran']['id']]);
															echo $this->Form->input('driver1id', ['type' => 'hidden', 'value' => $ordertran['Ordertran']['driver1id']]);
															echo $this->Form->input('driver2id', [
																'div'=>false,
																'label'=>false,
																'type' => 'select',
																'empty'=>'未定',
																'options' => $userlist,
																'data-rel'=>'chosen',
																'value' => $ordertran['Ordertran']['driver2id'],
																'onchange'=>'this.form.submit()',
															]);
														echo $this->Form->end();
													echo '</td>';
												echo '</tr>';
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<?php //echo $this->element('pager')?>

				<p>
					<?php
						$paramsDate2 = new DateTime($paramsDate);
						$tyy = $paramsDate2->format('Y');
						$tmm = $paramsDate2->format('m');
						$tdd = $paramsDate2->format('d');
						//echo $this->Html->link(('確定して運転手にメールを送信'), ['controller' => 'Ch091s', 'action' => 'driversend','tyy' => $tyy, 'tmm' => $tmm, 'tdd' => $tdd], ['class' => 'btn btn-success']);
						echo $this->Html->link(('確定して乗務員にメールを送信'), ['controller' => 'Ch091s', 'action' => 'confirm','tyy' => $tyy, 'tmm' => $tmm, 'tdd' => $tdd, 'bid' => $branchmasters_id], ['class' => 'btn btn-success']);
					?>
				</p>

		<!-- DriverandPerson 呼び出し -->
		<?php echo $this->element('DriverandPerson'); ?>

			<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
<script>
$(function(){
  $('td[data-href]', 'table.table-clickable').on('click', function(){
    location.href = $(this).data('href');
  });
});
</script>
