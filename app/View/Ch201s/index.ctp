<!-- 経費トラン -->
<!-- Expensestran -->
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

			echo $this->Form->end();
		?>

			<!-- /会社一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>個別経費一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th><?php echo $this->Paginator->sort('shippermasters_id', '荷主'); ?></th>
									        <th><?php echo $this->Paginator->sort('expensesname', '経費名称'); ?></th>
									        <th><?php echo $this->Paginator->sort('billconfirmdate', '請求月'); ?></th>
									        <th><?php echo $this->Paginator->sort('expensesprice', '金額'); ?></th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($expensestrans as $expensestran): ?>
									    <tr>
									    	<td><?php echo ($expensestran['Shippermaster']['shippershortname']); ?></td>
									    	<td><?php echo ($expensestran['Expensestran']['expensesname']); ?></td>
									    	<td><?php echo ($expensestran['Expensestran']['billconfirmdate']); ?></td>
									        <td class="text-right"><?php echo number_format($expensestran['Expensestran']['expensesprice']); ?>円</td>
									        <td>
												<?php echo $this->Html->link(('修正'), array('action' => 'edit',$expensestran['Expensestran']['id']), array('class'=>array('btn btn-info'))); ?>
									        	<?php echo $this->Form->postLink(('削除'), array('action' => 'delete',$expensestran['Expensestran']['id']), array('confirm' => __('削除しますか？'), 'class'=>array('btn btn-danger'))); ?>
											</td>
									    </tr>
									    <?php endforeach; ?>
								    </tbody>
								</table>
							</div>
					    </div>
				    </div>
			    </div>

				<p>
				<?php echo $this->Html->link(('新規登録'), array('action' => 'add'), array('class'=>array('btn btn-success'))); ?>
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
