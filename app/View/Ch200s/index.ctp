<!-- 請求書作成 -->
<!-- Ch200s -->
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
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>
			<?php
				echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);
				echo '<span>請求日 </span>';
				echo $this->Form->input('selectym1', [
					'div'=>false,
					'label'=>false,
					'type' => 'text',
					'class' => 'datepicker1',
					//'onchange'=>'this.form.submit()',
					'default' => $paramsDate,
				]);

				echo 'から';

				echo $this->Form->input('selectym2', [
					'div'=>false,
					'label'=>false,
					'type' => 'text',
					'class' => 'datepicker2',
					//'onchange'=>'this.form.submit()',
					'default' => $paramsDate2,
					]);

				echo '　荷主　';

					echo $this->Form->input('shippermasters_id',[
						'label'=>false,
						'type'=>'select',
						'empty'=>'未選択',
						'div'=>false,
						'class'=>'form-control',
						//'onchange'=>'this.form.submit()',
						'data-rel'=>'chosen',
						'default' => $shippermasters_id,
					]);

					echo '　営業所　';

					echo $this->Form->input('branchgroups_id',[
						'label'=>false,
						'type'=>'select',
						// 'empty'=>'未選択',
						'div'=>false,
						'class'=>'form-control',
						//'onchange'=>'this.form.submit()',
						'data-rel'=>'chosen',
						'default' => $branchgroups_id,
					]);

					echo '　車両　';

					echo $this->Form->input('c_id', [
						'label' => false,
						'type' => 'select',
						'options' => $carlist,
						'div' => false,
						'class' => 'form-control chosen',
						'empty'=>'未選択',
						//'onchange' => 'this.form.submit()',
						'data-rel' => 'chosen',
						'default' => 0
					]);
					echo $this->Form->submit('表示', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']);
			?>


			<!-- 受注内容工程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>出荷実績済み</h2>
							</div>

							<div class="box-content">
								<table class="table table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>日付</th>
											<th>荷主</th>
											<th>開始</th>
											<th>終了</th>
											<th>金額</th>
											<th>車両</th>
											<th>税</th>
										</tr>
									</thead>
									<tbody>
										<?php
											foreach ($ordertrans as $ordertran) {
												if($ordertran['Ordertran']['invoice_flg']==1){
													echo '<tr class="bg-danger">';
												}else{
													echo '<tr>';
												}
												echo '<td>';
													echo ($ordertran['Ordertran']['loadingdate']);
												echo '</td>';
												echo '<td>';
													echo ($ordertran['Shippermaster']['shippershortname']);
												echo '</td>';
												echo '<td>';
													echo ($ordertran['Workplace1']['workplaceshortname']);
												echo '</td>';
												echo '<td>';
													echo ($ordertran['Workplace2']['workplaceshortname']);
												echo '</td>';
												if($ordertran['Ordertran']['cancelprice']>0){
													echo '<td class="bg-warning text-right">';
													echo ($ordertran['Ordertran']['cancelprice']);
												}else{
													echo '<td class="text-right">';
													echo ($ordertran['Ordertran']['price']);
												}
												echo '</td>';
												echo '<td>';
													echo ($ordertran['Carmaster']['plateno']);
												echo '</td>';
												echo '<td>';
													echo ($taxflgs[$ordertran['Ordertran']['taxflg']]);
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
					if($shipperdata){
						if($shipperdata['Shippermaster']['billtype']==1){
							//Type A
							echo $this->Html->link('請求書作成(A)', ['controller' => 'Ch200s', 'action' => 'add_a', $paramsDate, $paramsDate2,$shippermasters_id,$branchgroups_id,$cid,'A'], ['class'=>'btn btn-warning','escape' => false]);
						}
					}
					if($shipperdata){
						if($shipperdata['Shippermaster']['billtype']==2){
						//Type B
						echo $this->Html->link('請求書作成(B)', ['controller' => 'Ch200s', 'action' => 'add_b', $paramsDate, $paramsDate2,$shippermasters_id,$branchgroups_id,$cid,'B'], ['class'=>'btn btn-warning','escape' => false]);
						}
					}
					if($shipperdata){
						if($shipperdata['Shippermaster']['billtype']==3){
						//Type C
						echo $this->Html->link('請求書作成(C)', ['controller' => 'Ch200s', 'action' => 'add_c', $paramsDate, $paramsDate2,$shippermasters_id,$branchgroups_id,$cid,'C'], ['class'=>'btn btn-warning','escape' => false]);
						}
					}
					if($shipperdata){
						if($shipperdata['Shippermaster']['billtype']==4){
						//Type D
						echo $this->Html->link('請求書作成(D)', ['controller' => 'Ch200s', 'action' => 'add_d', $paramsDate, $paramsDate2,$shippermasters_id,$branchgroups_id,$cid,'D'], ['class'=>'btn btn-warning','escape' => false]);
						}
					}
					if($shipperdata){
						if($shipperdata['Shippermaster']['billtype']==5){
						//Type E
						echo $this->Html->link('請求書作成(E)', ['controller' => 'Ch200s', 'action' => 'add_e', $paramsDate, $paramsDate2,$shippermasters_id,$branchgroups_id,$cid,'E'], ['class'=>'btn btn-warning','escape' => false]);
						}
					}
					if($shipperdata){
						if($shipperdata['Shippermaster']['billtype']==6){
						//Type F
						echo $this->Html->link('請求書作成(F)', ['controller' => 'Ch200s', 'action' => 'add_f', $paramsDate, $paramsDate2,$shippermasters_id,$branchgroups_id,$cid,'F'], ['class'=>'btn btn-warning','escape' => false]);
						}
					}
					echo '　';
					echo $this->Html->link('free請求書作成', ['controller' => 'Ch200s', 'action' => 'add2'], ['class'=>'btn btn-success','escape' => false]);
					echo '　';
					echo $this->Html->link('free請求書作成（計算無）', ['controller' => 'Ch200s', 'action' => 'add3'], ['class'=>'btn btn-success','escape' => false]);
				?>
					<small class="text-danger">　　※赤帯は請求書作成済みです</small>
				</p>
				<?php echo $this->Form->end(); ?>

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
<script>
$(".chosen").chosen({
    search_contains:true
});
</script>
