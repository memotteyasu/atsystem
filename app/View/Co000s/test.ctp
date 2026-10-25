<!-- コンテナ受注カレンダー -->
<!-- Co000s -->
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
						$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
						echo $this->element('breadcrumbs');
					?>
					<?php echo $this->Flash->render(); ?>
				</li>
			</ul>
		</div>

		<?php echo $this->element('000sForm'); ?>

		<?php
		/*
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
					'data-rel'=>'chosen',
					//'class'=>'form-control',
					'default' => $paramsDate,
					'style'=>'width:70px',
				]);

				echo 'から1週間　　　';
				echo '荷主　';

				echo $this->Form->input('shippermasters_id',[
					'label'=>false,
					'type'=>'select',
					'empty'=>'未選択',
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'default' => $shippermasters_id,
				]);

				echo '　　';
				echo '商品　';

				echo $this->Form->input('productdetailmasters_id',[
					'label'=>false,
					'type'=>'select',
					'empty'=>'未選択',
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'default' => $productdetailmasters_id,
				]);

				echo '　　営業所　';

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
			*/
		?>

		<!-- /荷主一覧 -->
		<div class="row">
			<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="box-inner">
					<div class="box-header well">
						<h2><i class="glyphicon glyphicon-info-sign"></i>受注カレンダー</h2>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered table-clickable">
							<thead>
								<tr>
									<th>車両番号</th>
										<?php
											$date = new DateTime($paramsDate);
											for ($i = 1; $i <= 7; $i++) {
												echo '<th>';

												if ($i == 1) {
													$date2 = $date->format('m月d日');
												} else {
													$date2 = $date->modify('+1 day')->format('m月d日');
												}

												if ($week[$date->format('w')] == '日') {
													echo $date2.'<font color="#ff0000">('.$week[$date->format('w')].')</font>';
												} else {
													echo $date2.'('.$week[$date->format('w')].')';
												}

												echo '</th>';
											}
										?>
								</tr>
							</thead>
							<tbody>
								<?php
									$car2 = null;
									foreach ($truckkinds as $truckkind) {
										$car1 = $truckkind['Truckkind']['carmasters_id'];
										if ($car1 != $car2) {
											echo '<tr class="info">';
												echo '<th colspan="8">'.$truckkind['Carmaster']['plateno'].'</th>';
											echo '</tr><tr>';
										} else {
											echo '<tr>';
										}
										echo '<td>';
											echo $truckkind['Carmaster']['plateno'].'（'.$truckkind['Productdetailmaster']['pdname'].'）';
										echo '</td>';
										$date = new DateTime($paramsDate);
											for ($i = 1; $i <= 7; $i++) {
												echo '<td data-href="'.
													$this->Html->url([
														'controller'=>'Co000s',
														'action'=>'add',
														0,
														'id' => $truckkind['Truckkind']['id'],
														'sid' => $truckkind['Truckkind']['shippermasters_id'],
														'pid' => $truckkind['Truckkind']['productdetailmasters_id'],
														'date' => $date->format('Y-m-d')
													]).
													'">';
													foreach ($truckkind['Ordertran'] as $ordertran) {
														if ($ordertran) {
															if ($ordertran['deliverydate'] == $date->format('Y-m-d')) {
																$deliverytime = new DateTime($ordertran['deliverydate'].$ordertran['deliverytime']);
																echo $this->Html->link($deliverymasters[$ordertran['deliverymasters_id']].$deliverytime->format('（H:i）'), ['controller' => 'Co000s', 'action' => 'edit', $ordertran['id'], 0]);
																echo nl2br("\n");
															}
														}
													}
												echo '</td>';
												$date->modify('+1 day');
											}
										echo '</tr>';
										$car2 = $truckkind['Truckkind']['carmasters_id'];
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
			<?php echo $this->Html->link(('新規登録'), ['controller' => 'Co000s', 'action' => 'add', 0], ['class'=>['btn btn-success']]); ?>
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
