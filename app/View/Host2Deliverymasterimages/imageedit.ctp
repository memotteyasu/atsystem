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
							$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
							$this->Html->addCrumb('納入先マスタ一覧', ['controller' => 'Host2Deliverymasters', 'action' => 'index']);
							$deliveryname = $this->Form->value('Deliverymaster.deliveryname');
							$this->Html->addCrumb('納入先【'.$deliveryname.'】', ['controller' => 'Host2Deliverymasters', 'action' => 'edit', $this->Form->value('Deliverymaster.id')]);
							$this->Html->addCrumb('画像詳細', ['controller' => 'Host2Deliverymasters', 'action' => 'image', $this->Form->value('Deliverymaster.id')]);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /営業所修正 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
					    <div class="box-header well">
					        <h2>画像コメント編集</h2>
					    </div>

							<?php echo $this->Form->create('Deliverymasterimage', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>

							<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

							<br>
						    <div class="form-group">
						      <label class="col-sm-2 control-label">コメント</label>
						      <div class="col-sm-3">
						      	<?php echo $this->Form->input('imgcomment', ['label' => false, 'div' => false, 'class' => 'form-control']); ?>
						      </div>
						    </div>

						    <hr>
						    <div class="form-group">
						      <div class="col-sm-offset-2 col-sm-10">
							    <?php echo $this->Form->submit('編集', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']); ?>
							    <?php echo $this->Html->link(('キャンセル'), ['action' => 'image', $this->Form->value('Deliverymaster.id')], ['class' => 'btn btn-danger']); ?>
						      </div>
						    </div>

					    <?php echo $this->Form->end(); ?>
					</div>
			    </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
