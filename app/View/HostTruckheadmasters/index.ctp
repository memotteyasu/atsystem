<!-- 車両ヘッドマスタ -->
<!-- HostTruckheadmasters -->
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

			<?php
				echo $this->Form->create('Carheadmaster', [
					'url' => ['controller' => 'HostTruckmasters', 'action' => 'index'],
					'label' => false,
					'div' => 'form-group',
					'class' => 'form-inline',
					'novalidate' => true
				]);

					echo '部署　';

					echo $this->Form->input('departmentmasters_id', [
						'label' => false,
						'type' => 'select',
						'div' => false,
						'class' => 'form-control',
						'empty'=>'未選択',
						'onchange' => 'this.form.submit()',
						'data-rel' => 'chosen',
						'default' => $did
					]);

					echo '　　営業所　';

					echo $this->Form->input('branchmasters_id', [
						'label' => false,
						'type' => 'select',
						'div' => false,
						'class' => 'form-control',
						'empty'=>'未選択',
						'onchange' => 'this.form.submit()',
						'data-rel' => 'chosen',
						'default' => $bid
					]);

					echo '　　車両　　';

					echo $this->Form->input('id', [
						'label' => false,
						'type' => 'select',
						'options' => $carlist,
						'div' => false,
						'class' => 'form-control chosen',
						'empty'=>'未選択',
						'onchange' => 'this.form.submit()',
						'data-rel' => 'chosen',
						'default' => $cid
					]);

					echo '　　現/廃　';

					echo $this->Form->input('scrapflg', [
						'label' => false,
						'type' => 'select',
						'options' => [
							0 => '現役',
							1 => '廃車'
						],
						'div' => false,
						'class' => 'form-control chosen',
						'onchange' => 'this.form.submit()',
						'data-rel' => 'chosen',
						'default' => $scrapflg
					]);

				echo $this->Form->end()
			?>

			<!-- /車番一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>車両マスタ一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th><?php echo $this->Paginator->sort('departmentmasters_id', '部署'); ?></th>
									        <th><?php echo $this->Paginator->sort('plateno', '車両'); ?></th>
									        <th><?php echo $this->Paginator->sort('carno', '通称、出光車番'); ?></th>
									        <th></th>
									        <th><?php echo $this->Paginator->sort('carcheckdate', '車検日'); ?></th>
									        <th><?php echo $this->Paginator->sort('katasiki', '型式'); ?></th>
									        <th><?php echo $this->Paginator->sort('syadaino', '車台'); ?></th>
									        <th><?php echo $this->Paginator->sort('loadingaccount', '積載'); ?></th>
									        <th><?php echo $this->Paginator->sort('trailermasters_id', 'トレーラー区分'); ?></th>
									        <th><?php echo $this->Paginator->sort('scrapflg', '現/廃'); ?></th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
								    	<?php foreach ($carheadmasters as $carheadmaster): ?>
									    <tr>
									        <td><?php echo ($carheadmaster['Departmentmaster']['departmentname']); ?></td>
									        <td><?php echo ($carheadmaster['Carheadmaster']['plateno']); ?></td>
									        <td><?php echo ($carheadmaster['Carheadmaster']['carno']); ?></td>
									        <td></td>
									        <td><?php
									        if($carmaster['Carmaster']['carcheckdate'] != '0000-00-00'){
									        	echo ($carmaster['Carmaster']['carcheckdate']);
									        }else{
									        	echo ' ';
									        }
									         ?></td>
									        <td><?php echo ($carmaster['Carmaster']['katasiki']); ?></td>
									        <td><?php echo ($carmaster['Carmaster']['syadaino']); ?></td>
									        <td class="text-right"><?php echo ($carmaster['Carmaster']['loadingaccount']); ?></td>
									        <td><?php echo ($carmaster['Trailermaster']['trailerkind']); ?></td>
									        <td>
									        <?php
									        	if ($carmaster['Carmaster']['scrapflg'] == 0) {
									        		echo '現役';
									        	} else {
									        		echo '廃車';
									        	}
									        ?>
									        </td>
									        <td>
												<?php echo $this->Html->link(('修正'), array('action' => 'edit',$carmaster['Carmaster']['id']), array('class'=>array('btn btn-info'))); ?>
												<?php echo $this->Html->link(('可能荷主'), ['controller'=>'HostTruckkinds', 'action' => 'index',$carmaster['Carmaster']['id']], ['class'=>['btn btn-info']]); ?>
												<?php
													/*
													$truckkindscount = ClassRegistry::init('Truckkind')->find('count', [
														'conditions' => [
															'AND' => [
																['carmasters_id' => $carmaster['Carmaster']['id']],
																'OR' => [
																	['delflg' => ''],
																	['delflg' => 0]
																]
															]
														],
														'recursive' => -1
													]);
													*/
													$truckkindscount = 0;
													foreach ($carmaster['Truckkind'] as $truckkind) {
														$truckkindscount = $truckkindscount + 1;
													}
													echo '（'.$truckkindscount.'）';
													//echo $this->Html->link(('可能荷主'.'（'.$truckkindscount.'）'), ['controller'=>'HostTruckkinds', 'action' => 'index',$carmaster['Carmaster']['id']], ['class'=>['btn btn-info']]);
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

			    <?php echo $this->element('pager')?>

				<p>
				<?php echo $this->Html->link(('新規登録'), array('action' => 'add'), array('class'=>array('btn btn-success'))); ?>
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<script>
$(".chosen").chosen({
    search_contains:true
});
</script>
