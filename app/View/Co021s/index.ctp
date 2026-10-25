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
					'default' => $paramsDate,
				]);

				echo '　　';
				echo '営業所　';

				if (isset($this->params['pass'][3])) {
					$default = $this->params['pass'][3];
				} else {
					$default = null;
				}

				echo $this->Form->input('branchmasters_id',[
					'label'=>false,
					'type'=>'select',
					'empty'=>'未選択',
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'default' => $default,
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
									<th colspan="4" class="col-sm-8">車両番号</th>
									<th class="col-sm-2">乗務員</th>
									<th class="col-sm-2">助手</th>
								</tr>
								<tr>
									<th class="col-sm-1">卸日</th>
									<th class="col-sm-1">回戦</th>
									<th class="col-sm-3">荷主</th>
									<th class="col-sm-3">納入先</th>
									<th>現在乗務員</th>
									<th>現在助手</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($truckkinds as $truckkind): ?>
									<?php if ($truckkind['Ordertran']): ?>
										<tr class="info">
											<th colspan="4">
												<?php echo $truckkind['Carmaster']['plateno'].'（'.$truckkind['Productdetailmaster']['pdname'].'）'; ?>
											</th>
											<th>
												<?php
													echo $this->Form->create('Driver1', ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);
														$i = 0;
														foreach ($truckkind['Ordertran'] as $ordertran) {
															echo $this->Form->input('Driver.'.$i.'.id', ['type' => 'hidden', 'value' => $ordertran['id']]);
															$i = $i + 1;
														}
														echo $this->Form->input('driver1id', [
															'div'=>false,
															'label'=>false,
															'type' => 'select',
															'empty'=>'',
															'options' => $userlist,
															'data-rel'=>'chosen',
															'onchange'=>'this.form.submit()',
														]);
													echo $this->Form->end();
												?>
											</th>
											<th>
												<?php
													echo $this->Form->create('Driver2', ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);
														$i = 0;
														foreach ($truckkind['Ordertran'] as $ordertran) {
															echo $this->Form->input('Driver.'.$i.'.id', ['type' => 'hidden', 'value' => $ordertran['id']]);
															$i = $i + 1;
														}
														echo $this->Form->input('driver2id', [
															'div'=>false,
															'label'=>false,
															'type' => 'select',
															'empty'=>'',
															'options' => $userlist,
															'data-rel'=>'chosen',
															'onchange'=>'this.form.submit()',
														]);
													echo $this->Form->end();
												?>
											</th>
										</tr>
										<?php foreach ($truckkind['Ordertran'] as $ordertran): ?>
											<tr>
												<td>
													<?php echo $ordertran['deliverydate']; ?>
												</td>
												<td>
													<?php echo $ordertran['kaisen']; ?>
												</td>
												<td>
													<?php echo $shippermasters[$ordertran['shippermasters_id']]; ?>
												</td>
												<td>
													<?php echo $deliverymasters[$ordertran['deliverymasters_id']]; ?>
												</td>
												<td>
													<?php
														if ($ordertran['driver1id']) {
															echo $userlist[$ordertran['driver1id']];
														}
													?>
												</td>
												<td>
													<?php
														if ($ordertran['driver2id']) {
															echo $userlist[$ordertran['driver2id']];
														}
													?>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php endif; ?>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>


		<p>
			<?php // echo $this->Html->link(('新規登録'), ['controller' => 'Co000s', 'action' => 'add'], ['class'=>['btn btn-success']]); ?>
		</p>

			<!-- 車両情報・ドライバ休み -->
<!--
			<div class="row">
				    <div class="box col-lg-6 col-md-6 col-sm-6 col-xs-6">
					    <div class="box-inner">
						    <div class="box-header well">車両情報
						    </div>
						    <div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<tbody>
										<?php
											foreach($carmasters as $carmaster) {
												echo '<tr>';
													echo '<td>';
														echo ($carmaster['Carmaster']['plateno']);
													echo '</td>';
													echo '<td>';
														$startdate = new DateTime($carmaster['Carmaster']['notrundatestart']);
														$enddate = new DateTime($carmaster['Carmaster']['notrundateend']);
														echo ($startdate->format('Y年m月d日')).'から'.($enddate->format('Y年m月d日')).'まで'.$carmaster['Carmaster']['notrunreason'];
													echo '</td>';
												echo '</tr>';
											}
										?>
									</tbody>
								</table>
							</div>
				  	  </div>
				  </div>
				    <div class="box col-lg-6 col-md-6 col-sm-6 col-xs-6">
					    <div class="box-inner">
						    <div class="box-header well">ドライバ休み
						    </div>
						    <div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<tbody>
										<?php
											foreach($drivers as $driver) {
												echo '<tr>';
													echo '<td>';
														echo ($driver['User']['usernameK']);
													echo '</td>';
													echo '<td>';
														$date = new DateTime($driver['Kintaitran']['kintaidate']);
														echo ($date->format('Y年m月d日')).($driver['Flgmaster']['flgname']);
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
 -->
			<!-- /車両情報・ドライバ休み -->

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
