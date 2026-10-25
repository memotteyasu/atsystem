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
	$pdf->SetFont('kozminproregular', '', 20);
	$pdf->Text( 10, 12, 'ご請求書', false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );
	// ロゴ
	$pdf->Image('img/pdflogo.png',130,16,70,0,'','','',0,200);
	$pdf->SetFontSize(9);
	$pdf->Text( 125, 6, '発行日：'.$invoices['Invoice']['billingdate'].'　　請求書No：'.$invoices['Invoice']['invoicenumber'], false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );

	$billingaddress = $invoices['Shippermaster']['shippername'];
	$honorific = $invoices['Invoice']['honorific'];

	$pdf->SetFontSize(12);
	$pdf->Text( 12, 30, $billingaddress.'　'.$honorific, false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );
	$pdf->SetLineStyle(array('width' => 0.2));
	$pdf->Line(11, 39, 100, 39);

	// 件名
	$pdf->SetFontSize(10);
	$pdf->Text( 10, 42, $invoices['Invoice']['subject'], false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );
	$pdf->Text( 10, 47, '下記の通りご請求申し上げます。', false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );
	$pdf->SetFontSize(9);
	$pdf->Text( 10, 63, 'お支払期限：'.$invoices['Invoice']['paymentdate'], false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );

	$pdf->Text( 179, 65, '(単位：円)', false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );

	$companyname = $kaisyas['Kaisya']['companyname'];
	$address1 = $kaisyas['Kaisya']['address1'];
	$address2 = $kaisyas['Kaisya']['address2'];
	$tel = $kaisyas['Kaisya']['tel1'];
	$fax = $kaisyas['Kaisya']['fax1'];

	$pdf->SetFontSize(10);
	$pdf->Text( 130, 45, $address1, false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );
	$pdf->Text( 130, 50, $address2, false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );
	$pdf->Text( 130, 55, 'TEL:'.$tel, false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );
	$pdf->Text( 130, 60, 'FAX:'.$fax, false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );

	$pdf->Ln(10);

// 見出し
	$pdf->SetFontSize(8);
	$pdf->MultiCell(12, 7, '日付', 1, 'C', 0, 0, 10, '', true, 0, false, true, 7, 'B');
	$pdf->MultiCell(90, 7, '納入先・諸費', 1, 'C', 0, 0, 22, '', true, 0, false, true, 7, 'B');
	$pdf->MultiCell(22, 7, 'ｺﾝﾃﾅNo', 1, 'C', 0, 0, 112, '', true, 0, false, true, 7, 'B');
	$pdf->MultiCell(22, 7, 'REF No', 1, 'C', 0, 0, 134, '', true, 0, false, true, 7, 'B');
	$pdf->MultiCell(22, 7, '品目', 1, 'C', 0, 0, 156, '', true, 0, false, true, 7, 'B');
	$pdf->MultiCell(20, 7, '金　額', 1, 'C', 0, 1, '', '', true, 0, false, true, 7, 'B');
//
	$cnt=0;
	foreach($invoices['Invoicedetail'] as $invoicedetails){
		$date=new DateTime($invoicedetails['detaildate']);
		$pdf->MultiCell(12, 7, $date->format('m/d'), 1, 'L', 0, 0, 10, '', true, 0, false, true, 7, '');
		$pdf->MultiCell(90, 7, $invoicedetails['itemname'], 1, 'L', 0, 0, 22, '', true, 0, false,true, 7, '');
		$pdf->MultiCell(22, 7, $invoicedetails['containerno'], 1, 'L', 0, 0, 112, '', true, 0, false, true, '', 'B');
		$pdf->MultiCell(22, 7, $invoicedetails['refno'], 1, 'L', 0, 0, 134, '', true, 0, false, true, '', 'B');
		$pdf->MultiCell(22, 7, $invoicedetails['productdetailname'], 1, 'L', 0, 0, 156, '', true, 0, false, true, '', 'B');
		$pdf->MultiCell(20, 7, number_format($invoicedetails['payment']), 1, 'R', 0, 1, '', '', true, 0, false, true, 7, 'B');
		$cnt++;
	}
	$pdf->MultiCell(22, 7, '小計', 1, 'C', 0, 0, 112, '', true, 0, false, true, 7, 'B');
	$pdf->MultiCell(64, 7, number_format($invoices['Invoice']['subtotal']), 1, 'R', 0, 1, '', '', true, 0, false, true, 7, 'B');

	$pdf->MultiCell(22, 7, '消費税', 1, 'C', 0, 0, 112, '', true, 0, false, true, 7, 'B');
	$pdf->MultiCell(64, 7, number_format($invoices['Invoice']['consumptiontax']), 1, 'R', 0, 1, '', '', true, 0, false, true, 7, 'B');

	$pdf->MultiCell(22, 7, '合計', 1, 'C', 0, 0, 112, '', true, 0, false, true, 7, 'B');
	$pdf->MultiCell(64, 7, number_format($invoices['Invoice']['total']), 1, 'R', 0, 1, '', '', true, 0, false, true, 7, 'B');

// 口座情報
	$pdf->SetFontSize(9);

	$pdf->MultiCell(40, 7, '口座情報', 1, 'C', 0, 0, 10, 262, true, 0, false, true, 7, 'B');
	$pdf->MultiCell(150, 7, $kaisyas['Kaisya']['bank'], 1, 'L', 0, 1, '', '', true, 0, false, true, 7, 'B');

	$pdf->MultiCell(40, 7, '口座名義', 1, 'C', 0, 0, 10, 269, true, 0, false, true, 7, 'B');
	$pdf->MultiCell(150, 7, $kaisyas['Kaisya']['bankname'].'  ('.$kaisyas['Kaisya']['banknamekana'].')', 1, 'L', 0, 1, '', '', true, 0, false, true, 7, 'B');

$pdf->lastPage();
$pdf->Output('invoice.pdf', 'I');
