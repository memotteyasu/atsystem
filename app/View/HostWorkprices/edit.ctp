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
								$this->Html->addCrumb('作業歩合マスタ一覧', ['controller' => 'HostWorkprices', 'action' => 'index']);
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
							<h2>作業歩合マスタ編集</h2>
						</div>

						<?php echo $this->Form->create('Workprice', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>

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
										<div class="input-group">
											<?php
												echo $this->Form->input('price', [
													'label' => false,
													'type' => 'text',
													'div' => false,
													'class' => 'form-control',
													'placeholder' => '金額',
													//'data-rel' => 'chosen',
												]);
											?>
											<span class="input-group-addon">円</span>
										</div>
									</div>
								</div>
							</div>

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
								<label class="col-sm-2 control-label">労働区分</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('roudouflg', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												'options'=>$roudouflgs
											]);
										?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">歩合ランク</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('buairank', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												'options'=>$buairanks
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
								<label class="col-sm-2 control-label">定額時間外</label>
								<div class="col-sm-2">
									<?php
										echo $this->Form->input('zangyotanka', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control',
											'placeholder' => '金額を入力',
											//'data-rel' => 'chosen',
										]);
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">定額深夜割増</label>
								<div class="col-sm-2">
									<?php
										echo $this->Form->input('shinyatanka', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control',
											'placeholder' => '金額を入力',
											//'data-rel' => 'chosen',
										]);
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">距離歩合</label>
								<div class="col-sm-2">
									<?php
										echo $this->Form->input('untinbuai', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control',
											'placeholder' => '歩合を入力',
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
