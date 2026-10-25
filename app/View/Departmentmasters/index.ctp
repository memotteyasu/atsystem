<!-- 部門マスタ -->
<!-- Departmentmaster -->
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

			<!-- /部門一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>部門マスタ一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th>部門名</th>
									        <th>距離歩合単価</th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($departmentmasters as $departmentmaster): ?>
									    <tr>
									        <td><?php echo ($departmentmaster['Departmentmaster']['departmentname']); ?></td>
											<td><?php echo ($departmentmaster['Departmentmaster']['kirotanka']); ?></td>
									        <td>
												<?php echo $this->Html->link(('修正'), ['action' => 'edit',$departmentmaster['Departmentmaster']['id']], ['class'=>['btn btn-info']]); ?>
											</td>
									    </tr>
									    <?php endforeach; ?>
								    </tbody>
								</table>
							</div>
					    </div>
				    </div>
			    </div>

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
