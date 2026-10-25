<?php
 //debug($ordertrans);

App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetMargins(5, 10, 0);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Partner');
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

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

function headerRow($pdf,$date,$nowdate,$branchname){
	//■■■■■■■■■■■■■ヘッダー start
	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 12);
	$pdf->Write(0, '手　配　明　細 ('.$branchname.')', '', 0, 'C', true, 0, false, false, 0);
	$pdf->SetFontSize(10);
	$pdf->MultiCell(70, '', '配車日：'.$date->format('Y年m月d日'), '0', 'L', 0, 0, '', '', true, 0, false, true, 5, 'B');
	$pdf->MultiCell(66, '', '', '0', 'C', 0, 0, '', '', true, 0, false, true, 5, 'B');
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
	$pdf->MultiCell($Column8, $T, '乗務員', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column6, $T, '作業場所', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, '作業', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, '品名', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column9, $T, '備考(助手)', '1', 'C', 0, 1, '', '', true, 0, false, true, $T, 'M');
	//■■■■■■■■■■■■■ヘッダー end

}

// ---------------------------------------------------------

$date = new DateTime($this->params['pass']['0']);
$nowdate = new Datetime();
$pdf->SetLineWidth(0.2);

$key1 = 0;
$w_branchmasters_id = '';
$w_branchname = '';
$wkey_plateno = '';
$i=0;
foreach ($ordertrans as $ordertran){
	if($i==0){
		//初回だけ
		$w_branchname = $ordertran['Branchmaster']['branchname'];
	}
	if($w_branchmasters_id != $ordertran['Ordertran']['branchmasters_id']){

		$w_branchmasters_id = $ordertran['Ordertran']['branchmasters_id'];
		$w_branchname = $ordertran['Branchmaster']['branchname'];
		//営業所が変わったらヘッダー処理
		//改ページ
		// page start
		$pdf->AddPage();
		headerRow($pdf,$date,$nowdate,$w_branchname);
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
		$linemax = 40;
		// page end 

	}
	$plateno = $ordertran['Carmaster']['plateno'];
	$orderid = $ordertran['Ordertran']['id'];
	$carname = $plateno.'('.$ordertran['Branchmaster']['branchname'].')';
	//		if(($wkey_plateno != $plateno)or($wkey_orderid != $orderid)){
	// オーダーが変わっても車両が変わってなかったら見出しを出さない
	if($wkey_plateno != $plateno){
		$pdf->SetLineWidth(0.5);
		if($linecnt >= $linemax){
			// page start
			$pdf->AddPage();
			headerRow($pdf,$date,$nowdate,$w_branchname);
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
			// page end 
		}
		$wkey_plateno = $plateno;
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
				$pdf->MultiCell($Column8, $T, '未定', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
			}
			if($processtran['workplaces_id']){
				$pdf->MultiCell($Column6, $T, $processtran['Workplace']['workplaceshortname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
			}else{
				$pdf->MultiCell($Column6, $T, '', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
			}
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
	$i++;
}

$pdf->lastPage();
$pdf->Output('tehai.pdf', 'I');
