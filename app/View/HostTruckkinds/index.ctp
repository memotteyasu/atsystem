<?php
	echo $this->element('AjaxsSelects', [
		'selectid1' => '#TruckkindDepartmentmastersId',
		'selectid2' => '#TruckkindShippermastersId',
		'selectid3' => '#TruckkindProductdetailmastersId',
		'action' => 'ajax_shipper',
		'action2' => 'ajax_productdetail',
		'model' => 'Truckkind',
	]);
?>

<!-- 車両マスタ -->
<!-- HostTruckmasters -->
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
							$this->Html->addCrumb('車両マスタ', ['controller' => 'HostTruckmasters', 'action' => 'index']);
							$this->Html->addCrumb('車両詳細', ['controller' => 'HostTruckmasters', 'action' => 'edit', $this->request->pass[0]]);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /車両一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>車両マスタ　可能荷主一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									    	<th><?php echo $this->Paginator->sort('carmasters_id', '車両'); ?></th>
									        <th><?php echo $this->Paginator->sort('productdetailmasters_id', '商品'); ?></th>
									        <th><?php echo $this->Paginator->sort('shippermasters_id', '荷主'); ?></th>
									        <th></th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($truckkinds as $truckkind): ?>
									    <tr>
									    	<td><?php echo ($truckkind['Carmaster']['plateno']); ?></td>
									        <td><?php echo ($truckkind['Productdetailmaster']['pdname']); ?></td>
									        <td><?php echo ($truckkind['Shippermaster']['shippershortname']); ?></td>
									        <td>
												<?php echo $this->Html->link(('編集'), ['action' => 'edit', $truckkind['Truckkind']['id'], $truckkind['Truckkind']['carmasters_id']], ['class' => ['btn btn-info']]); ?>
												<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $truckkind['Truckkind']['id'], $truckkind['Truckkind']['carmasters_id']], ['confirm' => __('削除しますか？'), 'class'=>['btn btn-danger']]); ?>
											</td>
									    </tr>
									    <?php endforeach; ?>
								    </tbody>
								</table>
							</div>
					    </div>
				    </div>
			    </div>

			    <div class="row">
			    <div class="box col-lg-8 col-md-8 col-sm-8 col-xs-8">
			      <div class="box-inner">
				    <div class="box-header well">
				        <h2>登録</h2>
				    </div>

				    <?php echo $this->Form->create('Truckkind',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

				    	<br>
				    	<div class="form-group">
							<label class="col-sm-2 control-label">部署</label>
							<div class="form-inline">
								<div class="col-sm-4">
									<?php
										echo $this->Form->input('departmentmasters_id', [
											'label' => false,
											'type' => 'select',
											'div' => false,
											'class' => 'form-control',
											'empty' => '選択してください',
											'default' => $did,
											//'id' => 'makerPulldown'
										]);
									?>
								</div>
							</div>
						</div>

				    	<div class="form-group">
							<label class="col-sm-2 control-label">荷主</label>
							<div class="form-inline">
								<div class="col-sm-4">
									<?php
										echo $this->Form->input('shippermasters_id', [
											'label' => false,
											'type' => 'select',
											'div' => false,
											'class' => 'form-control',
											'empty' => '選択してください',
											//'id' => 'makerPulldown'
										]);
									?>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">商品</label>
							<div class="form-inline">
								<div class="col-sm-4">
									<?php
										echo $this->Form->input('productdetailmasters_id', [
											'label' => false,
											'type' => 'select',
											'div' => false,
											'class' => 'form-control',
//											'data-rel'=>'chosen',
											'empty' => '選択してください',
											//'id' => 'makerPulldown'
										]);
									?>
								</div>
							</div>
						</div>

					    <hr>
					    <div class="form-group">
					      <div class="col-sm-offset-2 col-sm-10">
						    <?php echo $this->Form->submit('追加登録', array('div'=>false, 'class' => 'btn btn-info', 'name' => 'submit')); ?>
					      </div>
					    </div>

				    <?php echo $this->Form->end(); ?>
			    </div>
			  </div>
		    </div>




			    <?php //echo $this->element('pager')?>

				<p>
				<?php //echo $this->Html->link(('新規登録'), array('action' => 'add'), array('class'=>array('btn btn-success'))); ?>
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
