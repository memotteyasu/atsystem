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

		<?php
			echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);

				$defa = strtotime(date('Y-m-d'));
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
					'style'=>'width:70px',
				]);

				/*
				echo '　　';
				echo '部署　<select><option>化学品部門</option><option>石油部門</option></select>';
				echo '　<select><option>本社</option><option>四日市</option></select>';
				*/
				echo '　　';
				echo '荷主　';

				if (!$this->params['pass']) {
					$default = 0;
					$default2 = 0;
				} else {
					$default = $this->params['pass'][3];
					$default2 = $this->params['pass'][4];
				}

				echo $this->Form->input('shippermasters_id',[
					'label'=>false,
					'type'=>'select',
					'empty'=>'未選択',
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'default' => $default,
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
					'default' => $default2,
				]);

			echo $this->Form->end();
		?>

		<!-- /荷主一覧 -->
		<div class="row">
			<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="box-inner">
					<div class="box-header well">
						<h2><i class="glyphicon glyphicon-info-sign"></i>配車管理</h2>
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
										$car1 = $truckkind['Truckkind']['carmasters_id'];
										if ($car1 != $car2) {
											echo '<tr class="info">';
												echo '<th colspan="8">'.$truckkind['Carmaster']['plateno'].'</th>';
											echo '</tr><tr>';
										} else {
											echo '<tr>';
										}
											echo '<td>';
												$date = new DateTime($paramsDate);
												echo $truckkind['Carmaster']['plateno'].'（'.$truckkind['Productdetailmaster']['pdname'].'）';
											echo '</td>';
											for ($i = 1; $i <=6; $i++) {
												echo '<td data-href="'.
													$this->Html->url([
														'controller' => 'Oi010s',
														'action' => 'add',
														'id' => $truckkind['Truckkind']['id'],
														'sid'=>$truckkind['Truckkind']['shippermasters_id'],
														'pid'=>$truckkind['Truckkind']['productdetailmasters_id'],
														'kyy'=>$date->format('Y'),
														'kmm'=>$date->format('m'),
														'kdd'=>$date->format('d'),
														'k'=>$i,
													]).
													'">';
													foreach ($truckkind['Ordertran'] as $ordertran) {
														if ($ordertran) {
															if ($ordertran['loadingdate'] == $date->format('Y-m-d') && $ordertran['kaisen'] == $i) {
																$deliverytime = new DateTime($ordertran['deliverydate'].$ordertran['deliverytime']);
																echo $this->Html->link($deliverymasters[$ordertran['deliverymasters_id']].$deliverytime->format('（H:i）'), ['controller' => 'Oi010s', 'action' => 'edit', $ordertran['id'], 1]);
																echo nl2br("\n");
															}
														}
													}
												echo '</td>';
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


		<p>
			<?php echo $this->Html->link(('新規登録'), ['controller' => 'Oi010s', 'action' => 'add'], ['class'=>['btn btn-success']]); ?>
		</p>

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
