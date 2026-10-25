<div class="ch-container">
    <div class="row">
        <!-- left menu starts -->
		<?php echo $this->element('ClerkSidemenu'); ?>
        <!-- left menu ends -->
    <div id="content" class="col-lg-10 col-sm-10">
            <!-- content starts -->
				<ul class="breadcrumb">
					<li>
						<?php
						switch(true){
						  case (($user['Rolemaster']['id']>=10)and($user['Rolemaster']['id']<=19));
						     $this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
						     break;
						  case (($user['Rolemaster']['id']>=20)and($user['Rolemaster']['id']<=29));
						     $this->Html->addCrumb('Top', ['controller' => 'ClerkTops', 'action' => 'index']);
						     break;
						}
						echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
		<div class="row">
		    <div class="box col-md-12">
		        <div class="box-inner">
		            <div class="box-header well">
		                <h2><i class="glyphicon glyphicon-info-sign"></i> <?php echo $this->Html->link('来社予定登録',[
		                	'controller'=>'Re010s',
		                ]); ?></h2>

		            </div>
		            <div class="box-content row">

		                <div class="col-lg-12 col-md-12">
							<ul>
								<?php foreach($receptionmessages as $receptionmessage): ?>
									<li>
										<?php
											$date = new DateTime($receptionmessage['Receptionmessage']['receptiondate']);
											echo $date->format(h('Y年m月d日 H時i分'));
										?>
										<?php echo '　'; ?>
										<?php echo $receptionmessage['Receptionmessage']['receptioncomment']; ?>(<?php echo $receptionmessage['Receptionmessage']['personalname']; ?>) <?php //echo $this->Form->postLink('削除',['action'=>'deletereception',$receptionmessage['Receptionmessage']['id']],['class'=>'label label-danger']); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>

		            </div>
		        </div>
		    </div>
		</div>

		<div class="row">
		    <div class="box col-md-12">
		        <div class="box-inner">
		            <div class="box-header well">
		                <h2><i class="glyphicon glyphicon-info-sign"></i> 個人メモ</h2>

		            </div>
		            <div class="box-content row">

						<div class="col-lg-12 col-md-12">
							<ul>
								<?php foreach($personalmessages as $personalmessage): ?>
									<li>
										<?php //echo date('Y-m-d H:i', strtotime($personalmessage['Personalmessage']['created'])); ?>
										<?php
											$date = new DateTime($personalmessage['Personalmessage']['created']);
											echo $date->format(h('Y年m月d日'));
										?>
										<?php echo ' '; ?>
										<?php echo $personalmessage['Personalmessage']['personalcomment']; ?> <?php echo $this->Form->postLink('削除',['action'=>'delete',$personalmessage['Personalmessage']['id']],['class'=>'label label-danger']); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>

		            </div>
		        </div>
		    </div>
		</div>
		<div class="row">
		    <div class="box col-md-12">
		        <div class="box-inner">
		            <div class="box-header well" data-original-title="">
		                <h2><i class="glyphicon glyphicon-edit"></i> 個人メモ登録</h2>

		            </div>
					<div class="box-content">

						<div class="personalmessages form">
							<?php echo $this->Form->create('Personalmessage',[
								'url'=>[
									'controller'=>'ClerkTops',
									'action'=>'addpersonalmessage'
								],
								'label'=>false,
								'div'=>false,
								'class'=>'form-horizontal',
								'novalidate'=>true
							]); ?>
								<fieldset>
									<?php
										echo $this->Form->input('personalcomment',[
											'label'=>false,
											'div'=>false,
											'type'=>'text',
											'class'=>'form-control',
											'placeholder'=>'メモを入力',
											'style'=>'width:95%;margin-bottom:5px;',
										]);
									?>
								</fieldset>
								<?php echo $this->Form->submit('登録', ['div'=>false, 'class' => 'btn btn-default', 'name' => 'submit']); ?>
							<?php echo $this->Form->end(); ?>
						</div>

					</div>

				</div>
			</div>
		    <!--/span-->

		</div><!--/row-->

		<div class="row">
		    <div class="box col-md-12">
		        <div class="box-inner">
		            <div class="box-header well">
		                <h2><i class="glyphicon glyphicon-info-sign"></i> 業務メッセージ</h2>

		            </div>

		            <div class="box-content row">

		                <div class="col-lg-12 col-md-12">
							<ul>
								<?php foreach($gyoumumessages as $gyoumumessage): ?>
									<li>
										<?php //echo date('Y-m-d H:i', strtotime(h($gyoumumessage['Gyoumumessage']['created']))); ?>
										<?php
											$date = new DateTime($gyoumumessage['Gyoumumessage']['created']);
											echo $date->format(h('Y年m月d日'));
										?>
										<?php echo ' '; ?>
										<?php echo h($gyoumumessage['Gyoumumessage']['gyoumucomment']); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>

		            </div>

		        </div>
		    </div>
		</div>

		<div class="row">
		    <div class="box col-md-12">
		        <div class="box-inner">
		            <div class="box-header well">
		                <h2><i class="glyphicon glyphicon-info-sign"></i> 有休等申請</h2>

		            </div>

		            <div class="box-content row">

		                <div class="col-lg-12 col-md-12">
							<ul>
								<?php foreach($employeeholidays as $employeeholiday): ?>
									<?php
										$date = new DateTime($employeeholiday['Employeeholiday']['applicationdate']);
										$today = new DateTime();
										if ($date->format('Y-m-d') >= $today->format('Y-m-d')):
									?>
										<li>
											<?php echo $date->format(h('Y年m月d日')); ?>
											<?php echo ' '; ?>
											<?php echo $this->Html->link(h($employeeholiday['User']['usernameK']),['controller'=>'Host012s','action'=>'index']); ?> (<?php echo h($employeeholiday['Flgmaster']['flgname']); ?>)
										</li>
									<?php else: ?>
										<?php if ($employeeholiday['Flgmaster']['id'] == 42): ?>
											<li>
												<?php echo $date->format(h('Y年m月d日')); ?>
												<?php echo ' '; ?>
												<?php echo $this->Html->link(h($employeeholiday['User']['usernameK']),['controller'=>'Host012s','action'=>'index']); ?> (<?php echo h($employeeholiday['Flgmaster']['flgname']); ?>)
											</li>
										<?php endif; ?>
									<?php endif; ?>

									<!--
									<li>
										<?php
											$date = new DateTime($employeeholiday['Employeeholiday']['applicationdate']);
											echo $date->format(h('Y年m月d日'));
										?>
										<?php echo ' '; ?>
										<?php echo $this->Html->link(h($employeeholiday['User']['usernameK']),['controller'=>'Host012s','action'=>'index']); ?> (<?php echo h($employeeholiday['Flgmaster']['flgname']); ?>)
									</li>
									-->
								<?php endforeach; ?>
							</ul>
						</div>
		            </div>
		        </div>
		    </div>
		</div>


    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->
    <hr>

</div><!--/.fluid-container-->
<script type="text/javascript">
jQuery(function(){
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional[ "ja" ] );
	var dateFormat = 'yy/mm/dd';
	jQuery('.datepicker').datepicker({
		dateFormat: 'yy/mm/dd(DD)'
	});

	jQuery(document).ready(function(){
			jQuery.datetimepicker.setLocale('ja');
			jQuery('input.datetimepicker1').datetimepicker({
				format:'Y-m-d H:i',
				step:30,
			});
	});
});
</script>
