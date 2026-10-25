<?php
// debug($reservetran);

App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetMargins(10, 10, 10);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('receipt');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('tankback, transfer');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// ---------------------------------------------------------

	// ページを追加する
	$pdf->AddPage();


	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線,　テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 12);
	$pdf->Write(0, '2016年12月分', '', 0, 'C', true, 0, false, false, 0);
	$pdf->Write(0, 'ガソリン使用量', '', 0, 'C', true, 0, false, false, 0);

	//$pdf->SetLineStyle(array('width' => 0.2));
	//$pdf->Line(205, 10, 205, 200);

	$pdf->SetFontSize(10);

	$pdf->MultiCell(95, 5, '朝日運輸', '0', 'L', 0, 0, '', '', true, 0, false, true, 5, 'B');

	$pdf->SetFontSize(8);
	$pdf->MultiCell(95, 5, '印刷日　2017年01月18日　10:56', '0', 'R', 0, 1, '', '', true, 0, false, true, 5, 'B');

	//$pdf->Ln(4);

	$Column1 = 19;
	$Column2 = 19+19;
	$Column3 = 19*3;
	$T = 5;
	$T2 = 10;

	// 1行目

	$pdf->SetFontSize(8);

	$pdf->MultiCell($Column1, 0, '', 'LT', 'C', 0, 0, '', '', true, 0, false, true, 0, 'M');
	$pdf->MultiCell($Column1, 0, '', 'LT', 'C', 0, 0, '', '', true, 0, false, true, 0, 'M');
	$pdf->MultiCell($Column2, 0, 'ガソリン', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'M');
	$pdf->MultiCell($Column3, 0, '軽油', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'M');
	$pdf->MultiCell($Column2, 0, 'オイル', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'M');
	$pdf->MultiCell($Column1, 0, '', '1', 'C', 0, 1, '', '', true, 0, false, true, 0, 'M');

	$pdf->MultiCell($Column1, 0, 'CD', 'L', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($Column1, 0, '名　前', 'L', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($Column1, 0, '量', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($Column1, 0, '金額', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($Column1, 0, '量', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($Column1, 0, '金額', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($Column1, 0, '内手数料', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($Column1, 0, '量', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($Column1, 0, '金額', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, 'T');
	$pdf->MultiCell($Column1, 0, '合計（税込）', '1', 'C', 0, 1, '', '', true, 0, false, true, 0, 'T');

	for ($count = 1; $count <= 20; $count++) {

		$pdf->MultiCell($Column1, $T, '00055', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column1, $T, '古久保義経', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column1, $T, '230.00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column1, $T, '24,150', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column1, $T, '36.00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column1, $T, '2,800', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column1, $T, '50,000', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column1, $T, '0.00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column1, $T, '0', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column1, $T, '30,000', '1', 'R', 0, 1, '', '', true, 0, false, true, $T, 'M');

	}

	$pdf->MultiCell($Column1, $T, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column1, $T, '合計', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column1, $T, '230.00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column1, $T, '24,150', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column1, $T, '36.00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column1, $T, '2,800', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column1, $T, '50,000', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column1, $T, '0.00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column1, $T, '0', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column1, $T, '30,000', '1', 'R', 0, 1, '', '', true, 0, false, true, $T, 'M');


$pdf->lastPage();
$pdf->Output('HelloWorld.pdf', 'I');
