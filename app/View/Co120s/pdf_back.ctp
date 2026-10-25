<?php
if(empty($ordertrans)){
	echo 'データが存在しません。';
	exit;
}

/*
* 請求書
*/
App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('T', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->SetMargins(10, 10, 0);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('receipt');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('asahitrans, transfer');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// 自動改ページモード（コメントアウト
// $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// ---------------------------------------------------------

	// ページを追加する
	$pdf->AddPage();

	//debug($invoices);
	//debug($kaisyas);

	// 仮設置用変数及び中身
	$kari = "アクリル酸＜2EHA＞";


	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線, テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	$pdf->Setfillcolor(30,144,255);
	$pdf->Cell(190,5,'',0,0,'',1,'',1,false,'D','M');
	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 20);
	$pdf->Write(0, 'ご請求書', '', 0, 'L', true, 0, false, false, 0);
	// ロゴ
	$pdf->Image('img/pdflogo.png',130,16,70,0,'','','',0,200);
	$pdf->Ln(5);

	//$honorific = '御中';//$invoices['Invoice']['honorific'];

	$pdf->SetFontSize(12);
	$pdf->Write(0, $shippername['Shippermaster']['shippername'].'　御中', '', 0, 'L', true, 0, false, false, 0);
	$pdf->SetLineStyle(array('width' => 0.2));
	$pdf->Line(11, 39, 100, 39);

	$companyname = $kaisyas['Kaisya']['companyname'];
	$address1 = $kaisyas['Kaisya']['address1'];
	$address2 = $kaisyas['Kaisya']['address2'];
	$tel = $kaisyas['Kaisya']['tel1'];
	$fax = $kaisyas['Kaisya']['fax1'];

	$pdf->SetFontSize(14);
	$pdf->MultiCell(122, '', '', '0', 'R', 0, 0, '', 45, true, 0, false, true, '', 'M');
	$pdf->MultiCell('', '', $companyname, '0', 'L', 0, 1, '', '', true, 0, false, true, '', 'M');

	$pdf->Ln(7);
	$pdf->SetFontSize(10);
	$pdf->MultiCell(122, '', '', '0', 'R', 0, 0, '', '', true, 0, false, true, '', 'M');
	$pdf->MultiCell('', '', $address1, '0', 'L', 0, 1, '', '', true, 0, false, true, '', 'M');

	$pdf->Ln(1);
	$pdf->MultiCell(122, '', '', '0', 'R', 0, 0, '', '', true, 0, false, true, '', 'M');
	$pdf->MultiCell('', '', $address2, '0', 'L', 0, 1, '', '', true, 0, false, true, '', 'M');

	$pdf->Ln(1);

	$pdf->MultiCell(122, '', '', '0', 'R', 0, 0, '', '', true, 0, false, true, '', 'M');
	$pdf->MultiCell('', '', 'TEL:'.$tel, '0', 'L', 0, 1, '', '', true, 0, false, true, '', 'M');

	$pdf->Ln(1);

	$pdf->MultiCell(122, '', '', '0', 'R', 0, 0, '', '', true, 0, false, true, '', 'M');
	$pdf->MultiCell('', '', 'FAX:'.$fax, '0', 'L', 0, 1, '', '', true, 0, false, true, '', 'M');

	$pdf->Ln(10);

	$row1 = 10;

	$col1 = 80;
	$col2 = 50;
	$col3 = 60;

	$pdf->MultiCell(175, '', '単位：円', 0, 'R', 0, 1, 10, '', true, 0, false, true, '', 'M');

// 見出し
	$pdf->SetFontSize(12);
	$pdf->MultiCell($col1, $row1, '内　容', 1, 'C', 0, 0, 10, '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col2, $row1, '合　計', 1, 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col3, $row1, '摘　要', 1, 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');
//
	$cnt = 0;
	$price = 0;
	$subtotal = 0;

	//debug($ordertrans);
	foreach($ordertrans as $ordertran){
		$pdf->MultiCell($col1, $row1, $ordertran['Carmaster']['plateno'], 1, 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		if ($ordertran['Ordertran']['sum'] != null) {
			$price = number_format($ordertran['Ordertran']['sum']);
		} else {
			$price = '－';
		}

		$pdf->MultiCell($col2, $row1, $price, 1, 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3, $row1, $ordertran['Shippermaster']['shippershortname'], 1, 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');

		$subtotal = $subtotal + $ordertran['Ordertran']['sum'];
		$cnt++;
	}
	// 明細が１０件以下なら空白枠を印字
	/*
	if($cnt < 10){
		for($cnt;$cnt<=10;$cnt++){
			$pdf->MultiCell($col1, $row1, '', 1, 'L', 0, 0, '', '', true, 0, false, true, $row1, 'B');
			$pdf->MultiCell($col2, $row1, '', 1, 'R', 0, 0, '', '', true, 0, false, true, $row1, 'B');
			$pdf->MultiCell($col3, $row1, '', 1, 'R', 0, 1, '', '', true, 0, false, true, $row1, 'B');
		}
	}
	*/
	$pdf->MultiCell($col1, $row1, '合　計　額', 1, 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col2, $row1, number_format($subtotal), 1, 'R', 0, 1, '', '', true, 0, false, true, $row1, 'M');

	$tax = 0.10;
	$taxtotal = $subtotal * $tax;
	$pdf->MultiCell($col1, $row1, '消　費　税', 1, 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col2, $row1, number_format($taxtotal), 1, 'R', 0, 1, '', '', true, 0, false, true, $row1, 'M');

	$pdf->MultiCell($col1, $row1, 'ご請求金額', 1, 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col2, $row1, number_format($subtotal + $taxtotal), 1, 'R', 0, 1, '', '', true, 0, false, true, $row1, 'M');



//

$date = new DateTime();

$pdf->lastPage();
$pdf->Output($date->format('Ymd').'.pdf', 'I');
