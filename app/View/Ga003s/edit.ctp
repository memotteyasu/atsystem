<!-- Ordertran edit -->

<style>
.ui-timepicker-container {z-index: 9999 !important;}
.ui-timepicker-standard {z-index: 9999 !important;}
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
							$flg = $this->request->pass[1];

							$this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
							if ($flg == 0) {
								$this->Html->addCrumb('受注カレンダー', ['controller' => 'Ga003s', 'action' => 'index']);
							} elseif ($flg == 5) {
								$this->Html->addCrumb('受注一覧', ['controller' => 'Ga050s', 'action' => 'index']);
								$this->Html->addCrumb('工程一覧', ['controller' => 'Ga050s', 'action' => 'add', $id]);
							}
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<?php echo $this->Form->create('Ordertran', ['label' => false, 'div' => false, 'class' => 'form-horizontal', 'novalidate' => true]); ?>

				<div class="row">

					<div class="box col-md-12">
						<div class="box-inner">
				            <div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i> 受注編集</h2>
							</div>

							<?php echo $this->Form->input('id', ['type' => 'hidden']); ?>

							<!-- 荷主選択 -->
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">荷主</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('shippermasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												// 'empty' => '選択してください',
												'readonly',
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /荷主選択 -->

							<!-- 車両選択 -->
							<div class="box-content">
								<div class="control-group">
								<label class="control-label">車両</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('carmasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												//'style' => 'width:150px',
												'options' => $carmasters,
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /車両選択 -->

							<!-- 回戦選択 -->
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">回戦</label>
									<div class="form-inline">
										<?php
											$list = array_combine(range(1, 6), range(1, 6));
											echo $this->Form->input('kaisen', [
												'type' => 'select',
												'options' => $list,
												'label' => false,
												'div' => false,
												'class' => 'form-control',
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /回戦選択 -->
							<!-- 課税非課税選択 -->
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">課税対象</label>
									<div class="form-inline">
										<?php
											echo $this->Form->input('taxflg', [
												'type' => 'select',
												'options' => $taxflgs,
												'label' => false,
												'div' => false,
												'class' => 'form-control',
											]);
										?>
									</div>
								</div>
							</div>
							<!-- /課税非課税選択 -->

							<!-- 担当者 -->
							<div class="box-content">
								<div class="control-group">
									<label class="control-label">担当者名</label>
									<div class="controls">
										<?php
											echo $this->Form->input('tantomasters_id', [
												'type'=>'select',
												'options' => $tantomasters,
												'label' => false,
												'div' => false,
												'class' => 'input-control',
												'default'=>0
											]);
										?>
									</div>
								</div>
							</div>
							<!-- 担当者 -->
							<div class="box-content">
								(更新者) <?php echo $modifiedusername; ?>
							</div>
						</div>

					</div>
				</div>

				<div class="row">

					<div class="box col-md-12">
						<div class="box-inner">

							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i> 工程登録</h2>
							</div>

							<div class="box-content">

								<?php
									$i = 0;
									foreach ($this->request->data['Processtran'] as $processtran):
								?>
								<br>

								<?php echo $this->Form->input('Processtran.'.$i.'.id', ['type' => 'hidden', 'value' => $processtran['id']]); ?>

								<div class="form-inline">
								<!--
									<div class="input-group">
										<span class="input-group-addon">作業者</span>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.users_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => [ 0 => '未定'],
												'default' => $processtran['users_id']
											]);
										?>
									</div>
								-->
										<?php
											echo $this->Form->input('Processtran.'.$i.'.users_id', [
												'type' => 'hidden',
												'value' => 0,
											]);
										?>
									<div class="input-group">
										<span class="input-group-addon">作業日時</span>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.processstartdate',[
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'datepicker1 form-control',
												'default' => $processtran['processstartdate'],
												'style' => 'width:150px',
											]);
										?>
										<?php
											$w_time = substr($processtran['processstarttime'],0,5);
											echo $this->Form->input('Processtran.'.$i.'.processstarttime',[
												'label' => false,
												'type' => 'text',
												'timeFormat'=>'24',
												'div' => false,
												'class' => 'timepicker2 form-control',
												'value' => $w_time,
												'style' => 'width:100px',
											]);
										?>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.timenote',[
												'label'=>false,
												'type'=>'text',
												'div'=>false,
												'class'=>'form-control',
												'style'=>'width:100px',
												'placeholder'=>'AM PM'
											]);
										?>
									</div>

								</div>

								<div class="form-inline">

									<div class="input-group input-group-ex">
										<span class="input-group-addon">作業名称</span>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.workdetailmasters_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control',
												'empty' => '選択してください',
												//'style' => 'width:150px',
											]);
										?>
									</div>

									<div class="input-group input-group-ex">
										<span class="input-group-addon">作業場所</span>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.workplaces_id', [
												'label' => false,
												'type' => 'select',
												'div' => false,
												'class' => 'form-control chosen',
												'empty' => '選択してください',
												//'style'=>'width:100px;',
											]);
										?>
									</div>
									<div class="input-group  input-group-ex">
											<span class="input-group-addon">商品詳細</span>
											<?php
												echo $this->Form->input('Processtran.'.$i.'.productcontainers_id', [
													'label' => false,
													'type' => 'select',
													'div' => false,
													'class' => 'form-control chosen',
													'empty' => '選択してください',
													//'style'=>'width:100px;',
												]);
											?>
									</div>

								</div>

								<div class="form-inline">

									<div class="input-group">
										<span class="input-group-addon">コンテナNo.</span>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.container_no', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'placeholder' => 'コンテナNo.を入力'
												//'style'=>'width:100px;',
											]);
										?>
									</div>

									<div class="input-group">
										<span class="input-group-addon">REF/BOOKING No.</span>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.ref_no', [
												'label' => false,
												'type' => 'text',
												'div' => false,
												'class' => 'form-control',
												'placeholder' => 'REF/BOOKING No.を入力'
												//'style'=>'width:100px;',
											]);
										?>
									</div>
									<div class="input-group">
											<?php if ($i != 0): ?>
												<input type="button" id="copybutton<?= $i ?>" value="同上" class="btn btn-default" onclick="copybutton(<?= $i-1 ?>,<?= $i ?>)">
											<?php endif; ?>
										</div>

								</div>

								<div class="form-inline">
									<div class="input-group">
										<span class="input-group-addon">作業備考</span>
										<?php
											echo $this->Form->input('Processtran.'.$i.'.workddetailcomment', [
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

							<?php
								$i = $i + 1;
								endforeach;
							?>

							</div>

						</div>
					</div>
				</div>


				<div class="box-content">
					<?php
						echo $this->Form->button('<i class="glyphicon glyphicon-edit icon-white"></i>'.(' 更新'), [
							'type' => 'submit',
							'div' => false,
							'class' => 'btn btn-info',
							'name' => 'submit',
							'escape' => false,
						]);
					?>

			<?php echo $this->Form->end(); ?>

					<?php
						echo $this->Html->link('<span class="glyphicon glyphicon-list-alt icon-white" aria-hidden="true"></span>'.(' 工程内容'),[
							'action' => 'add',
							'controller' => 'Ga050s',
							$this->Form->value('Ordertran.id')
						], [
							'escape' => false,
							'class' => 'btn btn-success'
						]);
					?>

					<?= $this->Html->link('詳細', [
						'action' => 'view',
						'controller' => 'Ga003s',
						$this->Form->value('Ordertran.id')
					], [
						'escape' => false,
						'class' => 'btn btn-warning'
					]); ?>

					<?php
						 echo $this->Form->postLink('<span class="glyphicon glyphicon-trash icon-white" aria-hidden="true"></span>'.(' 削除'), [
							'action' => 'delete',
							$this->Form->value('Ordertran.id')
						], [
							'escape' => false, 'class' => 'btn btn-danger', 'role' => 'button',
							'confirm' => __('削除しますか？')
						]);
					?>


				</div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<script type="text/javascript">
$(".chosen").chosen({
    search_contains:true
});

$(function(){
	$('#makerPulldown').change(function(){

		// 選択されたオプションのkey番号を取得
		var num = $('#makerPulldown').val();

		// 選択値以外に class="hide" を追加
		$(".targetTd p[id != 'note+num']").addClass('hide');

		// 取得したkey番号の class="hide" を削除
		$("p#note"+num).removeClass('hide');
	});
});
// 工程登録フォームのコンテナNo.及びREFNo.の一つ上からのコピー
function copybutton(a,b) {
	const ContainerNo = $("#Processtran"+a+"ContainerNo").val();
	$("#Processtran"+b+"ContainerNo").val(ContainerNo);
	const RefNo = $("#Processtran"+a+"RefNo").val();
	$("#Processtran"+b+"RefNo").val(RefNo);
	const ProductName1 = $("#Processtran"+a+"Productdetailmastername").val();
	$("#Processtran"+b+"Productdetailmastername").val(ProductName1);
}
</script>
