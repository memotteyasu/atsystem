<?php
// debug($reservetran);

App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetMargins(5, 10, 0);

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

//	$pdf->SetLineStyle(array('width' => 0.2));
//	$pdf->Line(291, 10, 291, 36);

	$pdf->SetFontSize(14);

	$pdf->MultiCell(180, '', '運　転　日　報　一　覧', '0', 'R', 0, 0, '', '', true, 0, false, true, 10, 'B');

	$pdf->SetFontSize(8);
	$pdf->MultiCell(106, '', '配車日　2016年12月05日　印刷日　2016年12月08日　15:36　P.1', '0', 'R', 0, 1, '', '', true, 0, false, true, 10, 'B');

	//$pdf->Ln(4);

	$Column1 = 45;
	$Column2 = 10;
	$Column3 = 39.5;
	$Column4 = 11;
	$ColumnTime = 10;
	$T = 7;
	$T2 = 10;

	// 1行目
	$pdf->SetFontSize(8);

	$pdf->MultiCell(6, '', '', 'LT', 'L', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell(11, '', '', 'LT', 'L', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell(11, '', '', 'LT', 'L', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell(17, '', '', 'LT', 'L', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell(14, '', '', 'LT', 'L', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell(25, '', '＜タイムカード＞', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell(60, '', '　　＜時間外勤務＞', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell(142, '', '　　　　　　　　　　　　　＜手当＞', '1', 'L', 0, 1, '', '', true, 0, false, true, $T, 'T');

	$pdf->SetFontSize(8);

	$pdf->MultiCell(6, '', '営', 'LB', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($Column4, '', '車番', 'LB', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($Column4, '', '№', 'LB', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell(17, '', '乗務員', 'LB', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell(14, '', '出光番号', 'LB', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');

	$pdf->MultiCell(12.5, '', '始業', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell(12.5, '', '終業', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');

	$pdf->MultiCell($ColumnTime, '', '普残', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($ColumnTime, '', '普深', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($ColumnTime, '', '休残', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($ColumnTime, '', '休深', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($ColumnTime, '', '法残', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($ColumnTime, '', '法深', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');

	$pdf->MultiCell($ColumnTime, '', '休憩', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($Column4, '', '総走行', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($Column4, '', '長距離', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($Column4, '', '回次', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($Column4, '', '多ヶ所', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($Column4, '', 'ﾄﾚ1', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($Column4, '', 'ﾄﾚ2', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($Column4, '', '乗務', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($Column4, '', 'ﾁｪｰﾝ', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($Column4, '', 'ﾌﾟﾚｰﾄ', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($Column4, '', '営業ｷﾛ', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($Column4, '', '支払数量', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'T');
	$pdf->MultiCell($Column4, '', '高稼働', '1', 'C', 0, 1, '', '', true, 0, false, true, $T, 'T');



	for ($count = 1; $count <= 20; $count++) {

		$pdf->MultiCell(6, $T, '01', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column4, $T, '02029', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column4, $T, '00270', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell(17, $T, '森川嘉人', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell(14, $T, '40126', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');

		$pdf->MultiCell(12.5, $T, '3:05', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell(12.5, $T, '14:06', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');

		$pdf->MultiCell($ColumnTime, $T, '2:32', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($ColumnTime, $T, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($ColumnTime, $T, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($ColumnTime, $T, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($ColumnTime, $T, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($ColumnTime, $T, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');

		$pdf->MultiCell($ColumnTime, $T, '0:29', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column4, $T, '387', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column4, $T, '6600', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column4, $T, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column4, $T, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column4, $T, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column4, $T, '800', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column4, $T, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column4, $T, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column4, $T, '51990', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column4, $T, '210', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column4, $T, '20', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($Column4, $T, '300', '1', 'R', 0, 1, '', '', true, 0, false, true, $T, 'M');

	}

$pdf->lastPage();
$pdf->Output('HelloWorld.pdf', 'I');
