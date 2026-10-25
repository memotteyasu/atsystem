<?php
/*
* 請求書
*/
// debug($reservetran);

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
	$pdf->Image('http://localhost/img/logo.png',130,16,70,0,'','','',0,200);
	$pdf->Ln(5);

	$billingaddress = $invoices['Shippermaster']['shippername'];
	$honorific = $invoices['Invoice']['honorific'];

	$pdf->SetFontSize(12);
	$pdf->Write(0, $billingaddress.'　'.$honorific, '', 0, 'L', true, 0, false, false, 0);
	$pdf->SetLineStyle(array('width' => 0.2));
	$pdf->Line(11, 39, 100, 39);

	$companyname = $kaisyas['Kaisya']['companyname'];
	$address1 = $kaisyas['Kaisya']['address1'];
	$address2 = $kaisyas['Kaisya']['address2'];
	$tel = $kaisyas['Kaisya']['tel1'];
	$fax = $kaisyas['Kaisya']['fax1'];
/*
	$pdf->SetFontSize(14);
	$pdf->MultiCell(122, '', '', '0', 'R', 0, 0, '', 45, true, 0, false, true, '', 'M');
	$pdf->MultiCell('', '', $companyname, '0', 'L', 0, 1, '', '', true, 0, false, true, '', 'M');
*/
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

	$pdf->SetFontSize(14);
	$pdf->MultiCell(127, 9, '品　目', 1, 'C', 0, 0, 20, '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(40, 9, '金　額', 1, 'C', 0, 1, '', '', true, 0, false, true, 9, 'B');

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



	// ページを追加する
	$pdf->AddPage();

	$pdf->SetFont('kozminproregular', '', 18);
	$pdf->Write(0, '請　求　書', '', 0, 'C', true, 0, false, false, 0);

	$pdf->Ln(2);

	$pdf->SetFontSize(12);
	$pdf->Write(0, '請求締日　2017年03月31日', '', 0, 'C', true, 0, false, false, 0);

//	$pdf->SetLineStyle(array('width' => 0.2));
//	$pdf->Line(291, 10, 291, 36);

	$pdf->MultiCell(147, 0, '', 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'M');
	$pdf->MultiCell(43, 0, $companyname, 0, 'L', 0, 1, '', '', true, 0, false, true, 0, 'M');

	$pdf->SetFontSize(14);
	$pdf->MultiCell(70, 9, $billingaddress, 'B', 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');

	$pdf->SetFontSize(11);
	$pdf->MultiCell(77, 9, $honorific, 0, 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');

	$pdf->SetFontSize(10);
	$pdf->MultiCell(43, 9, $address1."\n".'TEL'.$tel, 0, 'L', 0, 1, '', '', true, 0, false, true, 9, 'B');

	$pdf->Ln(40);

	$Column1 = 25;
	$Column2 = 30;
	$Line = 135;

	$pdf->SetFontSize(10);
	$pdf->MultiCell(20, 6, '品名', 'B', 'L', 0, 0, '', '', true, 0, false, true, 6, 'B');

	$pdf->SetFontSize(12);
	$pdf->MultiCell(50, 6, $kari, 'B', 'R', 0, 1, '', '', true, 0, false, true, 6, 'B');

	$pdf->SetFontSize(10);
	$pdf->MultiCell($Column1, 7, '決定数量', 1, 'C', 0, 0, $Line, 43, true, 0, false, true, 7, 'B');

	$pdf->SetFontSize(12);
	$pdf->MultiCell($Column2, 7, 102.16, 1, 'R', 0, 1, '', '', true, 0, false, true, 7, 'B');

	$pdf->SetFontSize(10);
	$pdf->MultiCell($Column1, 7, '金　額', 1, 'C', 0, 0, $Line, '', true, 0, false, true, 7, 'B');

	$pdf->SetFontSize(12);
	$pdf->MultiCell($Column2, 7, '279,985', 1, 'R', 0, 1, '', '', true, 0, false, true, 7, 'B');

	$pdf->SetFontSize(10);
	$pdf->MultiCell($Column1, 7, '通行料', 1, 'C', 0, 0, $Line, '', true, 0, false, true, 7, 'B');

	$pdf->SetFontSize(12);
	$pdf->MultiCell($Column2, 7, '0', 1, 'R', 0, 1, '', '', true, 0, false, true, 7, 'B');

	$pdf->SetFontSize(10);
	$pdf->MultiCell($Column1, 7, 'スケール代', 1, 'C', 0, 0, $Line, '', true, 0, false, true, 7, 'B');

	$pdf->SetFontSize(12);
	$pdf->MultiCell($Column2, 7, '0', 1, 'R', 0, 1, '', '', true, 0, false, true, 7, 'B');

	$pdf->SetFontSize(10);
	$pdf->MultiCell($Column1, 7, '軽油代', 1, 'C', 0, 0, $Line, '', true, 0, false, true, 7, 'B');

	$pdf->SetFontSize(12);
	$pdf->MultiCell($Column2, 7, '0', 1, 'R', 0, 1, '', '', true, 0, false, true, 7, 'B');

	$pdf->SetFontSize(10);
	$pdf->MultiCell($Column1, 7, '合　計', 1, 'C', 0, 0, $Line, '', true, 0, false, true, 7, 'B');

	$pdf->SetFontSize(12);
	$pdf->MultiCell($Column2, 7, '611,785', 1, 'R', 0, 1, '', '', true, 0, false, true, 7, 'B');

	$pdf->Ln(6);

	$Column3 = 28;
	$Column4 = 55;
	$Column5 = 20;
	$Column6 = 20;
	$Column7 = 20;
	$Column8 = 23;
	$Column9 = 22;

	$kariDay = "2017/03/30";
	$karinounyu ="ジャパンコーティングレジン　枚";
	$karisuryo = "10.39";
	$karikingaku ="34,716";
	$karitukou = 0;
	$karisukeru = 0;
	$G = 14139;
	$GG = 0;

	$pdf->SetFontSize(11);

	$pdf->MultiCell($Column3, 0, '日付', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column4, 0, '納入先名', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column5, 0, '決定数量', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column6, 0, '金　額', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column7, 0, '通行料', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column8, 0, 'スケール代', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column9, 0, '合　計', '1', 'C', 0, 1, '', '', true, 0, false, true, 0, '');

	$pdf->SetFontSize(10);

	for ($count = 1; $count <= 14; $count++) {

		$pdf->MultiCell($Column3, 0, $kariDay, '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column4, 0, $karinounyu, '1', 'L', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column5, 0, $karisuryo, '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column6, 0, $karikingaku, '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column7, 0, $karitukou, '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column8, 0, $karisukeru, '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column9, 0, number_format($G), '1', 'R', 0, 1, '', '', true, 0, false, true, 0, '');

		$GG = $G + $GG;

	}

	$pdf->MultiCell($Column3, 0, '', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column4, 0, '協力費', '1', 'L', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column5, 0, '0', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column6, 0, '0', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column7, 0, '0', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column8, 0, '0', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column9, 0, '0', '1', 'R', 0, 1, '', '', true, 0, false, true, 0, '');

	$pdf->MultiCell($Column3, 0, '', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column4, 0, '固定費', '1', 'L', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column5, 0, '0', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column6, 0, '0', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column7, 0, '0', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column8, 0, '0', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column9, 0, number_format($GG), '1', 'R', 0, 1, '', '', true, 0, false, true, 0, '');

	$pdf->MultiCell($Column3, 0, '', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column4, 0, '', '1', 'L', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column5, 0, '1', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column6, 0, '0', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column7, 0, '0', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column8, 0, '', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
	$pdf->MultiCell($Column9, 0, '0', '1', 'R', 0, 1, '', '', true, 0, false, true, 0, '');

	for ($count = 1; $count <= 20; $count++) {

		$pdf->MultiCell($Column3, 0, '', '1', 'C', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column4, 0, '', '1', 'L', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column5, 0, '', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column6, 0, '', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column7, 0, '', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column8, 0, '', '1', 'R', 0, 0, '', '', true, 0, false, true, 0, '');
		$pdf->MultiCell($Column9, 0, '', '1', 'R', 0, 1, '', '', true, 0, false, true, 0, '');

	}

$pdf->lastPage();
$pdf->Output('test.pdf', 'I');
