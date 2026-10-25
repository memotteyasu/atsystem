<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
			<?php echo $this->element('Sidemenu'); ?>
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
							$this->Html->addCrumb('積地マスタ一覧', ['controller' => 'HostLoadingmasters', 'action' => 'index']);
							$loadingname = $loadingname['Loadingmaster']['loadingname'];
							$this->Html->addCrumb('積地【'.$loadingname.'】', ['controller' => 'HostLoadingmasters', 'action' => 'edit', $this->params['pass'][0]]);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<!-- /納入先一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>画像詳細一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th>画像</th>
									        <th>コメント</th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($loadingimages as $image): ?>
									    <tr>
									        <td>
												<div class="col-sm-2">
									        	<?php
													echo $this->Html->link(
														$this->Html->image('/loadingimages/resize/'.$image['Loadingmasterimage']['id'].'_'.$image['Loadingmasterimage']['img_file_name'], [
															'alt' => $image['Loadingmasterimage']['imgcomment'],
															'class' => 'img-responsive img-thumbnail'
														]),
														'/loadingimages/original/'.$image['Loadingmasterimage']['id'].'_'.$image['Loadingmasterimage']['img_file_name'],
														['escape' => false, 'target'=>'_blank']
													);
												?>
												</div>
									        </td>
									        <td><?php echo ($image['Loadingmasterimage']['imgcomment']); ?></td>
									        <td>
									        	<?php
									        		echo $this->Html->link(('コメント編集'), [
									        			'action' => 'imageedit',
									        			$image['Loadingmasterimage']['id']
									        		], [
									        			'class' => 'btn btn-info'
									        		]);
												?>
												<?php
													echo $this->Form->postLink(('削除'), [
														'action' => 'imagedelete',
														$image['Loadingmasterimage']['id'],
														$image['Loadingmaster']['id']
													], [
														'confirm' => __('削除しますか？'),
														'class' => 'btn btn-danger'
													]);
												?>
											</td>
									    </tr>
									    <?php endforeach; ?>
								    </tbody>
								</table>
							</div>
					    </div>
				    </div>
			    </div>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
