<!-- 請求データ出力 -->
<!-- Co081s -->
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
					//'onchange'=>'this.form.submit()',
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
	
					echo '　までの請求日　　荷主　';

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
				echo '　';
				echo $this->Form->submit('表示', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']);
				echo $this->Form->end();

			?>


			<!-- 受注内容工程 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>請求データ作成</h2>
							</div>

							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th>実績</th>
											<th>請求日</th>
											<th>荷主</th>
											<th>開始</th>
											<th></th>
											<th>終了</th>
											<th>金額</th>
											<th>車両</th>
											<th>担当</th>
										</tr>
									</thead>
									<tbody>

										<?php
											$totalPrice = 0;
											foreach ($ordertrans as $ordertran) {
													$displayPrice = $ordertran['Ordertran']['delflg'] == 3
														? $ordertran['Ordertran']['cancelprice']
														: $ordertran['Ordertran']['price'];
													$totalPrice += (int)$displayPrice;
													echo '<tr>';
													echo '<td class="text-danger text-center">';
														echo '確定済';
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Ordertran']['detailbilldate']);
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
													if($ordertran['Ordertran']['delflg']==3){
														//キャンセルデータ
														echo '<td class="bg-warning text-right">';
														echo number_format($displayPrice);
													}else{
														echo '<td class="text-right">';
														echo number_format($displayPrice);
													}
														 '</td>';
													echo '<td>';
														echo ($ordertran['Carmaster']['plateno']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Tantomaster']['tantoname']);
													echo '</td>';
												echo '</tr>';
											}
										?>
										<tr>
											<td colspan="6" class="text-right"><strong>合計</strong></td>
											<td class="text-right"><strong><?php echo number_format($totalPrice); ?></strong></td>
											<td colspan="2"></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<p>
				<?php 
				echo $this->Form->create(null,array('url'=>$this->Html->url(array('controller'=>'Co081s','action'=>'csv')) ));
				echo $this->Form->input('date', ['type' => 'hidden','value'=>$paramsDate]);
				echo $this->Form->input('date2', ['type' => 'hidden','value'=>$paramsDate2]);
				echo $this->Form->input('sid', ['type' => 'hidden','value'=>$shippermasters_id]);
				echo $this->Form->input('bgid', ['type' => 'hidden','value'=>$branchgroups_id]);
				echo $this->Form->input('cid', ['type' => 'hidden','value'=>$cid]);
				echo $this->Form->button((' データ抽出'), [
					'type' => 'submit',
					'div' => false,
					'class' => 'btn btn-info',
					'name' => 'submit',
					'escape' => false,
				]);
				echo "　";
				echo $this->Html->link(('車両別請求額'), ['controller'=>'Co081s','action' => 'ordercar', $paramsDate,$paramsDate2,$shippermasters_id,$branchgroups_id,$cid], ['class' => ['btn btn-info']]);
				echo ' 　　　<small class="bg-warning">※黄色はキャンセルオーダー</small>';
				echo $this->Form->end();
				?>
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
  var carlistUrl = '<?php echo $this->Html->url(['controller' => 'Co081s', 'action' => 'ajax_carlist']); ?>';

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
