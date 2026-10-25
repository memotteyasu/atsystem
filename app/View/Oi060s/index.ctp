<!-- 工程未登録 -->
<!-- Oi060s -->
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
					$dete = strtotime(date('Y-m-d'));
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

					echo '以前を表示';

					echo '　　荷主　';

					if (!$this->params['pass']) {
						$default = 0;
						$default2 = 0;
					} else {
						$default = $this->params['pass'][0];
						$default2 = $this->params['pass'][1];
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
						'default' => $default2,
					]);

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
											<th>積日</th>
											<th>卸日</th>
											<th>荷主</th>
											<th>納入先</th>
											<th>車両</th>
										</tr>
									</thead>
									<tbody>

										<?php
											foreach ($ordertrans as $ordertran) {
											//debug($ordertran);
												//if (!$ordertran['Ordertran']['correctdate'] or $ordertran['Ordertran']['correctdate'] == 0) {
												if ($ordertran['Ordertran']['correctflg'] == 0) {
													echo '<tr class="warning">';
														echo '<td>';
															//echo '<span class="label label-danger">実績未登録</span>';
															echo $this->Html->link(('実績登録'), ['action' => 'edit', 'controller' => 'Oi060s', $ordertran['Ordertran']['id']], ['class' => ['btn btn-primary']]);
														echo '</td>';
												} else {
													echo '<tr>';
														echo '<td>';
															//echo '<span class="label label-default">実績登録済み</span>';
															echo $this->Html->link(('実績編集'), ['action' => 'edit', 'controller' => 'Oi060s', $ordertran['Ordertran']['id']], ['class' => ['btn btn-default']]);
														echo '</td>';
												}
													echo '<td>';
														echo ($ordertran['Ordertran']['loadingdate']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Ordertran']['deliverydate']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Shippermaster']['shippershortname']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Deliverymaster']['deliveryshortname']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Carmaster']['plateno']).'（'.($ordertran['Productdetailmaster']['pdname']).'）';
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
