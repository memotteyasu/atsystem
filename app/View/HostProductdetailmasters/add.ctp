<?php
	echo $this->element('AjaxsSelects2', [
		'selectid1' => '#ProductdetailmasterDepartmentmastersId',
		'selectid2' => '#ProductdetailmasterShippermastersId',
		'action' => 'ajax_shipper',
		'model' => 'Productdetailmaster',
	]);
?>
<?php //debug($this->params); ?>
<!-- 商品マスタ -->
<!-- HostProductdetailmasters -->
<style type="text/css">
.hide{
    display: none;
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
							$this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
							$this->Html->addCrumb('商品マスタ一覧', ['controller' => 'HostProductdetailmasters', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /商品登録 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			      <div class="box-inner">
				    <div class="box-header well">
				        <h2>商品マスタ登録</h2>
				    </div>

				    <?php echo $this->Form->create('Productdetailmaster',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

				    	<br>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">商品名称</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('pdname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

						<div class="form-group">
							<label class="col-sm-2 control-label">部署</label>
							<div class="form-inline">
								<div class="col-sm-4">
									<?php echo $this->Form->input('departmentmasters_id',array('label'=>false, 'type'=>'select', 'div'=>false, 'class'=>'form-control', 'empty'=>'選択してください', /*'id'=>'makerPulldown'*/)); ?>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">荷主</label>
							<div class="form-inline">
								<div class="col-sm-4">
									<?php echo $this->Form->input('shippermasters_id',array('label'=>false, 'type'=>'select', 'div'=>false, 'class'=>'form-control', 'empty'=>'選択してください', /*'id'=>'makerPulldown'*/)); ?>
								</div>
							</div>
						</div>

					    <hr>
					    <div class="form-group">
					      <div class="col-sm-offset-2 col-sm-10">
						    <?php echo $this->Form->submit('登録', array('div'=>false, 'class' => 'btn btn-info', 'name' => 'submit')); ?>
							<?php echo $this->Html->link(('キャンセル'), array('action' => 'index'), array('class'=>array('btn btn-danger'))); ?>
					      </div>
					    </div>

				    <?php echo $this->Form->end(); ?>
			    </div>
			  </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<script type="text/javascript">
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
</script>
