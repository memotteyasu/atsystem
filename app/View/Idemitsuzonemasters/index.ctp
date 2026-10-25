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
				<!-- /出光ゾーン選択 -->
				            <div class="control-group">
				            <label class="control-label" for="selectError">ゾーンCD</label>
				                <div class="controls">
				                    <select id="selectError" data-rel="chosen" style="width:150px;">
				                        <option>0554878</option>
				                        <option>0557560</option>
				                        <option>18201000</option>
				                        <option>18201010</option>
				                        <option>18201080</option>
				                        <option>18202000</option>
				                        <option>18203000</option>
				                        <option>18204000</option>
				                        <option>18205000</option>
				                    </select>
				                </div>
				            </div>
				     </form>
				   </div>
				</div>

			<!-- /出光ゾーン一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>出光ゾーンマスタ一覧</h2>
						    </div>
							<div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
									    <tr>
									        <th>ゾーンCD</th>
									        <th>ゾーン名称</th>
									        <th> </th>
									    </tr>
								    </thead>
								    <tbody>
									    <tr>
									        <td>0554878</td>
									        <td>スパホテルアルピナ</td>
									        <td>
												<?php echo $this->Html->link(('修正'), ['action' => 'edit'], ['class'=>['btn btn-info']]); ?>
												<?php //echo $this->Form->postLink(('削除'), ['action' => 'delete', $productdetailmaster['Productdetailmaster']['id']], ['confirm' => __('削除しますか？'), 'class'=>['btn btn-danger']]); ?>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>0557560</td>
									        <td>丸郷建設</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>18201000</td>
									        <td>福井市</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>18201010</td>
									        <td>福井市</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>18201080</td>
									        <td>福井Ｂ</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>18202000</td>
									        <td>敦賀市</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>18203000</td>
									        <td>武生市</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>18204000</td>
									        <td>小浜市</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>18205000</td>
									        <td>大野市</td>
									        <td>
												<button type="submit" class="btn btn-info">修正</button>
												<button type="button" class="btn btn-danger">削除</button>
											</td>
									    </tr>
									    <tr>
									        <td>18206000</td>
									        <td>勝山市</td>
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
