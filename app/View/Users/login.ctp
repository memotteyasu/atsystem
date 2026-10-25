<?php echo $this->Form->create('User'); ?>
<div class="ch-container">
    <div class="row">

    <div class="row">
        <div class="col-md-12 center login-header">
            <h2>ログイン画面</h2><?php echo date('Y-m-d H:i'); ?>
        </div>
        <!--/span-->
    </div><!--/row-->

    <div class="row">
        <div class="well col-md-5 center login-box">
            <div class="alert alert-info">
                会社ID、ユーザーID、パスワードを入力してください
            </div>
            <?php echo $this->Flash->render(); ?>

			<?php echo $this->Form->create('User'); ?>
				<fieldset>
					<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="glyphicon glyphicon-home red"></i></span>
						<?php
							echo $this->Form->input('kaisyakey', [
								'label' => '',
								'tabindex' => '1',
								'class' => 'form-control input-lg',
								'placeholder' => '会社ID'
							]);
						?>
					</div>
					<div class="clearfix"></div><br>

					<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
						<?php
							echo $this->Form->input('username', [
								'label' => '',
								'tabindex' => '2',
								'class' => 'form-control input-lg',
								'placeholder' => 'ユーザーID'
							]);
						?>
					</div>
					<div class="clearfix"></div><br>

					<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock red"></i></span>
						<?php
							echo $this->Form->input('password', [
								'label' => '',
								'tabindex' => '3',
								'class' => 'form-control input-lg',
								'placeholder' => 'パスワード'
							]);
						?>
					</div>

					<div class="clearfix"></div>

					<p class="center col-md-5">
						<?php echo $this->Form->submit('ログイン', [
							'div' => false,
							'tabindex' => '4',
							'class' => 'btn btn-primary',
							'name' => 'submit'
							]);
						?>
					</p>
				</fieldset>
			<?php echo $this->Form->end(); ?>

        </div>
        <!--/span-->
    </div><!--/row-->
</div><!--/fluid-row-->

</div><!--/.fluid-container-->
