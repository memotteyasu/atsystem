<!-- 年間拘束時間 -->
<!-- Host017s -->
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
					'div' => false,
					'label' => false,
					'type' => 'select',
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'options'=>$yearlist,
					'default' => $w_y
				]);

				echo '　年　';

				echo '　　営業所　';
				echo $this->Form->input('branchmasters_id',[
					'label'=>false,
					'type'=>'select',
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'default' => $branchmasters_id
				]);
				echo '　　';

				echo '部署　';
				echo $this->Form->input('departmentmasters_id',[
					'label'=>false,
					'type'=>'select',
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'default' => $departmentmasters_id
				]);

			echo $this->Form->end();
		?>
		<div class="row">
			<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="box-inner">
					<div class="box-header well">
						<h2><i class="glyphicon glyphicon-info-sign"></i>拘束時間</h2>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable table-clickable">
							<thead>
								<tr>
								<th>社員名</th>
								<th><?php echo $selectym1; ?></th>
								<th>1月</th>
								<th>2月</th>
								<th>3月</th>
								<th>4月</th>
								<th>5月</th>
								<th>6月</th>
								<th>7月</th>
								<th>8月</th>
								<th>9月</th>
								<th>10月</th>
								<th>11月</th>
								<th>12月</th>
								<th>合計</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($users as $userlist) {
										$kousokutimetotal = '00:00';
										$kousokuzangyotimetital = '00:00';

										echo '<tr>';
										echo '<td>';
											// echo $userlist['User']['usernameK'];
                      echo $this->Html->link($userlist['User']['usernameK'], ['controller' => 'Host017s', 'action' => 'show', $userlist['User']['id']]);
										echo '</td>';
										echo '<td class="text-center">拘束:3400</td>';
										//kousokutranを個人事に読み込み
										$kousokutran = ClassRegistry::init('Kousokutran');
										$kousokutrans = $kousokutran->find('all',[
											'conditions' => [
												'kousokuyear' => $w_y,
												'users_id' => $userlist['User']['id']
											],
											'order' => [
												'kousokudate' => 'asc'
											]
										]);
										echo '<td class="text-center">';
										//1月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-01-01'){
													echo substr($kt['Kousokutran']['kousokutime'],0,-3);
													$kousokutimetotal = $this->Format->Addtime($kousokutimetotal,$kt['Kousokutran']['kousokutime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//2月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-02-01'){
													echo substr($kt['Kousokutran']['kousokutime'],0,-3);
													$kousokutimetotal = $this->Format->Addtime($kousokutimetotal,$kt['Kousokutran']['kousokutime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//3月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-03-01'){
													echo substr($kt['Kousokutran']['kousokutime'],0,-3);
													$kousokutimetotal = $this->Format->Addtime($kousokutimetotal,$kt['Kousokutran']['kousokutime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//4月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-04-01'){
													echo substr($kt['Kousokutran']['kousokutime'],0,-3);
													$kousokutimetotal = $this->Format->Addtime($kousokutimetotal,$kt['Kousokutran']['kousokutime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//5月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-05-01'){
													echo substr($kt['Kousokutran']['kousokutime'],0,-3);
													$kousokutimetotal = $this->Format->Addtime($kousokutimetotal,$kt['Kousokutran']['kousokutime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//6月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-06-01'){
													echo substr($kt['Kousokutran']['kousokutime'],0,-3);
													$kousokutimetotal = $this->Format->Addtime($kousokutimetotal,$kt['Kousokutran']['kousokutime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//7月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-07-01'){
													echo substr($kt['Kousokutran']['kousokutime'],0,-3);
													$kousokutimetotal = $this->Format->Addtime($kousokutimetotal,$kt['Kousokutran']['kousokutime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//8月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-08-01'){
													echo substr($kt['Kousokutran']['kousokutime'],0,-3);
													$kousokutimetotal = $this->Format->Addtime($kousokutimetotal,$kt['Kousokutran']['kousokutime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//9月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-09-01'){
													echo substr($kt['Kousokutran']['kousokutime'],0,-3);
													$kousokutimetotal = $this->Format->Addtime($kousokutimetotal,$kt['Kousokutran']['kousokutime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//10月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-10-01'){
													echo substr($kt['Kousokutran']['kousokutime'],0,-3);
													$kousokutimetotal = $this->Format->Addtime($kousokutimetotal,$kt['Kousokutran']['kousokutime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//11月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-11-01'){
													echo substr($kt['Kousokutran']['kousokutime'],0,-3);
													$kousokutimetotal = $this->Format->Addtime($kousokutimetotal,$kt['Kousokutran']['kousokutime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//12月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-12-01'){
													echo substr($kt['Kousokutran']['kousokutime'],0,-3);
													$kousokutimetotal = $this->Format->Addtime($kousokutimetotal,$kt['Kousokutran']['kousokutime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										echo $kousokutimetotal;
										echo '</td>';
										echo '</tr>';
										echo '<tr>';
										echo '<td>';
										echo '</td>';
										echo '<td class="text-center">時間外:960</td>';
										echo '<td class="text-center">';
										//1月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-01-01'){
													echo substr($kt['Kousokutran']['kousokuzangyotime'],0,-3);
													if(($kt['Kousokutran']['kousokuholiday'] != '00:00:00')and($kt['Kousokutran']['kousokuholiday']!=NULL)){
														echo '<font color="#ff0000;"> ('.substr($kt['Kousokutran']['kousokuholiday'],0,-3).')</font>';
													}
													$kousokuzangyotimetital = $this->Format->Addtime($kousokuzangyotimetital,$kt['Kousokutran']['kousokuzangyotime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//2月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-02-01'){
													echo substr($kt['Kousokutran']['kousokuzangyotime'],0,-3);
													if(($kt['Kousokutran']['kousokuholiday'] != '00:00:00')and($kt['Kousokutran']['kousokuholiday']!=NULL)){
														echo '<font color="#ff0000;"> ('.substr($kt['Kousokutran']['kousokuholiday'],0,-3).')</font>';
													}
													$kousokuzangyotimetital = $this->Format->Addtime($kousokuzangyotimetital,$kt['Kousokutran']['kousokuzangyotime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//3月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-03-01'){
													echo substr($kt['Kousokutran']['kousokuzangyotime'],0,-3);
													if(($kt['Kousokutran']['kousokuholiday'] != '00:00:00')and($kt['Kousokutran']['kousokuholiday']!=NULL)){
														echo '<font color="#ff0000;"> ('.substr($kt['Kousokutran']['kousokuholiday'],0,-3).')</font>';
													}
													$kousokuzangyotimetital = $this->Format->Addtime($kousokuzangyotimetital,$kt['Kousokutran']['kousokuzangyotime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//4月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-04-01'){
													echo substr($kt['Kousokutran']['kousokuzangyotime'],0,-3);
													if(($kt['Kousokutran']['kousokuholiday'] != '00:00:00')and($kt['Kousokutran']['kousokuholiday']!=NULL)){
														echo '<font color="#ff0000;"> ('.substr($kt['Kousokutran']['kousokuholiday'],0,-3).')</font>';
													}
													$kousokuzangyotimetital = $this->Format->Addtime($kousokuzangyotimetital,$kt['Kousokutran']['kousokuzangyotime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//5月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-05-01'){
													echo substr($kt['Kousokutran']['kousokuzangyotime'],0,-3);
													if(($kt['Kousokutran']['kousokuholiday'] != '00:00:00')and($kt['Kousokutran']['kousokuholiday']!=NULL)){
														echo '<font color="#ff0000;"> ('.substr($kt['Kousokutran']['kousokuholiday'],0,-3).')</font>';
													}
													$kousokuzangyotimetital = $this->Format->Addtime($kousokuzangyotimetital,$kt['Kousokutran']['kousokuzangyotime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//6月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-06-01'){
													echo substr($kt['Kousokutran']['kousokuzangyotime'],0,-3);
													if(($kt['Kousokutran']['kousokuholiday'] != '00:00:00')and($kt['Kousokutran']['kousokuholiday']!=NULL)){
														echo '<font color="#ff0000;"> ('.substr($kt['Kousokutran']['kousokuholiday'],0,-3).')</font>';
													}
													$kousokuzangyotimetital = $this->Format->Addtime($kousokuzangyotimetital,$kt['Kousokutran']['kousokuzangyotime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//7月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-07-01'){
													echo substr($kt['Kousokutran']['kousokuzangyotime'],0,-3);
													if(($kt['Kousokutran']['kousokuholiday'] != '00:00:00')and($kt['Kousokutran']['kousokuholiday']!=NULL)){
														echo '<font color="#ff0000;"> ('.substr($kt['Kousokutran']['kousokuholiday'],0,-3).')</font>';
													}
													$kousokuzangyotimetital = $this->Format->Addtime($kousokuzangyotimetital,$kt['Kousokutran']['kousokuzangyotime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//8月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-08-01'){
													echo substr($kt['Kousokutran']['kousokuzangyotime'],0,-3);
													if(($kt['Kousokutran']['kousokuholiday'] != '00:00:00')and($kt['Kousokutran']['kousokuholiday']!=NULL)){
														echo '<font color="#ff0000;"> ('.substr($kt['Kousokutran']['kousokuholiday'],0,-3).')</font>';
													}
													$kousokuzangyotimetital = $this->Format->Addtime($kousokuzangyotimetital,$kt['Kousokutran']['kousokuzangyotime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//9月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-09-01'){
													echo substr($kt['Kousokutran']['kousokuzangyotime'],0,-3);
													if(($kt['Kousokutran']['kousokuholiday'] != '00:00:00')and($kt['Kousokutran']['kousokuholiday']!=NULL)){
														echo '<font color="#ff0000;"> ('.substr($kt['Kousokutran']['kousokuholiday'],0,-3).')</font>';
													}
													$kousokuzangyotimetital = $this->Format->Addtime($kousokuzangyotimetital,$kt['Kousokutran']['kousokuzangyotime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//10月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-10-01'){
													echo substr($kt['Kousokutran']['kousokuzangyotime'],0,-3);
													if(($kt['Kousokutran']['kousokuholiday'] != '00:00:00')and($kt['Kousokutran']['kousokuholiday']!=NULL)){
														echo '<font color="#ff0000;"> ('.substr($kt['Kousokutran']['kousokuholiday'],0,-3).')</font>';
													}
													$kousokuzangyotimetital = $this->Format->Addtime($kousokuzangyotimetital,$kt['Kousokutran']['kousokuzangyotime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//11月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-11-01'){
													echo substr($kt['Kousokutran']['kousokuzangyotime'],0,-3);
													if(($kt['Kousokutran']['kousokuholiday'] != '00:00:00')and($kt['Kousokutran']['kousokuholiday']!=NULL)){
														echo '<font color="#ff0000;"> ('.substr($kt['Kousokutran']['kousokuholiday'],0,-3).')</font>';
													}
													$kousokuzangyotimetital = $this->Format->Addtime($kousokuzangyotimetital,$kt['Kousokutran']['kousokuzangyotime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										//12月
										if($kousokutrans){
											foreach($kousokutrans as $kt){
												if($kt['Kousokutran']['kousokudate']==$w_y.'-12-01'){
													echo substr($kt['Kousokutran']['kousokuzangyotime'],0,-3);
													if(($kt['Kousokutran']['kousokuholiday'] != '00:00:00')and($kt['Kousokutran']['kousokuholiday']!=NULL)){
														echo '<font color="#ff0000;"> ('.substr($kt['Kousokutran']['kousokuholiday'],0,-3).')</font>';
													}
													$kousokuzangyotimetital = $this->Format->Addtime($kousokuzangyotimetital,$kt['Kousokutran']['kousokuzangyotime']);
													break;
												}
											}
										}
										echo '</td>';
										echo '<td class="text-center">';
										echo $kousokuzangyotimetital;
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
				echo $this->Html->link(('年間拘束印刷'), [
					'action' => 'pdfhost002_5y',
					$selectym1,
					$departmentmasters_id,
					$branchmasters_id,
				], [
					'class' => ['btn btn-success'],
					'target'=>'_blank'
				]);
			?>
		</p>

	<!-- content ends -->
	</div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

