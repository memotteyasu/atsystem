<!-- 商品群マスタ -->
<!-- HostProductmasters -->
<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
<?php echo $this->element('HostSidemenu'); ?>
        <!-- left menu ends -->
        <div id="content" class="col-lg-10 col-md-10 col-sm-10">
			<!-- content starts -->
			            <div>
						    <ul class="breadcrumb">
		        <li>
		        <?php
		        $this->Html->addCrumb('Top','/HostTops/index/');
		        echo $this->element('breadcrumbs');
		        ?><?php echo $this->Flash->render(); ?>
		        </li>
						    </ul>
			            </div>

				<?php //echo $this->element('searchForm', ['model' => 'Productmaster', 'url' => ['controller'=>'HostProductmasters','action'=>'index'], 'desc' => '商品名称を入力', 'input' => 'pname']); ?>
<!--
検索窓はここに記述
 -->
部署　<select name="a">
<option value="1">化学品</option>
<option value="2">石油部門</option>
<option value="3">ガス部門</option>
<option value="4">コンテナ部門</option>
</select>
　荷主　<select name="a">
<option value="A0100">三菱商事</option>
<option value="B0100">辰巳商会</option>
<option value="C0100">BASFジャパン</option>
<option value="D0100">日本コンセプト</option>
</select>

			<!-- /商品一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>商品群マスタ一覧</h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									    	<th><?php echo $this->Paginator->sort('pcode', '商品群CD'); ?></th>
									        <th><?php echo $this->Paginator->sort('pname', '商品名称'); ?></th>
									        <th><?php echo $this->Paginator->sort('pnameshort', '商品略称（印字用'); ?></th>

									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
									    <tr>
									    <?php foreach ($productmaseters as $productmaseter): ?>
									    <td><?php echo ($productmaseter['Productmaster']['pcode']); ?></td>
									    <td><?php echo ($productmaseter['Productmaster']['pname']); ?></td>
											<td>
												<?php echo $this->Html->link(('修正'), array('action' => 'edit',$productmaseter['Productmaster']['id']), array('class'=>array('btn btn-info'))); ?>
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

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->
