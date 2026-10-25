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
							$this->Html->addCrumb('車両マスタ一覧', ['controller' => 'HostTruckmasters', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /車両登録 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
					    <div class="box-header well">
					        <h2>車両マスタ登録</h2>
					    </div>
					    <?php echo $this->Form->create('Carmaster',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

						    <br>
						    <div class="form-group">
						      <label class="col-sm-2 control-label">営業所</label>
						      <div class="col-sm-2">
						      	<?php echo $this->Form->input('branchmasters_id',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
						      </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">部署</label>
						      <div class="col-sm-2">
						      	<?php echo $this->Form->input('departmentmasters_id',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
						      </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">出光車番、化学品通称</label>
						      <div class="col-sm-2">
						      	<?php echo $this->Form->input('carno',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
						      </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">社内固有番号</label>
						      <div class="col-sm-2">
						      	<?php echo $this->Form->input('innerno',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
						      </div>
						    </div>

							<div class="form-group">
								<label class="col-sm-2 control-label">陸運登録No.</label>
								<!--<div class="form-inline">-->
									<div class="col-sm-2">
										<!--
										<label>陸運局　　<input type="text" class="form-control" id="toroku_rikuunn_kyoku"></label>
										<label>車種　　　<input type="text" class="form-control" id="toroku_syasyu"></label>
										<label>ひらがな　<input type="text" class="form-control" id="toroku_hiragana"></label>
										<label>番号　　　<input type="text" class="form-control" id="toroku_no"></label>
										-->
										<?php echo $this->Form->input('plateno',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
									</div>
								<!--</div>-->
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">車載端末用車両ID</label>
								<div class="col-sm-2">
									<?php echo $this->Form->input('car_id',['label'=>false, 'div'=>false, 'class'=>'form-control', 'type'=>'text']); ?>
								</div>
							</div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">トレーラー区分</label>
						      <div class="form-inline">
							      <div class="col-sm-12">
							      	<div class="radio">
							      		<?php echo $this->Form->radio('trailermasters_id', $trailermasters, array('legend'=>false, 'separator'=>' ', 'div'=>false, 'class'=>'radio')); ?>
							      	</div>
							      </div>
							  </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">トレーラー手当（円）</label>
						      <div class="col-sm-2">
									<?php echo $this->Form->input('trailerteate',['label'=>false, 'div'=>false, 'class'=>'form-control', 'type'=>'text', 'default' => '0']); ?>
						      </div>
						    </div>

							<div class="form-group">
								<label class="col-sm-2 control-label">初年度登録日</label>
								<div class="col-sm-2">
									<?php
										$ddate = date('Y-m-d');
										echo $this->Form->input('entrydate',[
											'label'=>false,
											'type'=>'text',
											'div'=>false,
											'class'=>'datepicker1 form-control'
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">購入日</label>
								<div class="col-sm-2">
									<?php
										$ddate = date('Y-m-d');
										echo $this->Form->input('purchasedate',[
											'label'=>false,
											'type'=>'text',
											'div'=>false,
											'class'=>'datepicker1 form-control'
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">車検期限日</label>
								<div class="col-sm-2">
									<?php
										$ddate = date('Y-m-d');
										echo $this->Form->input('carcheckdate',[
											'label'=>false,
											'type'=>'text',
											'div'=>false,
											'class'=>'datepicker1 form-control'
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">稼働不可能開始日</label>
								<div class="col-sm-2">
									<?php
										$ddate = date('Y-m-d');
										echo $this->Form->input('notrundatestart',[
											'label'=>false,
											'type'=>'text',
											'div'=>false,
											'class'=>'datepicker1 form-control'
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">稼働不可能終了日</label>
								<div class="col-sm-2">
									<?php
										$ddate = date('Y-m-d');
										echo $this->Form->input('notrundateend',[
											'label'=>false,
											'type'=>'text',
											'div'=>false,
											'class'=>'datepicker1 form-control'
										]);
									?>
								</div>
							</div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">稼働不可能理由</label>
						      <div class="col-sm-8">
								<?php echo $this->Form->input('notrunreason',['label'=>false, 'div'=>false, 'class'=>'form-control', 'type'=>'text']); ?>
						      </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">表示順</label>
						      <div class="col-sm-2">
						      	<?php echo $this->Form->input('displayno',array('label'=>false,'div'=>false, 'min'=>0, 'class'=>'form-control', 'min'=>'0')); ?>
						      </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">牽引区分</label>
						      <div class="form-inline">
							      <div class="col-sm-6">
							      	<div class="radio">
							      		<?php
							      			$options = array('0' => '前車', '1' => 'それ以外');
							      			echo $this->Form->radio('keninflg', $options, array('legend'=>false, 'separator'=>' ', 'div'=>false, 'class'=>'radio'));
							      		?>
							      	</div>
							      </div>
							  </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">積載数量</label>
						      <div class="col-sm-2">
						      	<?php echo $this->Form->input('loadingaccount',array('label'=>false,'div'=>false, 'min'=>0, 'class'=>'form-control', 'min'=>'0')); ?>
						      </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">現役/廃車区分</label>
						      <div class="form-inline">
							      <div class="col-sm-6">
							      	<div class="radio">
							      		<?php
							      			$options = array('0' => '現役', '1' => '廃車');
							      			echo $this->Form->radio('scrapflg', $options, array('legend'=>false, 'separator'=>' ', 'div'=>false, 'class'=>'radio'));
							      		?>
							      	</div>
							      </div>
							  </div>
						    </div>

						    <div class="form-group">
						      <label class="col-sm-2 control-label">炭酸区分</label>
						      <div class="form-inline">
							      <div class="col-sm-6">
							      	<div class="radio">
							      		<?php
							      			$options = array('0' => 'ノーマル', '1' => '炭酸用');
							      			echo $this->Form->radio('tansanflg', $options, array('legend'=>false, 'separator'=>' ', 'div'=>false, 'class'=>'radio'));
							      		?>
							      	</div>
							      </div>
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

<?php echo $this->Html->script(['jQuery.datepicker.js']); ?>
