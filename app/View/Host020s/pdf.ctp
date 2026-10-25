<?php
// debug($reservetran);

App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetMargins(10, 10, 10);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('使用量一覧');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('asahi');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// ---------------------------------------------------------

	// 印刷日時
	$printday = new DateTime();

	// ページを追加する
	$pdf->AddPage();

	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線,　テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 12);
	$pdf->Write(0, $paramsDate->format('Y年m月分'), '', 0, 'C', true, 0, false, false, 0);
	$pdf->Write(0, 'ガソリン使用量', '', 0, 'C', true, 0, false, false, 0);

	//$pdf->SetLineStyle(array('width' => 0.2));
	//$pdf->Line(205, 10, 205, 200);

	$pdf->SetFontSize(10);

	$pdf->MultiCell(95, 5, $kaisya['Kaisya']['companyname'], '0', 'L', 0, 0, '', '', true, 0, false, true, 5, 'B');

	$pdf->SetFontSize(8);
	$pdf->MultiCell('', 5, '印刷日　'.$printday->format('Y年m月d日　H:i').'　P:'.$pdf->PageNo(), '0', 'R', 0, 1, '', '', true, 0, false, true, 5, 'B');

	//$pdf->Ln(4);

	$col1 = 20;
	$col2 = 22;
	$col3 = 21;
	$col4 = $col3 * 2;

	$row1 = 5;

	// 見出し1ページ目
	$pdf->MultiCell($col1, 0, '', 'LT', 'C', 0, 0, '', '', true, 0, false, true, 0, 'M');
	$pdf->MultiCell($col2, 0, '', 'LT', 'C', 0, 0, '', '', true, 0, false, true, 0, 'M');
	$pdf->MultiCell($col4, 0, 'ガソリン', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'M');
	$pdf->MultiCell($col4, 0, '軽油', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'M');
	$pdf->MultiCell($col4, 0, 'オイル', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'M');
	$pdf->MultiCell($col3, 0, '', '1', 'C', 0, 1, '', '', true, 0, false, true, 0, 'M');

	$pdf->MultiCell($col1, 0, '給油日', 'L', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($col2, 0, '名　前', 'L', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($col3, 0, '量', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($col3, 0, '金額', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($col3, 0, '量', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($col3, 0, '金額', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($col3, 0, '量', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($col3, 0, '金額', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($col3, 0, '合計（税込）', '1', 'C', 0, 1, '', '', true, 0, false, true, 0, 'T');

	$i = 0;
	$gasgoukei_a = $dieselgoukei_a = $oilgoukei_a = 0;
	$gasgoukei_p = $dieselgoukei_p = $oilgoukei_p = 0;
	$sougoukei = 0;
	foreach ($gasusetrans as $gasusetran) {
		// debug($gasusetran);
		$gas = $diesel = $oil = 0;
		$gasprice = $dieselprice = $oilprice = 0;
		$goukei = 0;
		if ($i < 50) {
			$pdf->MultiCell($col1, $row1, $gasusetran['Gasusetran']['chargedate'], '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col2, $row1, $gasusetran['User']['usernameK'], '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');

			switch ($gasusetran['Gasusetran']['gaskind']) {
				case 31:
					$gas = $gasusetran['Gasusetran']['amount'];
					$gasprice = $kaisya['Kaisya']['gasprice'] * $gas;
					$goukei = $goukei + round($gasprice);
					$gasgoukei_a = $gasgoukei_a + $gas;
					$gasgoukei_p = $gasgoukei_p + round($gasprice);
					break;
				case 32:
					$diesel = $gasusetran['Gasusetran']['amount'];
					$dieselprice = $kaisya['Kaisya']['dieselprice'] * $diesel;
					$goukei = $goukei + round($dieselprice);
					$dieselgoukei_a = $dieselgoukei_a + $diesel;
					$dieselgoukei_p = $dieselgoukei_p + round($dieselprice);
					break;
				case 34:
					$oil = $gasusetran['Gasusetran']['amount'];
					$oilprice = $kaisya['Kaisya']['oilprice'] * $oil;
					$goukei = $goukei + round($oilprice);
					$oilgoukei_a = $oilgoukei_a + $oil;
					$oilgoukei_p = $oilgoukei_p + round($oilprice);
					break;
			}
			$sougoukei = $sougoukei + $goukei;
			$pdf->MultiCell($col3, $row1, number_format($gas,2), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col3, $row1, number_format($gasprice), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col3, $row1, number_format($diesel,2), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col3, $row1, number_format($dieselprice), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col3, $row1, number_format($oil,2), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col3, $row1, number_format($oilprice), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col3, $row1, number_format($goukei), '1', 'R', 0, 1, '', '', true, 0, false, true, $row1, 'M');

			$i = $i + 1;
		} else {
			$i = 0;
			$pdf->SetFontSize(12);
			$pdf->Write(0, $paramsDate->format('Y年m月分'), '', 0, 'C', true, 0, false, false, 0);
			$pdf->Write(0, 'ガソリン使用量', '', 0, 'C', true, 0, false, false, 0);

			$pdf->SetFontSize(10);

			$pdf->MultiCell(95, 5, $kaisya['Kaisya']['companyname'], '0', 'L', 0, 0, '', '', true, 0, false, true, 5, 'B');

			$pdf->SetFontSize(8);
			$pdf->MultiCell('', 5, '印刷日　'.$printday->format('Y年m月d日　H:i').'　P:'.$pdf->PageNo(), '0', 'R', 0, 1, '', '', true, 0, false, true, 5, 'B');

			$pdf->MultiCell($col1, 0, '', 'LT', 'C', 0, 0, '', '', true, 0, false, true, 0, 'M');
			$pdf->MultiCell($col2, 0, '', 'LT', 'C', 0, 0, '', '', true, 0, false, true, 0, 'M');
			$pdf->MultiCell($col4, 0, 'ガソリン', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'M');
			$pdf->MultiCell($col4, 0, '軽油', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'M');
			$pdf->MultiCell($col4, 0, 'オイル', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'M');
			$pdf->MultiCell($col3, 0, '', '1', 'C', 0, 1, '', '', true, 0, false, true, 0, 'M');

			$pdf->MultiCell($col1, 0, '給油日', 'L', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
			$pdf->MultiCell($col2, 0, '名　前', 'L', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
			$pdf->MultiCell($col3, 0, '量', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
			$pdf->MultiCell($col3, 0, '金額', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
			$pdf->MultiCell($col3, 0, '量', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
			$pdf->MultiCell($col3, 0, '金額', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
			$pdf->MultiCell($col3, 0, '量', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
			$pdf->MultiCell($col3, 0, '金額', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
			$pdf->MultiCell($col3, 0, '合計（税込）', '1', 'C', 0, 1, '', '', true, 0, false, true, 0, 'T');
		}
	}
	$pdf->MultiCell($col1, $row1, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col2, $row1, '合計', '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col3, $row1, number_format($gasgoukei_a), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col3, $row1, number_format($gasgoukei_p), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col3, $row1, number_format($dieselgoukei_a), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col3, $row1, number_format($dieselgoukei_p), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col3, $row1, number_format($oilgoukei_a), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col3, $row1, number_format($oilgoukei_p), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col3, $row1, number_format($sougoukei), '1', 'R', 0, 1, '', '', true, 0, false, true, $row1, 'M');

$pdf->lastPage();
$pdf->Output('gasusetrans.pdf', 'I');
