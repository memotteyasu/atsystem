<!-- 工程未登録 -->
<!-- Ch080s -->
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
				echo $this->Form->input('selectym1', [
					'div'=>false,
					'label'=>false,
					'type' => 'text',
					'class' => 'datepicker1',
					//'onchange'=>'this.form.submit()',
					'default' => $paramsDate,
				]);

					echo '　から　';

					echo $this->Form->input('selectym2', [
						'div'=>false,
						'label'=>false,
						'type' => 'text',
						'class' => 'datepicker2',
						//'onchange'=>'this.form.submit()',
						'default' => $paramsDate2,
						]);
	
					echo '　　荷主　';

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

					echo '　　営業所　';

					echo $this->Form->input('branchgroups_id',[
						'label'=>false,
						'type'=>'select',
						'div'=>false,
						'class'=>'form-control',
						//'onchange'=>'this.form.submit()',
						'default' => $branchgroups_id,
					]);

					echo '　　車両　　';

					echo $this->Form->input('cid', [
						'label' => false,
						'type' => 'select',
						'options' => $carlist,
						'div' => false,
						'class' => 'form-control chosen',
						'empty'=>'未選択',
						//'onchange' => 'this.form.submit()',
						'data-rel' => 'chosen',
					]);
/*
					echo '　　実績　';

					echo $this->Form->input('correctflg',[
						'label'=>false,
						'type'=>'select',
						'empty'=>'全て表示',
						'div'=>false,
						'class'=>'form-control',
						'onchange'=>'this.form.submit()',
						'data-rel'=>'chosen',
						'options' => $correctflgs,
						'default' => $correctflg,
					]);
*/
				echo '　';
				echo $this->Form->submit('表示', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']);
				echo $this->Form->end();

			?>


			<!-- 受注内容工程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>出荷実績</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>実績</th>
											<th>納入日</th>
											<th>時間</th>
											<th>荷主</th>
											<th>開始</th>
											<th>終了</th>
											<th>商品</th>
											<th>金額</th>
											<th>車両</th>
										</tr>
									</thead>
									<tbody>

										<?php
											foreach ($ordertrans as $ordertran) {
												if ($ordertran['Ordertran']['billconfirmflg'] == 0) {
													echo '<tr class="light">';
														echo '<td>';
															echo $this->Html->link(('未確定'), ['action' => 'edit', 'controller' => 'Ch080s', $ordertran['Ordertran']['id']], ['class' => ['btn btn-primary']]);
														echo '</td>';
												} else {
													echo '<tr>';
														echo '<td>';
															echo $this->Html->link(('確定済み'), ['action' => 'edit', 'controller' => 'Ch080s', $ordertran['Ordertran']['id']], ['class' => ['btn btn-default btn-warning']]);
														echo '</td>';
												}
													echo '<td>';
														echo ($ordertran['Ordertran']['deliverydate']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Ordertran']['deliverytime']);
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
													echo '<td>';
														echo ($ordertran['Productdetailmaster']['pdname']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Ordertran']['price']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Carmaster']['plateno']);
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
					<?php //echo $this->Html->link(('新規登録'), array('action' => 'add'), array('class'=>array('btn btn-success'))); ?>
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
</script>
