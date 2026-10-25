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

			<?php echo $this->element('searchForm', ['model' => 'Employeemaster', 'url' => ['controller'=>'Employeemasters','action'=>'index'], 'desc' => '社員名を入力', 'input' => 'employeename']); ?>

			<!-- /積地一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>社員マスタ一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th><?php echo $this->Paginator->sort('employeename', '名前'); ?></th>
									        <th><?php echo $this->Paginator->sort('namecana', '読み仮名'); ?></th>
									        <th><?php echo $this->Paginator->sort('entrydate', '入社日'); ?></th>
									        <th><?php echo $this->Paginator->sort('startdate', '適用開始日'); ?></th>
									        <th><?php echo $this->Paginator->sort('enddate', '適用終了日'); ?></th>
									        <th><?php echo $this->Paginator->sort('branchmasters_id', '部門'); ?></th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($employeemasters as $employeemaster): ?>
									    <tr>
									        <td><?php echo ($employeemaster['Employeemaster']['employeename']); ?></td>
									        <td><?php echo ($employeemaster['Employeemaster']['namecana']); ?></td>
									        <td><?php echo ($employeemaster['Employeemaster']['entrydate']); ?></td>
											<td><?php echo ($employeemaster['Employeemaster']['startdate']); ?></td>
											<td><?php echo ($employeemaster['Employeemaster']['enddate']); ?></td>
											<td><?php echo ($employeemaster['Branchmaster']['branchname']); ?></td>
									        <td>
												<?php echo $this->Html->link(('修正'), ['action' => 'edit',$employeemaster['Employeemaster']['id']], ['class'=>['btn btn-info']]); ?>
												<?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $employeemaster['Employeemaster']['id']], ['confirm' => __('削除しますか？'), 'class'=>['btn btn-danger']]); ?>
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

				<p>
				<?php echo $this->Html->link(('新規登録'), ['action' => 'add'], ['class'=>['btn btn-success']]); ?>
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->
