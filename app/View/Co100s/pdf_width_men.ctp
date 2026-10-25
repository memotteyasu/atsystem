<?php
/*
* 請求書（横）
*/
App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->SetMargins(10, 10, 0);
$max_cnt = 15;
$max_cnt2 = 22;

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
	$pagecnt = 1;

	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線, テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	$pdf->Setfillcolor(30,144,255);
	$pdf->Cell(278,1,'',0,0,'',1,'',1,false,'D','M');
	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 18);
	$pdf->Text( 10, 12, 'ご請求書', false, false, true, 0, 0, 'C', false, '', 0, false, 'T', 'M', false );
	// ロゴ
	$pdf->Image('img/pdflogo.png',230,12,70,0,'','','',0,200);
	$pdf->Image('img/kakuin.png',267,38,20);
	$pdf->SetFontSize(9);
	$pdf->Text( 210, 6, '発行日：'.$invoices['Invoice']['billingdate'].'　　請求書No：'.$invoices['Invoice']['invoicenumber'], false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );

	$billingaddress = $invoices['Shippermaster']['shippername'];
	$honorific = $invoices['Invoice']['honorific'];

	$pdf->SetFontSize(12);
	$pdf->Text( 12, 25, $billingaddress.'　'.$honorific, false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );
	$pdf->SetLineStyle(array('width' => 0.2));
	$pdf->Line(11, 32, 110, 32);

	// 件名
	// 件名
	$pdf->SetFontSize(15);
	$pdf->Text( 10, 35, $invoices['Invoice']['subject'], 0.2, false, true, 0, 0, 'C', false, '', 0, false, 'T', 'M', false );

	$companyname = $kaisyas['Kaisya']['companyname'];
	$address1 = $kaisyas['Kaisya']['address1'];
	$address2 = $kaisyas['Kaisya']['address2'];
	$tel = $kaisyas['Kaisya']['tel1'];
	$fax = $kaisyas['Kaisya']['fax1'];
	$invoiceno = $kaisyas['Kaisya']['invoiceno'];

	$pdf->Ln(0);
	$pdf->SetFontSize(10);
	$pdf->MultiCell(257, 10, $companyname, 0, 'R', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->Ln(4);
	$pdf->SetFontSize(8);
	$pdf->MultiCell(257, 10, $address1.$address2, 0, 'R', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->Ln(4);
	$pdf->SetFontSize(8);
	$pdf->MultiCell(257, 10, 'TEL '.$tel.' FAX '.$fax, 0, 'R', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->Ln(4);
	$pdf->SetFontSize(8);
	$pdf->MultiCell(257, 10, '登録番号 '.$invoiceno, 0, 'R', 0, 0, '', '', true, 0, false, true, 9, 'B');


	$pdf->SetFontSize(9);
	//$pdf->Text( 10, 55, '下記の通りご請求申し上げます。', false, false, true, 0, 0, 'L', false, '', 0, false, 'T', 'M', false );

	$pdf->Ln(13);
	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線, テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

// 見出し
	$pdf->SetFontSize(10);
	$pdf->MultiCell(15, 8, '日付', 1, 'C', 0, 0, '', '', true, 0, false, true, 7, 'B');
	$pdf->MultiCell(15, 8, '担当', 1, 'C', 0, 0, '', '', true, 0, false, true, 7, 'B');
	$pdf->MultiCell(90, 8, '項目', 1, 'C', 0, 0, '', '', true, 0, false, true, 7, 'B');
	$pdf->MultiCell(35, 8, "コンテナNo.", 1, 'C', 0, 0, '', '', true, 0, false, true, 7, 'B');
	$pdf->MultiCell(35, 8, "REF No.", 1, 'C', 0, 0, '', '', true, 0, false, true, 7, 'B');
	$pdf->MultiCell(60, 8, '品目', 1, 'C', 0, 0, '', '', true, 0, false, true, 7, 'B');
	$pdf->MultiCell(25, 8, '金額', 1, 'C', 0, 1, '', '', true, 0, false, true, 7, 'B');
//
	$cnt=0;
	$total0 = $total1 = $total2 = 0;
	foreach($invoices['Invoicedetail'] as $invoicedetails){
		$pdf->SetFontSize(9);
		//日付
		$pdf->MultiCell(15, 8, date('m/d',  strtotime($invoicedetails['detaildate'])), 1, 'C', 0, 0, '', '', true, 0, false, true, 7, 'B');
		//担当者
		if($invoicedetails['tantoname'] == ''){
			$pdf->MultiCell(15, 8,'', 1, 'C', 0, 0, '', '', true, 0, false, true, 7, 'B');
		}else{
			$pdf->MultiCell(15, 8, $invoicedetails['tantoname'], 1, 'C', 0, 0, '', '', true, 0, false, true, 7, 'B');
		}
		if($invoicedetails['tax_flg']==0){
			$total0 = $total0 + $invoicedetails['payment'];
			$zei = '';
		}		
		if($invoicedetails['tax_flg']==1){
			$total1 = $total1 + $invoicedetails['payment'];
			$zei = '(免税)';
		}
		if($invoicedetails['tax_flg']==2){
			$total2 = $total2 + $invoicedetails['payment'];
			$zei = '(非課税)';
		}
		//扶養な文字列を削除
		$workitemname = str_replace("(実入OUT)","",$invoicedetails['itemname']);
		$workitemname = str_replace("(実入IN)","",$workitemname);
		$workitemname = str_replace("(空OUT)","",$workitemname);
		$workitemname = str_replace("(空IN)","",$workitemname);

		//$pdf->MultiCell(90, 8, $invoicedetails['itemname'].' '.$zei, 1, 'L', 0, 0, '', '', true, 0, false, true, 7, 'B');
		$pdf->MultiCell(90, 8, $workitemname.' '.$zei, 1, 'L', 0, 0, '', '', true, 0, false, true, 7, 'B');
		$invoicedetails['containerno'] = str_replace(NULL,' ',$invoicedetails['containerno']);
		$pdf->MultiCell(35, 8, $invoicedetails['containerno'], 1, 'L', 0, 0, '', '', true, 0, false, true, 7, 'B');
		$invoicedetails['refno'] = str_replace(NULL,' ',$invoicedetails['refno']);
		$pdf->MultiCell(35, 8, $invoicedetails['refno'], 1, 'L', 0, 0, '', '', true, 0, false, true, 7, 'B');
		if($invoicedetails['productdetailname']){
			$pdf->MultiCell(60, 8, $invoicedetails['productdetailname'], 1, 'L', 0, 0, '', '', true, 0, false, true, 8, 'B');
		}else{
			$pdf->MultiCell(60, 8, "", 1, 'L', 0, 0, '', '', true, 0, false, true, 7, 'B');
		}
		//$pdf->SetFontSize(11);
		$pdf->SetFillColor(200);
		if($invoicedetails['delflg']==3){
			$pdf->MultiCell(25, 8, number_format($invoicedetails['payment']), 1, 'R', 1, 1, '', '', true, 0, false, true, 7, 'B');
		}else{
			$pdf->MultiCell(25, 8, number_format($invoicedetails['payment']), 1, 'R', 0, 1, '', '', true, 0, false, true, 7, 'B');
		}

		$cnt++;
		if(($cnt >= $max_cnt)&&($pagecnt==1)){
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
	// 明細が１０件以下なら空白枠を印字
/*
	if($cnt < 10){
		for($cnt;$cnt<=10;$cnt++){
			$pdf->MultiCell(15, 9, '', 1, 'L', 0, 0, '', '', true, 0, false, true, 9, 'B');
			$pdf->MultiCell(90, 9, '', 1, 'R', 0, 0, '', '', true, 0, false, true, 9, 'B');
			$pdf->MultiCell(30, 9, '', 1, 'R', 0, 0, '', '', true, 0, false, true, 9, 'B');
			$pdf->MultiCell(25, 9, '', 1, 'R', 0, 0, '', '', true, 0, false, true, 9, 'B');
			$pdf->MultiCell(25, 9, '', 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
		}
	}
*/


		//１０％税明細
	$pdf->SetFontSize(9);
	$pdf->MultiCell(30, 9, '小計', 1, 'C', 0, 0, 100, '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(30, 9, number_format($total0), 1, 'R', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(20, 9, '税(10%)', 1, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(30, 9, number_format($invoices['Invoice']['consumptiontax']), 1, 'R', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(20, 9, '合計', 1, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(55, 9, number_format($total0 + $invoices['Invoice']['consumptiontax']), 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
	//高速代明細
	//$pdf->MultiCell(30, 9, '高速代等', 1, 'C', 0, 0, 100, '', true, 0, false, true, 9, 'B');
	//$pdf->MultiCell(30, 9, number_format($total2), 1, 'R', 0, 0, '', '', true, 0, false, true, 9, 'B');
	//$pdf->MultiCell(20, 9, '税(10%内)', 1, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');
	//$pdf->MultiCell(30, 9, number_format($total2 - ($total2 / 1.1)), 1, 'R', 0, 0, '', '', true, 0, false, true, 9, 'B');
	//$pdf->MultiCell(20, 9, '合計', 1, 'C', 0, 0, '', '', true, 0, false, true, 9, 'B');
	//$pdf->MultiCell(55, 9, number_format($total2), 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');
	//総合計
	$pdf->SetFontSize(12);
	$pdf->MultiCell(20, 9, '総合計', 1, 'C', 0, 0, 210, '', true, 0, false, true, 9, 'B');
	$pdf->MultiCell(55, 9, number_format($invoices['Invoice']['total']), 1, 'R', 0, 1, '', '', true, 0, false, true, 9, 'B');

// 口座情報
	//$pdf->SetFontSize(10);

	//$pdf->MultiCell(40, 7, '口座情報', 1, 'C', 0, 0, 10, '175', true, 0, false, true, 7, 'B');
	//$pdf->MultiCell(150, 7, $kaisyas['Kaisya']['bank'], 1, 'L', 0, 1, '', '', true, 0, false, true, 7, 'B');

	//$pdf->MultiCell(40, 7, '口座名義', 1, 'C', 0, 0, 10, '182', true, 0, false, true, 7, 'B');
	//$pdf->MultiCell(150, 7, $kaisyas['Kaisya']['bankname'].'  ('.$kaisyas['Kaisya']['banknamekana'].')', 1, 'L', 0, 1, '', '', true, 0, false, true, 7, 'B');

$pdf->lastPage();
$pdf->Output('invoice.pdf', 'I');
