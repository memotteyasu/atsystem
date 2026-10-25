<!-- 工程未登録 -->
<!-- Co080s -->
<div class="ch-container">
	<div class="row">

		<!-- left menu starts -->
			<?php echo $this->element('Sidemenu'); ?>
		<!-- left menu ends -->

		<div id="content" class="col-lg-10 col-sm-10">
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
				echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline', 'id' => 'indexForm']);
				echo $this->Form->input('selectym1', [
					'div'=>false,
					'label'=>false,
					'type' => 'text',
					'class' => 'datepicker1',
					'onchange'=>'this.form.submit()',
					'default' => $paramsDate,
				]);

					echo '　から　';

					echo $this->Form->input('selectym2', [
						'div'=>false,
						'label'=>false,
						'type' => 'text',
						'class' => 'datepicker2',
						//'onchange'=>'this.form.submit()',
						'default' => $paramsDate2,
						]);
	
					echo '　　荷主　';

					echo $this->Form->input('shippermasters_id',[
						'label'=>false,
						'type'=>'select',
						'empty'=>'未選択',
						'div'=>false,
						'class'=>'form-control',
						//'onchange'=>'this.form.submit()',
						'data-rel'=>'chosen',
						'default' => $shippermasters_id,
					]);

					echo '　　営業所　';

					echo $this->Form->input('branchgroups_id',[
						'label'=>false,
						'type'=>'select',
						'div'=>false,
						'class'=>'form-control',
						//'onchange'=>'this.form.submit()',
						'default' => $branchgroups_id,
					]);

					echo '　　車両　　';

					echo $this->Form->input('cid', [
						'label' => false,
						'type' => 'select',
						'options' => $carlist,
						'div' => false,
						'class' => 'form-control chosen',
						'empty'=>'未選択',
						//'onchange' => 'this.form.submit()',
						'data-rel' => 'chosen',
						'default' => $cid,
					]);
/*
					echo '　　実績　';

					echo $this->Form->input('correctflg',[
						'label'=>false,
						'type'=>'select',
						'empty'=>'全て表示',
						'div'=>false,
						'class'=>'form-control',
						'onchange'=>'this.form.submit()',
						'data-rel'=>'chosen',
						'options' => $correctflgs,
						'default' => $correctflg,
					]);
*/
				echo '　';
				echo $this->Form->submit('表示', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']);
				echo $this->Form->end();

			?>


			<!-- 受注内容工程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>出荷実績</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>実績</th>
											<th>納入日</th>
											<th>時間</th>
											<th>荷主</th>
											<th>開始</th>
											<th></th>
											<th>終了</th>
											<th>金額</th>
											<th>車両</th>
											<th>担当</th>
											<th>税</th>
										</tr>
									</thead>
									<tbody>

										<?php
											foreach ($ordertrans as $ordertran) {
												if ($ordertran['Ordertran']['billconfirmflg'] == 0) {
													echo '<tr class="light">';
														echo '<td>';
															echo $this->Html->link(('未確定'), ['action' => 'edit', 'controller' => 'Co080s', $ordertran['Ordertran']['id']], ['class' => ['btn btn-primary']]);
														echo '</td>';
												} else {
													echo '<tr>';
														echo '<td>';
															echo $this->Html->link(('確定済み'), ['action' => 'edit', 'controller' => 'Co080s', $ordertran['Ordertran']['id']], ['class' => ['btn btn-default btn-warning']]);
														echo '</td>';
												}
													echo '<td>';
														echo ($ordertran['Ordertran']['deliverydate']);
													echo '</td>';
													echo '<td>';
														if($ordertran['Ordertran']['deliverytime']){
															$w_time = explode(':',$ordertran['Ordertran']['deliverytime']);
															echo $w_time[0].':'.$w_time[1];
														}
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Shippermaster']['shippershortname']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Workplace1']['workplaceshortname']);
													echo '</td>';
													echo '<td>';
														//processtranを読む
														$processtran = ClassRegistry::init('Processtran');
														$pts = $processtran->find('all',[
															'fields'=>[
																'Workplace.workplaceshortname',
																'Processtran.container_no'
															],
															'conditions'=>[
																'ordertrans_id' => $ordertran['Ordertran']['id']
															],
															'contain'=>[
																'Workplace'
															]
														]);
														if(count($pts)>1){
															echo $pts[1]['Workplace']['workplaceshortname'];
															echo ' (';
															echo $pts[0]['Processtran']['container_no'];
															echo ')';
														}
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Workplace2']['workplaceshortname']);
													echo '</td>';
													echo '<td>';
														echo (number_format($ordertran['Ordertran']['price']));
													echo '</td>';
													if($ordertran['Carmaster']['branchmasters_id']==3){
														echo "<td style='background-color:#ffffe0;'>";
													}elseif($ordertran['Carmaster']['branchmasters_id']==4){
														echo "<td style='background-color:#e0ffff;'>";
													}elseif($ordertran['Carmaster']['branchmasters_id']==1){
														echo "<td style='background-color:#ffcccc;'>";
													}elseif($ordertran['Carmaster']['branchmasters_id']==2){
														echo "<td style='background-color:#ccffcc;'>";
													}elseif($ordertran['Carmaster']['branchmasters_id']==25){
														echo "<td style='background-color:#ccccff;'>";
													}elseif($ordertran['Carmaster']['branchmasters_id']==27){
														echo "<td style='background-color:#99ccff;'>";
													}else{
														echo "<td>";
													}
																echo (substr($ordertran['Carmaster']['plateno'],-4));
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Tantomaster']['tantoname']);
													echo '</td>';
													echo '<td>';
														if($ordertran['Ordertran']['taxflg']==1){
															echo '免税';
														}else{
															echo '';
														}
													echo '</td>';
												echo '</tr>';
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<?php //echo $this->element('pager')?>

				<p>
					<?php //echo $this->Html->link(('新規登録'), array('action' => 'add'), array('class'=>array('btn btn-success'))); ?>
				</p>

			<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
<!--
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
-->
<script>
$(function(){
  var carlistUrl = '<?php echo $this->Html->url(['controller' => 'Co080s', 'action' => 'ajax_carlist']); ?>';

  $('td[data-href]', 'table.table-clickable').on('click', function(){
    location.href = $(this).data('href');
  });

  $('#branchgroups_id').on('change', function(){
    var branchgroupId = $(this).val();
    $.get(carlistUrl, {branchgroups_id: branchgroupId}, function(options){
      $('#cid').html(options).val('').trigger('chosen:updated');
    });
  });
});
$(".chosen").chosen({
    search_contains:true
});
</script>
