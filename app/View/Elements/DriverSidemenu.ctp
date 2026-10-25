		<?php $today = new DateTime(); ?>
        <div class="col-sm-2 col-lg-2">
            <div class="sidebar-nav">
                <div class="nav-canvas">
                    <div class="nav-sm nav nav-stacked">

                    </div>
                    <ul class="nav nav-pills nav-stacked main-menu">
                        <li class="nav-header">メニュー</li>
                        <li class="">
                            <ul class="nav nav-pills nav-stacked">
<!--				              <li><?php //echo $this->Html->link('運行日程', array('controller' => 'Dr020s','action'=>'index',$today->format('Y-m-d'))); ?></li> -->
<!--				              <li><?php //echo $this->Html->link($today->modify('-1 day')->format('m月d日').'の運行日程', array('controller' => 'Dr020s','action'=>'index',$today->format('Y-m-d'))); ?></li> -->
<!--				              <li><?php //echo $this->Html->link($today->modify('+1 day')->format('m月d日').'の運行日程', array('controller' => 'Dr020s','action'=>'index',$today->format('Y-m-d'))); ?></li> -->
<!--				              <li><?php //echo $this->Html->link($today->modify('+1 day')->format('m月d日').'の運行日程', array('controller' => 'Dr020s','action'=>'index',$today->format('Y-m-d'))); ?></li> -->
<!--				              <li><?php //echo $this->Html->link('確定確認', ['controller' => 'Dr030s','action'=>'index']); ?></li> -->
<!--				              <li><?php //echo $this->Html->link('アサヒコイン付与', ['controller' => 'Dr040s','action'=>'index']); ?></li> -->
<!--				              <li><?php //echo $this->Html->link('アサヒコイン履歴', ['controller' => 'Dr041s','action'=>'index']); ?></li> -->
							  <li><?php echo $this->Html->link('労働時間', ['controller' => 'Dr050s','action'=>'index']); ?></li>
				              <?php 
                              if($user['rolemasters_id'] == 39){
                                ?>
                                <?php }else{ ?>
                                  <li><?php echo $this->Html->link('有給等申請', array('controller' => 'Dr010s','action'=>'index')); ?></li>
                                <?php } ?>
							  <li>
								<?= $this->Html->link('パスワードの変更', ['controller' => 'Users','action'=>'password_confirm']); ?>
							  </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <!--/span-->
