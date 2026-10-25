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
					'style'=>'width:70px',
					'default' => $paramsDate,
				]);
				echo $this->Form->end();
		?>

			<!-- カレンダー一覧 -->
				<div class="row">
					<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="box-inner">
							<div class="box-header well">
								<h2>カレンダーマスタ一覧</h2>
							</div>
							<div class="box-content">
								<table class="table table-bordered table-striped" id="table01">
									<tbody>
										<?php echo $this->Html->tableHeaders(array_values($header)); ?>
										<?php echo $this->Html->tableCells($calendar,array(),array(),true); ?>
									</tbody>
								</table>
								<!-- 基準日数 <?php echo $this->Form->input('baseday',[
											'type'=>'text',
											'div'=>false,
											'label'=>false,
											'class'=>'input-control',
											'style'=>'width:70px;',
										]); ?>日 -->
								<div>※上記内容で指定月のカレンダーを全タイプ(全出勤・A班・B班・タイプ1・タイプ2・タイプ3)作成します。
								</div>
								<?php echo $this->Form->postLink(('全タイプ作成'), ['action' => 'alladd', $paramsDate], ['confirm' => __('全タイプを作成しますか？'), 'class'=> 'btn btn-success']); ?>
								<?php //echo $this->Form->postLink(('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 個人データ全削除'), ['action' => 'alldelete',$paramsDate], ['confirm' => __("個人のカレンダーを一括で削除します。\n再度、個別でカレンダーを編集してください"), 'class' =>'btn btn-danger', 'escape' => false]); ?>
<!--
								<div>※上記内容で指定月のカレンダーを全員分作成します。（作成後は、社員マスタより個別に修正してください）
									<?php //echo $this->Form->submit('全員分作成',array('name'=>'calendarsubmit','class'=>'btn btn-danger')); ?>
								</div>
								<?php echo $this->Form->postLink(('全員分作成'), ['action' => 'alladd', $paramsDate], ['confirm' => __('全員分を作成しますか？'), 'class'=> 'btn btn-success']); ?>
								<?php echo $this->Form->postLink(('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 個人データ全削除'), ['action' => 'alldelete',$paramsDate], ['confirm' => __("個人のカレンダーを一括で削除します。\n再度、個別でカレンダーを編集してください"), 'class' =>'btn btn-danger', 'escape' => false]); ?>
-->
							</div>
						</div>

					</div>
				</div>

			<!-- /カレンダー一覧 -->


			<!-- content ends -->
		</div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
