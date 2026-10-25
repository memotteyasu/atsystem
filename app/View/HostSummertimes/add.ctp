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
								$this->Html->addCrumb('就業時間マスタ一覧', ['controller' => 'HostSummertimes', 'action' => 'index']);
								echo $this->element('breadcrumbs');
							?>
							<?php echo $this->Flash->render(); ?>
						</li>
					</ul>
				</div>

			<!-- /就業時間登録 -->
			<div class="row">
				<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2>就業時間マスタ登録</h2>
						</div>

						<?php echo $this->Form->create('Summertime', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>

							<br>
							<div class="form-group">
								<label class="col-sm-2 control-label">部署</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('departmentmasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												//'data-rel' => 'chosen',
											]);
										?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">営業所</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('branchmasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												//'data-rel' => 'chosen',
											]);
										?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">サマータイム開始月</label>
								<div class="col-sm-2">
									<?php
										echo $this->Form->input('summerstartmonth', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control',
											'placeholder' => '開始0n月',
											//'data-rel' => 'chosen',
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">サマータイム終了月</label>
								<div class="col-sm-2">
									<?php
										echo $this->Form->input('summerendmonth', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control',
											'placeholder' => '終了0n月',
											//'data-rel' => 'chosen',
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">サマータイム就業時間</label>
								<div class="col-sm-2">
									<?php
										echo $this->Form->input('summertermtime', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control timepicker',
											'placeholder' => '就業時間',
											//'data-rel' => 'chosen',
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">サマータイム出社</label>
								<div class="col-sm-2">
									<?php
										echo $this->Form->input('summerstarttime', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control timepicker',
											'placeholder' => '開始時間',
											//'data-rel' => 'chosen',
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">サマータイム退社</label>
								<div class="col-sm-2">
									<?php
										echo $this->Form->input('summerendtime', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control timepicker',
											'placeholder' => '終了時間',
											//'data-rel' => 'chosen',
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">通常就業時間</label>
								<div class="col-sm-2">
									<?php
										echo $this->Form->input('termtime', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control timepicker',
											'placeholder' => '就業時間',
											//'data-rel' => 'chosen',
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">通常出社</label>
								<div class="col-sm-2">
									<?php
										echo $this->Form->input('starttime', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control timepicker',
											'placeholder' => '開始時間',
											//'data-rel' => 'chosen',
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">通常退社</label>
								<div class="col-sm-2">
									<?php
										echo $this->Form->input('endtime', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control timepicker',
											'placeholder' => '終了時間',
											//'data-rel' => 'chosen',
										]);
									?>
								</div>
							</div>
							<hr>

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
