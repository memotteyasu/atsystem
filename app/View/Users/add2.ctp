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
						<?php
							$this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
							$this->Html->addCrumb('社員マスタ一覧', ['controller' => 'Users', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /積地修正 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
					    <div class="box-header well">
					        <h2>ユーザーマスタ登録</h2>
					    </div>

						<?php echo $this->Form->create('User',['label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true]); ?>

							<br>
							<div class="form-group">
								<label class="col-sm-2 control-label">ユーザーID</label>
								<div class="col-sm-3">
									<?php echo $this->Form->input('username',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">パスワード</label>
								<div class="col-sm-3">
									<?php echo $this->Form->input('password',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">社内用社員コード</label>
								<div class="col-sm-3">
									<?php echo $this->Form->input('syain_cd',['label'=>false, 'div'=>false, 'class'=>'form-control']); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">会社</label>
								<div class="col-sm-3">
									<?php echo $this->Form->input('kaisyas_id',['label'=>false,'div'=>false, 'class'=>'form-control', 'empty'=>'選択してください']); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">営業所</label>
								<div class="col-sm-3">
									<?php echo $this->Form->input('branchmasters_id',['label'=>false,'div'=>false, 'class'=>'form-control', 'empty'=>'選択してください']); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">部署</label>
								<div class="col-sm-3">
									<?php echo $this->Form->input('departmentmasters_id',['label'=>false,'div'=>false, 'class'=>'form-control', 'empty'=>'選択してください']); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">権限</label>
								<div class="col-sm-3">
									<?php echo $this->Form->input('rolemasters_id',['label'=>false,'div'=>false, 'class'=>'form-control', 'empty'=>'選択してください']); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">名前</label>
								<div class="col-sm-3">
									<?php echo $this->Form->input('usernameK',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">名前カナ</label>
								<div class="col-sm-3">
									<?php echo $this->Form->input('usernameKK',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">Email</label>
								<div class="col-sm-3">
									<?php echo $this->Form->input('useremail',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">在籍状況</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											$list = ['0' => '在籍', '1' => '退職'];
											echo $this->Form->input('zaisekiflg',[
												'type' => 'select',
												'options' => $list,
												'label' => false,
												'div' => false,
												'class' => 'form-control',
												'empty'=>'選択してください'
											]);
										?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">労働区分</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											$list = ['1' => '正社員', '2' => '嘱託', '3' => 'パート', '4' => '季節(臨時)', '5' => 'バイト', '9' => '協力会社'];
											echo $this->Form->input('roudouflg',[
												'type' => 'select',
												'options' => $list,
												'label' => false,
												'div' => false,
												'class' => 'form-control',
												'empty'=>'選択してください'
											]);
										?>
									</div>
								</div>
							</div>
							<!-- 2021-01-08 buairankをやめて、キロ単価とする
							<div class="form-group">
								<label class="col-sm-2 control-label">歩合ランク</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											$list = ['1' => 'A', '2' => 'B', '3' => 'C', '4' => 'D', '5' => 'E'];
											echo $this->Form->input('buairank',[
												'type' => 'select',
												'options' => $list,
												'label' => false,
												'div' => false,
												'class' => 'form-control',
												'empty'=>'選択してください'
											]);
										?>
									</div>
								</div>
							</div>
							-->
							<div class="form-group">
								<label class="col-sm-2 control-label">基礎単価(円)</label>
								<div class="col-sm-2">
									<?php echo $this->Form->input('basetanka',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">電話当番(時間)</label>
								<div class="col-sm-2">
									<?php echo $this->Form->input('telhour',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">規定普通残業(時間)</label>
								<div class="col-sm-2">
									<?php echo $this->Form->input('zangyohour',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">普通乗務残業(時間)</label>
								<div class="col-sm-2">
									<?php echo $this->Form->input('jyoumuhour',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">深夜乗務残業(時間)</label>
								<div class="col-sm-2">
									<?php echo $this->Form->input('jyoumushinyahour',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">資格歩合(時間)</label>
								<div class="col-sm-2">
									<?php echo $this->Form->input('shikakuhour',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">有休規定時間</label>
								<div class="col-sm-2">
									<?php echo $this->Form->input('yuukyuhour',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
								</div>
									<small>(※ex 7.75)</small>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">残業計算時間</label>
								<div class="col-sm-2">
									<?php
										echo $this->Form->input('zangyocounttime', [
											'label' => false,
											'type' => 'text',
											'div' => false,
											'class' => 'form-control timepicker',
											'placeholder' => '07:45',
										]);
									?>
								</div>
							</div>
<!-- 2021-01-08 評価履歴としてusersevaluationmasterに切り替える
							<div class="form-group">
								<label class="col-sm-2 control-label">走行キロ単価(円)</label>
								<div class="col-sm-3">
									<?php echo $this->Form->input('kirotanka',['label'=>false,'div'=>false, 'class'=>'form-control']); ?>
								</div>
							</div>
-->
							<div class="form-group">
								<label class="col-sm-2 control-label">カレンダー種類</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
//											$list = ['1' => 'A', '2' => 'B', '3' => 'C', '4' => 'D', '5' => 'E', '6' => 'F'];
											$list = ['1' => '全出勤', '2' => 'A班', '3' => 'B班', '4' => 'タイプ1', '5' => 'タイプ2', '6' => 'タイプ3'];
											echo $this->Form->input('calendarflg',[
												'type' => 'select',
												'options' => $list,
												'label' => false,
												'div' => false,
												'class' => 'form-control',
												'empty'=>'選択してください'
											]);
										?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">給与区分</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											$list = ['1' => '月給', '2' => '日給月給', '3' => '日給', '4' => '時給'];
											echo $this->Form->input('salaryflg',[
												'type' => 'select',
												'options' => $list,
												'label' => false,
												'div' => false,
												'class' => 'form-control',
												'empty'=>'選択してください'
											]);
										?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">マルチ乗務員</label>
								<div class="form-inline">
									<div class="col-sm-4">
										<?php
											$list = ['0' => '無し', '1' => 'マルチ', '2' => '朝日・明治兼任'];
											echo $this->Form->input('multidriver',[
												'type' => 'select',
												'options' => $list,
												'label' => false,
												'div' => false,
												'class' => 'form-control',
												'empty'=>'選択してください'
											]);
										?>
										<small>※所属に関係ない者</small>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">入社日</label>
								<div class="col-sm-3">
									<?php
										$ddate = date('Y-m-d');
										echo $this->Form->input('realentrydate',[
										'label'=>false,
										'type'=>'text',
										'div'=>false,
										'default'=>$ddate,
										'class'=>'datepicker1 form-control'
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label text-danger">バイト開始日</label>
								<div class="col-sm-3">
									<?php
										$ddate = date('Y-m-d');
										echo $this->Form->input('entrydate',[
										'label'=>false,
										'type'=>'text',
										'div'=>false,
										'default'=>$ddate,
										'class'=>'datepicker1 form-control'
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">退職日</label>
								<div class="col-sm-3">
									<?php
										echo $this->Form->input('enddate',[
										'label'=>false,
										'type'=>'text',
										'div'=>false,
										'class'=>'datepicker2 form-control'
										]);
									?>
								</div>
							</div>
							<hr>
							<div class="form-group">
								<label class="col-sm-2 control-label">油種（自家用)</label>
								<div class="col-sm-3">
									<?php
										$selectyusyu = ['0'=>'徒歩','1'=>'レギュラー','2'=>'軽油','3'=>'電気','4'=>'水素','5'=>'自転車'];
										echo $this->Form->input('yusyu',[
										'label'=>false,
										'type'=>'select',
										'div'=>false,
										'options'=>$selectyusyu,
										'empty'=>'選択してください',
										'class'=>'form-control'
										]);
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">燃費</label>
								<div class="col-sm-3">
									<?php
										echo $this->Form->input('nenpi',[
										'label'=>false,
										'type'=>'number',
										'div'=>false,
										'class'=>'form-control'
										]);
									?>
								</div>
								<small>エンジン車は１リットルの燃費(km)、電気自動車は電費(wh)、水素自動車はグラム数(g)</small>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">片道(km)</label>
								<div class="col-sm-3">
									<?php
										echo $this->Form->input('katamichi',[
										'label'=>false,
										'type'=>'number',
										'div'=>false,
										'class'=>'form-control'
										]);
									?>
								</div>
							</div>


						    <hr>
						    <div class="form-group">
						      <div class="col-sm-offset-2 col-sm-10">
							    <?php echo $this->Form->submit('登録', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']); ?>
							    <?php echo $this->Html->link(('キャンセル'), ['action' => 'index'], ['class'=>['btn btn-danger']]); ?>
						      </div>
						    </div>

				    	<?php echo $this->Form->end(); ?>
					</div>
			    </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<?php echo $this->Html->script(['jQuery.datepicker.js']); ?>
