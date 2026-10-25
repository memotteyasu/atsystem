<div class="ch-container">
	<div class="row">

		<!-- left menu starts -->
		<?php
//		$bid = $user['Branchmaster']['id'];
		$rid = $user['Rolemaster']['id'];
		switch($rid){
				case $rid >= 10 && $rid <= 19:
				echo $this->element('HostSidemenu');
				break;
				case $rid >= 20 && $rid <= 29:
				echo $this->element('ClerkSidemenu');
				break;
		}
		?>
		<!-- left menu ends -->

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
				echo $this->Form->create('User', ['url' => ['controller'=>'Users','action'=>'index'], 'label' => false, 'div' => 'form-group', 'class' => 'form-inline', 'novalidate' => true]);

					echo '部署　';

					if (isset($conditions['User.departmentmasters_id'])) {
						$did = $conditions['User.departmentmasters_id'];
					} else {
						$did = 0;
					}
					echo $this->Form->input('departmentmasters_id', [
						'label' => false,
						'type' => 'select',
						'div' => false,
						'class' => 'form-control',
						'empty'=>'未選択',
						'onchange' => 'this.form.submit()',
						'data-rel' => 'chosen',
						// 'default'=>$conditions['User.departmentmasters_id'],
						'default' => $did,
					]);

					echo '　　';

					echo '営業所　';

					if (isset($conditions['User.branchmasters_id'])) {
						$branchid = $conditions['User.branchmasters_id'];
					} else {
						$branchid = 0;
					}
					echo $this->Form->input('branchmasters_id', [
						'label' => false,
						'type' => 'select',
						'div' => false,
						'class' => 'form-control',
						'empty'=>'未選択',
						'onchange' => 'this.form.submit()',
						'data-rel' => 'chosen',
						// 'default'=>$conditions['User.branchmasters_id'],
						'default' => $branchid,
					]);

					echo '　　在勤状態　';

					$list = ['0' => '在勤', '1' => '退職'];
					echo $this->Form->input('zaisekiflg', [
						'label' => false,
						'type' => 'select',
						'div' => false,
						'class' => 'form-control',
						'options' => $list,
						'onchange' => 'this.form.submit()',
						'data-rel' => 'chosen',
						'default'=>$conditions['User.zaisekiflg']
					]);

				echo $this->Form->end()
			?>

			<!-- /積地一覧 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2>社員マスタ一覧</h2>
							</div>
							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th><?php echo $this->Paginator->sort('syain_cd', '社番'); ?></th>
											<th><?php echo $this->Paginator->sort('usernameK', '名前'); ?></th>
											<th><?php echo $this->Paginator->sort('username', 'ユーザーID'); ?></th>
											<th><?php echo $this->Paginator->sort('rolemasters_id', '権限'); ?></th>
											<th><?php echo $this->Paginator->sort('useremail', 'Email'); ?></th>
											<th><?php echo $this->Paginator->sort('roudouflg', '労働区分'); ?></th>
											<th><?php echo $this->Paginator->sort('branchmasters_id', '支店名'); ?></th>
											<th> </th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($users as $user): ?>
										<?php //echo debug($user['Employeepoint']); ?>
										<tr>
											<td class="text-right"><?php echo ($user['User']['syain_cd']); ?></td>
											<td><?php echo ($user['User']['usernameK']); ?></td>
											<td><?php echo ($user['User']['username']); ?></td>
											<td><?php echo ($user['Rolemaster']['rolename']); ?></td>
											<td><a href="mailto:<?php echo ($user['User']['useremail']); ?>"><?php echo ($user['User']['useremail']); ?></a></td>
											<td>
												<?php
													switch($user['User']['roudouflg']){
														case 1:
															echo '正社員';
															break;
														case 2:
															echo '嘱託';
															break;
														case 3:
															echo 'パート';
															break;
														case 4:
															echo '季節（臨時）';
															break;
														case 5:
															echo 'バイト';
															break;
														case 9:
															echo '協力会社';
															break;
													}
												?>
											</td>
											<td><?php echo ($user['Branchmaster']['branchname']); ?></td>
											<td>
												<?php
													switch($rid){
														case 10: //管理者
														case 11: //管理者
														case 12: //管理者
														case 24: //事務職（全て）
															echo $this->Html->link(('修正'), ['action' => 'edit',$user['User']['id']], ['class'=>['btn btn-info']]);
															echo $this->Html->link(('キロ単価'), ['controller'=>'UsersEvaluationmasters','action' => 'index',$user['User']['id']], ['class'=>['btn btn-primary']]);
															//echo $this->Html->link(('カレンダ'), ['action' => 'calendaruser',$user['User']['id']], ['class'=>['btn btn-success']]);
															break;
														default:
															if ($branchmasters_id == $user['Branchmaster']['id']) {
																echo $this->Html->link(('修正'), ['action' => 'edit',$user['User']['id']], ['class'=>['btn btn-info']]);
															} else {
																echo $this->Html->link(('修正'), [
																	'action' => 'edit',
																	$user['User']['id']
																], [
																	'class'=> 'btn btn-warning',
																	'disabled' => 'disabled'
																]);
															}
													}
												?>
											</td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<?php echo $this->element('pager')?>

				<p>
				<?php echo $this->Html->link(('新規登録'), ['action' => 'add2'], ['class'=>['btn btn-success']]); ?>
				</p>

			<!-- content ends -->
		</div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
