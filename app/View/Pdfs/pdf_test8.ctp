<?php

App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->SetMargins(5, 10, 5);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('時間外距離手当明細書');
$pdf->SetSubject('時間外距離手当明細書');
$pdf->SetKeywords('pdf');

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

	// ページを追加する
	$pdf->AddPage();

	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線,　テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 12);

	$pdf->SetLineWidth(0.5);
	$pdf->Line(5,32.5,5,197);
	$pdf->Line(15.2,32.5,15.2,197);
	$pdf->Line(28,32.5,28,197);
	$pdf->Line(93,32.5,93,197);
	$pdf->Line(292,32.5,292,197);

	$pdf->Line(5,32.5,292,32.5);
	$pdf->Line(5,37.3,292,37.3);
	$pdf->Line(5,197,292,197);

	$pdf->SetFontSize(14);
	$pdf->MultiCell('', '', '時間外距離手当明細書', '0', 'C', 0, 1, '', '', true, 0, false, true, 0, 'M');

	$pdf->MultiCell('', '', '2020年03月分', '0', 'L', 0, 1, '', '', true, 0, false, true, 0, 'M');

	$row1 = 7;

	$pdf->MultiCell('207', $row1, '朝日運輸株式会社', '0', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'B');

	$pdf->SetFontSize(12);
	$pdf->MultiCell('25', $row1, '氏名', 'B', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'B');

	$pdf->SetFontSize(14);
	$pdf->MultiCell('35', $row1, '本田正道', 'B', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'B');
	$pdf->MultiCell('20', $row1, '殿', 'B', 'L', 0, 1, '', '', true, 0, false, true, $row1, 'B');

	$pdf->Ln(3);

	$col1 = 13;
	$col2 = 26;
	$col3 = 24.7;
	$T = 5;

	// 1行目

	$pdf->SetFontSize(10);
	$pdf->SetLineWidth(0.2);

	$pdf->MultiCell(10, '', '日', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');

	$pdf->MultiCell($col1, '', '普残', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col1, '', '普深', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col1, '', '休残', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col1, '', '休深', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col1, '', '法残', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col1, '', '法深', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');

	$pdf->MultiCell($col2, '', '営業キロ(実車)', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col3, '', '走行手当', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col3, '', '積載数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col3, '', '積載歩合給', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col3, '', '電話手当', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col3, '', '長距離手当', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col3, '', '多ヶ所・他', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col3, '', 'ﾄﾚｰﾗ手当', '1', 'C', 0, 1, '', '', true, 0, false, true, $T, 'M');



	for ($count = 1; $count <= 31; $count++) {
		$pdf->SetFontSize(8);
		$pdf->MultiCell(10, $T, $count, '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');

		$pdf->MultiCell($col1, $T, '2:20', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($col1, $T, '1:00', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($col1, $T, '0:00', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($col1, $T, '0:00', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($col1, $T, '10:30', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($col1, $T, '0:00', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');

		$pdf->SetFontSize(10);
		$pdf->MultiCell($col2, $T, '140', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($col3, $T, '840', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($col3, $T, '48.00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($col3, $T, '0', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($col3, $T, '300', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($col3, $T, '13,000', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($col3, $T, '1,500', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
		$pdf->MultiCell($col3, $T, '1,300', '1', 'R', 0, 1, '', '', true, 0, false, true, $T, 'M');

	}

	$pdf->SetFontSize(8);
	$pdf->MultiCell(10, $T, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');

	$pdf->MultiCell($col1, $T, '40:14', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col1, $T, '14:58', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col1, $T, '9:15', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col1, $T, '0:15', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col1, $T, '15:40', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col1, $T, '0:55', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');

	$pdf->SetFontSize(10);
	$pdf->MultiCell($col2, $T, '4,131', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col3, $T, '24,786', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col3, $T, '578.00', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col3, $T, '0', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col3, $T, '4,200', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col3, $T, '51,400', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col3, $T, '20,300', '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($col3, $T, '11,700', '1', 'R', 0, 1, '', '', true, 0, false, true, $T, 'M');


$pdf->lastPage();
$pdf->Output('zikangaikyoriteatemeisai.pdf', 'I');
