<div class="ch-container">
    <div class="row">
        <!-- left menu starts -->
		<?php echo $this->element('PartnerSidemenu'); ?>
        <!-- left menu ends -->
    <div id="content" class="col-lg-10 col-sm-10">
            <!-- content starts -->
				<ul class="breadcrumb">
					<li>
						<?php
						switch(true){
						  case ($user['Rolemaster']['id']==29);
						     $this->Html->addCrumb('Top', ['controller' => 'PartnerTops', 'action' => 'index']);
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
									'controller'=>'PartnerTops',
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

