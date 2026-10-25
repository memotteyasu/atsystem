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
				/*
				echo $this->element('searchForm2', [
					'model' => 'Gasusetran',
					'url' => ['controller'=>'Host020s','action'=>'index'],
					'text' => '部署　',
					'input' => 'departmentmasters_id',
					'text2' => '営業所　',
					'input2' => 'branchmasters_id',
				]);
				*/
			?>

			<?php
				echo $this->Form->create('Gasusetran', ['url' => ['controller'=>'Host020s','action'=>'index'], 'label' => false, 'div' => 'form-group', 'class' => 'form-inline', 'novalidate' => true]);

					echo $this->Form->input('chargedate', [
						'div' => false,
						'label' => false,
						//'type' => 'date',
						'dateFormat' => 'YM',
						'monthNames' => false,
						'minYear' => date('Y')-1,
						'maxYear' => date('Y')+1,
						'onchange' => 'this.form.submit()',
						'style' => 'width:70px',
						'data-rel' => 'chosen',
						'default' => $paramsDate,
					]);

/*
					echo '　　部署　';

					echo $this->Form->input('departmentmasters_id', [
						'label' => false,
						'type' => 'select',
						'div' => false,
						'class' => 'form-control',
						'empty'=>'未選択',
						'onchange' => 'this.form.submit()',
						'data-rel' => 'chosen',
						'default' => $departmentmasters_id,
					]);

					echo '　　';
*/
					echo '営業所　';

					echo $this->Form->input('branchmasters_id', [
						'label' => false,
						'type' => 'select',
						'div' => false,
						'class' => 'form-control',
						// 'empty'=>'未選択',
						'onchange' => 'this.form.submit()',
						'data-rel' => 'chosen',
						'default' => $branchmasters_id,
					]);

				echo $this->Form->end()
			?>
			<!-- ガソリン使用量一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>ガソリン使用量一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th><?php echo $this->Paginator->sort('chargedate', '給油日'); ?></th>
									        <th><?php echo $this->Paginator->sort('users_id', '従業員'); ?></th>
									        <th><?php echo $this->Paginator->sort('gaskind', '油種'); ?></th>
									        <th><?php echo $this->Paginator->sort('amount', '使用量'); ?></th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($gasusetrans as $gasusetran): ?>
									    <tr>
											<td><?php echo ($gasusetran['Gasusetran']['chargedate']); ?></td>
									        <td><?php echo ($gasusetran['User']['usernameK']); ?></td>
											<td><?php echo ($gasusetran['Flgmaster']['flgname']); ?></td>
											<td><?php echo ($gasusetran['Gasusetran']['amount']); ?></td>
									        <td>
												<?php echo $this->Html->link(('修正'),
														['action' => 'edit',$gasusetran['Gasusetran']['id']],
														['class'=>['btn btn-info']]); ?>
												<?php
													 echo $this->Form->postLink(('削除'),
														[
														'action' => 'delete', $gasusetran['Gasusetran']['id']
														],
														[
														'escape' => false,
														'class' => 'btn btn-danger',
														'role' => 'button',
														'confirm' => __('削除しますか？')
														]
													);
												?>
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

				<p>
					<?php echo $this->Html->link(('新規登録'), ['action' => 'add'], ['class'=>['btn btn-success']]); ?>
					<?php
						$date2 = new DateTime($paramsDate);
/*
						if ($departmentmasters_id) {
							$did = $departmentmasters_id;
						} else {
							$did = 0;
						}
*/
						if ($branchmasters_id) {
							$bid = $branchmasters_id;
						} else {
							$bid = 0;
						}
						echo $this->Html->link(('使用量一覧'), [
							'action' => 'pdf',
							$date2->format('Y-m-d'),
							$bid,
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
