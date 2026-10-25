<?php
switch(true){
  case ($user['Rolemaster']['id']>=90);
     echo $this->element('meta',['ThemeCss' => 'bootstrap-cerulean.min']);
     echo $this->element('Header_Reception');
     break;
}
 ?>

<!-- メイン画像 -->
<!-- / メイン画像 -->

<div id="wrapper">

  <div class="box login">
    <div class="boxContent">
    <!--
 	   	<div class="col-lg-12 text-center">
			<?php // echo $this->Flash->render(); ?>
		</div>
	 -->
			<?php echo $this->fetch('content'); ?>

    </div><!-- //div.boxContent -->
  </div><!-- //div.box login -->



</div>
<!-- / WRAPPER -->

<?php //echo $this->element('footer'); ?>

