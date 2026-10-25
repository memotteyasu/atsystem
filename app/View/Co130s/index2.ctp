<!-- 請求書明細一覧 -->
<!-- CO130s -->
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
							$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
							$this->Html->addCrumb('請求書作成', ['controller' => 'Co130s', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- 請求書一覧 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>請求書一覧</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th><?php echo $this->Paginator->sort('shippermasters_id', '請求書先'); ?></th>
											<th><?php echo $this->Paginator->sort('subject', '件名'); ?></th>
											<th><?php echo $this->Paginator->sort('billingdate', '請求日'); ?></th>
											<th><?php echo $this->Paginator->sort('paymentdate', '支払期限'); ?></th>
											<th><?php echo $this->Paginator->sort('invoicenumber', '請求書番号'); ?></th>
											<th>PDF出力</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($invoices as $invoice): ?>
											<tr>
												<td>
													<?php echo $invoice['Shippermaster']['shippername'].' '.$invoice['Invoice']['honorific']; ?>
												</td>
												<td>
													<?php echo $invoice['Invoice']['subject']; ?>
												</td>
												<td>
													<?php echo $invoice['Invoice']['billingdate']; ?>
												</td>
												<td>
													<?php echo $invoice['Invoice']['paymentdate']; ?>
												</td>
												<td>
													<?php echo $invoice['Invoice']['invoicenumber']; ?>
												</td>
												<td>
													<?php
														echo $this->Html->link('<span class="glyphicon glyphicon-file" aria-hidden="true"></span>'.(' PDF出力'), [
															'action' => 'pdf',
															$invoice['Invoice']['id'],
														],
														[
															'escape' => false,
															'class' => 'btn btn-primary',
															'target' => '_blank'
														]);
													?>
												</td>
												<td>
													<?php echo $this->Html->link(('詳細確認'), ['action' => 'view', $invoice['Invoice']['id']], ['class' => 'btn btn-info']); ?>
													<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $invoice['Invoice']['id']], ['confirm' => __('削除しますか？'), 'class' => 'btn btn-danger']); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<?php echo $this->element('pager')?>

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
