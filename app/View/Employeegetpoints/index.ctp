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
        <!-- left menu ends -->

		<div id="content" class="col-lg-10 col-md-10 col-sm-10">
			<!-- content starts -->
			            <div>
						    <ul class="breadcrumb">
						        <li>
						            <?php echo $this->Flash->render(); ?>
						        </li>
						    </ul>
			            </div>

			<?php //echo $this->element('searchForm', ['model' => 'Employeegetpoint', 'url' => ['controller'=>'Employeegetpoints','action'=>'index'], 'desc' => '名を入力', 'input' => 'sendeduserid']); ?>

			<!-- /積地一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>ポイント履歴一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									    	<th><?php echo $this->Paginator->sort('users_id', '受信者'); ?></th>
									    	<th><?php echo $this->Paginator->sort('getdate', 'ポイント獲得日'); ?></th>
									        <th><?php echo $this->Paginator->sort('sendeduserid', '送信者'); ?></th>
									        <th><?php echo $this->Paginator->sort('point', '送信ポイント'); ?></th>
									        <th><?php echo $this->Paginator->sort('sendreason', '受信メッセージ'); ?></th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($employeegetpoints as $employeegetpoint): ?>
									    <tr>
									    	<td><?php echo ($employeegetpoint['User']['usernameK']); ?></td>
									    	<td>
									    		<?php //echo ($employeegetpoint['Employeegetpoint']['getdate']); ?>
									    		<?php
									    			$getdate = new DateTime($employeegetpoint['Employeegetpoint']['getdate']);
									    			echo $getdate->format('Y-m-d H:i');
									    		?>
									    	</td>
									        <td><?php echo ($employeegetpoint['SendedUser']['usernameK']); ?></td>
									        <td><?php echo ($employeegetpoint['Employeegetpoint']['point']); ?></td>
									        <td><?php echo ($employeegetpoint['Employeegetpoint']['sendreason']); ?></td>
									        <td>
												<?php echo $this->Html->link(('修正'), ['action' => 'edit',$employeegetpoint['Employeegetpoint']['id']], ['class'=>['btn btn-info']]); ?>
												<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $employeegetpoint['Employeegetpoint']['id']], ['confirm' => __('削除しますか？'), 'class'=>['btn btn-danger']]); ?>
											</td>
									    </tr>
									    <?php endforeach; ?>
								    </tbody>
								</table>
							</div>
					    </div>
				    </div>
			    </div>

			    <?php echo $this->element('pager')?>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
