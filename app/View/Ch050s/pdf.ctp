<?php
App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->SetMargins(10, 5, 10);

// set document information
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('PDF');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default header data

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

	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 20);
	$pdf->MultiCell('', '', $paramsDate->format('Y年m月d日').'　受注一覧', '0', 'C', 0, 1, '', '', true, 0, false, true, '', '');

	$pdf->SetFontSize(16);

	if ($branchmasters) {
		$branchname = $branchmasters['Branchmaster']['branchname'];
	} else {
		$branchname = '';
	}

	$pdf->MultiCell(40, '', $branchname, '0', 'L', 0, 0, '', '', true, 0, false, true, '', '');
	$pdf->MultiCell(40, '', $department, '0', 'L', 0, 0, '', '', true, 0, false, true, '', '');
	$pdf->MultiCell('', '', '印刷日　'.$printday->format('Y年m月d日　H:i　').'P'.$pdf->PageNo(), '0', 'R', 0, 1, '', '', true, 0, false, true, '', '');

	$pdf->Ln(5);

	$i = 0;

	$col1 = 85; // 車番＋（化学物流）＋商品名
	$col2 = 10; // 回戦
	$col3 = 15; // 時間
	$col4 = 60; // workplace
	$col5 = 7; // －
	$col6 = 25; // 日付

	$row1 = 5;

	$pdf->SetFontSize(11);

	/*
	 * $workplace2 = $ordertran['Workplace2']['workplaceshortname'] ?: '';
	 * 上記の処理はエルビス演算子という処理を利用している
	 * $workplace2 = '';
	 * if(isset($ordertran['Workplace2']['workplaceshortname'])) {
	 * 	$workplace2 = $ordertran['Workplace2']['workplaceshortname'];
	 * }
	 * やっていることは上記の処理と同じ　表記を短くするために利用している
	 */

	$car1 = $car2 = $pd1 = $pd2 = null;

	foreach ($ordertrans as $ordertran) {
		if ($i <= 33) {
			// debug($ordertran);
			$car1 = $ordertran['Ordertran']['carmasters_id'];
			$pd1 = $ordertran['Ordertran']['productdetailmasters_id'];

			if ($car1 == $car1 && $pd1 == $pd2) {
				if ($i == 0) {
					$carname = $ordertran['Carmaster']['plateno'];
					$pdname = $ordertran['Productdetailmaster']['pdname'];
					$carpdname = $carname.'（化学物流）'.$pdname;
				} else {
					$carname = '';
					$pdname = '';
					$carpdname = $carname.''.$pdname;
				}
			} else {
				$carname = $ordertran['Carmaster']['plateno'];
				$pdname = $ordertran['Productdetailmaster']['pdname'];
				$carpdname = $carname.'（化学物流）'.$pdname;
			}

			$kaisen = $ordertran['Ordertran']['kaisen'];
			$startdate = new Datetime($ordertran['Ordertran']['loadingdate'].$ordertran['Ordertran']['loadingtime']);
			// $workplace1 = $ordertran['Workplace1']['workplaceshortname'];
			$workplace1 = $ordertran['Workplace1']['workplaceshortname'] ?: '';
			$enddate = new Datetime($ordertran['Ordertran']['deliverydate'].$ordertran['Ordertran']['deliverytime']);
			// $workplace2 = $ordertran['Workplace2']['workplaceshortname'];
			$workplace2 = $ordertran['Workplace2']['workplaceshortname'] ?: '';

			$pdf->MultiCell($col1, $row1, $carpdname, '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col2, $row1, $kaisen.'回', '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col3, $row1, $startdate->format('H:i'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col4, $row1, $workplace1, '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, '－', '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col6, $row1, $enddate->format('m月d日'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col3, $row1, $enddate->format('H:i'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col4, $row1, $workplace2, '1', 'L', 0, 1, '', '', true, 0, false, true, $row1, 'M');

			$car2 = $ordertran['Ordertran']['carmasters_id'];
			$pd2 = $ordertran['Ordertran']['productdetailmasters_id'];

			$i = $i + 1;
		} else {
			$pdf->AddPage();
			$i = 0;

			$pdf->SetFontSize(20);
			$pdf->MultiCell('', '', $paramsDate->format('Y年m月d日').'　受注一覧', '0', 'C', 0, 1, '', '', true, 0, false, true, '', '');

			$pdf->SetFontSize(16);
			$pdf->MultiCell(40, '', $branchname, '0', 'L', 0, 0, '', '', true, 0, false, true, '', '');
			$pdf->MultiCell(40, '', $department, '0', 'L', 0, 0, '', '', true, 0, false, true, '', '');
			$pdf->MultiCell('', '', '印刷日　'.$printday->format('Y年m月d日　H:i　').'P'.$pdf->PageNo(), '0', 'R', 0, 1, '', '', true, 0, false, true, '', '');

			$pdf->Ln(5);
			$pdf->SetFontSize(11);
		}
	}

$pdf->lastPage();
$pdf->Output('Ch050s.pdf', 'I');
