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
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /会社マスタ修正 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				    <div class="box-inner">
				    <div class="box-header well">
				        <h2>会社マスタ修正</h2>
				    </div>

						<?php echo $this->Form->create('Kaisya',['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'enctype'=>'multipart/form-data', 'novalidate' => true]); ?>

						<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

						<br>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">会社名称</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('companyname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">会社名略称</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('companyshortname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">会社ID</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('kaisyakey',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">１ヶ月基準日</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('baseday',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					      <span class="align-bottom">
					      	日
					      </span>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">１か月基準労働時間</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('basetime',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">レギュラー単価（個人給油用）</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('gasprice',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>（税込）
					    </div>
<!--
					    <div class="form-group">
					      <label class="col-sm-2 control-label">ハイオク単価</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('highokuprice',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>（税込）
					    </div>
-->
					    <div class="form-group">
					      <label class="col-sm-2 control-label">軽油単価（個人給油用）</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('dieselprice',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>（税込）
					    </div>
<hr>
<div class="box center text-danger">交通費用</div>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">レギュラー単価</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('regularprice',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>（税込）
					    </div>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">軽油単価</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('keiyuprice',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>（税込）
					    </div>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">電気単価(1whあたり）</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('electroprice',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>（税込）
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">水素単価(1gあたり）</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('hydrogenprice',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>（税込）
					    </div>
<hr>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">自転車単価(1回あたり）</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('bicycleprice',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>（税込）
					    </div>

						<div class="form-group">
					      <label class="col-sm-2 control-label">オイル単価</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('oilprice',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>（税込）
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">給与支給日</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('salarydate',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">住所１</label>
					      <div class="col-sm-8">
					      	<?php echo $this->Form->input('address1',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">住所２</label>
					      <div class="col-sm-8">
					      	<?php echo $this->Form->input('address2',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">電話番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('tel1',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">FAX番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('fax1',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">振込口座情報</label>
					      <div class="col-sm-8">
					      	<?php echo $this->Form->input('bank',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

						<div class="form-group">
					      <label class="col-sm-2 control-label">インボイス番号</label>
					      <div class="col-sm-8">
					      	<?php echo $this->Form->input('invoiceno',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>
						<!--
						<div class="form-group">
					      <label class="col-sm-2 control-label">燃料サーチャージ(%)</label>
					      <div class="col-sm-8">
					      	<?php echo $this->Form->input('surcharge',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>
						-->
					    <hr>
					    <div class="form-group">
					      <div class="col-sm-offset-2 col-sm-10">
						    <?php echo $this->Form->submit('更新', array('div'=>false, 'class' => 'btn btn-info', 'name' => 'submit')); ?>
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
