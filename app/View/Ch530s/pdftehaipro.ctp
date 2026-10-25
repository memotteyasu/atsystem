<?php
App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetMargins(5, 10, 0);

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
	$linemax = 40;
	// 1行目

	$pdf->SetFontSize(8);

	$pdf->MultiCell($Column1, $T, '日', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column2, $T, '時間', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column2, $T, '車両', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, '乗務員', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, '荷主', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, '作業', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column6, $T, '作業場所', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, '品名', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, 'コンテナNo', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
	$pdf->MultiCell($Column8, $T, '', '1', 'C', 0, 1, '', '', true, 0, false, true, $T, 'M');
	$key1 = 0;

	$wkey_plateno = '';
	foreach ($processtrans as $processtran){
		// carmaster読み込み
		$userModel = ClassRegistry::init('Carmaster');
		$carmaster = $userModel->find('first',[
			'conditions'=>[
				'Carmaster.id'=>$processtran['Ordertran']['carmasters_id'],
				'Carmaster.delflg'=>0
			],
			'recursive'=>-1
		]);

		if($processtran['Processtran']['processstarttime']==null){
			$workdate = new DateTime($processtran['Processtran']['processstartdate']);
		}else{
			$workdate = new DateTime($processtran['Processtran']['processstartdate'].$processtran['Processtran']['processstarttime']);
		}
		if($date->format('d')<=$workdate->format('d')){
			//今日より未来の日付は出力
			if($processtran['Processtran']['processstarttime']==null){
				if($date->format('d')==$workdate->format('d')){
					$pdf->MultiCell($Column1, $T, $workdate->format('d'), '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
				}else{
					$pdf->Setfillcolor(100,230,230);
					$pdf->MultiCell($Column1, $T, $workdate->format('d'), '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'M');
				}
				$pdf->MultiCell($Column2, $T, $processtran['Processtran']['timenote'], '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
			}else{
				if($date->format('d')==$workdate->format('d')){
					$pdf->MultiCell($Column1, $T, $workdate->format('d'), '1', 'R', 0, 0, '', '', true, 0, false, true, $T, 'M');
				}else{
					$pdf->Setfillcolor(100,230,230);
					$pdf->MultiCell($Column1, $T, $workdate->format('d'), '1', 'R', 1, 0, '', '', true, 0, false, true, $T, 'M');
				}
				$pdf->MultiCell($Column2, $T, $workdate->format('H:i'), '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'M');
			}
			$pdf->MultiCell($Column2, $T, $carmaster['Carmaster']['carno'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
			
			if (isset($processtran['PDriver1']['usernameK'])){
				$pdf->MultiCell($Column8, $T, $processtran['PDriver1']['usernameK'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
			}else{
				$pdf->MultiCell($Column8, $T, '未定', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
			}
			// shippermaster読み込み
			$userModel = ClassRegistry::init('Shippermaster');
			$shippermaster = $userModel->find('first',[
				'conditions'=>[
					'Shippermaster.id'=>$processtran['Ordertran']['shippermasters_id'],
					'Shippermaster.delflg'=>0
				],
				'recursive'=>-1
			]);

			$pdf->MultiCell($Column8, $T, $shippermaster['Shippermaster']['shippershortname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');

			$pdf->MultiCell($Column8, $T, $processtran['Workdetailmaster']['workname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
			if($processtran['Processtran']['workplaces_id']){
				$pdf->MultiCell($Column6, $T, $processtran['Workplace']['workplaceshortname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
			}else{
				$pdf->MultiCell($Column6, $T, '', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
			}
			if (isset($processtran['Productcontainer']['pdconname'])) {
				$pdf->MultiCell($Column8, $T, $processtran['Productcontainer']['pdconname'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
			}else{
				$pdf->MultiCell($Column8, $T, '', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
			}
			//コンテナNo
			if (isset($processtran['Processtran']['container_no'])) {
				$pdf->MultiCell($Column8, $T, $processtran['Processtran']['container_no'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
			}else{
				$pdf->MultiCell($Column8, $T, '', '1', 'L', 0, 0, '', '', true, 0, false, true, $T, 'M');
			}
			$pdf->MultiCell($Column8, $T, '', '1', 'L', 0, 1, '', '', true, 0, false, true, $T, 'M');
		}else{
			//過去日は印字しない
		}
		$linecnt++;

	}


$pdf->lastPage();
$pdf->Output('tehaipro.pdf', 'I');
