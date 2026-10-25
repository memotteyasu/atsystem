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
							$this->Html->addCrumb('出光積卸地マスタ一覧', ['controller' => 'IdemitsuLoadingmasters', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
						        </li>
						    </ul>
			            </div>

			<!-- /出光積卸地マスタ登録 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
				    <div class="box-header well">
				        <h2>出光積卸地マスタ登録</h2>
				    </div>
				    <form class="form-horizontal">

					    <br>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">積卸地CD</label>
					      <div class="col-sm-2">
					      	<input type="text" class="form-control" id="tsumioroti_cd">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">積卸地名称</label>
					      <div class="col-sm-6">
					      	<input type="text" class="form-control" id="tsumioroti_name">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">長距離手当（単夏）</label>
					      <div class="col-sm-2">
					      	<input type="text" class="form-control" id="tsumioroti_tan_natsu">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">長距離手当（ト夏）</label>
					      <div class="col-sm-2">
					      	<input type="text" class="form-control" id="tsumioroti_to_natsu">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">長距離手当（単冬）</label>
					      <div class="col-sm-2">
					      	<input type="text" class="form-control" id="tsumioroti_tan_fuyu">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">長距離手当（ト冬）</label>
					      <div class="col-sm-2">
					      	<input type="text" class="form-control" id="tsumioroti_to_fuyu">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">営業キロ</label>
					      <div class="col-sm-2">
					      	<input type="text" class="form-control" id="tsumioroti_ei_km">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">古川方面</label>
					      <div class="form-inline">
						      <div class="col-sm-6">
						      	<div class="checkbox">
						      		<label><input type="checkbox"></label>
						      	</div>
						      </div>
					      </div>
					    </div>


					    <hr>
					    <div class="form-group">
					      <div class="col-sm-offset-2 col-sm-10">
						    <button type="submit" class="btn btn-info">登録</button>
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
