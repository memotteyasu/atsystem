<div class="ch-container">
	<div class="row">

		<!-- left menu starts -->
<?php echo $this->element('HostSidemenu'); ?>
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

				<?php
					echo $this->Form->create('Csv',[
						'url'=>[
							'controller'=>'Csvs',
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
					<?php echo $this->Form->submit('CSVインポート', ['div'=>false, 'class' => 'btn btn-success btn-sm', 'name' => 'submit']); ?>
				<?php echo $this->Form->end(); ?>

		<p>
			<?php //echo $this->Html->link(('CSVエクスポート'), ['action' => 'export'], ['class'=>['btn btn-success btn-sm']]); ?>
			<?php //echo $this->Html->link(('CSVインポート'), ['action' => 'import'], ['class'=>['btn btn-success btn-sm']]); ?>
		</p>

<?php //echo debug($csvs['0']); ?>

			<!-- content ends -->
		</div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

	<footer class="row">
		<p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
	</footer>

</div><!--/.fluid-container-->
