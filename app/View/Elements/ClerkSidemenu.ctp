		<div class="col-sm-2 col-lg-2">
			<div class="sidebar-nav">
				<div class="nav-canvas">
					<div class="nav-sm nav nav-stacked">

					</div>
					<ul class="nav nav-pills nav-stacked main-menu">
						<li class="nav-header">メニュー</li>
						<?php
						switch($user['rolemasters_id']){
							case 21;
							case 24;
							case 25;
							case 28;
						?>
                        <li class="accordion">
                            <a href="#"><i class="glyphicon glyphicon-plus"></i><span> 化学品/他業務</span></a>
                            <ul class="nav nav-pills nav-stacked">
				              <li><?php echo $this->Html->link('受注カレンダ', array('controller' => 'Ch003s','action'=>'index')); ?></li>
				              <li><?php echo $this->Html->link('未承認一覧', array('controller' => 'Ch050s','action'=>'index')); ?></li>
				              <li><?php echo $this->Html->link('乗務員選択', array('controller' => 'Ch030s','action'=>'index')); ?></li>
				              <!-- <li><?php echo $this->Html->link('乗務員未確認', array('controller' => 'Ch091s','action'=>'index')); ?></li> -->
				              <li><?php echo $this->Html->link('出荷実績',['controller'=>'Ch080s','action'=>'index']); ?></li>
							  <li><?php echo $this->Html->link('請求データ出力',['controller'=>'Ch081s','action'=>'index']); ?></li>
							  <li><?php echo $this->Html->link('請求書作成',['controller'=>'Ch200s','action'=>'index']); ?></li>
							  <li><?php echo $this->Html->link('請求書印刷',['controller'=>'Ch100s','action'=>'index']); ?></li>
                            </ul>
                        </li>
						<?php
								break;
						}
						?>
						<?php
						switch($user['rolemasters_id']){
							case 20;
							case 24;
							case 28;
						?>
                        <li class="accordion">
                            <a href="#"><i class="glyphicon glyphicon-plus"></i><span> 出光業務</span></a>
                            <ul class="nav nav-pills nav-stacked">
							  <li><?php echo $this->Html->link('出光請求登録',['controller'=>'Oi140s','action'=>'index']); ?></li>
	  			              <li><?php echo $this->Html->link('Free請求書作成',['controller'=>'Oi100s','action'=>'index']); ?></li>
                            </ul>
                        </li>
						<?php
								break;
						}
						?>
						<?php
						switch($user['rolemasters_id']){
							case 23;
							case 24;
							case 25;
							case 28;
						?>
                        <li class="accordion">
                            <a href="#"><i class="glyphicon glyphicon-plus"></i><span> コンテナ業務</span></a>
                            <ul class="nav nav-pills nav-stacked">
							<li><?php echo $this->Html->link('地域別カレンダー', array('controller' => 'Co003s','action'=>'index')); ?></li>
							<li><?php echo $this->Html->link('未承認一覧', array('controller' => 'Co050s','action'=>'index')); ?></li>
							<li><?php echo $this->Html->link('キャンセル一覧', array('controller' => 'Co056s','action'=>'index')); ?></li>
				            <li><?php echo $this->Html->link('乗務員選択', array('controller' => 'Co030s','action'=>'index')); ?></li>
							<li><?php echo $this->Html->link('日別配車一覧',['controller'=>'Co052s','action'=>'index']); ?></li>
							<li><?php echo $this->Html->link('出荷実績',['controller'=>'Co080s','action'=>'index']); ?></li>
							<li><?php echo $this->Html->link('請求データ出力',['controller'=>'Co081s','action'=>'index']); ?></li>
							<li><?php echo $this->Html->link('請求書作成',['controller'=>'Co200s','action'=>'index']); ?></li>
							<li><?php echo $this->Html->link('請求書印刷',['controller'=>'Co100s','action'=>'index']); ?></li>
                            </ul>
                        </li>
						<?php
								break;
						}
						?>
						<?php
						switch($user['rolemasters_id']){
							case 22;
							case 24;
						?>
                        <li class="accordion">
                            <a href="#"><i class="glyphicon glyphicon-plus"></i><span> ガス業務</span></a>
                            <ul class="nav nav-pills nav-stacked">
								<li><?php echo $this->Html->link('地域別カレンダー', array('controller' => 'Ga003s','action'=>'index')); ?></li>
								<li><?php echo $this->Html->link('未承認一覧', array('controller' => 'Ga050s','action'=>'index')); ?></li>
								<li><?php echo $this->Html->link('キャンセル一覧', array('controller' => 'Ga056s','action'=>'index')); ?></li>
								<!-- <li><?php //echo $this->Html->link('日別配車一覧',['controller'=>'Ga052s','action'=>'index']); ?></li> -->
								<li><?php echo $this->Html->link('出荷実績',['controller'=>'Ga080s','action'=>'index']); ?></li>
								<li><?php echo $this->Html->link('請求データ出力',['controller'=>'Ga081s','action'=>'index']); ?></li>
								<li><?php echo $this->Html->link('請求書作成',['controller'=>'Ga200s','action'=>'index']); ?></li>
								<li><?php echo $this->Html->link('請求書印刷',['controller'=>'Ga100s','action'=>'index']); ?></li>
                            </ul>
                        </li>
						<?php
								break;
						}
						?>
						<li class="accordion">
							<a href="#"><i class="glyphicon glyphicon-plus"></i><span> 勤怠関連</span></a>
							<ul class="nav nav-pills nav-stacked">
