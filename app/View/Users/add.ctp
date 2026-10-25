<div class="ch-container">
	<div class="row">

		<div class="row">
			<div class="col-md-12 center login-header">
				<h2>ユーザー登録</h2>
			</div>
			<!--/span-->
		</div><!--/row-->

		<div class="row">
			<div class="well col-md-5 center login-box">
				<?php echo $this->Flash->render(); ?>
				<?php echo $this->Form->create('User',['novalidate' => true]); ?>
					<fieldset>
						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
							<?php
								echo $this->Form->input('username', [
									'label' => '',
									'tabindex' => '1',
									'class' => 'form-control input-lg',
									'placeholder'=>'ユーザーID'
								]);
							?>
						</div>
						<div class="clearfix"></div><br>

						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock red"></i></span>
							<?php
								echo $this->Form->input('password', [
									'label' => '',
									'tabindex' => '2',
									'class' => 'form-control input-lg',
									'placeholder'=>'パスワード'
								]);
							?>
						</div>
						<div class="clearfix"></div><br>

						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="glyphicon glyphicon-home red"></i></span>
							<?php
								echo $this->Form->input('kaisyas_id', [
									'label' => '',
									'tabindex' => '3',
									'class' => 'form-control input-lg',
									'empty'=>'会社を選択',
									//'options'=>$kaisyas
								]);
							?>
						</div>
						<div class="clearfix"></div><br>

						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="glyphicon glyphicon-home red"></i></span>
							<?php
								echo $this->Form->input('departmentmasters_id', [
									'label' => '',
									'tabindex' => '4',
									'class' => 'form-control input-lg',
									'empty'=>'部署を選択',
									//'options'=>$departmentmasters
								]);
							?>
						</div>
						<div class="clearfix"></div><br>

						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="glyphicon glyphicon-home red"></i></span>
							<?php
								echo $this->Form->input('branchmasters_id', [
									'label' => '',
									'tabindex' => '5',
									'class' => 'form-control input-lg',
									'empty'=>'営業所を選択',
									//'options'=>$branchmasters
								]);
							?>
						</div>
						<div class="clearfix"></div><br>

						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="glyphicon glyphicon-flag red"></i></span>
							<?php
								echo $this->Form->input('rolemasters_id', [
									'label' => '',
									'tabindex' => '6',
									'class' => 'form-control input-lg',
									'empty'=>'権限を選択',
									'options'=>$rolemasters
								]);
							?>
						</div>
						<div class="clearfix"></div><br>

						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
							<?php
								echo $this->Form->input('usernameK', [
									'label' => '',
									'tabindex' => '7',
									'class' => 'form-control input-lg',
									'placeholder'=>'名前',
								]);
							?>
						</div>
						<div class="clearfix"></div><br>

						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="glyphicon glyphicon-envelope red"></i></span>
							<?php
								echo $this->Form->input('useremail', [
									'label' => '',
									'tabindex' => '8',
									'class' => 'form-control input-lg',
									'placeholder'=>'Email',
								]);
							?>
						</div>
						<div class="clearfix"></div><br>

						<p class="center col-md-5">
							<?php echo $this->Form->submit('登録', ['div'=>false, 'class' => 'btn btn-primary', 'name' => 'submit']); ?>
						</p>
					</fieldset>
				<?php echo $this->Form->end(); ?>
			</div>
			<!--/span-->
		</div><!--/row-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
