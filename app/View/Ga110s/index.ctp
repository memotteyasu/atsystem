<!-- 請求書明細印刷 -->
<!-- Ga110s -->
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
							$this->Html->addCrumb('月別車両別請求書作成', ['controller' => 'Ga110s', 'action' => 'index']);
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
						'dateFormat' => 'YM',
						'monthNames' => false,
						'minYear' => date('Y')-1,
						'maxYear' => date('Y')+1,
						'onchange'=>'this.form.submit()',
						'data-rel'=>'chosen',
						'style'=>'width:70px',
						'default' => $paramsDate
					]);
					echo '月分　　';
					echo '荷主　';

					echo $this->Form->input('shippermasters_id',[
						'label'=>false,
						'type'=>'select',
						'empty'=>'未選択',
						'div'=>false,
						'class'=>'form-control chosen',
						'onchange'=>'this.form.submit()',
						'data-rel'=>'chosen',
						'default' => $shippermasters_id,
					]);

					echo '　　車両　';

					echo $this->Form->input('carmasters_id',[
						'label'=>false,
						'type'=>'select',
						'empty'=>'未選択',
						'div'=>false,
						'class'=>'form-control chosen',
						'onchange'=>'this.form.submit()',
						'data-rel'=>'chosen',
						'default' => $carmasters_id,
					]);

					echo '　　営業所　';

					echo $this->Form->input('branchmasters_id',[
						'label'=>false,
						'type'=>'select',
						// 'empty'=>'未選択',
						'div'=>false,
						'class'=>'form-control chosen',
						'onchange'=>'this.form.submit()',
						'data-rel'=>'chosen',
						'default' => $branchmasters_id,
					]);
					/*
					echo '　　商品　';

					echo $this->Form->input('productdetailmasters_id',[
						'label'=>false,
						'type'=>'select',
						'empty'=>'未選択',
						'div'=>false,
						'class'=>'form-control chosen',
						'onchange'=>'this.form.submit()',
						'data-rel'=>'chosen',
						'default' => $productdetailmasters_id,
					]);
					*/
				echo $this->Form->end();

			?>


			<!-- 受注内容工程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>月別車両別請求書作成</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<!--<th>請求書作成</th>-->
											<th>納入日</th>
											<th>荷主</th>
											<th>開始</th>
											<th>終了</th>
											<th>車両</th>
											<th>状態</th>
										</tr>
									</thead>
									<tbody>

										<?php
											foreach ($ordertrans as $ordertran) {
												echo '<tr>';
													echo '<td>';
														echo ($ordertran['Ordertran']['deliverydate']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Shippermaster']['shippershortname']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Workplace1']['workplacename']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Workplace2']['workplacename']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Carmaster']['plateno']).'（'.($ordertran['Productdetailmaster']['pdname']).'）';
													echo '</td>';
													echo '<td>';
														if($ordertran['Ordertran']['invoice_flg']==1){
															echo '作成済';
														}else{
															echo '';
														}
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
						$sid = $pid = $cid = $bid = 0;
						if ($shippermasters_id) {
							$sid = $shippermasters_id;
						}
						if ($productdetailmasters_id) {
							$pid = $productdetailmasters_id;
						}
						if ($carmasters_id) {
							$cid = $carmasters_id;
						}
						if ($branchmasters_id) {
							$bid = $branchmasters_id;
						}

						echo $this->Html->link('<span class="glyphicon glyphicon-file" aria-hidden="true"></span>'.(' 請求書作成'), [
							'action' => 'add',
							$paramsDate,
							$paramsDate2,
							$sid,
							$pid,
							$cid,
							$bid,
						],
						[
							'class' => ['btn btn-success'],
							'escape' => false,
							//'target'=>'_blank'
						]);
					?>
				</p>

			<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
<!--
<script type="text/javascript">
jQuery(function(){
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional[ "ja" ] );
	var dateFormat = 'yy/mm/dd';
	jQuery('.datepicker').datepicker({
		dateFormat: 'yy/mm/dd(DD)'
	});

	jQuery(document).ready(function(){
			jQuery.datetimepicker.setLocale('ja');
			jQuery('input.datetimepicker1').datetimepicker({
				format:'Y-m-d H:i',
				step:30,
			});
	});
});
</script>
-->
<script>
$(function(){
  $('td[data-href]', 'table.table-clickable').on('click', function(){
	location.href = $(this).data('href');
  });
});

$(".chosen").chosen({
	search_contains:true
});
</script>
