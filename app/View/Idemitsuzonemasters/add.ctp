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
							$this->Html->addCrumb('出光ゾーンマスタ一覧', ['controller' => 'Idemitsuzonemasters', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
						        </li>
						    </ul>
			            </div>

			<!-- /ゾーン登録 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    	<div class="box-inner">
				    <div class="box-header well">
				        <h2>出光ゾーンマスタ登録</h2>
				    </div>
				    <form class="form-horizontal">

				    	<br>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">ゾーンCD</label>
					      <div class="col-sm-4">
					      	<input type="text" class="form-control" id="zone_cd">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">ゾーン名称ｶﾅ</label>
					      <div class="col-sm-4">
					      	<input type="text" class="form-control" id="zone_name">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">営業キロ</label>
					      <div class="col-sm-2">
					      	<input type="text" class="form-control" id="zone_ei_km">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">営業キロガス</label>
					      <div class="col-sm-2">
					      	<input type="text" class="form-control" id="zone_ei_kg">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">ＹＴＣ営業キロ</label>
					      <div class="col-sm-2">
					      	<input type="text" class="form-control" id="zone_ei_ytc">
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
