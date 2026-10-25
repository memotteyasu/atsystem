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

<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
			<?php echo $this->element('Sidemenu'); ?>
        <!-- left menu ends -->

        <noscript>
            <div class="alert alert-block col-md-12">
                <h4 class="alert-heading">Warning!</h4>

                <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
                    enabled to use this site.</p>
            </div>
        </noscript>

		<div id="content" class="col-lg-10 col-md-10 col-sm-10">
			<!-- content starts -->
			<div>
				<ul class="breadcrumb">
					<li>
						<?php
							$this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
							$this->Html->addCrumb('車両マスタ', ['controller' => 'HostTruckmasters', 'action' => 'index']);
							$this->Html->addCrumb('可能荷主', ['controller' => 'HostTruckkinds', 'action' => 'index', $this->params['pass']['1']]);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /車両修正 -->
			<div class="row">
			    <div class="box col-lg-8 col-md-8 col-sm-8 col-xs-8">
			      <div class="box-inner">
				    <div class="box-header well">
				        <h2>修正</h2>
				    </div>

				    <?php echo $this->Form->create('Truckkind',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

				    	<br>
				    	<div class="form-group">
							<label class="col-sm-2 control-label">車番</label>
							<div class="form-inline">
								<div class="col-sm-4">
									<p class="form-control-static">
										<?php echo $carmasters['Carmaster']['plateno']; ?>
									</p>
								</div>
							</div>
						</div>

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
											'default' => $did
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
											'data-rel' => 'chosen',
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
											'empty' => '選択してください',
											'data-rel' => 'chosen',
										]);
									?>
								</div>
							</div>
						</div>

					    <hr>
					    <div class="form-group">
					      <div class="col-sm-offset-2 col-sm-10">
						    <?php echo $this->Form->submit('修正', array('div'=>false, 'class' => 'btn btn-info', 'name' => 'submit')); ?>
					      </div>
					    </div>

				    <?php echo $this->Form->end(); ?>
			    </div>
			  </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
