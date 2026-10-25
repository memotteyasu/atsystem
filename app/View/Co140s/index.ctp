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

				echo $this->Form->end();

			?>

			<!-- /請求書一覧 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2>作成済み請求書一覧</h2>
							</div>
							<div class="box-content">
								<table class="table table-striped table-bordered">
									<thead>
										<tr>
											<th colspan="2">請求書先</th>
											<th colspan="4">件名</th>
										</tr>
										<tr>
											<th class="col-sm-2">営業所</th>
											<th class="col-sm-2">請求日</th>
											<th class="col-sm-2">支払期限</th>
											<th class="col-sm-2">請求書番号</th>
											<th class="col-sm-2"></th>
											<th class="col-sm-1"></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($invoices as $invoice): ?>
											<tr class="info">
												<td colspan="2"><strong>
													<?php echo ($invoice['Shippermaster']['shippername']); ?>
													<?php echo ($invoice['Invoice']['honorific']); ?>
												</strong></td>
												<td colspan="4"><strong>
													<?php echo ($invoice['Invoice']['subject']); ?>
												</strong></td>
											</tr>
											<tr>
												<td>
													<?php echo ($invoice['Branchmaster']['branchname']); ?>
												</td>
												<td>
													<?php echo ($invoice['Invoice']['billingdate']); ?>
												</td>
												<td>
													<?php echo ($invoice['Invoice']['paymentdate']); ?>
												</td>
												<td>
													<?php echo ($invoice['Invoice']['invoicenumber']); ?>
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
													<?php echo $this->Html->link(('編集'), ['action' => 'edit', $invoice['Invoice']['id']], ['class' => 'btn btn-info']); ?>
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

				<?php //echo $this->element('pager')?>

				<p>
					<?php echo $this->Html->link(('新規登録'), array('action' => 'add'), array('class'=>array('btn btn-success'))); ?>
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
