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

				echo $this->Form->input('selectym1', [
					'div'=>false,
					'label'=>false,
					'type' => 'date',
					'dateFormat' => 'YM',
					'monthNames' => false,
					'minYear' => date('Y')-1,
					'maxYear' => date('Y')+1,
					'onchange'=>'this.form.submit()',
					'style'=>'width:70px',
					'default' => $paramsDate,
				]);
				echo $this->Form->end();
		?>

			<!-- カレンダー一覧 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2>タイプ2</h2>
							</div>
							<div class="box-content">
								<table class="table table-bordered table-striped" id="table01">
									<tbody>
										<?php echo $this->Html->tableHeaders(array_values($header)); ?>
										<?php echo $this->Html->tableCells($calendar,array(),array(),true); ?>
									</tbody>
								</table>

								基準日数
								<?php
									echo $this->Form->input('baseday', [
										'type' => 'text',
										'div' => false,
										'value' => $baseday,
										'label' => false,
										'class' => 'input-control',
										'style' => 'width:70px; text-align:right;',
										'disabled'
									]);
								?>
								日
							</div>
						</div>

					</div>
				</div>

			<!-- /カレンダー一覧 -->


			<!-- content ends -->
		</div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
