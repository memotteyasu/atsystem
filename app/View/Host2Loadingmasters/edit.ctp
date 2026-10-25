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
							$this->Html->addCrumb('積地マスタ一覧', ['controller' => 'Host2Loadingmasters', 'action' => 'index']);
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
				        <h2>積地マスタ修正</h2>
				    </div>

						<?php echo $this->Form->create('Loadingmaster', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>
						<?php //echo $this->Form->create('Loadingmaster',['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'enctype'=>'multipart/form-data', 'novalidate' => true]); ?>

						<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

						<br>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">積地名称</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('loadingname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">積地略名称</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('loadingshortname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">名称カナ（全角）</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('loadingnamekana',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">郵便番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('loadingpost',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">住所</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('loadingadd',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

						<!--
					    <div class="form-group">
					      <label class="col-sm-2 control-label">住所</label>
					      <div class="form-inline">
						      <div class="col-sm-4">
						      	<label>都道府県　<input type="text" class="form-control" id="jusyo_fuken"></label>
						      	<label>市区町村　<input type="text" class="form-control" id="jusyo_shiku"></label>
						      	<label>町名番地　<input type="text" class="form-control" id="jusyo_tyo"></label>
						      	<label>ﾋﾞﾙ・ﾏﾝｼｮﾝ名　<input type="text" class="form-control" id="jusyo_bill"></label>
						      </div>
						  </div>
					    </div>
					    -->

					    <div class="form-group">
					      <label class="col-sm-2 control-label">電話番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('loadingtel',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">FAX番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('loadingfax',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">積地CD</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('loadingcode',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>　<small>（※社内用番号）</small>
					      </div>
					    </div>

						<div class="form-group">
							<label class="col-sm-2 control-label">部署</label>
							<div class="form-inline">
								<div class="col-sm-4">
									<?php
										echo $this->Form->input('departmentmasters_id', [
											'label'=>false,
											'type'=>'select',
											'div'=>false,
											'class'=>'form-control',
											'empty'=>'選択してください',
											//'id'=>'makerPulldown'
										]);
									?>
								</div>
							</div>
						</div>
<!-- 2020-02-01
					    <div class="form-group">
							<label class="col-sm-2 control-label">営業所</label>
							<div class="form-inline">
								<div class="col-sm-4">
									<?php
										echo $this->Form->input('branchmasters_id', [
											'label'=>false,
											'type'=>'select',
											'div'=>false,
											'class'=>'form-control',
											'empty'=>'選択してください',
											//'id'=>'makerPulldown'
										]);
									?>
								</div>
							</div>
						</div>
-->
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
						    <?php echo $this->Form->submit('更新', array('div'=>false, 'class' => 'btn btn-info', 'name' => 'submit')); ?>
						    <?php echo $this->Html->link(('キャンセル'), array('action' => 'index'), array('class'=>array('btn btn-danger'))); ?>
						    <?php echo $this->Html->link(('画像詳細画面'), ['controller' => 'Host2Loadingmasterimages', 'action' => 'index', $this->Form->value('Loadingmaster.id')], ['class' => 'btn btn-success']); ?>
					      </div>
					    </div>

				    <?php echo $this->Form->end(); ?>
				    </div>

				    <?php //phpinfo(); ?>

			    </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
