<?php
App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->SetMargins(5, 5, 5);

// set document information
//$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('請求書PDF');
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
$pdf->SetAutoPageBreak(TRUE, 0);

// ---------------------------------------------------------

	// ページを追加する
	$pdf->AddPage();

	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線,　テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	//今月の始めと終わりを取得
	//$firstday = new DateTime('first day of this month');
	//$lastday  = new DateTime('last day of this month');
	//$lastday->add(new DateInterval('P1D'));


	//$firstday = new DateTime('first day of '.$this->params['named']['kyy'].'-'.$this->params['named']['kmm']);
	//$lastday = new DateTime('last day of '.$this->params['named']['kyy'].'-'.$this->params['named']['kmm']);
	//$lastday->add(new DateInterval('P1D'));

	//debug($users);

	//インターバルを設定
	//$interval = new DateInterval('P1D');

	//期間を設定
	//$period = new DatePeriod($firstday, $interval, $lastday);

	//印刷日時を取得
	//$printday = new DateTime();

	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 10);

	$pdf->SetFillColor(0, 102, 153);
	$pdf->MultiCell(230, 5, '', 0, 'L', 1, 1, 0, 0, true, 0, false, true, 5, 'M');

	$pdf->Ln(4);
	$pdf->SetLineWidth(1);

	$pdf->SetFontSize(25);
	$pdf->MultiCell('', '', '請求書', '0', 'L', 0, 1, '', '', true, 0, false, true, '', 'M');
	//debug ($shippermasters);

	$pdf->Image('img/pdflogo.png', 135, 23, 70, 25);

	$pdf->SetFontSize(20);
	$pdf->MultiCell('', '', '', '0', 'L', 0, 1, '', '', true, 0, false, true, '', 'M');

	//debug ($productdetailmasters);
	if (!$shippermasters) {
		$sname = '';
	} else {
		$sname = $shippermasters['Shippermaster']['shippername'];
	}
	$pdf->MultiCell(90, 9, $sname, '0', 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->SetFontSize(16);
	$pdf->MultiCell(30, 9, '御中', '0', 'L', 0, 1, '', '', true, 0, false, true, 9, 'B');

	$pdf->SetFillColor(0, 0, 0);
	$pdf->MultiCell(120, 1, '', 0, 'L', 1, 1, '', '', true, 0, false, true, 1, 'B');

	$pdf->SetFontSize(12);
	$pdf->MultiCell('', 8, '件名：ドメイン更新料', '0', 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');
	$pdf->MultiCell('', 8, '下記のとおりご請求申し上げます。', '0', 'L', 0, 1, '', '', true, 0, false, true, 8, 'M');

	$pdf->SetFontSize(16);
	$pdf->MultiCell(120, '', 'ご請求金額', '0', 'L', 0, 1, '', '', true, 0, false, true, '', 'B');
	$pdf->MultiCell(120, '', '￥12,928-', 'B', 'R', 0, 1, '', '', true, 0, false, true, '', 'B');

	$pdf->SetLineWidth(0.2);

	$pdf->SetFontSize(12);
	$pdf->MultiCell(122, '', '', '0', 'R', 0, 0, '', '', true, 0, false, true, '', 'M');
	$pdf->MultiCell('', '', '〒455-0074', '0', 'L', 0, 1, '', '', true, 0, false, true, '', 'M');

	$pdf->Ln(1);

	$pdf->SetFontSize(14);
	$pdf->MultiCell(122, '', '', '0', 'R', 0, 0, '', '', true, 0, false, true, '', 'M');
	$pdf->MultiCell('', '', '愛知県名古屋市港区生保町8-60', '0', 'L', 0, 1, '', '', true, 0, false, true, '', 'M');

	$pdf->Ln(1);

	$pdf->SetFontSize(12);
	$pdf->MultiCell(122, '', '', '0', 'R', 0, 0, '', '', true, 0, false, true, '', 'M');
	$pdf->MultiCell('', '', 'TEL:052-381-9361', '0', 'L', 0, 1, '', '', true, 0, false, true, '', 'M');

	$pdf->Ln(1);

	$pdf->MultiCell(122, '', '', '0', 'R', 0, 0, '', '', true, 0, false, true, '', 'M');
	$pdf->MultiCell('', '', 'FAX:052-382-6622', '0', 'L', 0, 1, '', '', true, 0, false, true, '', 'M');

	$pdf->Ln(4);
	$pdf->SetFillColor(0, 0, 0);
	$pdf->MultiCell('', 3, '', 0, 'L', 1, 1, '', '', true, 0, false, true, 3, 'B');

	$col1 = 26.5;
	$row1 = 10;

	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetLineWidth(1);

	$pdf->MultiCell(120, $row1, '品番・品名', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col1, $row1, '数量', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col1, $row1, '単価', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col1, $row1, '金額', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');

	for ($count = 1; $count <= 10; $count++) {
		if ($count % 2) {
			$pdf->SetFillColor(245, 245, 245);
			$pdf->MultiCell(120, $row1, '', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col1, $row1, '', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col1, $row1, '', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col1, $row1, '', '1', 'C', 1, 1, '', '', true, 0, false, true, $row1, 'M');
		} else {
			$pdf->MultiCell(120, $row1, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col1, $row1, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col1, $row1, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col1, $row1, '', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');
		}
	}

	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetLineWidth(0.2);

	$pdf->SetFillColor(0, 0, 0);
	$pdf->MultiCell('', 1, '', 0, 'L', 1, 1, '', '', true, 0, false, true, 1, 'B');

	$syoukei = 11970;
	$syouhizei = 0.10;
	$goukei = ceil($syoukei * $syouhizei) + $syoukei;

	$row2 = 8;

	$pdf->MultiCell(130, $row2, '', '0', 'L', 0, 0, '', '', true, 0, false, true, $row2, 'M');
	$pdf->MultiCell(15, $row2, '小計', '0', 'L', 0, 0, '', '', true, 0, false, true, $row2, 'M');
	$pdf->MultiCell(55, $row2, number_format($syoukei), '0', 'R', 0, 1, '', '', true, 0, false, true, $row2, 'M');

	$pdf->MultiCell(130, $row2, '', '0', 'L', 0, 0, '', '', true, 0, false, true, $row2, 'M');
	$pdf->MultiCell(25, $row2, '消費税(8%)', '0', 'L', 0, 0, '', '', true, 0, false, true, $row2, 'M');
	$pdf->MultiCell(45, $row2, number_format(ceil($syoukei * $syouhizei)), '0', 'R', 0, 1, '', '', true, 0, false, true, $row2, 'M');

	$pdf->MultiCell(130, $row2, '', '0', 'L', 0, 0, '', '', true, 0, false, true, $row2, 'M');
	$pdf->MultiCell(15, $row2, '合計', '0', 'L', 0, 0, '', '', true, 0, false, true, $row2, 'M');
	$pdf->MultiCell(55, $row2, number_format($goukei), '0', 'R', 0, 1, '', '', true, 0, false, true, $row2, 'M');

	$pdf->MultiCell('', 0.5, '', 0, 'L', 1, 1, '', '', true, 0, false, true, 0.5, 'B');

	$row3 = 10;
	$pdf->SetFontSize(14);
	$pdf->MultiCell('', $row3, 'お振込先', '0', 'C', 0, 1, '', '', true, 0, false, true, $row3, 'M');
	$pdf->MultiCell('', $row3, '名古屋銀行　××支店　当座1234567　口座名義：朝日運輸株式会社', '0', 'C', 0, 1, '', '', true, 0, false, true, $row3, 'M');

	$pdf->SetFillColor(0, 102, 153);
	$pdf->MultiCell(230, 5, '', 0, 'L', 1, 1, 0, 292, true, 0, false, true, 5, 'M');



	/*
	 * 2ページ目
	 */
	$pdf->AddPage();

	//debug($ordertrans);

	$pdf->SetFont('kozminproregular', '', 10);

	$pdf->SetFillColor(0, 102, 153);
	$pdf->MultiCell(230, 5, '', 0, 'L', 1, 1, 0, 0, true, 0, false, true, 5, 'M');

	$pdf->Ln(4);
	$pdf->SetLineWidth(1);

	$pdf->SetFontSize(25);
	$pdf->MultiCell('', '', '請求書', '0', 'L', 0, 1, '', '', true, 0, false, true, '', 'M');

	$pdf->Ln(2);
	$pdf->SetFontSize(20);

	if (!$shippermasters) {
		$sname = '';
	} else {
		$sname = $shippermasters['Shippermaster']['shippername'];
	}
	$pdf->MultiCell(110, 9, $sname, '0', 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->SetFontSize(16);
	$pdf->MultiCell(30, 9, '御中', '0', 'L', 0, 1, '', '', true, 0, false, true, 9, 'B');

	$pdf->SetFillColor(0, 0, 0);
	$pdf->MultiCell(140, 1, '', 0, 'L', 1, 1, '', '', true, 0, false, true, 1, 'B');

	$pdf->SetFontSize(12);
	$pdf->Ln(4);
	$pdf->SetFillColor(0, 0, 0);
	$pdf->MultiCell('', 3, '', 0, 'L', 1, 1, '', '', true, 0, false, true, 3, 'B');

	//$col1 = 26.5;
	$row2_1 = 10;

	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetLineWidth(1);

	$pdf->MultiCell(120, $row2_1, '品番・品名', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
	$pdf->MultiCell($col1, $row2_1, '数量', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
	$pdf->MultiCell($col1, $row2_1, '単価', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2_1, 'M');
	$pdf->MultiCell($col1, $row2_1, '金額', '1', 'C', 0, 1, '', '', true, 0, false, true, $row2_1, 'M');

	$syouhin = 'ドメイン更新料（day-service.net）';
	$suryo = '3年';
	$tanka = '3,990';
	$kingaku = '11,970';

	//debug ($ordertrans);

	$count = 0;

	foreach ($ordertrans as $ordertran) {
		$pdname = $ordertran['Productdetailmaster']['pdname'];
		$price = $ordertran['Ordertran']['price'];
		$amount = $ordertran['Ordertran']['amount'];
		$total = $price * $amount;

		if ($count % 2) {
			$pdf->SetFillColor(245, 245, 245);
			$pdf->MultiCell(120, '', $pdname, '1', 'C', 1, 0, '', '', true, 0, false, true, '', 'M');
			$pdf->MultiCell($col1, '', $amount, '1', 'C', 1, 0, '', '', true, 0, false, true, '', 'M');
			$pdf->MultiCell($col1, '', number_format($price), '1', 'C', 1, 0, '', '', true, 0, false, true, '', 'M');
			$pdf->MultiCell($col1, '', number_format($total), '1', 'C', 1, 1, '', '', true, 0, false, true, '', 'M');
		} else {
			$pdf->MultiCell(120, '', $pdname, '1', 'C', 0, 0, '', '', true, 0, false, true, '', 'M');
			$pdf->MultiCell($col1, '', $amount, '1', 'C', 0, 0, '', '', true, 0, false, true, '', 'M');
			$pdf->MultiCell($col1, '', number_format($price), '1', 'C', 0, 0, '', '', true, 0, false, true, '', 'M');
			$pdf->MultiCell($col1, '', number_format($total), '1', 'C', 0, 1, '', '', true, 0, false, true, '', 'M');
		}

		$count = $count + 1;
	}

	for ($count = $count; $count <= 44; $count++) {
		if ($count % 2) {
			$pdf->SetFillColor(245, 245, 245);
			$pdf->MultiCell(120, '', $syouhin, '1', 'C', 1, 0, '', '', true, 0, false, true, '', 'M');
			$pdf->MultiCell($col1, '', $suryo, '1', 'C', 1, 0, '', '', true, 0, false, true, '', 'M');
			$pdf->MultiCell($col1, '', $tanka, '1', 'C', 1, 0, '', '', true, 0, false, true, '', 'M');
			$pdf->MultiCell($col1, '', $kingaku, '1', 'C', 1, 1, '', '', true, 0, false, true, '', 'M');
		} else {
			$pdf->MultiCell(120, '', $syouhin, '1', 'C', 0, 0, '', '', true, 0, false, true, '', 'M');
			$pdf->MultiCell($col1, '', $suryo, '1', 'C', 0, 0, '', '', true, 0, false, true, '', 'M');
			$pdf->MultiCell($col1, '', $tanka, '1', 'C', 0, 0, '', '', true, 0, false, true, '', 'M');
			$pdf->MultiCell($col1, '', $kingaku, '1', 'C', 0, 1, '', '', true, 0, false, true, '', 'M');
		}
	}


	$pdf->SetFillColor(0, 102, 153);
	$pdf->MultiCell(230, 5, '', 0, 'L', 1, 1, 0, 292, true, 0, false, true, 5, 'M');

	$pdf->lastPage();
	$pdf->Output('kintai.pdf', 'I');
