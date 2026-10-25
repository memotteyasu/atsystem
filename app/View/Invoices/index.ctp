<?php
	/*
	$this->Js->get('#InvoiceShippermastersId')->event(
		'change',
		$this->Js->request(
			['controller' => 'Oi010s', 'action' => 'ajax_productdetail'],
			[
				'update' => '#InvoiceProductdetailmastersId',
				'dataExpression' => true,
				'data' => '$("#InvoiceShippermastersId").serialize()',
				//'complete' => '$("#InvoiceProductdetailmastersId").change();',
			]
		)
	);

	echo $this->Js->writeBuffer();
	*/
?>

<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
			<?php echo $this->element('Sidemenu'); ?>
        <!-- left menu ends -->

        <noscript>
            <div class="alert alert-block col-md-12">
                <h4 class="alert-heading">Warning!</h4>

                <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
                    enabled to use this site.</p>
            </div>
        </noscript>
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

			<div class="row">
				<!-- left start -->
					<div class="box col-md-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>請求書作成</h2>
							</div>

<!--
							<?php echo $this->Form->create('Invoice', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

									<div class="box-content">
										<div class="control-group">
											<label class="control-label" for="selectError">積日時</label>
											<div class="form-inline">
												<?php
													echo $this->Form->input('date',[
														'label' => false,
														'type' => 'text',
														'div' => false,
														'class' => 'datepicker1 form-control',
													]);
												?>
											</div>
										</div>
									</div>

									<div class="box-content">
										<div class="control-group">
											<label class="control-label" for="selectError">荷主</label>
											<div class="form-inline">
												<?php
													if ($this->params['named'] == null) {
														$v = 0;
													} else {
														$v = $this->params['named']['sid'];
													}
													echo $this->Form->input('shippermasters_id', [
														'label' => false,
														'type' => 'select',
														'div' => false,
														'class' => 'form-control',
														'empty' => '選択してください',
														//'style' => 'width:150px',
														'selected' => $v
													]);
												?>
											</div>
										</div>
									</div>

									<div class="box-content">
										<div class="control-group">
											<label class="control-label" for="selectError">商品</label>
											<div class="form-inline">
												<?php
													if ($this->params['named'] == null) {
														$v = 0;
													} else {
														$v = $this->params['named']['pid'];
													}
													echo $this->Form->input('productdetailmasters_id', [
														'label' => false,
														'type' => 'select',
														'div' => false,
														'class' => 'form-control',
														'empty' => '選択してください',
														//'style' => 'width:150px',
														'selected' => $v
													]);
												?>
											</div>
										</div>
									</div>

								<div class="box-content">
									<?php
										echo $this->Form->button('<i class="glyphicon glyphicon-file icon-white"></i>'.(' 作成'), [
											'type' => 'submit',
											'div' => false,
											'class' => 'btn btn-info',
											'name' => 'submit',
											'escape' => false,
										]);
									?>
								</div>

							<?php echo $this->Form->end(); ?>
						-->

							<?php
								echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);

									echo '　年月：';

									echo $this->Form->input('selectym', [
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
									]);

									echo '　　　荷主：';

									echo $this->Form->input('shippermasters_id',[
										'label'=>false,
										'type'=>'select',
										'empty'=>'未選択',
										'div'=>false,
										'class'=>'form-control',
										'onchange'=>'this.form.submit()',
										'data-rel'=>'chosen',
									]);

									echo '　　　車両：';

									/*
									echo $this->Form->input('productdetailmasters_id',[
										'label'=>false,
										'type'=>'select',
										'empty'=>'未選択',
										'div'=>false,
										'class'=>'form-control',
										'onchange'=>'this.form.submit()',
										'data-rel'=>'chosen',
									]);
									*/

									echo $this->Form->input('truckkinds_id',[
										'label'=>false,
										'type'=>'select',
										'empty'=>'未選択',
										'div'=>false,
										'class'=>'form-control',
										'onchange'=>'this.form.submit()',
										'data-rel'=>'chosen',
									]);

								echo $this->Form->end();
							?>

				</div>

				<?php
					//debug ($this->params['data']);
					if (!$this->params['data']) {
						$sid = 0;
						$tid = 0;
					} elseif (!$this->params['data']['shippermasters_id']) {
						$sid = 0;
						//$pid = $this->params['data']['productdetailmasters_id'];
						$tid = $this->params['data']['truckkinds_id'];
					} elseif (!$tid = $this->params['data']['truckkinds_id']) {
						$sid = $this->params['data']['shippermasters_id'];
						$tid = 0;
					} else {
						$sid = $this->params['data']['shippermasters_id'];
						$tid = $this->params['data']['truckkinds_id'];
					}

					echo $this->Html->link('<span class="glyphicon glyphicon-file" aria-hidden="true"></span>'.(' PDF作成'), [
						'action' => 'pdf',
						$paramsDate->format('Y'),
						$paramsDate->format('m'),
						$sid,
						$tid,
					],
					[
						'escape' => false,
						'class' => 'btn btn-info',
						'target' => '_blank'
					]);
				 ?>
			</div>
		<p>
			<?php //echo $this->Html->link(('CSVエクスポート'), ['action' => 'export'], ['class'=>['btn btn-success btn-sm']]); ?>
			<?php //echo $this->Html->link(('CSVインポート'), ['action' => 'import'], ['class'=>['btn btn-success btn-sm']]); ?>
		</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
