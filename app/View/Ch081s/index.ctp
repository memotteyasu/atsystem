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
					echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);
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
											<th>納入日</th>
											<th>時間</th>
											<th>荷主</th>
											<th>開始</th>
											<th></th>
											<th>終了</th>
											<th>金額</th>
											<th>車両</th>
										</tr>
									</thead>
									<tbody>

										<?php
											foreach ($ordertrans as $ordertran) {
													echo '<tr>';
													echo '<td class="text-danger text-center">';
														echo '確定済';
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Ordertran']['deliverydate']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Ordertran']['deliverytime']);
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
														echo ($ordertran['Ordertran']['price']);
													echo '</td>';
													echo '<td>';
														echo ($ordertran['Carmaster']['plateno']);
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

				<p>
				<?php 
				echo $this->Form->create(null,array('url'=>$this->Html->url(array('controller'=>'Ch081s','action'=>'csv')) ));
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
				echo $this->Html->link(('車両別請求額'), ['controller'=>'Ch081s','action' => 'ordercar', $paramsDate,$paramsDate2,$shippermasters_id,$branchmasters_id,$cid], ['class' => ['btn btn-info']]);
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
  $('td[data-href]', 'table.table-clickable').on('click', function(){
    location.href = $(this).data('href');
  });
});
$(".chosen").chosen({
    search_contains:true
});
</script>
