<?php
App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->SetMargins(10, 5, 10);

// set document information
//$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('PDF');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('asahitrans, transfer');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set auto page breaks
//$pdf->SetAutoPageBreak(true, 10);
$pdf->SetAutoPageBreak(false);

// ---------------------------------------------------------

	// ページを追加する
	$pdf->AddPage();

	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線,　テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	//印刷日時を取得
	$printday = new DateTime();

	//$pdf->Line(0, 3, 297, 3);

	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 20);
	$pdf->MultiCell('', '', '配車管理（回戦）', '0', 'C', 0, 1, '', '', true, 0, false, true, '', '');

	$pdf->SetFontSize(16);

	if ($branchmasters) {
		$branchname = $branchmasters['Branchmaster']['branchname'];
	} else {
		$branchname = '';
	}

	$pdf->MultiCell(120, '', $branchname, '0', 'L', 0, 0, '', '', true, 0, false, true, '', '');
	$pageno = $pdf->PageNo();
	$pdf->MultiCell('', '', '印刷日　'.$printday->format('Y年m月d日　H:i　').'P:'.$pageno, '0', 'R', 0, 1, '', '', true, 0, false, true, '', '');

	$pdf->Ln(5);

	$pdf->SetFillColor(187, 226, 241);
	$car2 = null;
	$kaigyo = 0;
	$text = null;

	$row = 7;

	$col1_1 = 20;
	$col1_2 = 85.7;

	$col2_1 = 20;
	$col2_2 = 85.7;

	$i = 0;

	//debug($truckkinds);
	//debug($ordertrans);

	foreach ($truckkinds as $truckkind) {
		if ($truckkind['Ordertran']) {
			$i = $i + 1;
			if ($i <= 24) {
				$car1 = $truckkind['Truckkind']['carmasters_id'];
				if ($car1 != $car2) {
					$pdf->SetFont('', 'B', 14);
					$pdf->MultiCell('', $row, $truckkind['Carmaster']['plateno'], '1', 'L', 1, 1, '', '', true, 0, false, true, $row, 'M');
				}

				foreach ($truckkind['Ordertran'] as $ordertran) {
					$i = $i + 1;
					if ($i <= 24) {
						$pdf->SetFont('', 'B' ,12);
						$pdf->MultiCell($col1_1, $row, $ordertran['kaisen'], '1', 'C', 0, 0, '', '', true, 0, false, true, $row, 'M');
						$pdf->SetFont('', '');
						$pdname = $ordertran['Productdetailmaster']['pdname'];
						if ($ordertran['Productcontainer']) {
							$pdconname = '（'.$ordertran['Productcontainer']['pdconname'].'）';
						} else {
							$pdconname = '（）';
						}
						$pdf->MultiCell($col1_2, $row, $pdname.$pdconname, '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
						$loadingtime = new DateTime($ordertran['loadingdate'].$ordertran['loadingtime']);
						$loading = $ordertran['Loadingmaster']['loadingname'].'（'.$loadingtime->format('H：i').'）';
						$pdf->MultiCell($col1_2, $row, $loading, '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
						$deliverytime = new DateTime($ordertran['deliverydate'].$ordertran['deliverytime']);
						$delivery = $ordertran['Deliverymaster']['deliveryname'].'（'.$deliverytime->format('H：i').'）';
						$pdf->MultiCell($col1_2, $row, $delivery, '1', 'L', 0, 1, '', '', true, 0, false, true, $row, 'M');

						if ($ordertran['Processtran']) {
							foreach ($ordertran['Processtran'] as $processtran) {
								$i = $i + 1;
								if ($i <= 24) {
									$pdf->MultiCell($col2_1, $row, $processtran['Workdetailmaster']['workname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
									$startdatetime = new DateTime($processtran['processstartdate'].$processtran['processstarttime']);
									$enddatetime = new DateTime($processtran['processenddate'].$processtran['processendtime']);
									$pdf->MultiCell($col2_2, $row, $startdatetime->format('n月d日 （H：i）'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
									$pdf->MultiCell($col2_2, $row, $enddatetime->format('n月d日 （H：i）'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
									$pdf->MultiCell($col2_2, $row, $processtran['workddetailcomment'], '1', 'L', 0, 1, '', '', true, 0, false, true, $row, 'M');
								} else {
									$i = 0;
									$pdf->AddPage();

									$pdf->SetFont('kozminproregular', '', 20);
									$pdf->MultiCell('', '', '配車管理（回戦）', '0', 'C', 0, 1, '', '', true, 0, false, true, '', '');

									$pdf->SetFontSize(16);

									$pdf->MultiCell(120, '', $branchname, '0', 'L', 0, 0, '', '', true, 0, false, true, '', '');
									$pageno = $pdf->PageNo();
									$pdf->MultiCell('', '', '印刷日　'.$printday->format('Y年m月d日　H:i　').'P:'.$pageno, '0', 'R', 0, 1, '', '', true, 0, false, true, '', '');

									$pdf->Ln(5);

									$i = $i + 1;

									$car1 = $truckkind['Truckkind']['carmasters_id'];
									if ($car1 != $car2) {
										$pdf->SetFont('', 'B', 14);
										$pdf->MultiCell('', $row, $truckkind['Carmaster']['plateno'], '1', 'L', 1, 1, '', '', true, 0, false, true, $row, 'M');
									}

									$pdf->SetFont('', 'B' ,12);
									$pdf->MultiCell($col1_1, $row, $ordertran['kaisen'], '1', 'C', 0, 0, '', '', true, 0, false, true, $row, 'M');
									$pdf->SetFont('', '');
									$pdname = $ordertran['Productdetailmaster']['pdname'];
									if ($ordertran['Productcontainer']) {
										$pdconname = '（'.$ordertran['Productcontainer']['pdconname'].'）';
									} else {
										$pdconname = '（）';
									}
									$pdf->MultiCell($col1_2, $row, $pdname.$pdconname, '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
									$loadingtime = new DateTime($ordertran['loadingdate'].$ordertran['loadingtime']);
									$loading = $ordertran['Loadingmaster']['loadingname'].'（'.$loadingtime->format('H：i').'）';
									$pdf->MultiCell($col1_2, $row, $loading, '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
									$deliverytime = new DateTime($ordertran['deliverydate'].$ordertran['deliverytime']);
									$delivery = $ordertran['Deliverymaster']['deliveryname'].'（'.$deliverytime->format('H：i').'）';
									$pdf->MultiCell($col1_2, $row, $delivery, '1', 'L', 0, 1, '', '', true, 0, false, true, $row, 'M');

									if ($ordertran['Processtran']) {
										foreach ($ordertran['Processtran'] as $processtran) {
											$i = $i + 1;
											$pdf->MultiCell($col2_1, $row, $processtran['Workdetailmaster']['workname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
											$startdatetime = new DateTime($processtran['processstartdate'].$processtran['processstarttime']);
											$enddatetime = new DateTime($processtran['processenddate'].$processtran['processendtime']);
											$pdf->MultiCell($col2_2, $row, $startdatetime->format('n月d日 （H：i）'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
											$pdf->MultiCell($col2_2, $row, $enddatetime->format('n月d日 （H：i）'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
											$pdf->MultiCell($col2_2, $row, $processtran['workddetailcomment'], '1', 'L', 0, 1, '', '', true, 0, false, true, $row, 'M');
										}
									}
									$car2 = $truckkind['Truckkind']['carmasters_id'];
									break;
								}
							}
						}
					} else {
						$i = 0;
						$pdf->AddPage();

						$pdf->SetFont('kozminproregular', '', 20);
						$pdf->MultiCell('', '', '配車管理（回戦）', '0', 'C', 0, 1, '', '', true, 0, false, true, '', '');

						$pdf->SetFontSize(16);

						$pdf->MultiCell(120, '', $branchname, '0', 'L', 0, 0, '', '', true, 0, false, true, '', '');
						$pageno = $pdf->PageNo();
						$pdf->MultiCell('', '', '印刷日　'.$printday->format('Y年m月d日　H:i　').'P:'.$pageno, '0', 'R', 0, 1, '', '', true, 0, false, true, '', '');

						$pdf->Ln(5);

						$i = $i + 1;

						$car1 = $truckkind['Truckkind']['carmasters_id'];
						if ($car1 != $car2) {
							$pdf->SetFont('', 'B', 14);
							$pdf->MultiCell('', $row, $truckkind['Carmaster']['plateno'], '1', 'L', 1, 1, '', '', true, 0, false, true, $row, 'M');
						}

						$pdf->SetFont('', 'B' ,12);
						$pdf->MultiCell($col1_1, $row, $ordertran['kaisen'], '1', 'C', 0, 0, '', '', true, 0, false, true, $row, 'M');
						$pdf->SetFont('', '');
						$pdname = $ordertran['Productdetailmaster']['pdname'];
						if ($ordertran['Productcontainer']) {
							$pdconname = '（'.$ordertran['Productcontainer']['pdconname'].'）';
						} else {
							$pdconname = '（）';
						}
						$pdf->MultiCell($col1_2, $row, $pdname.$pdconname, '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
						$loadingtime = new DateTime($ordertran['loadingdate'].$ordertran['loadingtime']);
						$loading = $ordertran['Loadingmaster']['loadingname'].'（'.$loadingtime->format('H：i').'）';
						$pdf->MultiCell($col1_2, $row, $loading, '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
						$deliverytime = new DateTime($ordertran['deliverydate'].$ordertran['deliverytime']);
						$delivery = $ordertran['Deliverymaster']['deliveryname'].'（'.$deliverytime->format('H：i').'）';
						$pdf->MultiCell($col1_2, $row, $delivery, '1', 'L', 0, 1, '', '', true, 0, false, true, $row, 'M');

						if ($ordertran['Processtran']) {
							foreach ($ordertran['Processtran'] as $processtran) {
								$i = $i + 1;
								$pdf->MultiCell($col2_1, $row, $processtran['Workdetailmaster']['workname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
								$startdatetime = new DateTime($processtran['processstartdate'].$processtran['processstarttime']);
								$enddatetime = new DateTime($processtran['processenddate'].$processtran['processendtime']);
								$pdf->MultiCell($col2_2, $row, $startdatetime->format('n月d日 （H：i）'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
								$pdf->MultiCell($col2_2, $row, $enddatetime->format('n月d日 （H：i）'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
								$pdf->MultiCell($col2_2, $row, $processtran['workddetailcomment'], '1', 'L', 0, 1, '', '', true, 0, false, true, $row, 'M');
							}
						}
						$car2 = $truckkind['Truckkind']['carmasters_id'];
					}
				}
				$car2 = $truckkind['Truckkind']['carmasters_id'];
			} else {
				$i = 0;
				$pdf->AddPage();

				$pdf->SetFont('kozminproregular', '', 20);
				$pdf->MultiCell('', '', '配車管理（回戦）', '0', 'C', 0, 1, '', '', true, 0, false, true, '', '');

				$pdf->SetFontSize(16);

				$pdf->MultiCell(120, '', $branchname, '0', 'L', 0, 0, '', '', true, 0, false, true, '', '');
				$pageno = $pdf->PageNo();
				$pdf->MultiCell('', '', '印刷日　'.$printday->format('Y年m月d日　H:i　').'P:'.$pageno, '0', 'R', 0, 1, '', '', true, 0, false, true, '', '');

				$pdf->Ln(5);

				$car1 = $truckkind['Truckkind']['carmasters_id'];
				if ($car1 != $car2) {
					$pdf->SetFont('', 'B', 14);
					$pdf->MultiCell('', $row, $truckkind['Carmaster']['plateno'], '1', 'L', 1, 1, '', '', true, 0, false, true, $row, 'M');
				}

				foreach ($truckkind['Ordertran'] as $ordertran) {
					$i = $i + 1;
					$pdf->SetFont('', 'B' ,12);
					$pdf->MultiCell($col1_1, $row, $ordertran['kaisen'], '1', 'C', 0, 0, '', '', true, 0, false, true, $row, 'M');
					$pdf->SetFont('', '');
					$pdname = $ordertran['Productdetailmaster']['pdname'];
					if ($ordertran['Productcontainer']) {
						$pdconname = '（'.$ordertran['Productcontainer']['pdconname'].'）';
					} else {
						$pdconname = '（）';
					}
					$pdf->MultiCell($col1_2, $row, $pdname.$pdconname, '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
					$loadingtime = new DateTime($ordertran['loadingdate'].$ordertran['loadingtime']);
					$loading = $ordertran['Loadingmaster']['loadingname'].'（'.$loadingtime->format('H：i').'）';
					$pdf->MultiCell($col1_2, $row, $loading, '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
					$deliverytime = new DateTime($ordertran['deliverydate'].$ordertran['deliverytime']);
					$delivery = $ordertran['Deliverymaster']['deliveryname'].'（'.$deliverytime->format('H：i').'）';
					$pdf->MultiCell($col1_2, $row, $delivery, '1', 'L', 0, 1, '', '', true, 0, false, true, $row, 'M');

					if ($ordertran['Processtran']) {
						foreach ($ordertran['Processtran'] as $processtran) {
							$i = $i + 1;
							$pdf->MultiCell($col2_1, $row, $processtran['Workdetailmaster']['workname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
							$startdatetime = new DateTime($processtran['processstartdate'].$processtran['processstarttime']);
							$enddatetime = new DateTime($processtran['processenddate'].$processtran['processendtime']);
							$pdf->MultiCell($col2_2, $row, $startdatetime->format('n月d日 （H：i）'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
							$pdf->MultiCell($col2_2, $row, $enddatetime->format('n月d日 （H：i）'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
							$pdf->MultiCell($col2_2, $row, $processtran['workddetailcomment'], '1', 'L', 0, 1, '', '', true, 0, false, true, $row, 'M');
						}
					}
				}
				$car2 = $truckkind['Truckkind']['carmasters_id'];
			}
		}
	}

$pdf->lastPage();
$pdf->Output('Co020s.pdf', 'I');
