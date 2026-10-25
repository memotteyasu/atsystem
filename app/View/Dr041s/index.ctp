<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
<?php echo $this->element('DriverSidemenu'); ?>
        <!-- left menu ends -->

        <noscript>
            <div class="alert alert-block col-md-12">
                <h4 class="alert-heading">Warning!</h4>

                <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
                    enabled to use this site.</p>
            </div>
        </noscript>

        <div id="content" class="col-lg-10 col-sm-10">
            <!-- content starts -->
            <div>
    <ul class="breadcrumb">
        <li>
            <?php echo $this->Html->link('Home',['controller'=>$role.'Tops','action'=>'index']); ?>
        </li>
    </ul>
</div>

<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well">
                <h2>アサヒコイン履歴</h2>
            </div>
            <div class="box-content row">
                <div class="col-lg-12 col-md-12">
                運転手：<?php echo $users['User']['usernameK']; ?>
                <br>
                アサヒコイン残高：<?php echo $users['Employeepoint']['pointzan']; ?>pt
                <hr>
				受け取ったアサヒコイン
				<table class="table table-striped">
	                <?php foreach ($employeegetpoints as $employeegetpoint): ?>
	                	<?php //echo debug($employeegetpoint); ?>
		                <tr>
		                	<td>
		                		<?php //echo $employeegetpoint['Employeegetpoint']['getdate']; ?>
		                		<?php
									$getdate = new DateTime($employeegetpoint['Employeegetpoint']['getdate']);
									echo $getdate->format('Y-m-d H:i');
								?>
		                	</td>
		                	<td><?php echo $employeegetpoint['Employeegetpoint']['point']; ?>pt <?php echo $employeegetpoint['SendedUser']['usernameK']; ?>さんに貰った</td>
						</tr>
	                <?php endforeach; ?>
				</table>
				<br>
				相手に付与したアサヒコイン
				<table class="table table-striped">
					<?php foreach ($employeegetpoint2s as $employeegetpoint2): ?>
						<tr>
							<td>
								<?php //echo $employeegetpoint2['Employeegetpoint']['getdate']; ?>
								<?php
									$getdate = new DateTime($employeegetpoint2['Employeegetpoint']['getdate']);
									echo $getdate->format('Y-m-d H:i');
								?>
							</td>
							<td><?php echo $employeegetpoint2['Employeegetpoint']['point']; ?>pt <?php echo $employeegetpoint2['User']['usernameK']; ?>さんに贈った</td>
						</tr>
					<?php endforeach; ?>
				</table>
				<?php echo $this->Html->link(('アサヒコイン付与'), ['controller'=>'Dr040s', 'action'=>'index'], ['class'=>['btn btn-default']]); ?>
                </div>
            </div>
        </div>
    </div>
</div>



    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->

</div><!--/.fluid-container-->


