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

        <noscript>
            <div class="alert alert-block col-md-12">
                <h4 class="alert-heading">Warning!</h4>

                <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
                    enabled to use this site.</p>
            </div>
        </noscript>
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

			<!-- /配車一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>
									<?php echo $this->Form->create(false); ?>
									<?php
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
									<?php
										echo $this->Form->input('productdetailmasters_id',[
											'label'=>false,
											'type'=>'select',
											'div'=>false,
											'class'=>false,
											'onchange'=>'this.form.submit()',
											//'data-rel'=>'chosen',
										]);
									?>

									<?php echo $this->Form->end(); ?>
						        </h2>
						    </div>
						    <div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<thead>
										<tr>
											<th></th>
											<th>オーダー名</th>
											<th>車両番号</th>
											<th>開始日時</th>
											<th>終了日時</th>
											<th></th>
										</tr>
									</thead>
									<?php for ($i = 0; $i <= 6; $i++): ?>
										<?php $date = new DateTime($paramsDate); ?>
										<thead>
											<tr>
												<th>
												<?php if ($i == 0) {
													echo $this->html->link($date->format('Y年m月d日').'('.$week[$date->format('w')].')',['action'=>'add',$date->format('Y-m-d')]);
												} else {
													echo $this->html->link($date->modify('+'.$i.' day')->format('Y年m月d日').'('.$week[$date->format('w')].')',['action'=>'add',$date->format('Y-m-d')]);
												} ?>
												</th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
											</tr>
										</thead>
										<?php foreach ($tests as $test): ?>
											<tbody>
												<?php if ($test['Gridtest']['start_date'] == $date->format('Y-m-d') or $test['Gridtest']['end_date'] == $date->format('Y-m-d')): ?>
													<?php if ($test['Gridtest']['sortflg'] != 0): ?>
														<tr>
															<td></td>
															<td>
																<?php echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']],['class' => 'sortflgcolor']); ?>
															</td>
															<td>
																<?php echo ($test['Carmaster']['plateno']); ?>
															</td>
															<td>
																<?php echo ($test['Gridtest']['start_date']); ?>
															</td>
															<td>
																<?php echo ($test['Gridtest']['end_date']); ?>
															</td>
															<td>
																<?php echo $this->Html->link(('修正'), ['action' => 'edit',$test['Gridtest']['id']], ['class'=>array('btn btn-info')]); ?>
																<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $test['Gridtest']['id']], ['confirm' => __('削除しますか？'), 'class'=>['btn btn-danger']]); ?>
															</td>
														</tr>
													<?php else: ?>
														<tr>
															<td></td>
															<td>
																<?php echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']]); ?>
															</td>
															<td>
																<?php echo ($test['Carmaster']['plateno']); ?>
															</td>
															<td>
																<?php echo ($test['Gridtest']['start_date']); ?>
															</td>
															<td>
																<?php echo ($test['Gridtest']['end_date']); ?>
															</td>
															<td>
																<?php echo $this->Html->link(('修正'), ['action' => 'edit',$test['Gridtest']['id']], ['class'=>array('btn btn-info')]); ?>
																<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $test['Gridtest']['id']], ['confirm' => __('削除しますか？'), 'class'=>['btn btn-danger']]); ?>
															</td>
														</tr>
													<?php endif; ?>
												<?php endif; ?>
											</tbody>
										<?php endforeach; ?>
									<?php endfor; ?>
								</table>
							</div>
					    </div>
				    </div>
			    </div>




			    <?php //echo $this->element('pager')?>

				<p>
				<?php echo $this->Html->link(('新規登録'), array('action' => 'add'), array('class'=>array('btn btn-success btn-sm'))); ?>
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->
