<?php
/*
* 請求書
*/
App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('T', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->SetMargins(10, 10, 0);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('Invoice');
$pdf->SetSubject('TCPDF');
$pdf->SetKeywords('Asahi, transfer');

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

	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線, テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	$pdf->Setfillcolor(30,144,255);
	$pdf->Cell(192,1,'',0,0,'',1,'',1,false,'D','M');
	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 18);
	$pdf->Text( 10, 12, $invoices['Invoice']['subject'].' ご請求書', 0.2, false, true, 0, 0, 'C', false, '', 0, false, 'T', 'M', false );
	// ロゴ
	if($kaisyas['Kaisya']['id']==1){ //朝日
		$pdf->Image('img/pdflogo.png',145,16,70,0,'','','',0,200);
		$pdf->Image('img/kakuin.png',177,45,21);
	}
	if($kaisyas['Kaisya']['id']==2){ //明治
		$pdf->Image('img/pdflogo_meiji.png',145,16,70,0,'','','',0,200);
		$pdf->Image('img/kakuin_meiji.png',177,45,21);
	}
	$pdf->SetFontSize(9);

	$billingaddress = $invoices['Shippermaster']['shippername'];
	$honorific = $invoices['Invoice']['honorific'];

	$aa = $pdf->GetStringWidth($billingaddress,'kozminproregular','',16);
	$pdf->SetFontSize(14);
	$pdf->Text( 11, 30, $billingaddress.'  '.$honorific, false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );
	$pdf->SetLineStyle(array('width' => 0.5));
	$pdf->Line(11, 37, 11+$aa, 37);

	$companyname = $kaisyas['Kaisya']['companyname'];
	$address1 = $kaisyas['Kaisya']['address1'];
	$address2 = $kaisyas['Kaisya']['address2'];
	$tel = $kaisyas['Kaisya']['tel1'];
	$fax = $kaisyas['Kaisya']['fax1'];
	$invoiceno = $kaisyas['Kaisya']['invoiceno'];
	$coverRows = [];
	$coverTotal = 0;
	foreach ($detailBranches as $detailBranch) {
		$fare = 0;
		foreach ($detailBranch['details'] as $detail) {
			$fare += !empty($detail['payment']) ? (int)$detail['payment'] : (int)$detail['price'];
		}
		$coverRows[] = ['company' => $detailBranch['label'], 'fare' => $fare];
		$coverTotal += $fare;
	}

	$pdf->Ln(11);
	$pdf->SetFontSize(10);
	$pdf->MultiCell(167, 10, $companyname, 0, 'R', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->Ln(5);
	$pdf->SetFontSize(8);
	$pdf->MultiCell(167, 10, $address1.$address2, 0, 'R', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->Ln(5);
	$pdf->SetFontSize(8);
	$pdf->MultiCell(167, 10, 'TEL '.$tel.' FAX '.$fax, 0, 'R', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->Ln(5);
	$pdf->SetFontSize(8);
	$pdf->MultiCell(167, 10, '登録番号 '.$invoiceno, 0, 'R', 0, 0, '', '', true, 0, false, true, 9, 'B');

	$pdf->SetFontSize(16);
	$pdf->Text( 14, 63, 'ご請求金額', false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );
	$pdf->SetFontSize(18);
	$pdf->MultiCell(50, 15, number_format($coverTotal).'円', 0, 'R', 0, 1, '47', '61', true, 0, false, true, 9, 'B');
	$pdf->SetLineStyle(array('width' => 0.2));
	$pdf->Line(11, 70, 100, 70);

	$pdf->SetFontSize(9);
	$pdf->Text( 10, 77, '下記の通りご請求申し上げます。', false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );

	$pdf->Ln(5);

// 鏡は営業所ごとの運賃小計と合計のみを表示する。
	$pdf->SetFontSize(12);
	$pdf->MultiCell(85, 9, '会社名', 1, 'C', 0, 0, 10, '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(55, 9, '運賃', 1, 'C', 0, 0, 95, '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(50, 9, '備考', 1, 'C', 0, 1, '', '', true, 0, false, true, 9, 'B');
	foreach ($coverRows as $coverRow) {
		$pdf->MultiCell(85, 9, $coverRow['company'], 1, 'L', 0, 0, 10, '', true, 0, false, true, 9, 'B');
		$pdf->MultiCell(55, 9, number_format($coverRow['fare']), 1, 'R', 0, 0, 95, '', true, 0, false, true, 9, 'B');
		$pdf->MultiCell(50, 9, '', 1, 'L', 0, 1, '', '', true, 0, false, true, 9, 'B');
	}
	$pdf->MultiCell(85, 9, '合計', 1, 'C', 0, 0, 10, '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(55, 9, number_format($coverTotal), 1, 'R', 0, 0, 95, '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(50, 9, '', 1, 'L', 0, 1, '', '', true, 0, false, true, 9, 'B');

// 口座情報
	$pdf->SetFontSize(9);

	$pdf->MultiCell(40, 7, '口座情報', 1, 'C', 0, 0, 10, 262, true, 0, false, true, 7, 'B');
	$pdf->MultiCell(150, 7, $kaisyas['Kaisya']['bank'], 1, 'L', 0, 1, '', '', true, 0, false, true, 7, 'B');

	$pdf->MultiCell(40, 7, '口座名義', 1, 'C', 0, 0, 10, 269, true, 0, false, true, 7, 'B');
	$pdf->MultiCell(150, 7, $kaisyas['Kaisya']['bankname'].'  ('.$kaisyas['Kaisya']['banknamekana'].')', 1, 'L', 0, 1, '', '', true, 0, false, true, 7, 'B');

// 石油部署の車両別請求明細（本社・大豊運輸・金川運輸の3ページ）
include APP . 'View' . DS . 'Oi100s' . DS . 'pdf_detail.ctp';

$pdf->lastPage();
$pdf->Output('invoice.pdf', 'I');
