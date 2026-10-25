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

        <div id="content" class="col-lg-10 col-md-10 col-sm-10">
			<!-- content starts -->
			            <div>
						    <ul class="breadcrumb">
						        <li>
						            <?php echo $this->Html->link('Home',array('controller'=>'HostTops','action'=>'index')); ?>
						        </li>
						        <li>
						            <a href="#">商品マスタ一覧</a>
						        </li>
						        <li>
						        	<?php echo $this->Html->link('追加',array('action'=>'add')); ?>
						        </li>
						    </ul>
			            </div>

				<div class="row">
				</div>

			<!-- /商品一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>商品マスタ一覧</h2>
						    </div>
					    	<table class="table table-striped table-bordered bootstrap-datatable">
					    		<thead>
								    <tr>
								        <th>商品CD</th>
								        <th>商品名称</th>
								        <th>商品名称ｶﾅ</th>
								        <th> </th>
								    </tr>
							    </thead>
							    <tbody>
								    <tr>
								        <td>S040</td>
								        <td>シェルゾールA100</td>
								        <td>ｼｪﾙｿﾞｰﾙA100</td>
								        <td>
								        	<button type="button" class="btn btn-success">詳細</button>
											<?php echo $this->Html->link(('修正'), array('action' => 'edit'), array('class'=>array('btn btn-info'))); ?>
											<button type="button" class="btn btn-danger">削除</button>
										</td>
								    </tr>
								    <tr>
								        <td>S050</td>
								        <td>PG</td>
								        <td>PG</td>
								        <td>
								        	<button type="button" class="btn btn-success">詳細</button>
											<?php echo $this->Html->link(('修正'), array('action' => 'edit'), array('class'=>array('btn btn-info'))); ?>
											<button type="button" class="btn btn-danger">削除</button>
										</td>
								    </tr>
								    <tr>
								        <td>S055</td>
								        <td>A重油</td>
								        <td>ｴｰｼﾞｭｳﾕ</td>
								        <td>
								        	<button type="button" class="btn btn-success">詳細</button>
											<?php echo $this->Html->link(('修正'), array('action' => 'edit'), array('class'=>array('btn btn-info'))); ?>
											<button type="button" class="btn btn-danger">削除</button>
										</td>
								    </tr>
								    <tr>
								        <td>S070</td>
								        <td>エマルミン</td>
								        <td>ｴﾏﾙﾐﾝ</td>
								        <td>
								        	<button type="button" class="btn btn-success">詳細</button>
											<?php echo $this->Html->link(('修正'), array('action' => 'edit'), array('class'=>array('btn btn-info'))); ?>
											<button type="button" class="btn btn-danger">削除</button>
										</td>
								    </tr>
								    <tr>
								        <td>V001</td>
								        <td>トリエチルアミン</td>
								        <td>ﾄﾘｴﾁﾙｱﾐﾝ</td>
								        <td>
								        	<button type="button" class="btn btn-success">詳細</button>
											<?php echo $this->Html->link(('修正'), array('action' => 'edit'), array('class'=>array('btn btn-info'))); ?>
											<button type="button" class="btn btn-danger">削除</button>
										</td>
								    </tr>
								    <tr>
								        <td>X001</td>
								        <td>サンプレン</td>
								        <td>ｻﾝﾌﾟﾚﾝ</td>
								        <td>
								        	<button type="button" class="btn btn-success">詳細</button>
											<?php echo $this->Html->link(('修正'), array('action' => 'edit'), array('class'=>array('btn btn-info'))); ?>
											<button type="button" class="btn btn-danger">削除</button>
										</td>
								    </tr>
								    <tr>
								    	<td>X001</td>
								    	<td>苛性カリ/td>
								    	<td>ｶｾｲｶﾘ</td>
								    	<td>
								    		<button type="button" class="btn btn-success">詳細</button>
											<?php echo $this->Html->link(('修正'), array('action' => 'edit'), array('class'=>array('btn btn-info'))); ?>
											<button type="button" class="btn btn-danger">削除</button>
										</td>
								    </tr>
							    </tbody>
							</table>
					    </div>
				    </div>
			    </div>
			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->