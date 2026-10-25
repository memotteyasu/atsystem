<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
			<?php echo $this->element('Sidemenu'); ?>
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
						            <?php
							$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
						        </li>
						    </ul>
			            </div>
				<?php //echo $this->element('searchForm', ['model' => 'Tradingmasters', 'url' => ['controller'=>'HostTradingmasters','action'=>'index'], 'desc' => '納入先名称を入力', 'input' => 'pdname']); ?>

				<div class="row">
				  <div class="box-content">
				    <form class="form-inline">
				<!-- /納入先選択 -->
				            <div class="control-group">
				            <label class="control-label" for="selectError">納入先</label>
				                <div class="controls">
				                    <select id="selectError" data-rel="chosen" style="width:150px;">
				                        <option>アイカ技研</option>
				                        <option>アイカ工業</option>
				                        <option>アイシーケイ</option>
				                        <option>アイシン化工</option>
				                        <option>青木油脂</option>
				                        <option>アキレス</option>
				                        <option>旭化学</option>
				                        <option>旭化成ケミカルズ</option>
				                        <option>旭化成建材</option>
				                    </select>
				                    <br>
				                    <a href="">あ </a><a href="">い </a><a href="">う </a><a href="">え </a><a href="">お　</a>
				                    <a href="">か </a><a href="">き </a><a href="">く </a><a href="">け </a><a href="">こ　</a>
				                    <a href="">さ </a><a href="">し </a><a href="">す </a><a href="">せ </a><a href="">そ　</a>
				                    <a href="">た </a><a href="">ち </a><a href="">つ </a><a href="">て </a><a href="">と　</a>
				                    <a href="">な </a><a href="">に </a><a href="">ぬ </a><a href="">ね </a><a href="">の　</a>
				                    <br>
				                    <a href="">は </a><a href="">ひ </a><a href="">ふ </a><a href="">へ </a><a href="">ほ　</ a>
				                    <a href="">ま </a><a href="">み </a><a href="">む </a><a href="">め </a><a href="">も　</a>
				                    <a href="">や </a><a href="">ゆ </a><a href="">よ　</a>
				                    <a href="">ら </a><a href="">り </a><a href="">る </a><a href="">れ </a><a href="">ろ　</a>
				                    <a href="">わ </a><a href="">ん</a>
				                </div>
				            </div>
				     </form>
				   </div>
				</div>

			<!-- /納入先営業キロ一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>納入先営業キロ一覧</h2>
						    </div>
							    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th>納入先名称</th>
									        <th>地域名称</th>
									        <th>営業キロ（名）</th>
									        <th>営業キロ（四）</th>
									        <th>長距離（単）</th>
									        <th>長距離（助）</th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
									    <tr>
									        <td>アイカ技研</td>
									        <td>各務原</td>
									        <td>0</td>
									        <td>0</td>
									        <td>0</td>
									        <td>0</td>
									        <td>
												<?php echo $this->Html->link(('登録修正'), array('action' => 'edit'), array('class'=>array('btn btn-info'))); ?>
											</td>
									    </tr>
									    <tr>
									        <td>アイカ工業</td>
									        <td>福島</td>
									        <td>0</td>
									        <td>0</td>
									        <td>0</td>
									        <td>0</td>
									        <td>
												<button type="submit" class="btn btn-info">登録修正</button>
											</td>
									    </tr>
									    <tr>
									        <td>アイカ工業</td>
									        <td>甚目寺</td>
									        <td>25</td>
									        <td>65</td>
									        <td>0</td>
									        <td>0</td>
									        <td>
												<button type="submit" class="btn btn-info">登録修正</button>
											</td>
									    </tr>
									    <tr>
									        <td>アイカ工業</td>
									        <td>篠山</td>
									        <td>260</td>
									        <td>240</td>
									        <td>7800</td>
									        <td>7800</td>
									        <td>
												<button type="submit" class="btn btn-info">登録修正</button>
											</td>
									    </tr>
									    <tr>
									        <td>アイカ工業</td>
									        <td>広島</td>
									        <td>0</td>
									        <td>0</td>
									        <td>32600</td>
									        <td>18100</td>
									        <td>
												<button type="submit" class="btn btn-info">登録修正</button>
											</td>
									    </tr>
									    <tr>
									        <td>アイカ工業</td>
									        <td>丹波</td>
									        <td>260</td>
									        <td>240</td>
									        <td>7800</td>
									        <td>7800</td>
									        <td>
												<button type="submit" class="btn btn-info">登録修正</button>
											</td>
									    </tr>
									    <tr>
									        <td>アイシーケイ</td>
									        <td>磐田</td>
									        <td>120</td>
									        <td>160</td>
									        <td>0</td>
									        <td>0</td>
									        <td>
												<button type="submit" class="btn btn-info">登録修正</button>
											</td>
									    </tr>
									    <tr>
									        <td>アイシン化工</td>
									        <td>藤岡</td>
									        <td>55</td>
									        <td>95</td>
									        <td>0</td>
									        <td>0</td>
									        <td>
												<button type="submit" class="btn btn-info">登録修正</button>
											</td>
									    </tr>
									    <tr>
									        <td>青木油脂</td>
									        <td>滋賀</td>
									        <td>105</td>
									        <td>0</td>
									        <td>0</td>
									        <td>0</td>
									        <td>
												<button type="submit" class="btn btn-info">登録修正</button>
											</td>
									    </tr>
									    <tr>
									        <td>青木油脂</td>
									        <td>鹿島</td>
									        <td>0</td>
									        <td>0</td>
									        <td>28500</td>
									        <td>15500</td>
									        <td>
												<button type="submit" class="btn btn-info">登録修正</button>
											</td>
									    </tr>
								    </tbody>
								</table>
							</div>
					    </div>
				    </div>
			    </div>

				<?php //echo $this->element('pager')?>

				<p>
				<?php echo $this->Html->link(('新規登録'), ['action' => 'add'], ['class'=>['btn btn-success']]); ?>
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->
