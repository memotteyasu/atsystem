<?php foreach($workplaces as $workplace){} ?>
<div class="ch-container">
	<div class="row">

		<!-- left menu starts -->
			<?php echo $this->element('DriverSidemenu'); ?>
		<!-- left menu ends -->

		<div id="content" class="col-lg-10 col-sm-10">
			<!-- content starts -->
			<div>
				<ul class="breadcrumb">
					<li>
						<?php echo $this->Html->link('Top',['controller'=>$role.'Tops','action'=>'index']); ?>
					</li>
				</ul>
			</div>

			<div class="row">
				<div class="box col-md-12">
					<div class="box-inner">
						<div class="box-header well">
							<h2><?php echo $workplace['Workplace']['workplaceshortname']; ?></h2>
						</div>
						<div class="box-content row">
							<div class="col-lg-12 col-md-12">
							TEL:<a href="tel:<?php echo $workplace['Workplace']['placetel']; ?>"><?php echo $workplace['Workplace']['placetel']; ?></a>
							</div>
							<div class="col-lg-12 col-md-12">
							<a href="<?php echo $workplace['Workplace']['googlemapurl']; ?>" target="_blank"><?php echo $workplace['Workplace']['placeadd']; ?></a>
							</div>
							<div class="col-lg-12 col-md-12">
							<?php echo $workplace['Workplace']['mapcomment']; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- image -->
			<div class="row">
				<div class="box col-md-12">
				<?php
				foreach($workplace['Workplaceimage'] as $wpimage){
					echo $this->Html->image('/workplaceimage/resize/'.$wpimage['id'].'.jpg',['style'=>'width:100%']);
					echo '<br>';
					echo $wpimage['imgcomment'];
				}
				 ?>
				</div>
			</div>



		<!-- content ends -->
		</div><!--/#content.col-md-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->


