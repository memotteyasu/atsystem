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

			<?php echo $this->Form->create(false, ['label' => false, 'div' => false, 'class' => false, 'novalidate' => true]); ?>
					<?php
						echo $this->Form->input('selectym1', [
							'div'=>false,
							'label'=>false,
							'type' => 'date',
							'dateFormat' => 'YM',
							'monthNames' => false,
							'minYear' => date('Y')-4,
							'maxYear' => date('Y')+1,
							'onchange'=>'this.form.submit()',
							'data-rel'=>'chosen',
							'style'=>'width:70px',
							'default' => $paramsDate,
						]);
					?>
					を表示
				<?php echo $this->Form->end(); ?>
			<!-- 拘束時間一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>拘束時間一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th><?php echo '従業員'; ?></th>
									        <th><?php echo '最大拘束'; ?></th>
									        <th><?php echo '拘束時間'; ?></th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($users as $user): ?>
									    <tr>
									        <td><?php echo $user['User']['usernameK']; ?></td>
											<?php
											$i=0;
											$over_kousoku = '';
											$total_kousoku = '00:00:00';
											foreach($user['Kintaitran'] as $kintai){
												if($kintai['roumu_kousoku'] > '16:00:00'){
													if($over_kousoku){
														$over_kousoku = $over_kousoku.','.$kintai['roumu_kousoku'];
													}else{
														$over_kousoku = $kintai['roumu_kousoku'];
													}
												}
												$total_kousoku = $this->Format->Addtime($total_kousoku,$kintai['roumu_kousoku']);
												$i++;
											}
											echo '<td>'.$over_kousoku.'</td>';
											if($i>0){
												if(substr('000'.$total_kousoku,0,6) > '293:00'){
													echo '<td class="text-danger"><b>'.$total_kousoku.'</b></td>';
												}else{
													echo '<td>'.$total_kousoku.'</td>';
												}
											}else{
												echo '<td></td>';
											}
											 ?>
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
