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
$pdf->SetAutoPageBreak(true, 10);

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
	$pdf->MultiCell('', '', '印刷日　'.$printday->format('Y年m月d日　H:i　'), '0', 'R', 0, 1, '', '', true, 0, false, true, '', '');

	$pdf->Ln(5);

	$pdf->SetFillColor(187, 226, 241);
	$car2 = null;
	$kaigyo = 0;
	$text = null;

	$row = 7;

	$col1_1 = 15;
	$col1_2 = 87.3;

	$col2_1 = 15;
	$col2_2 = 87.3;

	$i = 0;

	foreach ($orderlists as $orderlist) {
		if ($i <= 24) {
			$i = $i + 1;
			$car1 = $orderlist['Ordertran']['carmasters_id'];
			if ($car1 != $car2) {
				$pdf->SetFont('', 'B', 14);
				$pdf->MultiCell('', $row, $orderlist['Carmaster']['plateno'], '1', 'L', 1, 1, '', '', true, 0, false, true, $row, 'M');
			}
			foreach ($ordertrans as $ordertran) {
				$pdf->SetFontSize(12);
				if ($orderlist['Ordertran']['truckkinds_id'] == $ordertran['Ordertran']['truckkinds_id']) {
					if ($i <= 24) {
						$i = $i + 1;
						$pdf->SetFont('', 'B');
						$pdf->MultiCell($col1_1, $row, $ordertran['Ordertran']['kaisen'], '1', 'C', 0, 0, '', '', true, 0, false, true, $row, 'M');
						$pdf->SetFont('', '');
						$pdf->MultiCell($col1_2, $row, $ordertran['Productdetailmaster']['pdname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
						$deliverytime = new DateTime($ordertran['Ordertran']['deliverydate'].$ordertran['Ordertran']['deliverytime']);
						$pdf->MultiCell($col1_2, $row, $ordertran['Deliverymaster']['deliveryname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
						$pdf->MultiCell($col1_2, $row, $deliverytime->format('H：i'), '1', 'C', 0, 1, '', '', true, 0, false, true, $row, 'M');
					} else {
						$i = 0;
						$pdf->AddPage();
						$i = $i + 1;
						$pdf->SetFont('', 'B');
						$pdf->MultiCell($col1_1, $row, $ordertran['Ordertran']['kaisen'], '1', 'C', 0, 0, '', '', true, 0, false, true, $row, 'M');
						$pdf->SetFont('', '');
						$pdf->MultiCell($col1_2, $row, $ordertran['Productdetailmaster']['pdname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
						$deliverytime = new DateTime($ordertran['Ordertran']['deliverydate'].$ordertran['Ordertran']['deliverytime']);
						$pdf->MultiCell($col1_2, $row, $ordertran['Deliverymaster']['deliveryname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
						$pdf->MultiCell($col1_2, $row, $deliverytime->format('H：i'), '1', 'C', 0, 1, '', '', true, 0, false, true, $row, 'M');
					}

					if(isset($ordertran['Processtran'])) {
						foreach ($ordertran['Processtran'] as $processtran) {
							if ($i <= 24) {
								$i = $i + 1;
								$pdf->MultiCell($col2_1, $row, $workdetailmasters[$processtran['workdetailmasters_id']], '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
								$startdatetime = new DateTime($processtran['processstartdate'].$processtran['processstarttime']);
								$enddatetime = new DateTime($processtran['processenddate'].$processtran['processendtime']);
								$pdf->MultiCell($col2_2, $row, $startdatetime->format('n月d日 （H：i）'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
								$pdf->MultiCell($col2_2, $row, $enddatetime->format('n月d日 （H：i）'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
								$pdf->MultiCell($col2_2, $row, $processtran['workddetailcomment'], '1', 'L', 0, 1, '', '', true, 0, false, true, $row, 'M');

							} else {
								$i = 0;
								$pdf->AddPage();
								$i = $i + 1;
								$pdf->MultiCell($col2_1, $row, $workdetailmasters[$processtran['workdetailmasters_id']], '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
								$startdatetime = new DateTime($processtran['processstartdate'].$processtran['processstarttime']);
								$enddatetime = new DateTime($processtran['processenddate'].$processtran['processendtime']);
								$pdf->MultiCell($col2_2, $row, $startdatetime->format('n月d日 （H：i）'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
								$pdf->MultiCell($col2_2, $row, $enddatetime->format('n月d日 （H：i）'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
								$pdf->MultiCell($col2_2, $row, $processtran['workddetailcomment'], '1', 'L', 0, 1, '', '', true, 0, false, true, $row, 'M');
							}
						}
					}
				}
			}
			$car2 = $orderlist['Ordertran']['carmasters_id'];
		} else {
			$i = 0;
			$pdf->AddPage();
			$i = $i + 1;
			$car1 = $orderlist['Ordertran']['carmasters_id'];
			if ($car1 != $car2) {
				$pdf->SetFont('', 'B', 14);
				$pdf->MultiCell('', $row, $orderlist['Carmaster']['plateno'].'-'.$i.'--'.$s, '1', 'L', 1, 1, '', '', true, 0, false, true, $row, 'M');
			}
			foreach ($ordertrans as $ordertran) {
				$pdf->SetFontSize(12);
				if ($orderlist['Ordertran']['truckkinds_id'] == $ordertran['Ordertran']['truckkinds_id']) {
					$i = $i + 1;
					$pdf->SetFont('', 'B');
					$pdf->MultiCell($col1_1, $row, $ordertran['Ordertran']['kaisen'].'-'.$i, '1', 'C', 0, 0, '', '', true, 0, false, true, $row, 'M');
					$pdf->SetFont('', '');
					$pdf->MultiCell($col1_2, $row, $ordertran['Productdetailmaster']['pdname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
					$deliverytime = new DateTime($ordertran['Ordertran']['deliverydate'].$ordertran['Ordertran']['deliverytime']);
					$pdf->MultiCell($col1_2, $row, $ordertran['Deliverymaster']['deliveryname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
					$pdf->MultiCell($col1_2, $row, $deliverytime->format('H：i'), '1', 'C', 0, 1, '', '', true, 0, false, true, $row, 'M');

					if(isset($ordertran['Processtran'])) {
						foreach ($ordertran['Processtran'] as $processtran) {
							$i = $i + 1;

							$pdf->MultiCell($col2_1, $row, $workdetailmasters[$processtran['workdetailmasters_id']].'-'.$i, '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
							$startdatetime = new DateTime($processtran['processstartdate'].$processtran['processstarttime']);
							$enddatetime = new DateTime($processtran['processenddate'].$processtran['processendtime']);
							$pdf->MultiCell($col2_2, $row, $startdatetime->format('n月d日 （h：i）'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
							$pdf->MultiCell($col2_2, $row, $enddatetime->format('n月d日 （h：i）'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row, 'M');
							$pdf->MultiCell($col2_2, $row, $processtran['workddetailcomment'], '1', 'L', 0, 1, '', '', true, 0, false, true, $row, 'M');
						}
					}
				}
			}
			$car2 = $orderlist['Ordertran']['carmasters_id'];
		}
	}


$pdf->lastPage();
$pdf->Output('Ch020s.pdf', 'I');
