<!-- コンテナ配車管理 -->
<!-- Co020s -->
<style>
	table.table-clickable td[data-href]{
		cursor: pointer;
	}
	table.table-clickable td:hover{
		background-color:#e8e8e8 !important;
	}
</style>

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

				echo 'を表示　　　';
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

		<!-- /荷主一覧 -->
		<div class="row">
			<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="box-inner">
					<div class="box-header well">
						<h2><i class="glyphicon glyphicon-info-sign"></i>配車管理（回戦別）</h2>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered table-clickable">
							<thead>
								<tr>
									<th>車両番号</th>
									<th>1回戦</th>
									<th>2回戦</th>
									<th>3回戦</th>
									<th>4回戦</th>
									<th>5回戦</th>
									<th>6回戦</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$car2 = null;
									foreach ($truckkinds as $truckkind) {
										if ($truckkind['Ordertran']) {
											$car1 = $truckkind['Truckkind']['carmasters_id'];
											if ($car1 != $car2) {
												echo '<tr class="info">';
													echo '<th colspan="8">'.$truckkind['Carmaster']['plateno'].'</th>';
												echo '</tr><tr>';
											} else {
												echo '<tr>';
											}
												echo '<td rowspan="3">';
													$date = new DateTime($paramsDate);
													echo $truckkind['Carmaster']['plateno'].'（'.$truckkind['Productdetailmaster']['pdname'].'）';
												echo '</td>';
												for ($i = 1; $i <=6; $i++) {
													echo '<td data-href="'.
														$this->Html->url([
															'controller' => 'Co000s',
															'action' => 'add',
															2,
															'id' => $truckkind['Truckkind']['id'],
															'sid' => $truckkind['Truckkind']['shippermasters_id'],
															'pid' => $truckkind['Truckkind']['productdetailmasters_id'],
															'date' => $date->format('Y-m-d'),
															'k' => $i,
														]).
													'">';
														foreach ($truckkind['Ordertran'] as $ordertran) {
															if ($ordertran) {
																if ($ordertran['deliverydate'] == $date->format('Y-m-d') && $ordertran['kaisen'] == $i) {
																	$deliverytime = new DateTime($ordertran['deliverydate'].$ordertran['deliverytime']);
																	echo $this->Html->link($deliverymasters[$ordertran['deliverymasters_id']].$deliverytime->format('（H:i）'), ['controller' => 'Co000s', 'action' => 'edit', $ordertran['id'], 2]);
																	echo nl2br("\n");
																}
															}
														}
													echo '</td>';
												}
											echo '</tr>';
											echo '<tr>';
												for ($i = 1; $i <=6; $i++) {
													echo '<td>';
														foreach ($truckkind['Ordertran'] as $ordertran) {
															if ($ordertran) {
																if ($ordertran['deliverydate'] == $date->format('Y-m-d') && $ordertran['kaisen'] == $i) {
																	echo $this->Form->create('No1', ['label' => false, 'div' => 'form-group']);
																		echo $this->Form->input('id', ['type' => 'hidden', 'value' => $ordertran['id']]);
																		echo $this->Form->input('container_no', [
																			'div' => false,
																			'label' => false,
																			'class' => 'form-control input-sm',
																			'type' => 'text',
																			'default' => $ordertran['container_no'],
																			'placeholder' => 'コンテナNo.を入力',
																			'onchange'=>'this.form.submit()',
																			]);
																	echo $this->Form->end();
																}
															}
														}
													echo '</td>';
												}
											echo '</tr>';
											echo '<tr>';
												for ($i = 1; $i <=6; $i++) {
													echo '<td>';
														foreach ($truckkind['Ordertran'] as $ordertran) {
															if ($ordertran) {
																if ($ordertran['deliverydate'] == $date->format('Y-m-d') && $ordertran['kaisen'] == $i) {
																	echo $this->Form->create('No2', ['label' => false, 'div' => 'form-group']);
																		echo $this->Form->input('id', ['type' => 'hidden', 'value' => $ordertran['id']]);
																		echo $this->Form->input('ref_no', [
																			'div' => false,
																			'label' => false,
																			'class' => 'form-control input-sm',
																			'type' => 'text',
																			'default' => $ordertran['ref_no'],
																			'placeholder' => 'REF/BOOKING No.を入力',
																			'onchange'=>'this.form.submit()',
																		]);
																	echo $this->Form->end();
																}
															}
														}
													echo '</td>';
												}
											echo '</tr>';
											$car2 = $truckkind['Truckkind']['carmasters_id'];
										}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>


		<p>
			<?php
				$date2 = new DateTime($paramsDate);
				if ($branchmasters_id) {
					$bid = $branchmasters_id;
				} else {
					$bid = 0;
				}
				echo $this->Html->link(('配車管理PDF出力'), [
					'action' => 'pdf',
					$date2->format('Y'),
					$date2->format('m'),
					$date2->format('d'),
					$bid,
				],
				[
					'class' => ['btn btn-success'],
					'target'=>'_blank'
				]);
			?>
		</p>

		<!-- CarandPerson 呼び出し -->
		<?php echo $this->element('CarandPerson'); ?>

	<!-- content ends -->
	</div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<script>
$(function(){
  $('td[data-href]', 'table.table-clickable').on('click', function(){
    location.href = $(this).data('href');
  });
});
</script>
