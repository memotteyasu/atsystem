<!-- 工程管理 -->
<!-- Co050s/edit -->
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
							$this->Html->addCrumb('受注内容工程', ['controller' => 'Oi010s', 'action' => 'add2', $ordertrans['Ordertran']['id']]);
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
							<h2><i class="glyphicon glyphicon-info-sign"></i>受注内容工程編集</h2>
						</div>

						<?php echo $this->Form->create('Processtran', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

							<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

							<br>
							<div class="form-group">
								<label class="col-sm-2 control-label">オーダー</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<p class="form-control-static">
												<?php echo $ordertrans['Ordertran']['productnote']; ?>
										</p>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">作業者（乗務員）</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('users_id', [
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

							<div class="form-group">
								<label class="col-sm-2 control-label">作業名称</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('workdetailmasters_id', [
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

							<div class="form-group">
								<label class="col-sm-2 control-label">作業備考</label>
								<div class="col-sm-4">
									<?php
										echo $this->Form->input('workddetailcomment', [
											'label'=>false,
											'type'=>'textarea',
											'rows' => 3,
											'div'=>false,
											'class'=>'form-control',
											'placeholder' => '作業備考を入力'
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">作業開始日時</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('processstartdate',[
												'label'=>false,
												'div'=>false,
												'class'=>'datetimepicker1 form-control',
												'type'=>'text',
											]);
										?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">作業終了日時</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											echo $this->Form->input('processenddate',[
												'label'=>false,
												'div'=>false,
												'class'=>'datetimepicker1 form-control',
												'type'=>'text',
											]);
										?>
									</div>
								</div>
							</div>

							<hr>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<?php
										echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' 更新'), [
											'type' => 'submit',
											'div' => false,
											'class' => 'btn btn-info',
											'name' => 'submit',
											'escape' => false,
										]);
									?>
									<?php //echo $this->Form->submit('登録', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']); ?>
								</div>
							</div>

						<?php echo $this->Form->end(); ?>

					</div>
				</div>
			</div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->

<script type="text/javascript">
jQuery(function(){
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional[ "ja" ] );
	var dateFormat = 'yy/mm/dd';
	jQuery('.datepicker').datepicker({
		dateFormat: 'yy/mm/dd(DD)'
	});

	jQuery(document).ready(function(){
			jQuery.datetimepicker.setLocale('ja');
			jQuery('input.datetimepicker1').datetimepicker({
				format:'Y-m-d H:i',
				step:30,
			});
	});
});
</script>
