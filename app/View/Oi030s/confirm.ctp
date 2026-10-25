<!-- コンテナ運転手割振 -->
<!-- Co030s -->
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
							$this->Html->addCrumb('工程確認', ['controller' => 'Co030s', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>
			<!-- 受注内容工程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>最終確定処理</h2>
							</div>

							<div class="box-content">

								<?php echo $this->Form->create('Mail', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>
									</br>
									<div class="form-group">
										<label class="col-sm-2 control-label">メッセージ</label>
										<div class="col-sm-6">
											<?php
												echo $this->Form->input('message', [
													'label' => false,
													'div' => false,
													'class' => 'form-control',
													'type' => 'textarea',
													'rows' => 5,
												]);
											?>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<?php echo $this->Form->submit('登録', ['div' => false, 'class' => 'btn btn-info', 'name' => 'submit']); ?>
											<?php echo $this->Html->link(('キャンセル'), ['controller' => 'Co030s', 'action' => 'index'], ['class' => 'btn btn-danger']); ?>
										</div>
									</div>
								<?php echo $this->Form->end(); ?>

							</div>
						</div>
					</div>
				</div>

			<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
