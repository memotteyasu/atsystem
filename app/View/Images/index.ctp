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
        <!-- left menu ends -->

		<div id="content" class="col-lg-10 col-md-10 col-sm-10">
			<!-- content starts -->
			            <div>
						    <ul class="breadcrumb">
						        <li>
						            <?php echo $this->Html->link('Home',array('controller'=>'HostTops','action'=>'index')); ?>
						        </li>
						        <li>
						            <?php echo $this->Html->link('一覧',['action'=>'index']); ?>
						        </li>
						        <li>
						        	<?php echo $this->Html->link('追加',['action'=>'add']); ?>
						        </li>
						    </ul>
			            </div>

<?php foreach($images as $image):?>
<?php //echo debug($image); ?>
	<tr>
		<td>
			<?php echo $image['Image']['id'];?>
		</td>
		<td>
			<?php echo $image['Image']['title'];?>
		</td>
		<td>
			<?php echo $image['Image']['img_file_name'];?>
			<?php echo $this->upload->uploadImage($image['Image'], 'Image.img', array('style' => 'thumb')) ?>
		</td>
		<td>
			<?php echo $image['Image']['created'];?>
		</td>
	</tr>
<?php endforeach;?>
			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->