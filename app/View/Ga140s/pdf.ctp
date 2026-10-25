<?php
/*
* 出光請求書
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
	$pdf->SetFontSize(12);
	$pdf->MultiCell(80, 8, '車両', 1, 'C', 0, 0, 10, '', true, 0, false, true, 8, 'B');
	$pdf->MultiCell(35, 8, '通行料込運賃', 1, 'C', 0, 0, 90, '', true, 0, false, true, 8, 'B');
	$pdf->MultiCell(35, 8, '通行料', 1, 'C', 0, 0, 125, '', true, 0, false, true, 8, 'B');
	$pdf->MultiCell(35, 8, '手書通行料', 1, 'C', 0, 1, '', '', true, 0, false, true, 8, 'B');
//
	$cnt=0;
	$totalprice = $totaltollfee = $totalhandtollfee = 0;
	foreach($invoices['Invoicedetail'] as $invoicedetails){
		$pdf->MultiCell(80, 8, $invoicedetails['itemname'], 1, 'L', 0, 0, 10, '', true, 0, false, true, 8, 'B');
		$pdf->MultiCell(35, 8, number_format($invoicedetails['price']), 1, 'R', 0, 0, 90, '', true, 0, false, true, 8, 'B');
		$pdf->MultiCell(35, 8, number_format($invoicedetails['tollfee']), 1, 'R', 0, 0, 125, '', true, 0, false, true, 8, 'B');
		$pdf->MultiCell(35, 8, number_format($invoicedetails['handtollfee']), 1, 'R', 0, 1, '', '', true, 0, false, true, 8, 'B');
		$totalprice = $totalprice + $invoicedetails['price'];
		$totaltollfee = $totaltollfee + $invoicedetails['tollfee'];
		$totalhandtollfee = $totalhandtollfee + $invoicedetails['handtollfee'];
		$cnt++;
	}
	// 明細が１０件以下なら空白枠を印字
	if($cnt < 10){
		for($cnt;$cnt<=10;$cnt++){
			$pdf->MultiCell(80, 8, '', 1, 'L', 0, 0, 10, '', true, 0, false, true, 8, 'B');
			$pdf->MultiCell(35, 8, '', 1, 'R', 0, 0, 90, '', true, 0, false, true, 8, 'B');
			$pdf->MultiCell(35, 8, '', 1, 'R', 0, 0, 125, '', true, 0, false, true, 8, 'B');
			$pdf->MultiCell(35, 8, '', 1, 'R', 0, 1, '', '', true, 0, false, true, 8, 'B');
		}
	}
	$pdf->SetFontSize(9);
	$pdf->MultiCell(35, 8, '(a)通行料込運賃', 1, 'C', 0, 0, 90, '', true, 0, false, true, 8, 'B');
	$pdf->SetFontSize(12);
	$pdf->MultiCell(70, 8, number_format($totalprice), 1, 'R', 0, 1, '', '', true, 0, false, true, 8, 'B');

	$pdf->SetFontSize(9);
	$pdf->MultiCell(35, 8, '(b)通行料', 1, 'C', 0, 0, 90, '', true, 0, false, true, 8, 'B');
	$pdf->SetFontSize(12);
	$pdf->MultiCell(70, 8, number_format($totaltollfee), 1, 'R', 0, 1, '', '', true, 0, false, true, 8, 'B');

	$pdf->SetFontSize(9);
	$pdf->MultiCell(35, 8, '(c)通行料抜運賃(a-b)', 1, 'C', 0, 0, 90, '', true, 0, false, true, 8, 'B');
	$pdf->SetFontSize(12);
	$total_c = $totalprice - $totaltollfee;
	$pdf->MultiCell(70, 8, number_format($total_c), 1, 'R', 0, 1, '', '', true, 0, false, true, 8, 'B');

	$pdf->SetFontSize(9);
	$pdf->MultiCell(35, 8, '(d)消費税(c*10%)', 1, 'C', 0, 0, 90, '', true, 0, false, true, 8, 'B');
	$pdf->SetFontSize(12);
	$total_d = $total_c * 0.1;
	$pdf->MultiCell(70, 8, number_format($total_d), 1, 'R', 0, 1, '', '', true, 0, false, true, 8, 'B');

	$pdf->SetFontSize(9);
	$pdf->MultiCell(35, 8, '(e)支払運賃', 1, 'C', 0, 0, 90, '', true, 0, false, true, 8, 'B');
	$pdf->SetFontSize(12);
	$total = $totalprice + $total_d;
	$pdf->MultiCell(70, 8, number_format($total), 1, 'R', 0, 1, '', '', true, 0, false, true, 8, 'B');

// 口座情報
	$pdf->SetFontSize(9);

	$pdf->MultiCell(40, 6, '口座情報', 1, 'C', 0, 0, 10, 263, true, 0, false, true, 6, 'B');
	$pdf->MultiCell(150, 6, $kaisyas['Kaisya']['bank'], 1, 'L', 0, 1, '', '', true, 0, false, true, 6, 'B');

	$pdf->MultiCell(40, 6, '口座名義', 1, 'C', 0, 0, 10, 269, true, 0, false, true, 6, 'B');
	$pdf->MultiCell(150, 6, $kaisyas['Kaisya']['bankname'].'  ('.$kaisyas['Kaisya']['banknamekana'].')', 1, 'L', 0, 1, '', '', true, 0, false, true, 6, 'B');

$pdf->lastPage();
$pdf->Output('invoice.pdf', 'I');
