<!-- 走行実績選択画面（石油業務 -->
<!-- Oi040s -->
<div class="ch-container">
	<div class="row">

	<!-- left menu starts -->
	<?php //echo $this->element('ClerkSidemenu'); ?>
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
			echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);

				$defa = strtotime(date('Y-m-d'));
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
					'class'=>'form-control',
					'style'=>'width:70px',
				]);

				echo '　　　';
				echo '営業所　';

				echo $this->Form->input('branchmasters_id',[
					'label'=>false,
					'type'=>'select',
					'empty'=>'未選択',
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
				]);

				echo '　　';
				echo '部署　';

				echo $this->Form->input('departmentmasters_id',[
					'label'=>false,
					'type'=>'select',
					'empty'=>'未選択',
					'div'=>false,
					'class'=>'form-control',
					'onchange'=>'this.form.submit()',
					'data-rel'=>'chosen',
				]);
			echo $this->Form->end();
		?>

		<!-- /荷主一覧 -->
		<div class="row">
			<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="box-inner">
					<div class="box-header well">
						<h2><i class="glyphicon glyphicon-info-sign"></i>走行実績一覧</h2>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable table-clickable">
							<thead>
								<tr>
									<th width="15%">車両番号</th>
									<th>運転手</th>
									<th>実績確認登録</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($truckkinds as $carlist): ?>
									<tr>
										<td>
											<?php
												$date = new DateTime($paramsDate);
												echo $carlist['Carmaster']['plateno'];
											?>
										</td>
											<td>山田太郎</td>
											<td>確認済み</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<?php //echo $this->element('pager')?>

		<p>
			<?php echo $this->Html->link(('新規登録'), ['controller' => 'Oi010s', 'action' => 'add'], ['class'=>['btn btn-success btn-sm']]); ?>
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
