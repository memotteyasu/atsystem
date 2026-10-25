<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
<?php echo $this->element('DriverSidemenu'); ?>
        <!-- left menu ends -->

        <noscript>
            <div class="alert alert-block col-md-12">
                <h4 class="alert-heading">Warning!</h4>

                <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
                    enabled to use this site.</p>
            </div>
        </noscript>

        <div id="content" class="col-lg-10 col-sm-10">
            <!-- content starts -->
            <div>
    <ul class="breadcrumb">
        <li>
            <?php echo $this->Flash->render(); ?>
        </li>
    </ul>
</div>

<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well">
                <h2>アサヒコイン付与</h2>
            </div>
            <div class="box-content row">
                <div class="col-lg-12 col-md-12">
                <p>社員名：<?php echo $users['User']['usernameK']; ?><br>
                アサヒコイン残高：<?php echo $users['Employeepoint']['pointzan']; ?>pt
                </p>
                <hr>
                <p>
                誰にポイントを付与しますか？<br>
                自分のポイントを付与したい相手を選択してください。
				</p>
	            <div class="box-content">
	                <?php echo $this->Form->create('Employeegetpoint',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

	                	<br>
	                    <div class="form-group">
							<div class="col-sm-2">
								<div class="form-inline">
									<?php echo $this->Form->input('users_id',['label'=>false,'div'=>false, 'class'=>'form-control', 'empty'=>'選択してください','options' => $userlists]); ?>
								</div>
							</div>
						</div>

						<!--
						<div class="form-group">
							<div class="col-sm-2">
								<label class="control-label">付与するアサヒコイン</label>
								<?php //echo $this->Form->input('point',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?>
							</div>
						</div>
						-->

						<div class="form-group">
							<div class="col-sm-4">
								<label class="control-label">付与するアサヒコイン</label>
								<div class="form-inline">
									<?php
										$list = array_combine(range(100, 1000, 100), range(100, 1000, 100));
										echo $this->Form->input('point',array('type' => 'select', 'options' => $list, 'label' => false, 'div' => false, 'class' => 'form-control', 'empty'=>'選択してください'));
									?>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-3">
								<label class="control-label">送信メッセージ</label>
								<?php echo $this->Form->textarea('sendreason',['label'=>false,'div'=>false, 'class'=>'form-control','rows'=>'3']); ?>
							</div>
						</div>

	                    <?php echo $this->Form->submit('ポイント送信', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']); ?>
	                <?php echo $this->Form->end(); ?>
	            </div>

                </div>
            </div>
        </div>
    </div>
</div>



    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->


    <hr>

</div><!--/.fluid-container-->


