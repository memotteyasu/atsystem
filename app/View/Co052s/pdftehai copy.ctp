<?php
 //debug($ordertrans);

App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetMargins(5, 10, 0);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('tehai');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('asahi, transfer');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// ---------------------------------------------------------

	$date = new DateTime($this->params['pass']['0']);
	$nowdate = new Datetime();
	$pdf->SetLineWidth(0.2);

	// ページを追加する
	$pdf->AddPage();
	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 12);
	$pdf->Write(0, '手　配　明　細', '', 0, 'C', true, 0, false, false, 0);
	$pdf->SetFontSize(10);
	$pdf->MultiCell(70, '', '配車日：'.$date->format('Y年m月d日'), '0', 'L', 0, 0, '', '', true, 0, false, true, 5, 'B');
	$pdf->MultiCell(66, '', '('.$branchname['Branchmaster']['branchname'].')', '0', 'C', 0, 0, '', '', true, 0, false, true, 5, 'B');
	$pdf->SetFontSize(8);
	$pdf->MultiCell(60, '', '作成日　'.$nowdate->format('Y年m月d日 h:m'), '0', 'R', 0, 1, '', '', true, 0, false, true, 5, 'B');

	$Column1 = 6;
	$Column2 = 10;
	$Column4 = 16;
	$Column5 = 16;
	$Column6 = 50;
	$Column7 = 14;
	$Column8 = 20;
	$Column9 = 30;
	$Column12 = 50;
	$Column13 = 49;
	$T = 5;
	$T2 = 10;
	$linecnt = 0;
	// 1行目

	$pdf->SetFontSize(8);

	$pdf->MultiCell($Column1, $T, '日', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column2, $T, '時間', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, '運転手', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
//	$pdf->MultiCell($Column8, $T, '助手', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column6, $T, '作業場所', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, '作業', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, '品名', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, 'コンテナNo', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, 'REF', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column9, $T, '備考(助手)', '1', 'C', 0, 1, '', '', true, 0, false, true, $T, 'M');
//	$pdf->MultiCell($Column12, $T, '備考', '1', 'C', 0, 1, '', '', true, 0, false, true, $T, 'M');
	$key1 = 0;

	$wkey_plateno = '';
	$wkey_orderid = '';
	foreach ($ordertrans as $ordertran){
		$plateno = $ordertran['Carmaster']['plateno'];
		$orderid = $ordertran['Ordertran']['id'];
		if ($ordertran['Carmaster']['id'] != 0) {
			$w_type = '（'.$ordertran['Carmaster']['Trailermaster']['trailerkind'].'）';
		} else {
			$w_type = null;
		}
		$carname = $plateno.$w_type;
		//if(($wkey_plateno != $plateno)or($wkey_orderid != $orderid)){
		if($wkey_orderid != $orderid){
			$pdf->SetLineWidth(0.5);
			if($linecnt >= 46){
				//改ページ
				// ページを追加する
				$pdf->AddPage();
				// フォントの設定
				$pdf->SetFont('kozminproregular', '', 12);
				$pdf->Write(0, '手　配　明　細', '', 0, 'C', true, 0, false, false, 0);
				$pdf->SetFontSize(10);
				$pdf->MultiCell(70, '', '配車日：'.$date->format('Y年m月d日'), '0', 'L', 0, 0, '', '', true, 0, false, true, 5, 'B');
				$pdf->MultiCell(66, '', '('.$branchname['Branchmaster']['branchname'].')', '0', 'C', 0, 0, '', '', true, 0, false, true, 5, 'B');
				$pdf->SetFontSize(8);
				$pdf->MultiCell(60, '', '作成日　'.$nowdate->format('Y年m月d日 h:m'), '0', 'R', 0, 1, '', '', true, 0, false, true, 5, 'B');
				$linecnt = 1;
			}
			$wkey_orderid = $orderid;
			//車両情報出力
			$pdf->Setfillcolor(230,230,230);
			$pdf->MultiCell($Column13, $T, $carname, '1', 'L', 1, 0, '', '', true, 0, false, true, $T, 'M');
			$pdf->Multicell($Column13, $T, $ordertran['Shippermaster']['shippershortname'], '1', 'L', 1, 0, '', '', true, 0, false, true, $T, 'M');
			$loadingtime = new DateTime($ordertran['Ordertran']['loadingdate']);
			$pdf->MultiCell($Column13, $T, '始)'.$loadingtime->format('Y-m-d'), '1', 'L', 1, 0, '', '', true, 0, false, true, $T, 'M');
			$deliverytime = new DateTime($ordertran['Ordertran']['deliverydate']);
			$pdf->MultiCell($Column13, $T, '終)'.$deliverytime->format('Y-m-d'), '1', 'L', 1, 1, '', '', true, 0, false, true, $T, 'M');
			$linecnt++;
		}
		$pdf->SetLineWidth(0.2);
		foreach($ordertran['Processtran'] as $processtran){
			if($processtran['processstarttime']==null){
				$workdate = new DateTime($processtran['processstartdate']);
			}else{
				$workdate = new DateTime($processtran['processstartdate'].$processtran['processstarttime']);
			}
			if($date->format('d')<=$workdate->format('d')){
				//今日より未来の日付は出力
				if($processtran['processstarttime']==null){
					if($date->format('d')==$workdate->format('d')){
						$pdf->MultiCell($Column1, $T, $workdate->format('d'), '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
					}else{
						$pdf->Setfillcolor(100,230,230);
						$pdf->MultiCell($Column1, $T, $workdate->format('d'), '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'M');
					}
					$pdf->MultiCell($Column2, $T, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
				}else{
					if($date->format('d')==$workdate->format('d')){
						$pdf->MultiCell($Column1, $T, $workdate->format('d'), '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
					}else{
						$pdf->Setfillcolor(100,230,230);
						$pdf->MultiCell($Column1, $T, $workdate->format('d'), '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'M');
					}
					$pdf->MultiCell($Column2, $T, $workdate->format('H:i'), '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
				}
				if (isset($processtran['PDriver1']['usernameK'])){
					$pdf->MultiCell($Column8, $T, $processtran['PDriver1']['usernameK'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
				}else{
					$pdf->MultiCell($Column8, $T, '', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
				}
				$pdf->MultiCell($Column6, $T, $processtran['Workplace']['workplaceshortname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
				$pdf->MultiCell($Column8, $T, $processtran['Workdetailmaster']['workname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
				if (isset($processtran['Productcontainer']['pdconname'])) {
					$pdf->MultiCell($Column8, $T, $processtran['Productcontainer']['pdconname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
				}else{
					$pdf->MultiCell($Column8, $T, '', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
				}
				//コンテナNo
				if (isset($processtran['container_no'])) {
					$pdf->MultiCell($Column8, $T, $processtran['container_no'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
				}else{
					$pdf->MultiCell($Column8, $T, '', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
				}
				//REF No
				if (isset($processtran['ref_no'])) {
					$refno = [];
					$refno = explode('-',$processtran['ref_no']);
					$pdf->MultiCell($Column8, $T, $refno[0], '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
				}else{
					$pdf->MultiCell($Column8, $T, '', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
				}
				if (isset($processtran['PDriver2']['usernameK'])){
					if (isset($processtran['Productcontainer']['workddetailcomment'])) {
						$pdf->MultiCell($Column9, $T, $processtran['PDriver2']['usernameK'].$processtran['workddetailcomment'], '1', 'L', 0, 1, '', '', true, 0, false, true, $T, 'M');
					}else{
						$pdf->MultiCell($Column9, $T, $processtran['PDriver2']['usernameK'], '1', 'L', 0, 1, '', '', true, 0, false, true, $T, 'M');
					}
				}else{
					if (isset($processtran['workddetailcomment'])) {
						$pdf->MultiCell($Column9, $T, $processtran['workddetailcomment'], '1', 'L', 0, 1, '', '', true, 0, false, true, $T, 'M');
					}else{
						$pdf->MultiCell($Column9, $T, '', '1', 'L', 0, 1, '', '', true, 0, false, true, $T, 'M');
					}
				}
			}else{
				//過去日は印字しない
			}
			$linecnt++;
		}

	}

$pdf->lastPage();
$pdf->Output('tehai.pdf', 'I');
