<!-- 有給管理 -->
<!-- Host012s/edit -->
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
									$this->Html->addCrumb('有休管理', ['controller' => 'Host012s', 'action' => 'index']);
									echo $this->element('breadcrumbs');
								?>
								<?php echo $this->Flash->render(); ?>
							</li>
						</ul>
					</div>

					<!--
					検索窓はここに記述
					 -->
					<?php echo $this->Form->create('Employeeholiday', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>
					<!-- コンテンツ -->
						<div class="row">
							<div class="box col-md-12">
						        <div class="box-inner">
						            <div class="box-header well">
										<?php
											if ($employeeholidays['Employeeholiday']['flgmasters_id'] == 41) {
												$title = '有休等確定済み';
											} else {
												$title = '有休等申請中';
											}
											echo '<h2><i class="glyphicon glyphicon-info-sign"></i> '.$title.'</h2>';
										?>
										<!--<h2><i class="glyphicon glyphicon-info-sign"></i> 有休申請中</h2>-->
									</div>

									<div class="box-content">
										<div class="control-group h4">
											<label class="control-label" for="selectError">申請者：</label>
											<?php echo $employeeholidays['User']['usernameK']; ?>
											<br>
											<label class="control-label" for="selectError">内容：</label>
											<?php echo $employeeholidays['Flgmaster2']['flgname']; ?>
											<br>
											<label class="control-label" for="selectError">申請日：</label>
											<?php echo $employeeholidays['Employeeholiday']['applicationdate']; ?>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-12">
								<?php //echo $this->Form->submit('確定する', ['div'=>false, 'class' => 'btn btn-success', 'name' => 'submit']); ?>
								<?php
									echo $this->Form->button('<i class="glyphicon glyphicon-ok icon-white"></i>'.(' 確定する'), [
										'type' => 'submit',
										'div' => false,
										'class' => 'btn btn-success',
										'name' => 'OK',
										'escape' => false,
									]);
								?>
								<?php
									echo $this->Form->button('<i class="glyphicon glyphicon-remove icon-white"></i>'.(' 却下する'), [
										'type' => 'submit',
										'div' => false,
										'class' => 'btn btn-danger',
										'name' => 'No',
										'escape' => false,
									]);
								?>
								<?php //echo $this->Html->link(('戻る'), ['controller' => 'Host012s', 'action' => 'index'], ['class' => 'btn btn-default']); ?>
								<?php
									echo $this->Html->link('<span class="glyphicon glyphicon-backward" aria-hidden="true"></span>'.(' 戻る'), [
										'action' => 'index'
									], [
										'escape' => false,
										'class' => 'btn btn-default'
									]);
								?>
							</div>
						</div>
					<!-- /コンテンツ -->
					<?php echo $this->Form->end(); ?>
			</div>

		</div>
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

