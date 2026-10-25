<?php
$oid1=array();
$oid2=array();
if(isset($dr1confirm1)){
	foreach($dr1confirm1 as $aaa){
		$oid1 = $aaa['Processtran']['ordertrans_id'];
	}
}
if(isset($dr2confirm1)){
	foreach($dr2confirm1 as $bbb){
		$oid2 = $bbb['Processtran']['ordertrans_id'];
	}
}
?>

<div class="ch-container">

	<div class="row">

	<!-- left menu starts -->
		<?php echo $this->element('DriverSidemenu'); ?>
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
				echo $this->Html->link(('印刷'), [
					'action' => 'pdf',
					$date,
				], [
					'class' => ['btn btn-success'],
					'target'=>'_blank'
				]);
			?>

			<!-- 運行日程 -->
			<div class="row">
				<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				    <div class="box-inner">
					    <div class="box-header well">
					    	<?php
					    		$w_oid=null;
								$today = new DateTime($date);
								$order = ClassRegistry::init('Ordertran');
								$ordertrans = $order->findById($oid1);
								$ordertrans2 = $order->findById($oid2);
								echo $today->format('Y年m月d日').'('.$week[$today->format('w')].')'.' 運行日程';
								if($oid1){
									echo '（確定済み）';
								}
							?>
						</div>
						<div class="box-content">
							<table class="table table-bordered bootstrap-datatable">
								<tbody>
								<?php
								foreach($dr1confirm1 as $drprocess){
									// １行目はordertranの内容を表示
									if($w_oid == $oid1){
										echo '<tr>';
										echo '<td>';
										echo $drprocess['PDriver1']['usernameK'].'・'.$drprocess['PDriver2']['usernameK'];
										echo '</td>';
										echo '<td>';
										$aa = new DateTime($drprocess['Processtran']['processstartdate'].$drprocess['Processtran']['processstarttime']);
										if($drprocess['Processtran']['processstarttime']){
											echo $aa->format('d日 H:i');
										}else{
											echo $aa->format('d日');
										}
										echo '</td>';
										echo '<td>';
										$url = $drprocess['Workplace']['googlemapurl'];
										if($url){
											echo '<a href=';
											echo $url;
											echo ' target="_blank">';
											echo $drprocess['Workplace']['workplaceshortname'];
											echo '</a>　(';
											echo $drprocess['Workplace']['mapcomment'];
											echo ')';
										}else{
											echo $drprocess['Workplace']['workplaceshortname'];
											echo '　(';
											echo $drprocess['Workplace']['mapcomment'];
											echo ')';
										}
										echo '</td>';
										echo '<td class="text-danger">';
										echo $drprocess['Workdetailmaster']['workname'];
										echo '</td>';
										echo '<td>';
										echo $drprocess['Productcontainer']['pdconname'];
										echo '</td>';
										echo '<td>ｺﾝﾃﾅNo:';
										echo $drprocess['Processtran']['container_no'];
										echo '</td>';
										echo '<td>REF:';
										echo $drprocess['Processtran']['ref_no'];
										echo '</td>';
										echo '<td>';
										echo $drprocess['Processtran']['workddetailcomment'];
										echo '</td>';
										echo '</tr>';
									}else{
										// キーが変わったらordertranの情報を表示
										echo '<tr class="info">';
										echo '<td colspan="8">';
										echo $ordertrans['Carmaster']['plateno'];
										echo ' 【';
										echo $ordertrans['Shippermaster']['shippershortname'];
										echo '】';
										// echo $ordertrans['Workplace1']['workplaceshortname'];
										echo $this->Html->link($ordertrans['Workplace1']['workplaceshortname'], ['action' => 'pdf_work', $ordertrans['Workplace1']['id']], ['target' => '_blank']);
										echo '　→　';
										// echo $ordertrans['Workplace2']['workplaceshortname'];
										echo $this->Html->link($ordertrans['Workplace2']['workplaceshortname'], ['action' => 'pdf_work', $ordertrans['Workplace2']['id']], ['target' => '_blank']);
										echo '</td>';
										echo '</tr>';
										$w_oid = $oid1;

										// タイトルの後の１件目
										echo '<tr>';
										echo '<td>';
										echo $drprocess['PDriver1']['usernameK'].'・'.$drprocess['PDriver2']['usernameK'];
										echo '</td>';
										echo '<td>';
										$aa = new DateTime($drprocess['Processtran']['processstartdate'].$drprocess['Processtran']['processstarttime']);
										if($drprocess['Processtran']['processstarttime']){
											echo $aa->format('d日 H:i');
										}else{
											echo $aa->format('d日');
										}
										echo '</td>';
										echo '<td>';
										$url = $drprocess['Workplace']['googlemapurl'];
										if($url){
											echo '<a href=';
											echo $url;
											echo ' target="_blank">';
											echo $drprocess['Workplace']['workplaceshortname'];
											echo '</a>　(';
											echo $drprocess['Workplace']['mapcomment'];
											echo ')';
										}else{
											echo $drprocess['Workplace']['workplaceshortname'];
											echo '　(';
											echo $drprocess['Workplace']['mapcomment'];
											echo ')';
										}
										echo '</td>';
										echo '<td class="text-danger">';
										echo $drprocess['Workdetailmaster']['workname'];
										echo '</td>';
										echo '<td>';
										echo $drprocess['Productcontainer']['pdconname'];
										echo '</td>';
										echo '<td>ｺﾝﾃﾅNo:';
										echo $drprocess['Processtran']['container_no'];
										echo '</td>';
										echo '<td>REF:';
										echo $drprocess['Processtran']['ref_no'];
										echo '</td>';
										echo '<td>';
										echo $drprocess['Processtran']['workddetailcomment'];
										echo '</td>';
										echo '</tr>';
									}
								}
								 ?>
								</tbody>
							</table>
						</div>
