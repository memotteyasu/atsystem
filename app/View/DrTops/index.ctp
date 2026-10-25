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
            <?php echo $this->Flash->render(); ?>
        </li>
    </ul>
</div>
<?php $datachk=0; ?>

<div class="row">
    <div class="box col-md-12">
		<?php
		foreach($ordertrans as $ordertran){
		 ?>
        <div class="box-header well">
            <h2><?php echo substr($ordertran['Ordertran']['loadingdate'],-5); ?> <?php echo substr($ordertran['Ordertran']['loadingtime'],0,5); ?> <?php echo $ordertran['Workplace1']['workplaceshortname']; ?> → <?php echo substr($ordertran['Ordertran']['deliverydate'],-5); ?> <?php echo substr($ordertran['Ordertran']['deliverytime'],0,5); ?> <?php echo $ordertran['Workplace2']['workplaceshortname']; ?></h2>
        </div>
		<div class="box-content">
		 <table class="table">
		 	<?php
		 	foreach($ordertran['Processtran'] as $processtran){
		 		//日付が当日以降のものを表示
		 		if($processtran['processstartdate'] >= $wdate){
		 			$datachk = 1;
			 		//作業場所マスタからデータを取得
			 		$workplace = ClassRegistry::init('Workplace');
			 		$workplacedata = [];
			 		$workplacedata = $workplace->find('first',[
			 			'fields'=>[
			 				'Workplace.workplaceshortname'
			 			],
			 			'conditions'=>[
			 				'Workplace.id'=>$processtran['workplaces_id']
			 			],
			 			'recursive'=>0
			 		]);
			 		//ユーザーマスタからデータを取得
			 		$user = ClassRegistry::init('User');
			 		$userdata = [];
			 		$userdata = $user->find('first',[
			 			'fields'=>[
			 				'User.usernameK'
			 			],
			 			'conditions'=>[
			 				'User.id'=>$processtran['pdriver1id']
			 			],
			 			'recursive'=>0
			 		]);
			 		//商品コンテナマスタからデータを取得
			 		$productcontainer = ClassRegistry::init('Productcontainer');
			 		$pcdata = [];
			 		$pcdata = $productcontainer->find('first',[
			 			'fields'=>[
			 				'Productcontainer.pdconname'
			 			],
			 			'conditions'=>[
			 				'Productcontainer.id'=>$processtran['productcontainers_id']
			 			],
			 			'recursive'=>0
			 		]);
			 		//作業マスタからデータを取得
			 		$workdetailmaster = ClassRegistry::init('Workdetailmaster');
			 		$wddata = [];
			 		$wddata = $workdetailmaster->find('first',[
			 			'fields'=>[
			 				'Workdetailmaster.workname'
			 			],
			 			'conditions'=>[
			 				'Workdetailmaster.id'=>$processtran['workdetailmasters_id']
			 			],
			 			'recursive'=>0
			 		]);
			 	?>
			 		<tr>
			 		<td><?php echo substr($processtran['processstartdate'],-5); ?><br><?php echo substr($processtran['processstarttime'],0,5); ?></td><td><?php
			 		if($userdata){
			 			echo $userdata['User']['usernameK'];
			 		}else{
			 			echo ' ';
			 		}
			 		 ?></td><td><?php
			 		 if($wddata){
			 		 	echo $wddata['Workdetailmaster']['workname'];
			 		 }else{
			 		 	echo ' ';
			 		 }
			 		  ?></td><td><?php echo $this->html->link($workplacedata['Workplace']['workplaceshortname'],['controller'=>'Dr060s','action'=>'index',$processtran['workplaces_id']]); ?></td><td><?php echo $processtran['workddetailcomment']; ?></td>
			 		</tr>
			 		<?php if(($processtran['container_no'])or($processtran['ref_no'])or($pcdata)){ ?>
					 <tr style="color:#ff6347;">
					 <?php
					 if(($userdata)&&($processtran['container_no'])){
					 	echo '<td colspan="2">コンテナ：';
					 	echo $processtran['container_no'];
					 }else{
					 	echo ' ';
					 } ?></td><td colspan="2"><?php
					 if(($userdata)&&($processtran['ref_no'])){
						 echo $processtran['ref_no'];
					 }else{
					 	echo ' ';
					 }
					  ?></td><td><?php
					  if(($userdata)&&($pcdata)){
					  	echo $pcdata['Productcontainer']['pdconname'];
					  }else{
					  	echo ' ';
					  }
					   ?></td>
					 </tr>
					 <?php } ?>
		 	<?php
				}
		 	}
		 	?>
		 </table>
		</div>
		<?php
		}
		?>
		<?php
		if(($confirmchk=='OK')&&($datachk==1)){
			echo $this->Html->link('運行指示書・日報印刷及び承諾',['action'=>'pdf',$wdate],['class'=>'btn-success','target'=>'blank']);
		}else{
			if($datachk==0){
				//明細なしは空で印刷する
					echo $this->Html->link('日報印刷（データなし）',['action'=>'pdf23',$wdate],['class'=>'btn-success','target'=>'blank']);
			}else{
				echo 'まだ確定していません';
			}
		}
		 ?>

	</div>
</div>

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
<!-- 個人用 -->
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
								<?php echo $personalmessage['Personalmessage']['personalcomment']; ?> <?php echo $this->Form->postLink('削除',array('action'=>'delete',$personalmessage['Personalmessage']['id']),array('class'=>'label label-danger')); ?>
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
					<?php //echo $this->Form->create('Personalmessage',array('url'=>array('controller'=>'DrTops','action'=>'addpersonalmessage'))); ?>
					<?php echo $this->Form->create('Personalmessage',[
						'url'=>[
							'controller'=>'DrTops',
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
								]
							);
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


    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->


    <hr>

</div><!--/.fluid-container-->


