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

			<!-- /受付一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>受付一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th><?php echo $this->Paginator->sort('receptiondate', '来社日時'); ?></th>
									        <th><?php echo $this->Paginator->sort('receptioncomment', '社名'); ?></th>
									        <th><?php echo $this->Paginator->sort('personalname', '名前'); ?></th>
									        <th><?php echo $this->Paginator->sort('receptioncomment', '社名2'); ?></th>
									        <th><?php echo $this->Paginator->sort('personalname', '名前2'); ?></th>
									        <th><?php echo $this->Paginator->sort('receptioncomment', '社名3'); ?></th>
									        <th><?php echo $this->Paginator->sort('personalname', '名前3'); ?></th>
									        <th></th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($receptionmessages as $receptionmessage): ?>
									    <tr>
									    	<td><?php echo ($receptionmessage['Receptionmessage']['receptiondate']); ?></td>
									    	<td><?php echo ($receptionmessage['Receptionmessage']['receptioncomment']); ?></td>
									    	<td><?php echo ($receptionmessage['Receptionmessage']['personalname']); ?></td>
									    	<td><?php echo ($receptionmessage['Receptionmessage']['receptioncomment2']); ?></td>
									    	<td><?php echo ($receptionmessage['Receptionmessage']['personalname2']); ?></td>
									    	<td><?php echo ($receptionmessage['Receptionmessage']['receptioncomment3']); ?></td>
									    	<td><?php echo ($receptionmessage['Receptionmessage']['personalname3']); ?></td>
											<td><?php echo $this->Html->link(('修正'), array('action' => 'edit',$receptionmessage['Receptionmessage']['id']), array('class'=>array('btn btn-info'))); ?><?php echo $this->Form->postLink(('削除'), ['action' => 'delete', $receptionmessage['Receptionmessage']['id']], ['confirm' => __('完全に削除しますか？'), 'class'=>['btn btn-danger']]); ?></td>
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
				<?php echo $this->Html->link(('新規登録'), array('action' => 'add'), array('class'=>array('btn btn-success'))); ?>
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
