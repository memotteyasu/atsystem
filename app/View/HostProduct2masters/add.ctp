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
						            <a href="#">Home</a>
						        </li>
						        <li>
						            <a href="#">詳細商品マスタ登録</a>
						        </li>
						    </ul>
			            </div>

			<!-- /商品登録 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				    <div class="box-header well">
				        <h2>商品マスタ登録</h2>
				    </div>
				    <form class="form-horizontal">

				    	<br>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">商品CD</label>
					      <div class="col-sm-2">
					      	<input type="text" class="form-control" id="syohin_cd">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">商品名称</label>
					      <div class="col-sm-6">
					      	<input type="text" class="form-control" id="syohin_name">
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">商品名称ｶﾅ</label>
					      <div class="col-sm-6">
					      	<input type="text" class="form-control" id="syohin_name_kana">
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

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->