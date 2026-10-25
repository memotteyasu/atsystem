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
						            <?php echo $this->Html->link('Home',['controller'=>'HostTops','action'=>'index']); ?>
						        </li>
						        <li>
						            <?php echo $this->Html->link('商品群マスタ一覧',['action'=>'index']); ?>
						        </li>
						    </ul>
			            </div>

			<!-- /商品登録 -->
			<div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				    <div class="box-header well">
				        <h2>商品マスタ登録</h2>
				    </div>

				    <?php echo $this->Form->create('Productmaster',array('label'=>false,'div'=>false, 'class'=>'form-horizontal', 'novalidate' => true)); ?>

				    	<br>
					    <div class="form-group">
					      <label class="col-sm-2 control-label">商品CD</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('pcode',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <div class="form-group">
					      <label class="col-sm-2 control-label">商品名称</label>
					      <div class="col-sm-4">
					      	<?php echo $this->Form->input('pname',array('label'=>false,'div'=>false, 'class'=>'form-control')); ?>
					      </div>
					    </div>

					    <hr>
					    <div class="form-group">
					      <div class="col-sm-offset-2 col-sm-10">
						    <?php echo $this->Form->submit('登録', array('div'=>false, 'class' => 'btn btn-info', 'name' => 'submit')); ?>
						    <?php echo $this->Html->link(('キャンセル'), array('action' => 'index'), array('class'=>array('btn btn-danger'))); ?>
					      </div>
					    </div>

				    <?php echo $this->Form->end(); ?>
			    </div>
		    </div>

	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->