<!-- 受注一覧 -->
<!-- Co060s/edit -->
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
							$this->Html->addCrumb('受注一覧', ['controller' => 'Co060s', 'action' => 'index']);
							$this->Html->addCrumb('価格一覧', ['controller' => 'Co060s', 'action' => 'add', $this->request->data['Ordertran']['id']]);
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
							<h2><i class="glyphicon glyphicon-info-sign"></i>価格内容変更</h2>
						</div>

						<?php echo $this->Form->create('OrdertranPricemaster', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

							<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

							<br>
							<div class="form-group">
								<label class="col-sm-3 control-label">価格内容</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('pricemasters_id', [
												'label'=>false,
												'type'=>'select',
												'div'=>false,
												'class'=>'form-control',
												'empty'=>'選択してください',
												'data-rel' => 'chosen',
												'options' => $pricemasters
											]);
										?>
									</div>
								</div>
							</div>

							<hr>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<?php
										echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' 更新'), [
											'type' => 'submit',
											'div' => false,
											'class' => 'btn btn-info',
											'name' => 'submit',
											'escape' => false,
										]);
									?>
									<?php //echo $this->Form->submit('登録', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']); ?>
								</div>
							</div>

						<?php echo $this->Form->end(); ?>

					</div>
				</div>
			</div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
