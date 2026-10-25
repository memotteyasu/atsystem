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
							$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
							$this->Html->addCrumb('納入先営業キロマスタ一覧', ['controller' => 'HostTradingmasters', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
						        </li>
						    </ul>
			            </div>

			<!-- /納入先営業キロ登録修正 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
				    <div class="box-header well">
				        <h2>納入先営業キロ登録修正</h2>
				    </div>
				    <form class="form-horizontal">

					    <div class="form-group">
					      <label class="col-sm-2 control-label">納入先名称</label>
					      <div class="col-sm-8">
					      	<p class="form-control-static">オーシカケミテック</p>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">地域名称</label>
					      <div class="col-sm-6">
					      	<p class="form-control-static">半田</p>
					      </div>
					    </div>


					    <div class="form-group">
					      <label class="col-sm-2 control-label">営業キロ（名）</label>
					      <div class="col-sm-2">
					      	<input type="text" class="form-control" id="ei_km_mei">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">営業キロ（四）</label>
					      <div class="col-sm-2">
					      	<input type="text" class="form-control" id="ei_km_yon">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">長距離（単）</label>
					      <div class="col-sm-2">
					      	<input type="text" class="form-control" id="ei_km_tan">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">長距離（助）</label>
					      <div class="col-sm-2">
					      	<input type="text" class="form-control" id="ei_km_suke">
					      </div>
					    </div>


					    <hr>
					    <div class="form-group">
					      <div class="col-sm-offset-2 col-sm-10">
						    <button type="submit" class="btn btn-info">更新</button>
						    <button type="button" class="btn btn-danger">キャンセル</button>
					      </div>
					    </div>

				    </form>
				    </div>
			    </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->
