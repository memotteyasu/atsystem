<?php
/*
* 請求書(Atype)
*/
App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('T', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->SetMargins(10, 10, 0);
$max_cnt = 21;
$max_cnt2 = 29;

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

// ■■■■■■■■■■　鏡　■■■■■■■■■■■■■
$pdf->AddPage();
$cnt=0;
$pagecnt = 1;
$pdf->Setfillcolor(30,144,255);
$pdf->Cell(192,1,'',0,0,'',1,'',1,false,'D','M');
// フォントの設定
$pdf->SetFont('kozminproregular', '', 18);
// 件名
$pdf->Text( 10, 12, $invoices['Invoice']['subject'].' ご請求書', 0.2, false, true, 0, 0, 'C', false, '', 0, false, 'T', 'M', false );

// ロゴ
$pdf->Image('img/pdflogo.png',145,16,70,0,'','','',0,200);
$pdf->Image('img/kakuin.png',177,45,21);
$pdf->SetFontSize(10);
//$pdf->Text( 165, 5, '発行日：'.$invoices['Invoice']['billingdate'], false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );

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
$pdf->MultiCell(50, 15, number_format($invoices['Invoice']['total']).'円', 0, 'R', 0, 1, '47', '61', true, 0, false, true, 9, 'B');
$pdf->SetLineStyle(array('width' => 0.2));
$pdf->Line(11, 70, 100, 70);


$pdf->SetFontSize(9);
$pdf->Text( 10, 77, '下記の通りご請求申し上げます。', false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );

$pdf->Ln(5);

$pdf->SetLineStyle(array('width' => 0.2));
// 見出し
$pdf->SetFontSize(12);
$pdf->MultiCell(125, 9, '内容', 1, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');
$pdf->MultiCell(60, 9, '合計', 1, 'C', 0, 1, '', '', true, 0, false, true, 9, 'B');
//二重線用
$pdf->Line(10, 92, 195, 92);
// 明細
$pdf->MultiCell(125, 9, '課税対象額', 1, 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');
$pdf->MultiCell(60, 9, number_format($invoices['Invoice']['subtotal']), 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
// 明細（非課税対象額）
if($invoices['Invoice']['subtotalm'] > 0){
	$pdf->MultiCell(125, 9, '免税対象額', 1, 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(60, 9, number_format($invoices['Invoice']['subtotalm']), 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
}else{
	$pdf->MultiCell(125, 9, '', 1, 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(60, 9, '', 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
}
// 明細（空欄）
$pdf->MultiCell(125, 9, '', 1, 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');
$pdf->MultiCell(60, 9, '', 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
// 明細（空欄）
$pdf->MultiCell(125, 9, '', 1, 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');
$pdf->MultiCell(60, 9, '', 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
// 明細（空欄）
$pdf->MultiCell(125, 9, '', 1, 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');
$pdf->MultiCell(60, 9, '', 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
// 背景
$pdf->Setfillcolor(240,240,240);
// 課税明細
$pdf->MultiCell(65, 9, '', 0, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');
$pdf->MultiCell(60, 9, '小計', 1, 'C', 1, 0, '', '', true, 0, false, true, 9, 'B');
$pdf->MultiCell(60, 9, number_format($invoices['Invoice']['subtotal'] + $invoices['Invoice']['subtotalm']), 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
// 明細
$pdf->MultiCell(65, 9, '', 0, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');
$pdf->MultiCell(60, 9, '消費税(10%)', 1, 'C', 1, 0, '', '', true, 0, false, true, 9, 'B');
$pdf->MultiCell(60, 9, number_format($invoices['Invoice']['consumptiontax']), 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');

// 高速代
if($invoices['Invoice']['subtotal3']>0){
	$pdf->MultiCell(65, 9, '', 0, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(60, 9, '高速代', 1, 'C', 1, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(60, 9, number_format($invoices['Invoice']['subtotal3']), 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
}

// 明細
$pdf->MultiCell(65, 9, '', 0, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');
$pdf->MultiCell(60, 9, 'ご請求金額', 1, 'C', 1, 0, '', '', true, 0, false, true, 9, 'B');
$pdf->MultiCell(60, 9, number_format($invoices['Invoice']['total']), 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');

// 口座情報
$pdf->SetFontSize(13);

$pdf->MultiCell(40, 10, '口座情報', 1, 'C', 0, 0, '', 245, true, 0, false, true, 7, 'B');
$pdf->MultiCell(150, 10, $kaisyas['Kaisya']['bank'], 1, 'L', 0, 1, '', '', true, 0, false, true, 7, 'B');

$pdf->MultiCell(40, 10, '口座名義', 1, 'C', 0, 0, '', 255, true, 0, false, true, 7, 'B');
$pdf->MultiCell(150, 10, $kaisyas['Kaisya']['bankname'].'  ('.$kaisyas['Kaisya']['banknamekana'].')', 1, 'L', 0, 1, '', '', true, 0, false, true, 7, 'B');
$pdf->SetFontSize(9);
$pdf->Text( 10, 267, '※恐れ入りますが、振込手数料はお客様のご負担として頂ますよう宜しくお願い申し上げます', false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );

// ■■■■■■■■■■　明細　■■■■■■■■■■■■■
// ■■■■■■■■■■　明細　■■■■■■■■■■■■■
// ■■■■■■■■■■　明細　■■■■■■■■■■■■■
// ■■■■■■■■■■　明細　■■■■■■■■■■■■■
	// ページを追加する
	$pdf->AddPage();
	$pagecnt++;

	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線, テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	$pdf->Setfillcolor(30,144,255);
	//$pdf->Cell(192,1,'',0,0,'',1,'',1,false,'D','M');
	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 18);
	// 件名
	$pdf->Text( 10, 12, $invoices['Invoice']['subject'].' ご請求明細', 0.2, false, true, 0, 0, 'C', false, '', 0, false, 'T', 'M', false );
	// ロゴ
	$pdf->Image('img/pdflogo.png',145,16,70,0,'','','',0,200);
	//$pdf->Image('img/kakuin.png',177,45,21);
	$pdf->SetFontSize(9);
	//$pdf->Text( 125, 6, '発行日：'.$invoices['Invoice']['billingdate'].'　　請求書No：'.$invoices['Invoice']['invoicenumber'], false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );

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
/*
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
*/

	$pdf->SetFontSize(9);
	//$pdf->Text( 10, 47, '下記の通りご請求申し上げます。', false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );

	$pdf->Ln(20);
	$pdf->SetLineStyle(array('width' => 0.2));

// 見出し
	$pdf->SetFontSize(10);
	$pdf->MultiCell(13, 9, '日付', 1, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(9, 9, '', 1, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(85, 9, '項目', 1, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(30, 9, "コンテナNo.\nREF No.", 1, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(25, 9, '品目', 1, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(23, 9, '金額', 1, 'C', 0, 1, '', '', true, 0, false, true, 9, 'B');
//
	$cnt=0;
	$total0 = $total1 = $total2_nuki = $total2_komi = $total3 = $total3_tax = 0;
	foreach($invoices['Invoicedetail'] as $invoicedetails){
		$pdf->SetFontSize(9);
		$pdf->MultiCell(13, 9, date('m/d',  strtotime($invoicedetails['detaildate'])), 1, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');
		if($invoicedetails['tax_flg']==0){
			$zei = '';
		}		
		if($invoicedetails['tax_flg']==1){
			$zei = '免税';
		}
		$pdf->MultiCell(9, 9, $zei, 1, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');

		if($invoicedetails['tax_flg']==2){ //高速代
			$total2_nuki = $total2_nuki + ceil($invoicedetails['payment'] / 1.1);
			$total2_komi = $total2_komi + $invoicedetails['payment'];
			$zei = '';
		}
		if($invoicedetails['tax_flg']==3){ //サーチャージ
			$total3 = $total3 + $invoicedetails['payment'];
			$zei = '';
		}

		$workitemname = str_replace("(実入OUT)","",$invoicedetails['itemname']);
		$workitemname = str_replace("(実入IN)","",$workitemname);
		$workitemname = str_replace("(空OUT)","",$workitemname);
		$workitemname = str_replace("(空IN)","",$workitemname);

		$cancel = '';
		if($invoicedetails['delflg']==3){
			$cancel = '【cancel】';
		}else{
			$cancel = '';
		}

		//$pdf->MultiCell(90, 9, $invoicedetails['itemname'].' '.$zei, 1, 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');
		$pdf->MultiCell(85, 9, $workitemname.' '.$zei.' '.$cancel, 1, 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');
		$pdf->MultiCell(30, 9, $invoicedetails['containerno']."\n".$invoicedetails['refno'], 1, 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');
		if($invoicedetails['productdetailname']){
			$pdf->MultiCell(25, 9, $invoicedetails['productdetailname'], 1, 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');
		}else{
			$pdf->MultiCell(25, 9, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');
		}
		//$pdf->SetFontSize(11);
		$pdf->SetFillColor(200);
		if($invoicedetails['delflg']==3){
			$pdf->MultiCell(23, 9, number_format($invoicedetails['payment']), 1, 'R', 1, 1, '', '', true, 0, false, true, 9, 'B');
		}else{
			if($invoicedetails['tax_flg']==2){
				$pdf->MultiCell(23, 9, number_format(ceil($invoicedetails['payment'] / 1.1)), 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
			}else{
				$pdf->MultiCell(23, 9, number_format($invoicedetails['payment']), 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
			}
		}
		$cnt++;
		if(($cnt >= $max_cnt)&&($pagecnt==2)){
			//改ページ処理
			$pdf->AddPage();
			$cnt=0;
			$pagecnt++;
		}else{
			if($cnt >= $max_cnt2){
				$pdf->AddPage();
				$cnt=0;
				$pagecnt++;
			}
		}
	}

$pdf->lastPage();
$pdf->Output('invoice_a.pdf', 'I');
