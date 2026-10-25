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
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!--  -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2><i class="glyphicon glyphicon-info-sign"></i>sample</h2>
							</div>

							<div class="box-content">
								<?php
									for ($i = 0; $i <= 6; $i++) {
										echo $array4[$i];
										echo '　:　';
										echo $array[$i];
										echo '<br>';
									}
								?>

								<hr>

								<?php
									echo $dom['.innerDate > div > dl'];
									/*
									$test = $dom['.innerDate > div > dl > dd > strong']->text();
									$array = explode("\n", $test); // とりあえず行に分割
									$array = array_map('trim', $array); // 各行にtrim()をかける
									$array = array_filter($array, 'strlen'); // 文字数が0の行を取り除く
									$array = array_values($array); // これはキーを連番に振りなおしてるだけ
									debug($array);

									$test2 = $dom['.innerDate > div > dl > dt']->text();
									$array = explode("\n", $test2); // とりあえず行に分割
									$array = array_map('trim', $array); // 各行にtrim()をかける
									$array = array_filter($array, 'strlen'); // 文字数が0の行を取り除く
									$array = array_values($array); // これはキーを連番に振りなおしてるだけ
									debug($array);
									*/
								?>

							</div>
						</div>
					</div>
				</div>
			<!-- -->

			<!-- content ends -->
		</div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->