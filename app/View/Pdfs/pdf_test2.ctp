<?php
// debug($reservetran);

App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
	$pdf->Write(0, '化　学　品　手　配　明　細', '', 0, 'C', true, 0, false, false, 0);

	//$pdf->SetLineStyle(array('width' => 0.2));
	//$pdf->Line(205, 10, 205, 200);

	$pdf->SetFontSize(10);

	$pdf->MultiCell(66, '', '2016年12月28日　水曜日', '0', 'L', 0, 0, '', '', true, 0, false, true, 5, 'B');
	$pdf->MultiCell(66, '', '（本社）', '0', 'C', 0, 0, '', '', true, 0, false, true, 5, 'B');

	$pdf->SetFontSize(8);
	$pdf->MultiCell(68, '', '作成日　2016年12月28日　11:11　P.1', '0', 'R', 0, 1, '', '', true, 0, false, true, 5, 'B');

	//$pdf->Ln(4);

	$Column1 = 5.5;
	$Column2 = 14;
	$Column3 = 4;
	$Column4 = 11;
	$Column5 = 13.5;
	$Column6 = 35;
	$Column7 = 13.5;
	$Column8 = 12.5;
	$Column9 = 8;
	$Column10 = 8;
	$Column11 = 13.5;
	$Column12 = 20;
	$Column13 = 25;
	$Column14 = 10;
	$Column15 = 8;
	$T = 5;
	$T2 = 10;

	// 1行目

	$pdf->SetFontSize(8);

	$pdf->MultiCell($Column1, $T, '営', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column2, $T, '乗務員', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column3, $T, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column4, $T, '車番', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column5, $T, '店名', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column6, $T, '納入先名', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column7, $T, '積地', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, '品名', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column9, $T, '積卸', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column10, $T, '卸日', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column11, $T, '時間', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column12, $T, '備考', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column13, $T, '納入先（積置分）', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column14, $T, '品名', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column15, $T, '届日', '1', 'C', 0, 1, '', '', true, 0, false, true, $T, 'M');

	for ($count = 1; $count <= 20; $count++) {

	$pdf->MultiCell($Column1, $T, '01', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column2, $T, '福山雅治', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column3, $T, '1', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column4, $T, '0415X', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column5, $T, '東海協和', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column6, $T, 'サンラックス　名古屋', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column7, $T, '三洋衣浦', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, 'GP3050', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column9, $T, '積卸', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column10, $T, '', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column11, $T, '間部卓也', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column12, $T, '14:00積込み', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column13, $T, '', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column14, $T, '', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column15, $T, '', '1', 'L', 0, 1, '', '', true, 0, false, true, $T, 'M');

	}

$pdf->lastPage();
$pdf->Output('HelloWorld.pdf', 'I');
