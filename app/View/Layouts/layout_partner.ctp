<?php
     echo $this->element('meta',['ThemeCss' => 'bootstrap-simplex.min']);
     echo $this->element('Header_Partner');
 ?>

<!-- メイン画像 -->
<!-- / メイン画像 -->

<div id="wrapper">

  <div class="box login">
    <div class="boxContent">
			<?php echo $this->fetch('content'); ?>

    </div><!-- //div.boxContent -->
  </div><!-- //div.box login -->



</div>
<!-- / WRAPPER -->

<?php echo $this->element('footer'); ?>

