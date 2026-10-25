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
							$this->Html->addCrumb('諸費請求一覧', ['controller' => 'Co082s', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /諸費請求登録 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
					    <div class="box-header well">
					        <h2>諸費請求登録</h2>
					    </div>

					    <?php echo $this->Form->create('Expensestran',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

					    	<br>
							<div class="form-group">
								<label class="col-sm-2 control-label">荷主</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php echo $this->Form->input('shippermasters_id',array(
											'label'=>false, 
											'type'=>'select', 
											'div'=>false, 
											'class'=>'form-control', 
											'data-rel' => 'chosen',
											'empty'=>'選択してください')); ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">車両</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php echo $this->Form->input('carmasters_id',array(
											'label'=>false, 
											'type'=>'select', 
											'div'=>false, 
											'class'=>'form-control', 
											'data-rel' => 'chosen',
											'empty'=>'選択してください')); ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">請求締日</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php echo $this->Form->input('billconfirmdate',[
											'label'=>false,
											'div'=>false,
											'class'=>'datepicker1',
											'tabindex'=>-1,
											'type'=>'text',
											'default' => $today,
										]); ?>
									</div>
								</div>
							</div>
					    	<br>
						    <div class="form-group">
						      <label class="col-sm-2 control-label">諸費名</label>
						      <div class="col-sm-4">
						      	<?php echo $this->Form->input('expensesname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
						      </div>
						    </div>


						    <div class="form-group">
						      <label class="col-sm-2 control-label">金額（円）</label>
						      <div class="col-sm-2">
								<?php
									echo $this->Form->input('expensesprice',[
										'label' => false,
										'type' => 'text',
										'div' => false,
										'class' => 'form-control',
									]);
								?>
						      </div>
						    </div>
						    <hr>
						    <div class="form-group">
						      <div class="col-sm-offset-2 col-sm-10">
							    <?php echo $this->Form->submit('登録', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']); ?>
							    <?php echo $this->Html->link(('キャンセル'), ['action' => 'index'], ['class'=>['btn btn-danger']]); ?>
						      </div>
						    </div>

					    <?php echo $this->Form->end(); ?>
					</div>
			    </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->
