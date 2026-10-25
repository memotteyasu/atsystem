<!-- 出光請求データ登録 -->
<!-- Oi140s -->
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
							$this->Html->addCrumb('出光請求一覧', ['controller' => 'Oi140s', 'action' => 'index']);
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
					'dateFormat' => 'YM',
					'monthNames' => false,
					'minYear' => date('Y')-1,
					'maxYear' => date('Y')+1,
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'style'=>'width:70px',
					'default' => $paramsDate,
				]);
				echo 'の請求データを作成';

				echo '　　　営業所　';

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
			?>
			<!-- 請求車両一覧 -->

			<?= $this->Form->create('Invoicedetail', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>出光用車両一覧</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>出光車番</th>
											<th>金額（税抜）</th>
											<th>通行料</th>
											<th>手書通行料</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$count = 0;
											foreach ($carmasters as $key=>$value):
										?>
											<tr>
												<td><?php
												echo $value;
												echo $this->Form->input($count.'.carmasters_id', [
													'label' => false,
													'type' => 'hidden',
													'div' => false,
													'value'=>$key,
													'id' => 'carmasters_id'.$count,
												]);
												echo $this->Form->input($count.'.itemname', [
													'label' => false,
													'type' => 'hidden',
													'div' => false,
													'value'=>$value,
													'id' => 'itemname'.$count,
												]);
												 ?></td>
												<td>
													<?php
														echo $this->Form->input($count.'.price', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'id' => 'price'.$count,
															'size' => 16
														]);
													?>
												</td>
												<td>
													<?php
														echo $this->Form->input($count.'.tollfee', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'size' => 16
														]);
													?>
												</td>
												<td>
													<?php
														echo $this->Form->input($count.'.handtollfee', [
															'label' => false,
															'type' => 'text',
															'div' => false,
															'size' => 16
														]);
													?>
												</td>
											</tr>
										<?php
											$count = $count + 1;
											endforeach;
										?>

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>


				<p>
					<?=
						$this->Form->button(('登録'), [
							'type' => 'submit',
							'div' => false,
							'class' => 'btn btn-sm btn-success',
						]);
					?>
				</p>

			<?= $this->Form->end(); ?>

			<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