<!-- 助手用 -->
						<div class="box-content">
							<table class="table table-bordered bootstrap-datatable">
								<tbody>
								<?php
								$drprocess = array();
								foreach($dr2confirm1 as $drprocess){
									// １行目はordertranの内容を表示
									if($w_oid == $oid2){
										echo '<tr>';
										echo '<td>';
										echo $drprocess['PDriver1']['usernameK'].'・'.$drprocess['PDriver2']['usernameK'];
										echo '</td>';
										echo '<td>';
										$aa = new DateTime($drprocess['Processtran']['processstartdate'].$drprocess['Processtran']['processstarttime']);
										if($drprocess['Processtran']['processstarttime']){
											echo $aa->format('d日 H:i');
										}else{
											echo $aa->format('d日');
										}
										echo '</td>';
										echo '<td>';
										echo $drprocess['Workplace']['workplaceshortname'];
										echo '</td>';
										echo '<td class="text-danger">';
										echo $drprocess['Workdetailmaster']['workname'];
										echo '</td>';
										echo '<td>';
										echo $drprocess['Productcontainer']['pdconname'];
										echo '</td>';
										echo '<td>ｺﾝﾃﾅNo:';
										echo $drprocess['Processtran']['container_no'];
										echo '</td>';
										echo '<td>REF:';
										echo $drprocess['Processtran']['ref_no'];
										echo '</td>';
										echo '<td>';
										echo $drprocess['Processtran']['workddetailcomment'];
										echo '</td>';
										echo '</tr>';
									}else{
										// キーが変わったらordertranの情報を表示
										echo '<tr class="info">';
										echo '<td colspan="8">';
										echo $ordertrans2['Carmaster']['plateno'];
										echo ' 【';
										echo $ordertrans2['Shippermaster']['shippershortname'];
										echo '】';
										echo $ordertrans2['Workplace1']['workplaceshortname'];
										echo '　→　';
										echo $ordertrans2['Workplace2']['workplaceshortname'];
										echo '</td>';
										echo '</tr>';
										$w_oid = $oid2;

										// タイトルの後の１件目
										echo '<tr>';
										echo '<td>';
										echo $drprocess['PDriver1']['usernameK'].'・'.$drprocess['PDriver2']['usernameK'];
										echo '</td>';
										echo '<td>';
										$aa = new DateTime($drprocess['Processtran']['processstartdate'].$drprocess['Processtran']['processstarttime']);
										if($drprocess['Processtran']['processstarttime']){
											echo $aa->format('d日 H:i');
										}else{
											echo $aa->format('d日');
										}
										echo '</td>';
										echo '<td>';
										echo $drprocess['Workplace']['workplaceshortname'];
										echo '</td>';
										echo '<td class="text-danger">';
										echo $drprocess['Workdetailmaster']['workname'];
										echo '</td>';
										echo '<td>';
										echo $drprocess['Productcontainer']['pdconname'];
										echo '</td>';
										echo '<td>ｺﾝﾃﾅNo:';
										echo $drprocess['Processtran']['container_no'];
										echo '</td>';
										echo '<td>REF:';
										echo $drprocess['Processtran']['ref_no'];
										echo '</td>';
										echo '<td>';
										echo $drprocess['Processtran']['workddetailcomment'];
										echo '</td>';
										echo '</tr>';
									}
								}
								 ?>
								</tbody>
							</table>
						</div>

					</div>
				</div>
			<!-- /運行日程 -->
			</div>
    	</div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
