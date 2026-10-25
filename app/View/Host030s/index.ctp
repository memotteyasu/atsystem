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

			<?php //20220117 echo $this->Form->create(false, ['label' => false, 'div' => false, 'class' => false, 'novalidate' => true]); ?>
			<?php echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']); ?>
					<?php
						echo $this->Form->input('selectym1', [
							'div'=>false,
							'label'=>false,
							'type' => 'date',
							'dateFormat' => 'YM',
							'monthNames' => false,
							'minYear' => date('Y')-4,
							'maxYear' => date('Y')+1,
							'onchange'=>'this.form.submit()',
							'data-rel'=>'chosen',
							'style'=>'width:70px',
							'default' => $paramsDate,
						]);
					?>
					を表示
				<?php echo $this->Form->end(); ?>
			<!-- 事故一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>事故一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th><?php echo $this->Paginator->sort('accidentdate', '事故日'); ?></th>
									        <th><?php echo $this->Paginator->sort('users_id', '従業員'); ?></th>
									        <th><?php echo $this->Paginator->sort('carno', '車両'); ?></th>
									        <th><?php echo $this->Paginator->sort('treatmentflg', '扱い'); ?></th>
									        <th><?php echo $this->Paginator->sort('accidentplace', '現場・内容'); ?></th>
									        <th><?php echo $this->Paginator->sort('salarynote', '給与メモ'); ?></th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($accidenttrans as $accidenttran): ?>
									    <tr>
											<td><?php echo date('Y-m-d H:i',strtotime($accidenttran['Accidenttran']['accidentdate'])); ?></td>
									        <td><?php echo ($accidenttran['User']['usernameK']); ?></td>
											<td><?php echo ($accidenttran['Accidenttran']['carno']); ?></td>
											<td><?php echo ($treatmentflgs[$accidenttran['Accidenttran']['treatmentflg']]); ?></td>
											<td><?php echo ($accidenttran['Accidenttran']['accidentplace']); ?></td>
											<td><?php
												echo ($accidenttran['Accidenttran']['salarynote']);
											 ?></td>
									        <td>
												<?php echo $this->Html->link(('修正'),
														['action' => 'edit',$accidenttran['Accidenttran']['id']],
														['class'=>['btn btn-info']]); ?>
												<?php echo $this->Html->link(('印刷'),
														['action' => 'edit',$accidenttran['Accidenttran']['id']],
														['class'=>['btn btn-success']]); ?>
												<?php
													 echo $this->Form->postLink(('削除'),
														[
														'action' => 'delete', $accidenttran['Accidenttran']['id']
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
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
