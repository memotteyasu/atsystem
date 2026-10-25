<!-- 勤怠実績 -->
<!-- Host010s -->
<style>
	table.table-clickable td[data-href]{
		cursor: pointer;
	}
	table.table-clickable td:hover{
		background-color:#e8e8e8 !important;
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
					'type' => 'date',
					'dateFormat' => 'YMD',
					'monthNames' => false,
					'minYear' => date('Y')-1,
					'maxYear' => date('Y')+1,
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'style'=>'width:70px',
					'default' => $paramsDate
				]);

				echo 'から1週間　　　';
				echo '部署　';
				echo $this->Form->input('departmentmasters_id',[
					'label'=>false,
					'type'=>'select',
					'empty'=>'未選択',
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'default' => $departmentmasters_id
				]);
				echo '　　';
				echo '営業所　';
				echo $this->Form->input('branchmasters_id',[
					'label'=>false,
					'type'=>'select',
					// 'empty'=>'未選択',
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'default' => $branchmasters_id
				]);


			echo $this->Form->end();
		?>
		<!-- /勤怠実績 -->
		<div class="row">
			<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="box-inner">
					<div class="box-header well">
						<h2><i class="glyphicon glyphicon-info-sign"></i>勤怠実績</h2>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable table-clickable">
							<thead>
								<tr>
									<th>社員名</th>
										<?php
											$date = new DateTime($paramsDate);
											for ($i = 1; $i <= 7; $i++) {
												echo '<th>';
													if ($i == 1) {
														$date2 = $date->format('m月d日');
													} else {
														$date2 = $date->modify('+1 day')->format('m月d日');
													}
													if ($week[$date->format('w')] == '日') {
														echo $date2.'<font color="#ff0000">('.$week[$date->format('w')].')</font>';
													} else {
														echo $date2.'('.$week[$date->format('w')].')';
													}
												echo '</th>';
											}
										?>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($users as $userlist) {
										echo '<tr>';
											echo '<td>';
												echo $userlist['User']['usernameK'];
											echo '</td>';
											$date = new DateTime($paramsDate);
											$max_date = new DateTime($paramsDate);
											$max_date->modify('+6 day');
											$lineflg=0;
											foreach ($userlist['Kintaitran'] as $kintaitran) {
												if($date->format('Y-m-d') > $max_date->format('Y-m-d')){
													break;
												}
												while($kintaitran['kintaidate'] > $date->format('Y-m-d')){
													echo '<td class="text-center" data-href="'.
														$this->Html->url([
															'controller' => 'Host010s',
															'action' => 'add',
															'uid' => $userlist['User']['id'],
															'kyy' => $date->format('Y'),
															'kmm' => $date->format('m'),
															'kdd' => $date->format('d'),
														]).
													'">';
													$date->modify('+1 day');
													echo '</td>';
												}
												if ($kintaitran['kintaidate'] == $date->format('Y-m-d')) {
													echo '<td class="text-center">';
													echo $this->Html->link($flgmasters[$kintaitran['flgmasters_id']], [
														'controller' => 'Host010s',
														'action' => 'edit',
														'keyid' => $kintaitran['id'],
														'uid' => $userlist['User']['id'],
														'kyy' => $date->format('Y'),
														'kmm' => $date->format('m'),
														'kdd' => $date->format('d'),
													]);
													$date->modify('+1 day');
													echo '</td>';
												}
												$lineflg=1;
											}
											// データがあるが、期間の最後までｔｄを埋める
											if($lineflg==1){
												while($date->format('Y-m-d') <= $max_date->format('Y-m-d')){
													echo '<td class="text-center" data-href="'.
														$this->Html->url([
															'controller' => 'Host010s',
															'action' => 'add',
															'uid' => $userlist['User']['id'],
															'kyy' => $date->format('Y'),
															'kmm' => $date->format('m'),
															'kdd' => $date->format('d'),
														]).
													'">';
													$date->modify('+1 day');
													echo '</td>';
												}
											}
											// データがない場合
											if($lineflg==0){
												for ($i = 1; $i <= 7; $i++) {
													echo '<td class="text-center" data-href="'.
														$this->Html->url([
															'controller' => 'Host010s',
															'action' => 'add',
															'uid' => $userlist['User']['id'],
															'kyy' => $date->format('Y'),
															'kmm' => $date->format('m'),
															'kdd' => $date->format('d'),
														]).
													'">';
													$date->modify('+1 day');
													echo '</td>';
												}
											}
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
				$date2 = new DateTime($paramsDate);
				if ($departmentmasters_id) {
					$did = $departmentmasters_id;
				} else {
					$did = 0;
				}
				if ($branchmasters_id) {
					$bid = $branchmasters_id;
				} else {
					$bid = 0;
				}
				echo $this->Html->link(('時間外距離手当明細PDF出力'), [
					'action' => 'pdf',
					'kyy' => $date2->format('Y'),
					'kmm' => $date2->format('m'),
					'did' => $did,
					'bid' => $bid,
				],
				[
					'class' => ['btn btn-success'],
					'target'=>'_blank'
				]);
			?>
		</p>

	<!-- content ends -->
	</div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<script>
$(function(){
  $('td[data-href]', 'table.table-clickable').on('click', function(){
    location.href = $(this).data('href');
  });
});
</script>
