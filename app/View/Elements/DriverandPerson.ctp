
			<!-- 空きドライバ -->
			<div class="row">
			    <div class="box col-lg-6 col-md-6 col-sm-6 col-xs-6">
				    <div class="box-inner">
					    <div class="box-header well">空き乗務員
					    </div>
					    <div class="box-content">
							<ul>
									<?php
										foreach($vacancyusers2 as $vacancyuser) {
											echo '<li>';
													echo $vacancyuser;
											echo '</li>';
										}
									?>
							</ul>
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
											foreach($driversrest as $driver) {
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
