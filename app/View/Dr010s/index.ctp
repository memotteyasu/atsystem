<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
<?php echo $this->element('DriverSidemenu'); ?>
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
			<!-- 有給申請 -->
			<div class="row">
				<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">有休等申請（残数：<?php echo $user_holidayzan; ?>日）
						    </div>
						    <div class="box-content">
							    <p class="h4">
							    ※日付と該当の項目を選択して、申請ボタンを押して下さい。<br>（5日以内の日付については担当者に直接問い合わせください）
							    </p>

								<?php echo $this->Form->create('Employeeholiday', [
								'type' => 'post',
								'label' => false,
								'div' => false,
								'class' => 'form-inline',
								'novalidate' => true,
								'onsubmit' => 'return confirm("有給申請を行います。よろしいですか？");'
								]); ?>
									対象希望日
									<?php
										echo $this->Form->input('applicationdate', array(
											'type' => 'date',
											'label' => false,
											'div' => false,
											'dateFormat' => 'YMD',
											'monthNames' => false,
											'minYear' => date('Y')-1,
											'maxYear' => date('Y')+1,
											'empty' => false,
											'default' => date('Y-m-d',strtotime("+5 day")),
											'separator' => array('年', '月', '日を'),
										));
									?><br>
									<?php
										echo $this->Form->input('flgmasters_id2',[
											'label'=>false,
											'type'=>'select',
											'options'=>$flgmasters,
											'empty'=>'未選択',
											'div'=>false,
											'class'=>'form-control',
										]);
									?>
				                    <?php echo $this->Form->submit('申請する', ['div'=>false, 'class' => 'btn btn-info btn-sm', 'name' => 'submit']); ?>
								<?php echo $this->Form->end(); ?>


							</div>
				  	  </div>

					<div class="box-content">
						■申請状況
						<ul>
							<?php
								foreach($employeeholidays as $employeeholiday) {
									echo '<li>';
										$applicationdate = new DateTime($employeeholiday['Employeeholiday']['applicationdate']);
											$text = $employeeholiday['Flgmaster']['flgname'];
											$text2 = $employeeholiday['Flgmaster2']['flgname'];
										echo ($applicationdate->format('Y年m月d日')).'　'.$text2.'　('.$text.')　';
										echo $this->Html->link(('削除'), ['controller' => 'Dr010s', 'action' => 'delete',$employeeholiday['Employeeholiday']['id']],
										['confirm'=>'この有給申請を削除してもいいですか？']
										);
									echo '</li>';
								}
							?>
						</ul>
						<ul>
							<?php
								foreach($kintaitrans as $kintaitran) {
									echo '<li>';
										$kintaidate = new DateTime($kintaitran['Kintaitran']['kintaidate']);
										echo ($kintaidate->format('Y年m月d日')).'　取得済み';
									echo '</li>';
								}
							?>
						</ul>
					</div>
				</div>
			</div>
			<!-- /有給申請 -->



    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->

</div><!--/.fluid-container-->
