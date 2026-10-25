<!-- 交通費精算一覧 -->
<!-- HostS20s -->
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
					'dateFormat' => 'YM',
					'monthNames' => false,
					'minYear' => date('Y')-1,
					'maxYear' => date('Y')+1,
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'style'=>'width:70px',
					'default' => $paramsDate1
				]);
				echo '月分';


				echo '　　　営業所　';

				echo $this->Form->input('branchmasters_id',[
					'label'=>false,
					'type'=>'select',
					'empty'=>'未選択',
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'default' => $branchmasters_id,
				]);
				echo '　　　部署　';

				echo $this->Form->input('departmentmasters_id',[
					'label'=>false,
					'type'=>'select',
					'empty'=>'未選択',
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
					'default' => $departmentmasters_id,
				]);

			echo $this->Form->end();
		?>

		<!-- 交通費精算一覧 -->
		<div class="row">
			<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="box-inner">
					<div class="box-header well">
						<h2><i class="glyphicon glyphicon-info-sign"></i>交通費精算</h2>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable table-clickable">
							<thead>
								<tr>
									<th>社員名</th>
									<th>出勤日数</th>
									<th>油種</th>
									<th>燃費</th>
									<th>片道(km)</th>
									<th>交通費合計</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$selectyusyu = ['0'=>'なし','1'=>'レギュラー','2'=>'軽油','3'=>'電気','4'=>'水素','5'=>'自転車'];
							foreach($users as $userlist){
								echo '<tr>';
									echo '<td>'.$userlist['User']['usernameK'].'</td>';
									echo '<td>'.$userlist['Kintaitran']['flgcount'].'</td>';
									echo '<td>'.$selectyusyu[$userlist['User']['yusyu']].'</td>';
									echo '<td>'.$userlist['User']['nenpi'].'</td>';
									echo '<td>'.$userlist['User']['katamichi'].'</td>';
									// エンジン車の場合ー＞出勤日数 x (往復の距離÷燃費) x 会社マスタの燃料単価
									$kingaku=0;
									switch($userlist['User']['yusyu']){
									case 1: //レギュラー
										if($userlist['User']['nenpi']>0){
											if($userlist['User']['nenpi']<10){ //10kmより小さいときは１０ｋｍに強制的にする
												if(((($userlist['User']['katamichi']*2) / 10) * $kaisya['Kaisya']['regularprice']) < 50){
													$kingaku = $userlist['Kintaitran']['flgcount'] * 50;
												}else{
													$kingaku = $userlist['Kintaitran']['flgcount'] * (($userlist['User']['katamichi']*2) / 10) * $kaisya['Kaisya']['regularprice'];
												}
											}else{
												if(((($userlist['User']['katamichi']*2) / $userlist['User']['nenpi']) * $kaisya['Kaisya']['regularprice']) < 50){
													$kingaku = $userlist['Kintaitran']['flgcount'] * 50;		
												}else{
													$kingaku = $userlist['Kintaitran']['flgcount'] * (($userlist['User']['katamichi']*2) / $userlist['User']['nenpi']) * $kaisya['Kaisya']['regularprice'];
												}
											}
										}
										break;
									case 2: //軽油
										if($userlist['User']['nenpi']>0){
											if($userlist['User']['nenpi']<10){ //10kmより小さいときは１０ｋｍに強制的にする
												if(((($userlist['User']['katamichi']*2) / 10) * $kaisya['Kaisya']['keiyuprice']) < 50){
													$kingaku = $userlist['Kintaitran']['flgcount'] * 50;
												}else{
													$kingaku = $userlist['Kintaitran']['flgcount'] * (($userlist['User']['katamichi']*2) / 10) * $kaisya['Kaisya']['keiyuprice'];
												}
											}else{
												if(((($userlist['User']['katamichi']*2) / $userlist['User']['nenpi']) * $kaisya['Kaisya']['keiyuprice']) < 50){
													$kingaku = $userlist['Kintaitran']['flgcount'] * 50;
												}else{
													$kingaku = $userlist['Kintaitran']['flgcount'] * (($userlist['User']['katamichi']*2) / $userlist['User']['nenpi']) * $kaisya['Kaisya']['keiyuprice'];
												}
											}
										}
										break;
									case 3: //電気の場合ー＞出勤日数 x (往復の距離÷燃費) x 会社マスタの燃料単価
										if($userlist['User']['nenpi']>0){
											$kingaku = $userlist['Kintaitran']['flgcount'] * ($userlist['User']['katamichi']*2) * $userlist['User']['nenpi'] * $kaisya['Kaisya']['electroprice'];
										}
										break;
									case 4: //水素の場合ー＞出勤日数 x (往復の距離÷燃費) x 会社マスタの燃料単価
										if($userlist['User']['nenpi']>0){
											$kingaku = $userlist['Kintaitran']['flgcount'] * ($userlist['User']['katamichi']*2) * $userlist['User']['nenpi'] * $kaisya['Kaisya']['hydrogenprice'];
										}
										break;
									case 5: //自転車の場合ー＞出勤日数 x 会社マスタの自転車単価
										$kingaku = $userlist['Kintaitran']['flgcount'] * $kaisya['Kaisya']['bicycleprice'];
										break;
									default:
										$kingaku = 0;
									}
									echo '<td>'.number_format(round($kingaku)).'</td>';
								echo '</tr>';
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
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
