<?php echo $this->element('meta',['ThemeCss' => 'bootstrap-cerulean.min']); ?>
<?php echo $this->element('header'); ?>

<div id="wrapper">
	<div class="box login">
		<div class="boxContent">
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
</div>

<?php echo $this->element('footer'); ?>

