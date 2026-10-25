<!-- 有給一覧 -->
<!-- Host012s -->
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

			echo $this->Form->end();
		?>

<div class="row">
	<div class="box col-md-12">
		<div class="box-inner">
			<div class="box-header well">
				<h2><i class="glyphicon glyphicon-info-sign"></i> 有休等申請</h2>
			</div>

			<div class="box-content row">

				<div class="col-lg-12 col-md-12 h4">
					<ul>
						<?php foreach($employeeholidays as $employeeholiday): ?>
							<?php
								$date = new DateTime($employeeholiday['Employeeholiday']['applicationdate']);
								$today = new DateTime();
								if ($date->format('Y-m-d') >= $today->format('Y-m-d')):
							?>
								<li>
									<?php echo $date->format(h('Y年m月d日')); ?>
									<?php echo ' '; ?>
									<?php echo $this->Html->link(h($employeeholiday['User']['usernameK']),['controller'=>'Host012s','action'=>'edit',$employeeholiday['Employeeholiday']['id']]); ?>
									<?php echo ' '; ?>
									<?php echo $employeeholiday['Flgmaster2']['flgname']?>
									<?php echo ' '; ?>
								 (<?php echo h($employeeholiday['Flgmaster']['flgname']); ?>)
								</li>
							<?php else: ?>
								<?php if ($employeeholiday['Flgmaster']['id'] == 42): ?>
									<li>
										<?php echo $date->format(h('Y年m月d日')); ?>
										<?php echo ' '; ?>
										<?php echo $this->Html->link(h($employeeholiday['User']['usernameK']),['controller'=>'Host012s','action'=>'edit',$employeeholiday['Employeeholiday']['id']]); ?>
								 (<?php echo h($employeeholiday['Flgmaster']['flgname']); ?>)
									</li>
								<?php endif; ?>
							<?php endif; ?>

							<!--
							<li>
								<?php
									$date = new DateTime($employeeholiday['Employeeholiday']['applicationdate']);
									echo $date->format(h('Y年m月d日'));
								?>
								<?php echo ' '; ?>
								<?php echo $this->Html->link(h($employeeholiday['User']['usernameK']),['controller'=>'Host012s','action'=>'edit',$employeeholiday['Employeeholiday']['id']]); ?>
								 (<?php echo h($employeeholiday['Flgmaster']['flgname']); ?>)
							</li>
							-->
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
				<p>
				<?php echo $this->Html->link(('新規申請'), ['action' => 'add'], ['class'=>['btn btn-success']]); ?>
				</p>

		<!-- /有給一覧 -->
		<div class="row">
			<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="box-inner">
					<div class="box-header well">
						<h2><i class="glyphicon glyphicon-info-sign"></i>有休管理</h2>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable table-clickable">
							<thead>
								<tr>
									<th>社員名</th>
									<th>取得日数</th>
									<th>取得日</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($users as $userlist){
								echo '<tr>';
									echo '<td>'.$userlist['User']['usernameK'].'</td>';
									$workarea='';
									$workcount=0;
									foreach($userlist['Kintaitran'] as $kintais){
										if($kintais){
											$workarea .= $kintais['kintaidate'].',';
											$workcount = $workcount + 1;
										}else{
											$workarea = '';
											$workcount = '';
										}
									}
									echo '<td>';
									echo $workcount;
									echo '</td>';
									echo '<td>';
									echo $workarea;
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
