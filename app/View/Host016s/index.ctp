<!-- ４ｈ速度超過一覧 -->
<!-- Host016s -->
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

		<!-- 距離一覧 -->
		<div class="row">
			<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="box-inner">
					<div class="box-header well">
						<h2><i class="glyphicon glyphicon-info-sign"></i>距離管理</h2>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable table-clickable">
							<thead>
								<tr>
									<th>社員名</th>
									<th>トータル距離</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								//カウンタクリア
								$renzoku = 0;
								$sokudo = 0;
								?>
							<?php foreach($users as $userlist){
								echo '<tr>';
									echo '<td>'.$userlist['User']['usernameK'].'</td>';
									echo '<td>';
									echo $userlist['Kintaitran']['kintaidate'];
									echo '　→　';
									if($userlist['Kintaitran']['shiharaisuryo']==1){
										//4h連続
										echo '４ｈ連続、';
										$renzoku = $renzoku + 1;
									}
									if($userlist['Kintaitran']['kousokudai']==1){
										//速度超過
										echo '速度超過';
										$sokudo = $sokudo + 1;
									}
									echo ' </td>';
								echo '</tr>';
							}
							?>
							</tbody>
						</table>
						　合計（４ｈ連続回数：<span class="text-danger h3"><?php echo $renzoku; ?></span>回）　（速度超過：<span class="text-danger h3"><?php echo $sokudo; ?></span>回）
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
