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
							$this->Html->addCrumb('作業場所一覧', ['controller' => 'HostWorkplaces', 'action' => 'index']);
							$workplacenames = $this->request->data['Workplace']['workplacename'];
							$this->Html->addCrumb('作業場所【'.$workplacenames.'】', ['controller' => 'HostWorkplaces', 'action' => 'edit', $this->params['pass'][0]]);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>
			<!-- /画像一覧 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
				    <div class="box-header well">
				        <h2>作業場所画像更新</h2>
				    </div>
				    <?php
						echo $this->Form->create('Workplaceimage', [
							'label' => false,
							'div' => false,
							'class' => 'form-horizontal',
							'enctype' => 'multipart/form-data',
							'novalidate' => true,
							'onsubmit' => 'return confirm("更新します。よろしいですか？");'
						]);
					?>

				    	<br>

					    <div class="form-group">
							<label class="col-sm-2 control-label">画像</label>
							<div class="col-sm-2">
								<?php echo $this->Form->input('img',['label'=>false,'div'=>false, 'class'=>false, 'type'=>'file']); ?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">画像コメント</label>
							<div class="col-sm-2">
								<?php echo $this->Form->input('imgcomment',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
							</div>
						</div>


					    <hr>
					    <div class="form-group">
					      <div class="col-sm-offset-2 col-sm-10">
						    <?php echo $this->Form->submit('画像登録', ['div' => false, 'class' => 'btn btn-info', 'name' => 'submit']); ?>
						    <?php echo $this->Html->link(('キャンセル'), ['action' => 'index', $this->request->data['Workplace']['id']], ['class' => 'btn btn-danger']); ?>
					      </div>
					    </div>

				    <?php echo $this->Form->end(); ?>
				    </div>
			    </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