<!--							  <li><?php echo $this->Html->link('勤怠管理',['controller'=>'Host010s','action'=>'index']); ?></li> -->
<!--				              <li><?php echo $this->Html->link('勤怠管理（川崎Ver）',['controller'=>'Host000s','action'=>'index']); ?></li> -->
				              <li><?php echo $this->Html->link('勤怠管理',['controller'=>'Host002s','action'=>'index']); ?></li>
				              <li><?php echo $this->Html->link('勤怠確認',['controller'=>'Host050s','action'=>'index',date('Y-m')]); ?></li>
				              <li><?php echo $this->Html->link('勤怠確認(個人)',['controller'=>'Dr050s','action'=>'index',date('Y-m')]); ?></li>
				              <li><?php echo $this->Html->link('直近2w休み', array('controller' => 'Host013s','action'=>'index')); ?></li>
							  <li><?php echo $this->Html->link('有休管理',['controller'=>'Host012s','action'=>'index']); ?></li>
				              <li><?php echo $this->Html->link('距離管理',['controller'=>'Host014s','action'=>'index']); ?></li>
				              <li><?php echo $this->Html->link('4h連続/速度',['controller'=>'Host016s','action'=>'index']); ?></li>
							</ul>
						</li>
						<li class="accordion">
							<a href="#"><i class="glyphicon glyphicon-plus"></i><span> 社員管理</span></a>
							<ul class="nav nav-pills nav-stacked">
 							  <!-- <li><?php echo $this->Html->link('社員マスタ',['controller'=>'Users','action'=>'index']); ?></li> -->
							  <li><?php echo $this->Html->link('ガソリン管理',['controller'=>'Host020s','action'=>'index']); ?></li>
				              <li><?php echo $this->Html->link('事故処理',['controller'=>'Host030s','action'=>'index']); ?></li>
				              <li><?php echo $this->Html->link('休憩時間',['controller'=>'Host060s','action'=>'index']); ?></li>
							</ul>
						</li>
						<li class="accordion">
							<a href="#"><i class="glyphicon glyphicon-plus"></i><span> 受付</span></a>
							<ul class="nav nav-pills nav-stacked">
								<li><?php echo $this->Html->link('受付履歴',['controller'=>'Re010s','action'=>'index']); ?></li>
							</ul>
						</li>
						<li class="accordion">
							<a href="#"><i class="glyphicon glyphicon-plus"></i><span> 各種マスタ</span></a>
							<ul class="nav nav-pills nav-stacked">
								<li><?php echo $this->Html->link('荷主マスタ',array('controller'=>'HostShippermasters','action'=>'index')); ?></li>
								<li><?php echo $this->Html->link('商品マスタ',array('controller'=>'HostProductdetailmasters','action'=>'index')); ?></li>
				                <li><?php echo $this->Html->link('担当者マスタ',array('controller'=>'Tantomasters','action'=>'index')); ?></li>
						<?php
						switch($user['rolemasters_id']){
							case 23;
							case 24;
							case 25;
						?>
								<li><?php echo $this->Html->link('ｺﾝﾃﾅ詳細ﾏｽﾀ',array('controller'=>'HostProductcontainers','action'=>'index')); ?></li>
						<?php
								break;
						}
						?>
								<li><?php echo $this->Html->link('作業マスタ',array('controller'=>'HostWorkdetailmasters','action'=>'index')); ?></li>
								<li><?php echo $this->Html->link('車両マスタ',array('controller'=>'HostTruckmasters','action'=>'index')); ?></li>
								<li><?php echo $this->Html->link('価格マスタ',array('controller'=>'HostPricemasters','action'=>'index')); ?></li>
				                <li><?php echo $this->Html->link('★ﾆﾁｺﾝ価格付替',array('controller'=>'HostPricemaster2s','action'=>'index')); ?></li>
								<li><?php echo $this->Html->link('作業場所マスタ', ['controller'=>'HostWorkplaces','action'=>'index']); ?></li>
				                <li><?php echo $this->Html->link('個別手当マスタ',array('controller'=>'HostKobetsuteates','action'=>'index')); ?></li>
							</ul>
						</li>
						<li>
							<?= $this->Html->link('パスワードの変更', ['controller' => 'Users','action'=>'password_confirm']); ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!--/span-->
