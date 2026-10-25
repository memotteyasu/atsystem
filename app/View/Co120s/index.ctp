<!-- 請求書合計印刷 -->
<!-- Co120s -->
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
							$this->Html->addCrumb('請求書一覧', ['controller' => 'Co111s', 'action' => 'index']);
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

					echo '月分　　荷主　';

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
*/
				echo $this->Form->end();

			?>


			<!-- 受注内容工程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>請求書合計印刷</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<!--<th>請求書作成</th>-->
											<th>請求日</th>
											<th>内容</th>
											<th>期限</th>
										</tr>
									</thead>
									<tbody>

										<?php
											foreach ($invoices as $invoice) {
												echo '<tr>';
													echo '<td>';
														echo ($invoice['Invoice']['billingdate']);
													echo '</td>';
													echo '<td>';
														echo ($invoice['Invoice']['subject']);
													echo '</td>';
													echo '<td>';
														echo ($invoice['Invoice']['paymentdate']);
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
						if ($branchmasters_id) {
							$bid = $branchmasters_id;
						}

						echo $this->Html->link('<span class="glyphicon glyphicon-file" aria-hidden="true"></span>'.(' PDF作成'), [
							'action' => 'pdf',
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
							'target'=>'_blank'
						]);
					?>
				</p>

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

$(".chosen").chosen({
    search_contains:true
});
</script>
