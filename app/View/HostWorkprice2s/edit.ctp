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
								$this->Html->addCrumb('作業歩合マスタ2一覧', ['controller' => 'HostWorkprice2s', 'action' => 'index']);
								echo $this->element('breadcrumbs');
							?>
							<?php echo $this->Flash->render(); ?>
						</li>
					</ul>
				</div>

			<!-- /作業歩合修正 -->
			<div class="row">
				<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2>作業歩合マスタ2編集</h2>
						</div>

						<?php echo $this->Form->create('Workprice2', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>

							<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

							<br>
							<div class="form-group">
								<label class="col-sm-2 control-label">作業歩合</label>
								<div class="form-inline">
									<div class="col-sm-10">
										<?php
											echo $this->Form->input('startkilo', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'placeholder' => 'スタートkm'
												//'data-rel' => 'chosen',
											]);
										?>
										～
										<?php
											echo $this->Form->input('endkilo', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'placeholder' => 'エンドkm',
												//'data-rel' => 'chosen',
											]);
										?>
									</div>
								</div>
							</div>


							<div class="form-group">
								<label class="col-sm-2 control-label">普通残業時間</label>
								<div class="col-sm-2">
									<?php
										echo $this->Form->input('zangyojikan', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control',
											'placeholder' => '時間を4桁で入力',
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">深夜残業時間</label>
								<div class="col-sm-2">
									<?php
										echo $this->Form->input('shinyajikan', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control',
											'placeholder' => '時間を4桁で入力',
										]);
									?>
								</div>
							</div>


							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<?php echo $this->Form->submit('登録', array('div'=>false, 'class' => 'btn btn-info', 'name' => 'submit')); ?>
									<?php echo $this->Html->link(('キャンセル'), array('action' => 'index'), array('class'=>array('btn btn-danger'))); ?>
								</div>
							</div>

						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>

		</div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
