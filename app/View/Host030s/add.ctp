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
							$this->Html->addCrumb('事故一覧', ['action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>
			<!-- 事故登録 -->
			    <div class="row">
					<?php echo $this->Form->create('Accidenttran', ['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>事故登録</h2>
						    </div>
						    <div class="row">
							    <div class="box-content">
									<!-- 運転手選択 -->
									<label class="col-sm-2 control-label">従業員名</label>
									<div class="col-sm-3">
										<?php
											echo $this->Form->input('users_id',[
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'options' => $drivers,
												'empty' => '選択して下さい',
											]);
										?>
									</div>
									<!-- /運転手選択 -->
									<!-- 車番 -->
									<label class="col-sm-2 control-label">車番</label>
									<div class="col-sm-5">
									<?php
									echo $this->Form->input('carno',[
										'label'=>false,
										'div'=>false,
										'placeholder'=>'名古屋800か1234',
										'class'=>'form-control'
										]); ?>
									</div>
									<!-- /車番 -->
								</div>
							</div>
							<div class="row">
									<!-- 事故日選択 -->
								<div class="box-content">
									<label class="col-sm-2 control-label">事故日時</label>
									<div class="col-sm-3">
										<?php
											echo $this->Form->input('accidentdate',[
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'datetimepicker1 form-control',
											]);
										?>
									</div>
									<!-- /事故日選択 -->
									<!-- 報告時期 -->
									<label class="col-sm-2 control-label">報告時期</label>
									<div class="col-sm-2">
									<?php
									echo $this->Form->input('reportflg',[
										'label'=>false,
										'type'=>'select',
										'div'=>false,
										'options'=>$reportflgs,
										'class'=>'form-control'
									]);
									?>
									</div>
									<!-- /報告時期 -->
								</div>
							</div>
							<!-- 場所 -->
							<div class="row">
								<div class="box-content">
									<div class="control-group">
										<label class="col-sm-2 control-label">場所・内容</label>
										<div class="col-sm-10">
											<?php
												echo $this->Form->input('accidentplace', [
													'type' => 'text',
													'label'=>false,
													'div' => false,
													'class' => 'form-control',
												]);
											?>
										</div>
									</div>
								</div>
							</div>
							<!-- /場所 -->
							<!-- 処置 -->
							<div class="row">
								<div class="box-content">
									<div class="control-group">
										<label class="col-sm-2 control-label">立会者／処置</label>
										<div class="col-sm-10">
											<?php
												echo $this->Form->input('responsenote', [
													'type' => 'text',
													'label'=>false,
													'div' => false,
													'class' => 'form-control',
												]);
											?>
										</div>
									</div>
								</div>
							</div>
							<!-- /処置 -->
							<!-- 状況 -->
							<div class="row">
								<div class="box-content">
									<div class="control-group">
										<label class="col-sm-2 control-label">状況</label>
										<div class="col-sm-10">
											<?php
												echo $this->Form->input('reportnote', [
													'type' => 'textarea',
													'label'=>false,
													'div' => false,
													'class' => 'form-control',
												]);
											?>
										</div>
									</div>
								</div>
							</div>
							<!-- /状況 -->
							<!-- 処分内容 -->
							<div class="row">
								<div class="box-content">
									<div class="control-group">
										<label class="col-sm-2 control-label">処分内容</label>
										<div class="col-sm-10">
											<?php
												echo $this->Form->input('disposalnote', [
													'type' => 'text',
													'label'=>false,
													'div' => false,
													'class' => 'form-control',
												]);
											?>
										</div>
									</div>
								</div>
							</div>
							<!-- /処分内容 -->
							<!-- 対策 -->
							<div class="row">
								<div class="box-content">
									<div class="control-group">
										<label class="col-sm-2 control-label">対策</label>
										<div class="col-sm-10">
											<?php
												echo $this->Form->input('counterplannote', [
													'type' => 'textarea',
													'label'=>false,
													'div' => false,
													'class' => 'form-control',
												]);
											?>
										</div>
									</div>
								</div>
							</div>
							<!-- /対策 -->
							<!-- 反省文提出日 -->
							<div class="row">
								<div class="box-content">
									<div class="control-group">
										<label class="col-sm-2 control-label">反省文提出日</label>
										<div class="col-sm-2">
										<?php
											echo $this->Form->input('treatmentdate',[
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'datepicker1 form-control',
											]);
										?>
										</div>
									</div>
								</div>
							</div>
							<!-- /反省文提出日 -->
							<!-- 扱い -->
							<div class="row">
								<div class="box-content">
									<div class="control-group">
										<label class="col-sm-2 control-label">事故扱い</label>
										<div class="col-sm-2">
											<?php
												echo $this->Form->input('treatmentflg', [
													'type' => 'select',
													'label'=>false,
													'div' => false,
													'options'=>$treatmentflgs,
													'class' => 'form-control',
												]);
											?>
										</div>
									</div>
									<div class="control-group">
										<label class="col-sm-1 control-label">給与メモ</label>
										<div class="col-sm-7">
											<?php
												echo $this->Form->input('salarynote', [
													'type' => 'text',
													'label'=>false,
													'div' => false,
													'class' => 'form-control',
												]);
											?>
										</div>
									</div>
								</div>
							</div>
							<!-- /扱い -->

							<div class="row">
								<div class="col-sm-12">
									<div class="box-content">
										<?php
											echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' 登録'), [
												'type' => 'submit',
												'div' => false,
												'class' => 'btn btn-info',
												'name' => 'submit',
												'escape' => false,
											]);
										?>
									</div>
								</div>
							</div>
					    </div><!-- /box-inner -->
				    </div><!-- /box -->
					<?php echo $this->Form->end(); ?>
			    </div><!-- /row -->

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
<?php echo $this->Html->script(['jQuery.datepicker.js']); ?>

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