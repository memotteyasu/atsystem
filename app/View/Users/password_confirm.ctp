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
				case $rid >= 30 && $rid <= 39:
				echo $this->element('DriverSidemenu');
				break;
			}
		?>
        <!-- left menu ends -->

        <noscript>
            <div class="alert alert-block col-md-12">
                <h4 class="alert-heading">Warning!</h4>

                <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
                    enabled to use this site.</p>
            </div>
        </noscript>

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

			<!-- /積地修正 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
					    <div class="box-header well">
					        <h2>ユーザーマスタ修正</h2>
					    </div>

						<?= $this->Form->create('User', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>

							<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

							<br>
							<div class="form-group">
								<label class="col-sm-2 control-label">パスワード</label>
								<div class="col-sm-3">
									<?= $this->Form->input('password_confirm', [
										'label' => false,
										'div' => false,
										'value' => '',
										'class' => 'form-control',
										'type' => 'password'
									]); ?>
								</div>
							</div>

						    <hr>
						    <div class="form-group">
						      <div class="col-sm-offset-2 col-sm-10">
							    <?php echo $this->Form->submit('変更', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']); ?>
							    <?php // echo $this->Html->link(('キャンセル'), ['action' => 'index'], ['class'=>['btn btn-danger']]); ?>
						      </div>
						    </div>

				    	<?php echo $this->Form->end(); ?>
					</div>
			    </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<?php echo $this->Html->script(['jQuery.datepicker.js']); ?>
