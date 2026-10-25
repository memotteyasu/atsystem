<!-- コンテナ受注カレンダー（test用をindexにリネーム中 -->
<!-- Co000s -->
<style>
	table.table-clickable td[data-href]{
		cursor: pointer;
	}
	table.table-clickable td:hover{
		background-color:#e8e8e8 !important;
	}
</style>

<div class="ch-container">
	<div class="row">

	<!-- left menu starts -->
		<?php echo $this->element('Sidemenu'); ?>
	<!-- left menu ends -->

	<div id="content" class="col-lg-10 col-md-10 col-sm-10">
	<!-- content starts -->
		<div>
			<ul class="breadcrumb">
				<li>
					<?php
						$this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
						echo $this->element('breadcrumbs');
					?>
					<?php echo $this->Flash->render(); ?>
				</li>
			</ul>
		</div>

		<?php echo $this->element('Co0001sForm'); ?>
		<!-- /荷主一覧 -->
		<div class="row">
			<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="box-inner">
					<div class="box-header well">
						<h2><i class="glyphicon glyphicon-info-sign"></i>受注カレンダー</h2>
					</div>
					<div class="box-content">
						<!--<div class="table-responsive">-->
						<table class="table table-striped table-bordered table-clickable">
							<thead>
								<tr>
									<th>車両番号</th>
										<?php
											$date = new DateTime($paramsDate);
											for ($i = 1; $i <= 7; $i++) {
												echo '<th>';

												if ($i == 1) {
													$date2 = $date->format('m月d日');
												} else {
													$date2 = $date->modify('+1 day')->format('m月d日');
												}

												if ($week[$date->format('w')] == '日') {
													echo $date2.'<font color="#ff0000">('.$week[$date->format('w')].')</font>';
												} else {
													echo $date2.'('.$week[$date->format('w')].')';
												}

												echo '</th>';
											}
										?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($truckkinds as $truckkind): ?>
								<!--
									<tr style="color:#ff0000;">
										<td colspan="8"></td>
									</tr>
								-->
									<tr>
										<td>
											<?php
												if ($truckkind['Carmaster']['trailermasters_id'] != 0) {
													$w_type = '（'.$truckkind['Carmaster']['Trailermaster']['trailerkind'].'）';
												} else {
													$w_type = null;
												}
												echo $truckkind['Carmaster']['plateno'].'<br>'.'<font style="font-size:x-small;">'.$w_type.'</font>';
											?>
										</td>
										<?php
											$date = new DateTime($paramsDate);
											for ($i = 1; $i <= 7; $i++):
												$url = $this->Html->url([
													'controller'=>'Co000s',
													'action'=>'add',
													0,
													'id' => $truckkind['Truckkind']['id'],
													'sid' => $truckkind['Truckkind']['shippermasters_id'],
													'pid' => $truckkind['Truckkind']['productdetailmasters_id'],
													'cid' => $truckkind['Truckkind']['carmasters_id'],
													'date' => $date->format('Y-m-d')
												]);
										?>
											<td data-href="<?= $url; ?>">
												<?php
													foreach ($ordertrans as $ordertran) {
														/*
														 * ここで車両番号によるオーダーの表示制御
														 * carmasters_idでTruckkindをGroupByしているので、Ordertran.carmasters_idが同じかどうか判定
														 */
														if ($truckkind['Truckkind']['carmasters_id'] == $ordertran['Ordertran']['carmasters_id']) {
															$judgmentdate = $date->format('Y-m-d');
															$firstdate = $ordertran['Ordertran']['loadingdate'];
															$lastdate = $ordertran['Ordertran']['deliverydate'];

															$color = $ordertran['Ordertran']['color'];

															/* 背景色に対して見やすい文字色にするため輝度による判別を行っている */
															$rgb = ['r' => 255, 'g' => 255, 'b' => 255];
															$lum = 140;	// 輝度信号 Y の白黒分岐点 (128 がジャスト真ん中)

															// カラーコードを RGB に分割する
															if (stripos($color, '#') !== false) {
																$colorcode = str_replace('#', '', $color);

																if (ctype_xdigit($colorcode) && strlen($colorcode) >= 6) {
																	$rgb['r'] = hexdec(substr($colorcode, 0, 2));
																	$rgb['g'] = hexdec(substr($colorcode, 2, 2));
																	$rgb['b'] = hexdec(substr($colorcode, 4, 2));
																}
															}

															// RGB から YUV の輝度信号に変換
															$yuv = 0.299 * $rgb['r'] + 0.587 * $rgb['g'] + 0.114 * $rgb['b'];
															$textcolor = ($yuv >= $lum) ? '#000' : '#fff';
															/* 輝度による文字色判別ここまで */

															/* ここからProcesstran */
															if ($judgmentdate >= $firstdate && $judgmentdate <= $lastdate) {
																$text1 = $text2 = $text3 = $text4 = null;
																if ($ordertran['Processtran']) {
																	foreach ($ordertran['Processtran'] as $processtran) {
																		if ($processtran['processstartdate'] == $judgmentdate) {
																			if (isset($processtran['Workplace']['workplaceshortname'])) {
																				$workplace = '→'.$processtran['Workplace']['workplaceshortname'];
																				$workname = '（'.$processtran['Workdetailmaster']['workname'].'）';
																			} else {
																				$workplace = null;
																				$workname = '（'.$processtran['Workdetailmaster']['workname'].'）';
																			}
																			$text1[] = $workplace.$workname;
																			$text2 = implode(nl2br("\n"), $text1);
																		} else {
																			if (!$text1) {
																				$text2 = '該当工程なし';
																			}
																			$text4=null;
																			/*
																			if ($processtran['processstartdate'] < $judgmentdate) {
																				$text3 = $processtran['Workdetailmaster']['workname'];
																				if (strpos($text3, '空ON') === false || strpos($text3, '実入ON') === false) {
																					$text4 = '<'.$processtran['Workplace']['workplaceshortname'].'>（'.$processtran['Workdetailmaster']['workname'].'）'.nl2br("\n");
																				} elseif (strpos($text3, '空OFF') !== false || strpos($text3, '実入OFF') !== false) {
																					$text4 = '無し'.nl2br("\n");
																				}
																			}
																			*/
																		}
																	}
																} else {
																	$text2 = '工程未登録';
																}

																//$badge = '<span class="label label-primary">オーダー番号：'.$ordertran['id'].'</span> '.nl2br("\n");

																$loading = new DateTime($firstdate);
																$delivery = new DateTime($lastdate);
																//承認済みのチェック
																if($ordertran['Ordertran']['check1flg']==1){
																	$checkmark = '○';
																}else{
																	$checkmark = '';
																}
																$badge2 = '<span class="label" style="background-color:'.$color.'; color:'.$textcolor.'">'.$loading->format('m月d日').'〜'.$delivery->format('m月d日').'</span> '.$checkmark.nl2br("\n");

																if (strpos($text2, '該当工程なし') !== false || strpos($text2, '工程未登録') !== false) {
																	//なにもしない
																} else {
																	if ($text4) {
																		$text2 = $text4.$text2;
																	}
																}

																$linktext = '<div class="well well-sm">'.$badge2.'<h6>'.$text2.'</h6></div>';
																if($ordertran['Ordertran']['correctdate']){
																	echo $linktext;
																}else{
																	echo $this->Html->link($linktext, ['controller' => 'Co000s', 'action' => 'edit', $ordertran['Ordertran']['id'], 0], ['escape' => false]);
																}
															}
														}
													}
												?>
											</td>
										<?php
											$date->modify('+1 day');
											endfor;
										?>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<!--</div>-->
					</div>
				</div>
			</div>
		</div>

		<?php //echo $this->element('pager')?>

		<p>
			<?php // echo $this->Html->link(('新規登録'), ['controller' => 'Co000s', 'action' => 'add', 0], ['class'=>['btn btn-success']]); ?>
		</p>

		<!-- CarandPerson 呼び出し -->
		<?php echo $this->element('CarandPerson'); ?>

	<!-- content ends -->
	</div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

<script>
$(function(){
  $('td[data-href]', 'table.table-clickable').on('click', function(){
    location.href = $(this).data('href');
  });
});
</script>
