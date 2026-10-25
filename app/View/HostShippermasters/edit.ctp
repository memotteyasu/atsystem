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
							$this->Html->addCrumb('荷主マスタ一覧', ['controller' => 'HostShippermasters', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /荷主修正 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
				    <div class="box-header well">
				        <h2>荷主マスタ修正</h2>
				    </div>
				    <?php echo $this->Form->create('Shippermaster',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

				    	<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

					    <br>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">荷主CD</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('shippercode',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">荷主名称</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('shippername',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">荷主通称名</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('shippershortname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">部署名称</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('departmentmasters_id',array('label'=>false, 'type'=>'select', 'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">郵便番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('shipperpost',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">住所</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('shipperadd',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">電話番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('shippertel',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">FAX番号</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('shipperfax',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">締日</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php echo $this->Form->input('shipperlimitday',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'1', 'min'=>'0')); ?><label>　日　月末締めは「99」を入力</label>
					      	</div>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">請求基準日</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php
					      			$list = array('1' => '積日', '2' => '卸日');
					      			echo $this->Form->input('shipperbaseday',array('type' => 'select', 'options' => $list, 'label' => false, 'div' => false, 'class' => 'form-control', 'empty'=>'選択してください')); ?>
					      	</div>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">商品内外税区分</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php
					      			$list = array('1' => '内税', '2' => '外税');
					      			echo $this->Form->input('producttax',array('type' => 'select', 'options' => $list, 'label' => false, 'div' => false, 'class' => 'form-control', 'empty'=>'選択してください')); ?>
					      	</div>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">固定費内外税区分</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php
					      			$list = array('1' => '内税', '2' => '外税');
					      			echo $this->Form->input('fixedcosttax',array('type' => 'select', 'options' => $list, 'label' => false, 'div' => false, 'class' => 'form-control', 'empty'=>'選択してください')); ?>
					      	</div>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">通行料内外税区分</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php
					      			$list = array('1' => '内税', '2' => '外税');
					      			echo $this->Form->input('tollfeetax',array('type' => 'select', 'options' => $list, 'label' => false, 'div' => false, 'class' => 'form-control', 'empty'=>'選択してください')); ?>
					      	</div>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">スケール代内外税区分</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php
					      			$list = array('1' => '内税', '2' => '外税');
					      			echo $this->Form->input('scaletax',array('type' => 'select', 'options' => $list, 'label' => false, 'div' => false, 'class' => 'form-control', 'empty'=>'選択してください')); ?>
					      	</div>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">売上丸め区分</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php
					      			$list = array('1' => '切り捨て', '2' => '切り上げ', '3' => '四捨五入');
					      			echo $this->Form->input('salesprocess',array('type' => 'select', 'options' => $list, 'label' => false, 'div' => false, 'class' => 'form-control', 'empty'=>'選択してください')); ?>
					      	</div>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">税丸め区分</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php
					      			$list = array('1' => '切り捨て', '2' => '切り上げ', '3' => '四捨五入');
					      			echo $this->Form->input('taxprocess',array('type' => 'select', 'options' => $list, 'label' => false, 'div' => false, 'class' => 'form-control', 'empty'=>'選択してください')); ?>
					      	</div>
					      </div>
					    </div>

						<div class="form-group">
					      <label class="col-sm-2 control-label">最低保証額</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php echo $this->Form->input('lowwarrantyprice',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?><label>　円</label>
					      	</div>
					      </div>
					    </div>

						<div class="form-group">
					      <label class="col-sm-2 control-label">手数料</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php echo $this->Form->input('commision',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?><label>　円</label>
					      	</div>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">祝祭日出勤単価</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php echo $this->Form->input('holidayprice',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'100', 'min'=>'0')); ?><label>　円</label>
					      	</div>
					      </div>
					    </div>

						<?php echo $this->Form->input('billtype', ['type' => 'hidden', 'value' => 3]); ?>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">明細</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php
					      			$list2 = array('0' => '混在', '1' => '個別');
					      			echo $this->Form->input('detailmix',array('type' => 'select', 'options' => $list2, 'label' => false, 'div' => false, 'class' => 'form-control')); ?>
					      	</div>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">諸費税</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php
					      			$list3 = array('0' => '課税', '1' => '免税');
					      			echo $this->Form->input('expensestaxflg',array('type' => 'select', 'options' => $list3, 'label' => false, 'div' => false, 'class' => 'form-control')); ?>
					      	</div>
					      </div>
					    </div>

						<div class="form-group">
					      <label class="col-sm-2 control-label">燃料サーチャージ(%)</label>
					      <div class="col-sm-2">
					      	<?php echo $this->Form->input('surcharge',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

						<div class="form-group">
					      <label class="col-sm-2 control-label">表示順</label>
					      <div class="form-inline">
					      	<div class="col-sm-4">
					      		<?php echo $this->Form->input('displayno',array('label'=>false,'div'=>false, 'class'=>'form-control', 'step'=>'1', 'min'=>'0')); ?>
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
