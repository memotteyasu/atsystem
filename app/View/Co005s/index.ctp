<!-- 過去データコピー -->
<!-- Co005s -->
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
							$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<?php
				echo $this->element('searchForm3', [
					'model' => 'Pricemaster',
					'url' => ['controller'=>'Co005s','action'=>'index'],
					'text' => '部署　',
					'input' => 'departmentmasters_id',
					'text3' => '場所',
					'input3' => 'pricename',
				]);
			?>

			<!-- /商品一覧 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2>過去データ一覧</h2>
							</div>
							<div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
										<th><?php echo '荷主'; ?></th>
										<th><?php echo '営'; ?></th>
										<th><?php echo '場所'; ?></th>
										<th><?php echo '作業内容'; ?></th>
											<th><?php echo '価格'; ?></th>
											<th><?php echo '商品'; ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php $workdetailmaster = ClassRegistry::init('Workdetailmaster'); ?>
									<?php $productcontainer = ClassRegistry::init('Productcontainer'); ?>
										<?php foreach ($pricemasters as $pricemaster): ?>
											<tr>
											<td><?php echo ($pricemaster['Shippermaster']['shippershortname']); ?></td>
											<td><?php echo ($pricemaster['Branchmaster']['branchname']); ?></td>
											<td><?php echo ($pricemaster['Pricemaster']['pricename']); ?></td>
												<td><?php
												//作業内容2
												if($pricemaster['Pricemaster']['key2']!=0){
													$key2 = $pricemaster['Pricemaster']['key2'];
													$sagyo2 = $workdetailmaster->findById($key2);
													echo $sagyo2['Workdetailmaster']['workname'];
												}
												//商品詳細3
												if($pricemaster['Pricemaster']['key3']!=0){
													$key3 = $pricemaster['Pricemaster']['key3'];
													$product3 = $productcontainer->findById($key3);
													echo '('.$product3['Productcontainer']['pdconname'].')';
												}
												//作業内容5
												if($pricemaster['Pricemaster']['key5']!=0){
													$key5 = $pricemaster['Pricemaster']['key5'];
													$sagyo5 = $workdetailmaster->findById($key5);
													echo 'ー'.$sagyo5['Workdetailmaster']['workname'];
												}
												//商品詳細6
												if($pricemaster['Pricemaster']['key6']!=0){
													$key6 = $pricemaster['Pricemaster']['key6'];
													$product6 = $productcontainer->findById($key6);
													echo '('.$product6['Productcontainer']['pdconname'].')';
												}
												//作業内容8
												if($pricemaster['Pricemaster']['key8']!=0){
													$key8 = $pricemaster['Pricemaster']['key8'];
													$sagyo8 = $workdetailmaster->findById($key8);
													echo 'ー'.$sagyo8['Workdetailmaster']['workname'];
												}
												//商品詳細9
												if($pricemaster['Pricemaster']['key9']!=0){
													$key9 = $pricemaster['Pricemaster']['key9'];
													$product9 = $productcontainer->findById($key9);
													echo '('.$product9['Productcontainer']['pdconname'].')';
												}
												//作業内容11
												if($pricemaster['Pricemaster']['key11']!=0){
													$key11 = $pricemaster['Pricemaster']['key11'];
													$sagyo11 = $workdetailmaster->findById($key11);
													echo 'ー'.$sagyo11['Workdetailmaster']['workname'];
												}
												//商品詳細12
												if($pricemaster['Pricemaster']['key12']!=0){
													$key12 = $pricemaster['Pricemaster']['key12'];
													$product12 = $productcontainer->findById($key12);
													echo '('.$product12['Productcontainer']['pdconname'].')';
												}
												//作業内容14
												if($pricemaster['Pricemaster']['key14']!=0){
													$key14 = $pricemaster['Pricemaster']['key14'];
													$sagyo14 = $workdetailmaster->findById($key14);
													echo 'ー'.$sagyo14['Workdetailmaster']['workname'];
												}
												//商品詳細15
												if($pricemaster['Pricemaster']['key15']!=0){
													$key15 = $pricemaster['Pricemaster']['key15'];
													$product15 = $productcontainer->findById($key15);
													echo '('.$product15['Productcontainer']['pdconname'].')';
												}
												//作業内容17
												if($pricemaster['Pricemaster']['key17']!=0){
													$key17 = $pricemaster['Pricemaster']['key17'];
													$sagyo17 = $workdetailmaster->findById($key17);
													echo 'ー'.$sagyo17['Workdetailmaster']['workname'];
												}
												//商品詳細18
												if($pricemaster['Pricemaster']['key18']!=0){
													$key18 = $pricemaster['Pricemaster']['key18'];
													$product18 = $productcontainer->findById($key18);
													echo '('.$product18['Productcontainer']['pdconname'].')';
												}
												 ?></td>
												<td><?php echo ($pricemaster['Pricemaster']['price']); ?></td>
												<td><?php //echo ($pricemaster['Departmentmaster']['departmentname']); ?>
												<?php echo $pricemaster['Productcontainer']['pdconname']; ?>
											</td>
												<td>
													<?php echo $this->Html->link(('コピーしてオーダーを登録'), ['controller'=>'Co005s','action' => 'copy',$pricemaster['Pricemaster']['id'],'id'=>$id,'sid'=>$sid,'pid'=>$pid,'cid'=>$cid,'haidate'=>$haidate], ['class' => 'btn btn-info']); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<?php echo $this->element('pager')?>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
