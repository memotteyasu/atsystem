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
				<!-- /積卸地選択 -->
				            <div class="control-group">
				            <label class="control-label" for="selectError">積卸地</label>
				                <div class="controls">
				                    <select id="selectError" data-rel="chosen" style="width:300px;">
				                        <option>関西触媒化学</option>
				                        <option>アクトス大垣</option>
				                        <option>谷建材</option>
				                        <option>北日本物産(株)八日市営業所</option>
				                        <option>大塚近江陶業</option>
				                        <option>北日本物産（株)滋賀営業所</option>
				                        <option>フライスター滋賀</option>
				                        <option>山川滋賀</option>
				                        <option>全農野洲</option>
				                        <option>日本製箔滋賀</option>
				                    </select>
				                </div>
				            </div>
				     </form>
				   </div>
				</div>

			<!-- /積卸地一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>出光積卸地マスタ一覧</h2>
						    </div>
							    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th>積卸地CD</th>
									        <th>積卸地名称</th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
									    <tr>
									        <td>56668</td>
									        <td>関西触媒化学</td>
									        <td>
												<?php echo $this->Html->link(('修正'), ['action' => 'edit'], ['class'=>['btn btn-info']]); ?>
												<?php //echo $this->Form->postLink(('削除'), ['action' => 'delete', $productdetailmaster['Productdetailmaster']['id']], ['confirm' => __('削除しますか？'), 'class'=>['btn btn-danger']]); ?>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>57662</td>
									        <td>アクトス大垣/td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>78607</td>
									        <td>谷建材</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>101164</td>
									        <td>北日本物産(株)八日市営業所</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>101180</td>
									        <td>大塚近江陶業</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>101181</td>
									        <td>北日本物産（株)滋賀営業所</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>101184</td>
									        <td>フライスター滋賀</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>101195</td>
									        <td>山川滋賀</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>101197</td>
									        <td>全農野洲</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>101198</td>
									        <td>日本製箔滋賀</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
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
