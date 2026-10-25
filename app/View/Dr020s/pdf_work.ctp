<?php
// debug($reservetran);

App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('T', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->SetMargins(10, 10, 10);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('場所情報');
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

	// ページを追加する
	$pdf->AddPage();

	//印刷日時を取得
	$printday = new DateTime();

	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線,　テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 16);

	$pdf->MultiCell('', '', '場所情報', '0', 'C', 0, 1, '', '', true, 0, false, true, '', 'M');

	// 210-20

	$col1 = 20;
	$col2 = 85;
	$col3 = 85;
	$col4 = 110;
	$col5 = 40;
	$col6 = 40;
	$col7 = (210-20-20)/2;

	$row1 = 8;
	$row2 = 6;
	$row3 = 50;

	$pdf->Ln(4);

	$pdf->SetFontSize('10');
	$pdf->MultiCell('', '', $printday->format('Y年m月d日').' P.'.$pdf->PageNo(), '0', 'R', 0, 1, '', '', true, 0, false, true, '', 'M');

	$pdf->SetFontSize('12');
	$pdf->SetFillColor(216, 216, 216);
	$pdf->MultiCell($col1, $row1, '部署', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col2, $row1, '作業場所名称', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col3, $row1, '略名称', '1', 'C', 1, 1, '', '', true, 0, false, true, $row1, 'M');


	// debug($workplaces);
	$pdf->SetFontSize('10');
	$pdf->MultiCell($col1, $row2, $workplaces['Departmentmaster']['departmentname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $row2, 'M');
	$pdf->MultiCell($col2, $row2, $workplaces['Workplace']['workplacename'], '1', 'L', 0, 0, '', '', true, 0, false, true, $row2, 'M');
	$pdf->MultiCell($col3, $row2, $workplaces['Workplace']['workplaceshortname'], '1', 'L', 0, 1, '', '', true, 0, false, true, $row2, 'M');

	$pdf->SetFontSize('12');
	$pdf->SetFillColor(216, 216, 216);
	$pdf->MultiCell($col4, $row1, '住所', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col5, $row1, '電話番号', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col6, $row1, 'FAX番号', '1', 'C', 1, 1, '', '', true, 0, false, true, $row1, 'M');

	$pdf->SetFontSize('10');
	$pdf->MultiCell($col4, $row2, $workplaces['Workplace']['placeadd'], '1', 'L', 0, 0, '', '', true, 0, false, true, $row2, 'M');
	$pdf->MultiCell($col5, $row2, $workplaces['Workplace']['placetel'], '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
	$pdf->MultiCell($col6, $row2, $workplaces['Workplace']['placefax'], '1', 'C', 0, 1, '', '', true, 0, false, true, $row2, 'M');

	$pdf->Ln(10);

	// ここから画像
	$pdf->SetFontSize('12');
	$pdf->SetFillColor(216, 216, 216);
	$pdf->MultiCell('', $row1, '画像', '1', 'C', 1, 1, '', '', true, 0, false, true, $row1, 'M');

	$pdf->SetFontSize('10');
	if (!$workplaces['Workplaceimage']) {
		$pdf->MultiCell('', $row2, '画像データが存在していません', '1', 'C', 0, 1, '', '', true, 0, false, true, $row2, 'M');
	} else {
		foreach ($workplaces['Workplaceimage'] as $image) {
			// $x = $pdf->GetX();
			// $y = $pdf->GetY();
			$url = $this->Html->url('/workplaceimage/original/'.$image['id'].'.jpg',true);
			$pdf->Image($url, '', '', 0, $row3, '', '', 'T', false, 300, 'L', false, false, '1');
			$pdf->MultiCell('', $row3, $image['imgcomment'], '1', 'L', 0, 1, '', '', true, 0, false, true, $row3, 'T');
		}
	}

$pdf->lastPage();
$pdf->Output('workplace.pdf', 'I');