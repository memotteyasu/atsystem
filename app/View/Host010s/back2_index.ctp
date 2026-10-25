<!-- 勤怠照会 -->
<!-- Host010s -->
<style>
	a.sortflgcolor:link {
		color:#ff0000;
	}
	a.sortflgcolor:visited {
		color:#ff0000;
	}
	a.sortflgcolor:hover {
		color:#dc143c;
	}
	a.sortflgcolor:active {
		color:#dc143c;
	}
</style>

<div class="ch-container">
	<div class="row">

	<!-- left menu starts -->
	<?php echo $this->element('HostSidemenu'); ?>
	<!-- left menu ends -->


	<div id="content" class="col-lg-10 col-md-10 col-sm-10">
	<!-- content starts -->
		<div>
				<ul class="breadcrumb">
					<li>
						<?php
							$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
							$this->Html->addCrumb('index2', ['controller' => 'Host010s', 'action' => 'index2']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
		</div>
			<?php
				echo $this->element('searchForm2', [
					'model' => 'User',
					'url' => ['controller'=>'Host010s','action'=>'index'],
					'text' => '営業所　',
					'input' => 'branchmasters_id',
					'text2' => '部署　',
					'input2' => 'departmentmasters_id',
				]);
			?>

		<!-- /勤怠一覧 -->
		<div class="row">
			<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="box-inner">
					<div class="box-header well">
						<h2>
							<?php echo $this->Form->create(false); ?>
							勤怠管理　<?php
								echo $this->Form->input('selectym1', [
									'div'=>false,
									'label'=>false,
									'type' => 'date',
									'dateFormat' => 'YMD',
									'monthNames' => false,
									'minYear' => date('Y')-1,
									'maxYear' => date('Y')+1,
									'onchange'=>'this.form.submit()',
									//'data-rel'=>'chosen'
								]);
							?>
							から1週間
							<?php echo $this->Form->end(); ?>
						</h2>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable">
							<thead>
								<tr>
									<th>社員名</th>
										<?php
											$date = new DateTime($paramsDate);
											for ($i = 1; $i <= 7; $i++) {
												$youbi = $week[$date->format('w')];
												if($youbi == '日'){
													$youbi_work = '<font color="#ff0000">('.$youbi.')</font>';
												}else{
													$youbi_work = '('.$youbi.')';
												}

												if($i==1){
													echo '<th>';
													echo $date->format('m月d日').$youbi_work;
												}else{
													echo '<th>';
													echo $date->modify('+1 day')->format('m月d日').$youbi_work;
												}
												echo '</th>';
											}
										?>
								</tr>
							</thead>
							<tbody>
							<?php foreach($users as $userlist): ?>
								<tr>
									<td><?php echo $userlist['User']['usernameK']; ?>
									</td>
										<?php
											$date = new DateTime($paramsDate);
											for ($i = 1; $i <= 7; $i++) {
												echo '<td>';
												echo $this->Html->link('出勤',array('controller'=>'Host011s','action'=>'index',
												'uid'=>$userlist['User']['id'],
												'kyy'=>$date->format('Y'),
												'kmm'=>$date->format('m'),
												'kdd'=>$date->format('d'),
												));
												echo '</td>';
												$date->modify('+1 day');
											}
										?>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>


		<p>
			<?php echo $this->Html->link(('新規登録'), ['action' => 'add'], ['class'=>['btn btn-success btn-sm']]); ?>
		</p>

	<!-- content ends -->
	</div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

	<footer class="row">
		<p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
	</footer>

</div><!--/.fluid-container-->
