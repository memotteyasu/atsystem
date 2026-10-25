<?php
// debug($reservetran);

App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, 'A3', true, 'UTF-8', false);

$pdf->SetMargins(10, 10, 0);

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

	$pdf->Write(0, '拘束時間・時間外一覧表　2017年01月', '', 0, 'L', true, 0, false, false, 0);

//	$pdf->SetLineStyle(array('width' => 0.2));
//	$pdf->Line(291, 10, 291, 36);

	$pdf->MultiCell(180, '', '名古屋　石油', '0', 'L', 0, 0, '', '', true, 0, false, true, 10, 'B');
	$pdf->MultiCell(200, '', 'P.1', '0', 'R', 0, 1, '', '', true, 0, false, true, 10, 'B');

	//$pdf->Ln(4);

	$Column1 = 10;
	$Column2 = 10;
	$Column3 = 39.5;
	$Column4 = 11;
	$ColumnTime = 10;
	$T = 7;
	$T2 = 10;

	// 1行目
	$pdf->SetFontSize(10);

	$pdf->MultiCell(20, $T, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell(10, $T, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');

	for ($count = 1; $count <= 14; $count++) {
		$pdf->MultiCell($Column1, $T, $count, '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'B');
	}

	$pdf->MultiCell(12, $T, '※', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'B');

	for ($count = 15; $count <= 28; $count++) {
		$pdf->MultiCell($Column1, $T, $count, '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'B');
	}

	$pdf->MultiCell(12, $T, '※', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'B');

	for ($count = 29; $count <= 31; $count++) {
		$pdf->MultiCell($Column1, $T, $count, '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'B');
	}

	$pdf->MultiCell(16, $T, '合計', '1', 'L', 0, 1, '', '', true, 0, false, true, $T, 'B');


	for ($count = 1; $count <= 8; $count++) {

		$pdf->SetFillColor(191, 191, 191);

		$pdf->MultiCell(20, $T, '黒川光徳', '1', 'L', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell(10, $T, '拘束', '1', 'C', 1, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->SetFontSize(8);

		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell(12, '', '000:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell(12, '', '000:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->SetFontSize(10);

		$pdf->MultiCell(16, '', '119:12', '1', 'R', 1, 1, '', '', true, 0, false, true, $T, 'B');


		//


		$pdf->MultiCell(20, '', '', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell(10, '', '残業', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->SetFontSize(8);

		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell(12, '', '000:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell(12, '', '000:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->SetFontSize(10);

		$pdf->MultiCell(16, '', '119:12', '1', 'R', 0, 1, '', '', true, 0, false, true, $T, 'B');


		//


		$pdf->SetFillColor(216, 216, 216);

		$pdf->MultiCell(20, '', '', '1', 'C', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell(10, '', '運転', '1', 'C', 1, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->SetFontSize(8);

		$pdf->SetFontSize(8);

		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell(12, '', '000:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell(12, '', '000:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->SetFontSize(10);

		$pdf->MultiCell(16, '', '119:12', '1', 'R', 1, 1, '', '', true, 0, false, true, $T, 'B');


		//


		$pdf->MultiCell(20, '', '', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell(10, '', '平均', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->SetFontSize(8);

		$pdf->MultiCell(15, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell(22, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell(22, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');
		$pdf->MultiCell($Column1, '', '00:00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->MultiCell(5, '', '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'B');

		$pdf->SetFontSize(10);

		$pdf->MultiCell(16, '', '', '1', 'R', 0, 1, '', '', true, 0, false, true, $T, 'B');

	}

$pdf->lastPage();
$pdf->Output('test.pdf', 'I');
