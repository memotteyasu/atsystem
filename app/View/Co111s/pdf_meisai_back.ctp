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
	//debug($invoices2);
	//debug($kaisyas);

	// 仮設置用変数及び中身


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

	$billingaddress = $invoices['Shippermaster']['shippername'];
	$honorific = $invoices['Invoice']['honorific'];

	$pdf->SetFontSize(12);
	$pdf->Write(0, $billingaddress.'　'.$honorific, '', 0, 'L', true, 0, false, false, 0);
	$pdf->SetLineStyle(array('width' => 0.2));
	$pdf->Line(11, 39, 100, 39);

	// 件名
	$pdf->SetFontSize(10);
	$pdf->Text( 10, 42, $invoices['Invoice']['subject'], false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );
	$pdf->Text( 10, 47, '下記の通りご請求申し上げます。', false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );

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
	$pdf->SetFontSize(9);
	$pdf->Text( 10, 75, 'お支払期限：'.$invoices['Invoice']['paymentdate'], false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );
	$pdf->Text( 171, 75, '(単位：円)', false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );
	$pdf->Ln(8);

	$pdf->SetFontSize(12);
	$pdf->MultiCell(127, 9, '合　計　運　賃', 1, 'L', 0, 0, 20, '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(40, 9, '100,000', 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');

	foreach($invoices2['Invoicedetail'] as $invoicedetails){
		$pdf->MultiCell(127, 9, $invoicedetails['itemname'], 1, 'L', 0, 0, 20, '', true, 0, false, true, 9, 'B');
		$pdf->MultiCell(40, 9, number_format($invoicedetails['payment']), 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
	}

/*
	$pdf->MultiCell(127, 9, '変動費', 1, 'L', 0, 0, 20, '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(40, 9, '279,985', 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');

	$pdf->MultiCell(127, 9, '固定費', 1, 'L', 0, 0, 20, '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(40, 9, '331,800', 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');

	$pdf->MultiCell(127, 9, '通行料', 1, 'L', 0, 0, 20, '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(40, 9, '0', 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');

	$pdf->MultiCell(127, 9, 'スケール代', 1, 'L', 0, 0, 20, '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(40, 9, '0', 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
*/

	for ($count = 1; $count <= 4; $count++) {
		$pdf->MultiCell(127, 9, '', 1, 'L', 0, 0, 20, '', true, 0, false, true, 9, 'B');
		$pdf->MultiCell(40, 9, '', 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
	}

	$pdf->MultiCell(50, 9, '小計', 1, 'C', 0, 0, 97, '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(40, 9, number_format($invoices['Invoice']['mirror_subtotal']), 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');

	$pdf->MultiCell(50, 9, '消費税', 1, 'C', 0, 0, 97, '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(40, 9, number_format($invoices['Invoice']['mirror_consumptiontax']), 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');

	$pdf->MultiCell(50, 9, '合計', 1, 'C', 0, 0, 97, '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(40, 9, number_format($invoices['Invoice']['mirror_total']), 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');



//



	// ページを追加する（横）
	$pdf->AddPage('L','A4');

	$pdf->SetFont('kozminproregular', '', 18);
	$pdf->Write(0, '請　求　明　細', '', 0, 'C', true, 0, false, false, 0);

	$pdf->Ln(2);

//	$pdf->SetFontSize(12);
//	$pdf->Write(0, '請求締日　2022年08月31日', '', 0, 'C', true, 0, false, false, 0);

//	$pdf->SetLineStyle(array('width' => 0.2));
//	$pdf->Line(291, 10, 291, 36);


	$Column1 = 25;
	$Column2 = 30;
	$Line = 135;

	$pdf->SetFontSize(10);
	$pdf->MultiCell(100, 6, $invoices['Invoice']['subject'], 'B', 'L', 0, 0, '', '', true, 0, false, true, 6, 'B');

	$pdf->Ln(10);


	$Column2 = 35;
	$Column3 = 28;
	$Column4 = 125;
	$Column5 = 20;
	$Column6 = 16;
	$Column7 = 20;
	$Column8 = 23;
	$Column9 = 27;

	$kariDay = "08/01";
	$karinounyu ="ジャパンコーティングレジン　枚";
	$G = 14139;

	$pdf->SetFontSize(11);

	$pdf->MultiCell($Column6, 0, '日付', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column4, 0, '作業', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column2, 0, 'コンテナNo.', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column2, 0, 'REF No.', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column2, 0, '品　名', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column9, 0, '金　額', '1', 'C', 0, 1, '', '', true, 0, false, true, 0, '');

	$pdf->SetFontSize(10);

	for ($count = 1; $count <= 14; $count++) {

		$pdf->MultiCell($Column6, 0, $kariDay, '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column4, 0, $karinounyu, '1', 'L', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column2, 0, '12345667', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column2, 0, 'REF112093', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column2, 0, 'HDCP', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column9, 0, number_format($G), '1', 'R', 0, 1, '', '', true, 0, false, true, 0, '');


	}

$pdf->lastPage();
$pdf->Output('invoice_test.pdf', 'I');
