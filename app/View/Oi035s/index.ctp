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

			<div class="row">
				<!-- left start -->
					<div class="box col-md-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>デジタコ取り込み（石油） </h2>
							</div>
				<?php
					echo $this->Form->create('Csv',[
						'url'=>[
							'controller'=>'Oi030s',
							'action'=>'import'
						],
						'label'=>false,
						'div'=>false,
						'class'=>'form-horizontal',
						'novalidate'=>true,
						'type'=>'file'
					]);
				?>
					<fieldset>
						<?php
							echo $this->Form->input('csvFile',[
								'label'=>false,
								'div'=>false,
								'type'=>'file',
								'class'=>false,
							]);
						?>
					</fieldset>
</div>
					<?php echo $this->Form->submit('CSVインポート', ['div'=>false, 'class' => 'btn btn-success', 'name' => 'submit']); ?>
				<?php echo $this->Form->end(); ?>
</div>
</div>
		<p>
			<?php //echo $this->Html->link(('CSVエクスポート'), ['action' => 'export'], ['class'=>['btn btn-success btn-sm']]); ?>
			<?php //echo $this->Html->link(('CSVインポート'), ['action' => 'import'], ['class'=>['btn btn-success btn-sm']]); ?>
		</p>

<?php //echo debug($csvs['0']); ?>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
