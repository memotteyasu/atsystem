<!-- 商品詳細マスタ -->
<!-- HostProductcontainers -->
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
							$this->Html->addCrumb('商品詳細マスタ一覧', ['controller' => 'HostProductcontainers', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /商品修正 -->
			<div class="row">
				<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			      <div class="box-inner">
					<div class="box-header well">
						<h2>商品詳細マスタ<?php if ($this->action == 'edit') {
                            echo '修正';
                        } else {
                            echo '登録';
                        } ?>
						</h2>
				</div>

						<?php echo $this->Form->create('Productcontainer',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

							<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

							<br>
							<div class="form-group">
								<label class="col-sm-2 control-label">商品詳細名称</label>
								<div class="col-sm-4">
									<?php echo $this->Form->input('pdconname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">商品マスタ</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php echo $this->Form->input('productdetailmasters_id',array('label'=>false, 'type'=>'select', 'div'=>false, 'data-rel' => 'chosen', 'class'=>'form-control', 'empty'=>'選択してください')); ?>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<?php echo $this->Form->submit('登録', array('div'=>false, 'class' => 'btn btn-info', 'name' => 'submit')); ?>
									<?php if (isset($this->params['named']['page'])) {
										$return = ['controller' => $this->params['named']['page'], 'action' => 'index','pid'=>$this->params->named['pid']];
									} else {
										$return = ['action' => 'index','pid'=>$this->request->data['Productcontainer']['productdetailmasters_id']];
									}
										echo $this->Html->link('キャンセル', $return, [
											'class' => 'btn btn-danger',
										]);
									?>

								</div>
							</div>
						<?php echo $this->Form->end(); ?>
				</div>
			  </div>
			</div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

