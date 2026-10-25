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
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /納入先修正 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
				    <div class="box-header well">
				        <h2>納入先マスタ修正</h2>
				    </div>
				    <?php echo $this->Form->create('Deliverymaster', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>

				    	<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>

				    	<br>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">部署</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('departmentmasters_id',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">納入先名称</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('deliveryname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">納入先略名称</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('deliveryshortname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">名称カナ（全角）</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('deliverynamekana',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">郵便番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('deliverypost',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">住所</label>
						      <div class="col-sm-4">
						      	<?php echo $this->Form->input('deliveryadd',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
						      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">電話番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('deliverytel',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">FAX番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('deliveryfax',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">納入先CD</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('deliverycode',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">長距離（運転）</label>
					      <div class="form-inline">
						      <div class="col-sm-4">
						      	<?php echo $this->Form->input('longdistancefee1',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?><label>　円</label>
						      </div>
						  </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">長距離（助手）</label>
					      <div class="form-inline">
						      <div class="col-sm-4">
						      	<?php echo $this->Form->input('longdistancefee2',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?><label>　円</label>
						      </div>
						  </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">営業キロ（名）</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('kilometer1',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">営業キロ（四）</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('kilometer2',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?>
					      </div>
					    </div>

						<div class="form-group">
							<label class="col-sm-2 control-label">所要時間</label>
							<div class="col-sm-2">
								<?php
									echo $this->Form->input('processtime',[
										'label' => false,
										'type' => 'text',
										'div' => false,
										'class' => 'timepicker form-control',
									]);
								?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">詳細情報及び注意事項</label>
							<div class="col-sm-9">
								<?php echo $this->Form->input('checkcomment',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label"><a href="https://www.google.com/maps/" target="_blank">google map</a></label>
							<div class="col-sm-9">
								<?php echo $this->Form->input('googlemapurl',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">地図の詳細情報</label>
							<div class="col-sm-9">
								<?php echo $this->Form->input('mapcomment',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?>
							</div>
						</div>

					    <hr>
					    <div class="form-group">
					      <div class="col-sm-offset-2 col-sm-10">
						    <?php echo $this->Form->submit('登録', array('div'=>false, 'class' => 'btn btn-info', 'name' => 'submit')); ?>
						    <?php echo $this->Html->link(('画像詳細画面'), ['controller'=>'Host2Deliverymasterimages','action' => 'index', $this->Form->value('Deliverymaster.id')], ['class' => 'btn btn-success']); ?>
						    <?php echo $this->Html->link(('キャンセル'), array('action' => 'index'), array('class'=>array('btn btn-danger'))); ?>
						    <?php echo $this->Html->link(('コピーして新規作成'), array('action' => 'add',$this->Form->value('Deliverymaster.id')), array('class'=>array('btn btn-warning'))); ?>
					      </div>
					    </div>

				    <?php echo $this->Form->end(); ?>

				    </div>
			    </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
