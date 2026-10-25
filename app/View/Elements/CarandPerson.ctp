
			<!-- 未配車管理 -->
			<div class="row">
				    <div class="box col-lg-6 col-md-6 col-sm-6 col-xs-6">
					    <div class="box-inner">
						    <div class="box-header well">車両情報
						    </div>
						    <div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<tbody>
										<?php
											//notruncarmasters->carmastersに変更した
											foreach($notruncarmasters as $carmaster) {
												echo '<tr>';
													echo '<td>';
														echo ($carmaster['Carmaster']['plateno']);
													echo '</td>';
													echo '<td>';
														$startdate = new DateTime($carmaster['Carmaster']['notrundatestart']);
														$enddate = new DateTime($carmaster['Carmaster']['notrundateend']);
														echo ($startdate->format('Y年m月d日')).'から'.($enddate->format('Y年m月d日')).'まで'.$carmaster['Carmaster']['notrunreason'];
													echo '</td>';
												echo '</tr>';
											}
										?>
									</tbody>
								</table>
							</div>
				  	  </div>
				  </div>
				    <div class="box col-lg-6 col-md-6 col-sm-6 col-xs-6">
					    <div class="box-inner">
						    <div class="box-header well">乗務員休み
						    </div>
						    <div class="box-content">
								<table class="table table-striped table-bordered bootstrap-datatable">
									<tbody>
										<?php
											foreach($drivers as $driver) {
												echo '<tr>';
													echo '<td>';
														echo ($driver['User']['usernameK']);
													echo '</td>';
													echo '<td>';
														$date = new DateTime($driver['Kintaitran']['kintaidate']);
														echo ($date->format('Y年m月d日')).($driver['Flgmaster']['flgname']);
													echo '</td>';
												echo '</tr>';
											}
										?>
									</tbody>
								</table>
							</div>
				  	  </div>
				  </div>
			</div>
			<!-- /配車管理 -->
