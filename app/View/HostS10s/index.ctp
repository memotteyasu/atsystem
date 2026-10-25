<!-- 給与データ作成 -->
<!-- HostS10s -->
<div class="ch-container">
	<div class="row">

		<!-- left menu starts -->
<?php echo $this->element('HostSidemenu'); ?>
		<!-- left menu ends -->

		<div id="content" class="col-lg-10 col-sm-10">
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

			<div class="row">
				<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2>給与データ作成</h2>
						</div>
						<div class="box-content">

							<?php echo $this->Form->create(false, ['label' => false, 'div' => false, 'class' => false, 'novalidate' => true]); ?>

								<?php
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
										'default' => $paramsDateprev,
									]);
								?>
								月分の給与取り込みデータを作成します。
								<br><br>
								支給日：
								<?php
									echo $this->Form->input('selectym2', [
										'div'=>false,
										'label'=>false,
										'type' => 'date',
										'dateFormat' => 'YMD',
										'monthNames' => false,
										'minYear' => date('Y')-1,
										'maxYear' => date('Y')+1,
										'onchange'=>'this.form.submit()',
										'data-rel'=>'chosen',
										'style'=>'width:70px',
										'default' => $paramsDate,
									]);
								?>
							<?php echo $this->Form->end(); ?>

						</div>
					</div>
				</div>
			</div>

			<?php
				echo $this->Html->link(('<i class="glyphicon glyphicon-edit icon-white"></i>データ作成'), [
					'action' => 'csv',
					$paramsDate,
					$paramsDate2,
				], [
					'class' => 'btn btn-info',
					'escape' => false,
				]);
			?>

			<div class="row">
				<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2>給与確定状況</h2>
						</div>
						<div class="box-content">
						<?php foreach($salarytrans as $st){ ?>
							<?php echo date('Y年m月',strtotime($st['Salarytran']['salarydate'])); ?>分確定済み
							<?php echo '<br>' ?>
						<?php } ?>
						</div>
					</div>
				</div>
			</div>


			<?php
				echo $this->Html->link(('<i class="glyphicon glyphicon-edit icon-white"></i>指定の月の給与を確定します'), [
					'action' => 'salaryconfirm',
					$paramsDate,
					$paramsDate2,
				], [
					'class' => 'btn btn-danger',
					'escape' => false,
				]);
			?>

			<!-- content ends -->
		</div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
