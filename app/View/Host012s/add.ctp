<div class="ch-container">
    <div class="row">

			<!-- left menu starts -->
				<?php echo $this->element('Sidemenu'); ?>
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
            <div class="box-header well">
                <h2><i class="glyphicon glyphicon-info-sign"></i> 有休等申請</h2>
            </div>
						    <div class="box-content">
							    <p class="h4">
							    ※有休等を申請したい日程を選択して、申請ボタンを押して下さい。<br>（受理されたものは下記明細にて確認してください）
							    </p>
								<?php echo $this->Form->create('Employeeholiday', ['label' => false, 'div' => false, 'class' => 'form-inline', 'novalidate' => true]); ?>
									有給取得希望日
									<?php
										echo $this->Form->input('users_id',array(
											'type'=>'select',
											'label'=>false,
											'div'=>false,
											'options'=>$users
										));
									?>
									<?php
										echo $this->Form->input('flgmasters_id2',[
											'label'=>false,
											'type'=>'select',
											'options'=>$flgmasters,
											'empty'=>'未選択',
											'div'=>false,
											'class'=>'',
										]);
									?>
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
											'default' => date('Y-m-d'),
											'separator' => array('年', '月', '日を'),
										));
									?><br>
				                    <?php echo $this->Form->submit('申請する', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']); ?>
								<?php echo $this->Form->end(); ?>

								<!--
								<div>
								    有給取得希望日　<select><option>2019</option></select>年
								    <select><option>01</option></select>月
								    <select><option>31</option></select>日を<input type="button" value="有休申請する">
							    </div>
							    -->

							</div>
				  	  </div>

					<?php
						//echo debug($employeeholidays);
					?>
					<div class="box-content">
						■有休取得状況
						<ul>
							<?php
								foreach($employeeholidays as $employeeholiday) {
									echo '<li>';
										$applicationdate = new DateTime($employeeholiday['Employeeholiday']['applicationdate']);
											$text = $employeeholiday['Flgmaster']['flgname'];
										echo ($applicationdate->format('Y年m月d日')).'　'.$text;
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
