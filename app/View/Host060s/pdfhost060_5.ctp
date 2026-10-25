<?php
//pdfhost002_5のcopy
App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, 'A3', true, 'UTF-8', false);

$pdf->SetMargins(10, 10, 10);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('休憩超過時間一覧');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('asahi');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 5);

// ---------------------------------------------------------

	// ページを追加する
	$pdf->AddPage();

	// 印刷日時
	$printday = new DateTime();

	// lastcount
	$lastcount = $lastday->format('j');

	// lastday+1
	$lastday->add(new DateInterval('P1D'));

	//インターバルを設定
	$interval = new DateInterval('P1D');

	//期間を設定
	$period = new DatePeriod($firstday, $interval, $lastday);

	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線,　テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 12);

	$pdf->Write(0, '休憩超過時間一覧表　'.$paramsDate->format('Y年m月'), '', 0, 'L', true, 0, false, false, 0);

	$pdf->MultiCell(180, '', $departmentmasters['Departmentmaster']['departmentname'].' '.$branchmasters['Branchmaster']['branchname'], '0', 'L', 0, 0, '', '', true, 0, false, true, 10, 'B');
	$pdf->MultiCell(200, '', 'P.'.$pdf->PageNo(), '0', 'R', 0, 1, '', '', true, 0, false, true, 10, 'B');

	//$pdf->Ln(4);

	$col1 = 10;
	$col2 = 20;
	$col3 = 10;
	$col4 = 16;

	$row1 = 7;

	// 1行目
	$pdf->SetFontSize(10);

	$pdf->MultiCell($col2, $row1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'B');
	$pdf->MultiCell($col3, $row1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'B');

	foreach ($period as $perioddate) {
		$pdf->MultiCell($col1, $row1, $perioddate->format('j'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'B');
	}
	$pdf->MultiCell($col4, $row1, '合計', '1', 'L', 0, 1, '', '', true, 0, false, true, $row1, 'B');

	$i = 0;
	foreach ($users as $user) {
		if ($i < 36) {
			//
			$pdf->SetFillColor(255, 255, 255);
			$pdf->MultiCell($col2+$col3, $row1, $user['User']['usernameK'], '1', 'L', 1, 0, '', '', true, 0, false, true, $row1, 'B');
			//$pdf->MultiCell($col3, $row1, '', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'B');

			$pdf->SetFontSize(8);
			$roumu_goukei = '';
			foreach ($period as $date) {
				$kyukeitime = '';
				if ($user['Kintaitran']) {
					foreach ($user['Kintaitran'] as $kintaitran) {
						if ($kintaitran['kintaidate'] == $date->format('Y-m-d')) {
							$kyukeitime = $this->Format->Subtime($kintaitran['kyukeitime'],'03:00:00');
							if($kyukeitime > '00:00'){
								$roumu_goukei = $this->Format->Addtime($roumu_goukei,$kyukeitime);
							}
						}
					}
				}
				if($kyukeitime > '00:00'){
					$pdf->MultiCell($col1, $row1, $kyukeitime, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
				}else{
					$pdf->MultiCell($col1, $row1, '', '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
				}
			}
			$pdf->SetFontSize(10);
			unset($roumu_h);
			$roumu_h = explode(':',$roumu_goukei);
			$pdf->SetFillColor(230, 230, 230);
			$pdf->MultiCell($col4, $row1, $roumu_goukei, '1', 'R', 1, 1, '', '', true, 0, false, true, $row1, 'B');
			$i = $i + 1;


		} else {
			$i = 0;
			$pdf->AddPage();

			$pdf->SetFontSize(12);
			$pdf->Write(0, '休憩超過時間一覧表　'.$paramsDate->format('Y年m月'), '', 0, 'L', true, 0, false, false, 0);

			$pdf->MultiCell(180, '', $departmentmasters['Departmentmaster']['departmentname'].' '.$branchmasters['Branchmaster']['branchname'], '0', 'L', 0, 0, '', '', true, 0, false, true, 10, 'B');
			$pdf->MultiCell(200, '', 'P.'.$pdf->PageNo(), '0', 'R', 0, 1, '', '', true, 0, false, true, 10, 'B');

			$pdf->SetFontSize(10);
			$pdf->MultiCell($col2, $row1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'B');
			$pdf->MultiCell($col3, $row1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'B');

			foreach ($period as $perioddate) {
				if ($perioddate->format('j') == 14) {
					$pdf->MultiCell($col1, $row1, $perioddate->format('j'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'B');
					$pdf->MultiCell(12, $row1, '※', '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'B');
				} elseif ($perioddate->format('j') == 28) {
					$pdf->MultiCell($col1, $row1, $perioddate->format('j'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'B');
					$pdf->MultiCell(12, $row1, '※', '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'B');
				} else {
					$pdf->MultiCell($col1, $row1, $perioddate->format('j'), '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'B');
				}
			}
			$pdf->MultiCell($col4, $row1, '合計', '1', 'L', 0, 1, '', '', true, 0, false, true, $row1, 'B');
		}
	}

$pdf->lastPage();
$pdf->Output('kyukeitime.pdf', 'I');
