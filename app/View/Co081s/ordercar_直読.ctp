<!-- 請求データ出力（車両別） -->
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
			<h4>
			<?php
				echo "<b>".$date->format('Y-m-d')."</b>";

				echo '　から　';

				echo $date2->format('Y-m-d');
	
				echo 'までの請求日　(荷主)　';

				echo $shippershortname['Shippermaster']['shippershortname'];

				echo '　(営業所)　';

				echo $branchgroups['Branchgroup']['branchgroupname'];
			?>
			</h4>

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
											<th>車両</th>
											<th>金額</th>
										</tr>
									</thead>
									<tbody>

										<?php
											$ordertrans = ClassRegistry::init('Ordertran');
											$orderexpensestrans = ClassRegistry::init('Orderexpensestran');

											foreach ($carmasters as $key => $val) {
												//cidでordertranを検索し、priceを集計し、かつ各ordertran.idでorderexpensestranを集計して合算する

												$ots = $ordertrans->find('all',[
													'fields'=>[
														'Ordertran.id',
														'Ordertran.price'
													],
													'conditions'=>[
														'Ordertran.carmasters_id' => $key,
														'Ordertran.delflg in' => [0,3],
														'Ordertran.departmentmasters_id' => 4,
														'Ordertran.branchmasters_id in' => $selectbid,
														'Ordertran.billconfirmdate >=' => $date->format('Y-m-d'),
														'Ordertran.billconfirmdate <=' => $date2->format('Y-m-d'),
														'Ordertran.shippermasters_id' => $sid
													],
													'recursive'=>-1
												]);
												$total_price = 0;
												$total_expensesprice = 0;
												foreach($ots as $ot){

													$total_price = $total_price + $ot['Ordertran']['price'];
													$oet = $orderexpensestrans->find('all',[
														'fields' => [
															'sum(Orderexpensestran.expensesprice) as sumexpenses'
														],
														'conditions' => [
															'ordertrans_id' => $ot['Ordertran']['id'],
														],
														'group'=>[
															'ordertrans_id'
														],
														'recursive'=>-1
													]);
													debug($oet);
													foreach($oet as $oe){
														$total_expensesprice = $oe['Orderexpensestran']['sumexpenses'];
													}
												}
												if($total_price >0 ){
													echo '<tr>';
													echo '<td>';
													echo $val;
													echo '</td>';

													echo '<td>';
													echo number_format($total_price + $total_expensesprice);
													echo ' ex=';
													echo $total_expensesprice;
													echo '</td>';
													echo '</tr>';
												}else{

												}
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<?php echo $this->Html->link(('戻る'), ['controller' => 'Co081s', 'action' => 'index'], ['class'=>array('btn btn-danger')]); ?>


			<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
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
