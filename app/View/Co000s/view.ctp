<style>
	#dragDropArea {
		background-color: #f4f4f4;
		margin: 10px;
		padding: 10px;
		border: 1px dotted rgba(0, 0, 0, .4);
		min-height: 200px;
		text-align: center;
	}
	#dragDropArea i {
		font-size: 120px;
		opacity: .1;
		text-align: center;
	}
	#dragDropArea p {
		color: #999;
		font-weight: bold;
		font-size: 14px;
		font-size: 1.4em;
	}
	#dragDropArea .drag-drop-buttons {
		margin-top: 20px;
		font-size: 12px;
		font-size: 1.2em;
	}
	.drag-drop-buttons input {
		margin: auto;
	}
	#previewArea #previewImage {
			max-width: 450px;
	}
</style>

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
							$id = $this->request->pass[0];

							$this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
							$this->Html->addCrumb('受注カレンダー', ['controller' => 'Co000s', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<div class="row">
				<div class="box col-md-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2><i class="glyphicon glyphicon-info-sign"></i> OrderView</h2>
						</div>

						<div class="box-content">
							<div class="page-header">
								<h1>
									Ordertran
									<small>Id:<?= $ordertran['Ordertran']['id']; ?></small>
								</h1>
							</div>

								<dl>
									<dt>荷主</dt>
									<dd><?= $ordertran['Shippermaster']['shippername']; ?></dd>
									<dt>商品</dt>
									<dd><?= $ordertran['Productdetailmaster']['pdname']; ?></dd>
									<dt>商品備考</dt>
									<dd><?= $ordertran['Ordertran']['productnote']; ?></dd>
									<dt>車両</dt>
									<dd><?= $ordertran['Carmaster']['plateno'].'/'.$ordertran['Productdetailmaster']['pdname']; ?></dd>
									<dt>回戦</dt>
									<dd><?= $ordertran['Ordertran']['kaisen']; ?></dd>
									<dt>担当者名</dt>
									<dd><?= $ordertran['Tantomaster']['tantoname']; ?></dd>
								</dl>
						</div>

					</div>
				</div>
			</div>

			<div class="row">
				<div class="box col-md-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2><i class="glyphicon glyphicon-info-sign"></i> ImageUpload</h2>
						</div>

						<br>
						<?= $this->Form->create('Deliverymasterimage', [
							'label' => false,
							'div' => false,
							'class' => 'form-horizontal',
							'enctype' => 'multipart/form-data',
							'novalidate' => true,
							'onsubmit' => 'return confirm("画像をアップロードします。よろしいですか？");'
						]); ?>

							<div class="box-content">
								<div id="dragDropArea">
									<div class="drag-drop-inside">
										<i class="glyphicon glyphicon-upload icon-white"></i>
										<p class="drgg-drop-info">ここにファイルをドロップ</p>
										<p>または</p>
										<p class="drag-drop-buttons">
											<?= $this->Form->input('img', [
												'label' => false,
												'div' => false,
												'class' => false,
												'type' => 'file',
												'onChange' => 'photoPreview(event)'
											]); ?>
										</p>
										<div id="previewArea"></div>
									</div>
								</div>
							</div>

							<div class="box-content">
								<div class="control-group">
									<label class="control-label">画像備考</label>
									<div class="controls">
										<?= $this->Form->input('imgcomment', [
											'label' => false,
											'div' => false,
											'class' => 'form-control',
											'placeholder' => '備考'
										]); ?>
									</div>
								</div>
							</div>

							<hr>
							<div class="box-content">
								<?= $this->Form->submit('登録', [
									'div' => false,
									'class' => 'btn btn-info',
									'name' => 'submit'
								]); ?>
							</div>

						<?= $this->Form->end(); ?>
					</div>
				</div>
			</div>

			<?= $this->Html->link('<span class="glyphicon glyphicon-backward icon-white" aria-hidden="true"></span>'.(' 戻る'),[
				'action' => 'index',
				'controller' => 'Co000s'
			], [
				'escape' => false,
				'class' => 'btn btn-success'
			]); ?>

		</div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<?= $this->Html->script('drag-drop-file-input'); ?>
