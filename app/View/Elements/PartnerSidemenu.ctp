		<div class="col-sm-2 col-lg-2">
			<div class="sidebar-nav">
				<div class="nav-canvas">
					<div class="nav-sm nav nav-stacked">

					</div>
					<ul class="nav nav-pills nav-stacked main-menu">
						<li class="nav-header">メニュー</li>
						<?php
						switch($user['rolemasters_id']){
							case 29;
						?>
						<li class="">
							<ul class="nav nav-pills nav-stacked">
							  <li><?php echo $this->Html->link('乗務員選択', array('controller' => 'Ch530s','action'=>'index')); ?></li>
							</ul>
						</li>
						<?php
								break;
						}
						?>
					</ul>
				</div>
			</div>
		</div>
		<!--/span-->
