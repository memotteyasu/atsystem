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

	<div id="content" class="col-lg-10 col-md-10 col-sm-10">
	<!-- content starts -->
		<div>
			<ul class="breadcrumb">
				<li>
					<?php echo $this->Html->link('Home',['controller'=>'HostTops','action'=>'index']); ?>
				</li>
				<li>
					<?php echo $this->Html->link('化学品配車週間',['action'=>'index']); ?>
				</li>
			</ul>
		</div>

		<!-- /荷主一覧 -->
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
									<th>車両番号</th>
										<?php
											$date = new DateTime($paramsDate);
											for ($i = 1; $i <= 7; $i++) {
												echo '<th>';
												if ($i == 1) {
													//echo $date->format('Y年m月d日').'('.$week[$date->format('w')].')';
													echo $this->html->link($date->format('Y年m月d日').'('.$week[$date->format('w')].')',['action'=>'add',$date->format('Y-m-d')]);
												} else {
													//echo $date->modify('+1 day')->format('Y年m月d日').'('.$week[$date->format('w')].')';
													echo $this->html->link($date->modify('+1 day')->format('Y年m月d日').'('.$week[$date->format('w')].')',['action'=>'add',$date->format('Y-m-d')]);
												}
											echo '</th>';
											}
										?>
								</tr>
							</thead>
							<tbody>
							<!--
							<?php for ($i = 1; $i <= 8; $i++) {
								echo '<tr>';
								echo '<td class="center droppable">'.$i.$i.$i.$i.'</td>';
								for ($n = 1; $n <= 7; $n++) {
									echo '<td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>';
								}
								echo '</tr>';
								}
							?>
							-->
							<?php foreach ($tests as $test): ?>
								<?php //echo debug($test); ?>
								<tr>
									<td>
										<?php echo ($test['Carmaster']['plateno']); ?>
										<?php
										/*
											foreach ($test as $key1 => $value) {
												foreach ($value as $key2 => $value2) {
								    				if (is_array($value2)) {
														foreach ($value2 as $key3 => $value3) {
															//echo debug($value3);
															echo ($value3['carno']).'</br>';
														}
													}
												}
											}
										*/
										?>
									</td>
									<td>
										<?php
											$date = new DateTime($paramsDate);
											//echo debug($a);
											if ($test['Gridtest']['start_date'] == $date->format('Y-m-d') or $test['Gridtest']['end_date'] == $date->format('Y-m-d')) {
												//echo debug($date->format('Y-m-d'));
												if ($test['Gridtest']['sortflg'] != 0) {
													echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']],['class' => 'sortflgcolor']);
												} else {
													echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']]);
												}
											}
										?>
									</td>
									<td>
										<?php
											if ($test['Gridtest']['start_date'] == $date->modify('+1 day')->format('Y-m-d') or $test['Gridtest']['end_date'] == $date->format('Y-m-d')) {
												if ($test['Gridtest']['sortflg'] != 0) {
													echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']],['class' => 'sortflgcolor']);
												} else {
													echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']]);
												}
											}
										?>
									</td>
									<td>
										<?php
											if ($test['Gridtest']['start_date'] == $date->modify('+1 day')->format('Y-m-d') or $test['Gridtest']['end_date'] == $date->format('Y-m-d')) {
												if ($test['Gridtest']['sortflg'] != 0) {
													echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']],['class' => 'sortflgcolor']);
												} else {
													echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']]);
												}
											}
										?>
									</td>
									<td>
										<?php
											if ($test['Gridtest']['start_date'] == $date->modify('+1 day')->format('Y-m-d') or $test['Gridtest']['end_date'] == $date->format('Y-m-d')) {
												if ($test['Gridtest']['sortflg'] != 0) {
													echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']],['class' => 'sortflgcolor']);
												} else {
													echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']]);
												}
											}
										?>
									</td>
									<td>
										<?php
											if ($test['Gridtest']['start_date'] == $date->modify('+1 day')->format('Y-m-d') or $test['Gridtest']['end_date'] == $date->format('Y-m-d')) {
												if ($test['Gridtest']['sortflg'] != 0) {
													echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']],['class' => 'sortflgcolor']);
												} else {
													echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']]);
												}
											}
										?>
									</td>
									<td>
										<?php
											if ($test['Gridtest']['start_date'] == $date->modify('+1 day')->format('Y-m-d') or $test['Gridtest']['end_date'] == $date->format('Y-m-d')) {
												if ($test['Gridtest']['sortflg'] != 0) {
													echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']],['class' => 'sortflgcolor']);
												} else {
													echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']]);
												}
											}
										?>
									</td>
									<td>
										<?php
											if ($test['Gridtest']['start_date'] == $date->modify('+1 day')->format('Y-m-d') or $test['Gridtest']['end_date'] == $date->format('Y-m-d')) {
												if ($test['Gridtest']['sortflg'] != 0) {
													echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']],['class' => 'sortflgcolor']);
												} else {
													echo $this->Html->link($test['Gridtest']['name'], ['controller' => 'Gridtests', 'action' => 'edit', $test['Gridtest']['id']]);
												}
											}
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

		<?php //echo $this->element('pager')?>

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
