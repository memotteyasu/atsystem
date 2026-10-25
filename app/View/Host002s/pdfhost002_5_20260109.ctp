<?php
App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, 'A3', true, 'UTF-8', false);

$pdf->SetMargins(10, 10, 10);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('拘束時間一覧');
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

	$pdf->Write(0, '拘束時間・時間外一覧表　'.$paramsDate->format('Y年m月'), '', 0, 'L', true, 0, false, false, 0);

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

	$i = 0;
	foreach ($users as $user) {
		if ($i < 36) {
			// 拘束行
			$pdf->SetFillColor(230, 230, 230);
			$pdf->MultiCell($col2, $row1, $user['User']['usernameK'], '1', 'L', 1, 0, '', '', true, 0, false, true, $row1, 'B');
			$pdf->MultiCell($col3, $row1, '拘束', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'B');

			$pdf->SetFontSize(8);
			$roumu_syoukei1 = $roumu_syoukei2 = '';
			$roumu_goukei = '';
			foreach ($period as $date) {
				$roumu_kousoku = '';
				if ($user['Kintaitran']) {
					foreach ($user['Kintaitran'] as $kintaitran) {
						if ($kintaitran['kintaidate'] == $date->format('Y-m-d')) {
							$roumu_kousoku = $this->Format->TimeFormat($kintaitran['roumu_kousoku']);
							$roumu_goukei = $this->Format->Addtime($roumu_goukei,$kintaitran['roumu_kousoku']);
							if ($date->format('j') <= 14) {
								$roumu_syoukei1 =  $this->Format->Addtime($roumu_syoukei1,$kintaitran['roumu_kousoku']);
							} elseif ($date->format('j') > 14 && $date->format('j') <= 28) {
								$roumu_syoukei2 =  $this->Format->Addtime($roumu_syoukei2,$kintaitran['roumu_kousoku']);
							}
						}
					}
				}
				if ($date->format('j') == 14) {
					if($roumu_kousoku > '15:00'){
						$pdf->SetFillColor(240, 128, 128);
					}else{
						$pdf->SetFillColor(230, 230, 230);
					}
					$pdf->MultiCell($col1, $row1, $roumu_kousoku, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
					$pdf->SetFillColor(230, 230, 230);
					$pdf->MultiCell(12, $row1, $roumu_syoukei1, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
				} elseif ($date->format('j') == 28) {
					if($roumu_kousoku > '15:00'){
						$pdf->SetFillColor(240, 128, 128);
					}else{
						$pdf->SetFillColor(230, 230, 230);
					}
					$pdf->MultiCell($col1, $row1, $roumu_kousoku, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
					$pdf->SetFillColor(230, 230, 230);
					$pdf->MultiCell(12, $row1, $roumu_syoukei2, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
				} else {
					//2024年04月以降１５時間に変更
					//if($roumu_kousoku > '16:00'){
					if($roumu_kousoku > '15:00'){
						//赤系
						$pdf->SetFillColor(240, 128, 128);
						$pdf->MultiCell($col1, $row1, $roumu_kousoku, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
					}else{
						$pdf->SetFillColor(230, 230, 230);
						$pdf->MultiCell($col1, $row1, $roumu_kousoku, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
					}
				}
			}
			$pdf->SetFontSize(10);
			unset($roumu_h);
			$roumu_h = explode(':',$roumu_goukei);
			if($roumu_h[0] > 293){
//			if($roumu_goukei > '293:00'){
				//オレンジ
				$pdf->SetFillColor(255, 165, 0);
				$pdf->MultiCell($col4, $row1, $roumu_goukei, '1', 'R', 1, 1, '', '', true, 0, false, true, $row1, 'B');
			}else{
				$pdf->SetFillColor(230, 230, 230);
				$pdf->MultiCell($col4, $row1, $roumu_goukei, '1', 'R', 1, 1, '', '', true, 0, false, true, $row1, 'B');
			}
			$i = $i + 1;

			// 運転行
			$pdf->SetFillColor(255, 255, 255);
			$pdf->MultiCell($col2, $row1, '', '1', 'L', 1, 0, '', '', true, 0, false, true, $row1, 'B');
			$pdf->MultiCell($col3, $row1, '運転', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'B');

			$pdf->SetFontSize(8);
			$unten_syoukei1 = $unten_syoukei2 = '';
			$unten_goukei = '';
			foreach ($period as $date) {
				$unten_jikan = '';
				$over4 = 0;
				if ($user['Kintaitran']) {
					foreach ($user['Kintaitran'] as $kintaitran) {
						if ($kintaitran['kintaidate'] == $date->format('Y-m-d')) {
							if($over4==0){
								$over4 = $kintaitran['shiharaisuryo']; // 1なら４時間オーバー
							}
							$unten_jikan = $this->Format->TimeFormat($kintaitran['tako_gokei_soukou_h']);
							$unten_goukei = $this->Format->Addtime($unten_goukei,$kintaitran['tako_gokei_soukou_h']);

							if ($date->format('j') <= 14) {
								$unten_syoukei1 =  $this->Format->Addtime($unten_syoukei1,$kintaitran['tako_gokei_soukou_h']);
							} elseif ($date->format('j') > 14 && $date->format('j') <= 28) {
								$unten_syoukei2 =  $this->Format->Addtime($unten_syoukei2,$kintaitran['tako_gokei_soukou_h']);
							}
						}
					}
				}
				if ($date->format('j') == 14) {
					$pdf->MultiCell($col1, $row1, $unten_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
					$pdf->MultiCell(12, $row1, $unten_syoukei1, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
				} elseif ($date->format('j') == 28) {
					$pdf->MultiCell($col1, $row1, $unten_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
					$pdf->MultiCell(12, $row1, $unten_syoukei2, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
				} else {
					if($over4 == 1){
						//４時間オーバーの場合
						$pdf->SetFillColor(154, 205, 50);
						$pdf->MultiCell($col1, $row1, $unten_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
					}else{
						$pdf->SetFillColor(255, 255, 255);
						$pdf->MultiCell($col1, $row1, $unten_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
					}
				}
			}
			$pdf->SetFontSize(10);
			$pdf->MultiCell($col4, $row1, $unten_goukei, '1', 'R', 1, 1, '', '', true, 0, false, true, $row1, 'B');
			$i = $i + 1;

			// 平均行
			$pdf->MultiCell($col2, $row1, '', '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'B');
			$pdf->MultiCell($col3, $row1, '平均', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'B');

			$pdf->SetFontSize(8);
			$heikin_array = [];
			foreach ($period as $date) {
				$heikin_array = $heikin_array + [$date->format('j') => '00:00'];
				if ($user['Kintaitran']) {
					foreach ($user['Kintaitran'] as $kintaitran) {
						if ($kintaitran['kintaidate'] == $date->format('Y-m-d')) {
							$heikin_array[$date->format('j')] = $kintaitran['tako_gokei_soukou_h'];
						}
					}
					// debug($heikin_array);
				}
			}
			foreach ($period as $date) {
				$heikin_jikan = '';
				$count = $date->format('j');

				if ($count == $lastcount) {
				} else {
					$heikin_jikan = $this->Format->AverageTime($heikin_array[$count], $heikin_array[$count+1]);
				}

				if ($date->format('j') == 1) {
					if($heikin_jikan > '09:00'){
						$pdf->SetFillColor(176, 224, 230);
						$pdf->MultiCell(15, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
					}else{
						$pdf->SetFillColor(255, 255, 255);
						$pdf->MultiCell(15, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
					}
				} elseif ($date->format('j') == 14) {
					$pdf->SetFillColor(255, 255, 255);
					$pdf->MultiCell(22, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
				} elseif ($date->format('j') == 28) {
					if ($lastcount <= 28) {
						if($heikin_jikan > '09:00'){
							$pdf->SetFillColor(176, 224, 230);
							$pdf->MultiCell($col1+7, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
						}else{
							$pdf->SetFillColor(255, 255, 255);
							$pdf->MultiCell($col1+7, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
						}
					} else {
						if($heikin_jikan > '09:00'){
							$pdf->SetFillColor(176, 224, 230);
							$pdf->MultiCell(22, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
						}else{
							$pdf->SetFillColor(255, 255, 255);
							$pdf->MultiCell(22, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
						}
					}
				} elseif ($date->format('j') == $lastcount) {
					$pdf->SetFillColor(255, 255, 255);
					$pdf->MultiCell($col1/2, $row1, '', '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
				} else {
					if($heikin_jikan > '09:00'){
						$pdf->SetFillColor(176, 224, 230);
						$pdf->MultiCell($col1, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
					}else{
						$pdf->SetFillColor(255, 255, 255);
						$pdf->MultiCell($col1, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
					}
				}
			}
			$pdf->SetFontSize(10);
			$pdf->MultiCell($col4, $row1, '', '1', 'R', 0, 1, '', '', true, 0, false, true, $row1, 'B');
			$i = $i + 1;
		} else {
			$i = 0;
			$pdf->AddPage();

			$pdf->SetFontSize(12);
			$pdf->Write(0, '拘束時間・時間外一覧表　'.$paramsDate->format('Y年m月'), '', 0, 'L', true, 0, false, false, 0);

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
			//20220905 改ページしたあとに、レコードを印字
						// 拘束行
						$pdf->SetFillColor(230, 230, 230);
						$pdf->MultiCell($col2, $row1, $user['User']['usernameK'], '1', 'L', 1, 0, '', '', true, 0, false, true, $row1, 'B');
						$pdf->MultiCell($col3, $row1, '拘束', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'B');
			
						$pdf->SetFontSize(8);
						$roumu_syoukei1 = $roumu_syoukei2 = '';
						$roumu_goukei = '';
						foreach ($period as $date) {
							$roumu_kousoku = '';
							if ($user['Kintaitran']) {
								foreach ($user['Kintaitran'] as $kintaitran) {
									if ($kintaitran['kintaidate'] == $date->format('Y-m-d')) {
										$roumu_kousoku = $this->Format->TimeFormat($kintaitran['roumu_kousoku']);
										$roumu_goukei = $this->Format->Addtime($roumu_goukei,$kintaitran['roumu_kousoku']);
										if ($date->format('j') <= 14) {
											$roumu_syoukei1 =  $this->Format->Addtime($roumu_syoukei1,$kintaitran['roumu_kousoku']);
										} elseif ($date->format('j') > 14 && $date->format('j') <= 28) {
											$roumu_syoukei2 =  $this->Format->Addtime($roumu_syoukei2,$kintaitran['roumu_kousoku']);
										}
									}
								}
							}
							if ($date->format('j') == 14) {
								if($roumu_kousoku > '15:00'){
									//赤系
									$pdf->SetFillColor(240, 128, 128);
								}else{
									$pdf->SetFillColor(230, 230, 230);
								}
								$pdf->MultiCell($col1, $row1, $roumu_kousoku, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
								$pdf->SetFillColor(230, 230, 230);
								$pdf->MultiCell(12, $row1, $roumu_syoukei1, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
							} elseif ($date->format('j') == 28) {
								if($roumu_kousoku > '15:00'){
									$pdf->SetFillColor(240, 128, 128);
								}else{
									$pdf->SetFillColor(230, 230, 230);
								}
								$pdf->MultiCell($col1, $row1, $roumu_kousoku, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
								$pdf->SetFillColor(230, 230, 230);
								$pdf->MultiCell(12, $row1, $roumu_syoukei2, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
							} else {
								//2024年04月以降１５時間に変更
								//if($roumu_kousoku > '16:00'){
								if($roumu_kousoku > '15:00'){
									//赤系
									$pdf->SetFillColor(240, 128, 128);
									$pdf->MultiCell($col1, $row1, $roumu_kousoku, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
								}else{
									$pdf->SetFillColor(230, 230, 230);
									$pdf->MultiCell($col1, $row1, $roumu_kousoku, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
								}
							}
						}
						$pdf->SetFontSize(10);
						unset($roumu_h);
						$roumu_h = explode(':',$roumu_goukei);
						if($roumu_h[0] > 293){
			//			if($roumu_goukei > '293:00'){
							//オレンジ
							$pdf->SetFillColor(255, 165, 0);
							$pdf->MultiCell($col4, $row1, $roumu_goukei, '1', 'R', 1, 1, '', '', true, 0, false, true, $row1, 'B');
						}else{
							$pdf->SetFillColor(230, 230, 230);
							$pdf->MultiCell($col4, $row1, $roumu_goukei, '1', 'R', 1, 1, '', '', true, 0, false, true, $row1, 'B');
						}
						$i = $i + 1;
			
						// 運転行
						$pdf->SetFillColor(255, 255, 255);
						$pdf->MultiCell($col2, $row1, '', '1', 'L', 1, 0, '', '', true, 0, false, true, $row1, 'B');
						$pdf->MultiCell($col3, $row1, '運転', '1', 'C', 1, 0, '', '', true, 0, false, true, $row1, 'B');
			
						$pdf->SetFontSize(8);
						$unten_syoukei1 = $unten_syoukei2 = '';
						$unten_goukei = '';
						foreach ($period as $date) {
							$unten_jikan = '';
							$over4 = 0;
							if ($user['Kintaitran']) {
								foreach ($user['Kintaitran'] as $kintaitran) {
									if ($kintaitran['kintaidate'] == $date->format('Y-m-d')) {
										if($over4==0){
											$over4 = $kintaitran['shiharaisuryo']; // 1なら４時間オーバー
										}
										$unten_jikan = $this->Format->TimeFormat($kintaitran['tako_gokei_soukou_h']);
										$unten_goukei = $this->Format->Addtime($unten_goukei,$kintaitran['tako_gokei_soukou_h']);
			
										if ($date->format('j') <= 14) {
											$unten_syoukei1 =  $this->Format->Addtime($unten_syoukei1,$kintaitran['tako_gokei_soukou_h']);
										} elseif ($date->format('j') > 14 && $date->format('j') <= 28) {
											$unten_syoukei2 =  $this->Format->Addtime($unten_syoukei2,$kintaitran['tako_gokei_soukou_h']);
										}
									}
								}
							}
							if ($date->format('j') == 14) {
								$pdf->MultiCell($col1, $row1, $unten_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
								$pdf->MultiCell(12, $row1, $unten_syoukei1, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
							} elseif ($date->format('j') == 28) {
								$pdf->MultiCell($col1, $row1, $unten_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
								$pdf->MultiCell(12, $row1, $unten_syoukei2, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
							} else {
								if($over4 == 1){
									//４時間オーバーの場合
									$pdf->SetFillColor(154, 205, 50);
									$pdf->MultiCell($col1, $row1, $unten_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
								}else{
									$pdf->SetFillColor(255, 255, 255);
									$pdf->MultiCell($col1, $row1, $unten_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
								}
							}
						}
						$pdf->SetFontSize(10);
						$pdf->MultiCell($col4, $row1, $unten_goukei, '1', 'R', 1, 1, '', '', true, 0, false, true, $row1, 'B');
						$i = $i + 1;
			
						// 平均行
						$pdf->MultiCell($col2, $row1, '', '1', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'B');
						$pdf->MultiCell($col3, $row1, '平均', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'B');
			
						$pdf->SetFontSize(8);
						$heikin_array = [];
						foreach ($period as $date) {
							$heikin_array = $heikin_array + [$date->format('j') => '00:00'];
							if ($user['Kintaitran']) {
								foreach ($user['Kintaitran'] as $kintaitran) {
									if ($kintaitran['kintaidate'] == $date->format('Y-m-d')) {
										$heikin_array[$date->format('j')] = $kintaitran['tako_gokei_soukou_h'];
									}
								}
								// debug($heikin_array);
							}
						}
						foreach ($period as $date) {
							$heikin_jikan = '';
							$count = $date->format('j');
			
							if ($count == $lastcount) {
							} else {
								$heikin_jikan = $this->Format->AverageTime($heikin_array[$count], $heikin_array[$count+1]);
							}
			
							if ($date->format('j') == 1) {
								if($heikin_jikan > '09:00'){
									$pdf->SetFillColor(176, 224, 230);
									$pdf->MultiCell(15, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
								}else{
									$pdf->SetFillColor(255, 255, 255);
									$pdf->MultiCell(15, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
								}
							} elseif ($date->format('j') == 14) {
								$pdf->SetFillColor(255, 255, 255);
								$pdf->MultiCell(22, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
							} elseif ($date->format('j') == 28) {
								if ($lastcount <= 28) {
									if($heikin_jikan > '09:00'){
										$pdf->SetFillColor(176, 224, 230);
										$pdf->MultiCell($col1+7, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
									}else{
										$pdf->SetFillColor(255, 255, 255);
										$pdf->MultiCell($col1+7, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
									}
								} else {
									if($heikin_jikan > '09:00'){
										$pdf->SetFillColor(176, 224, 230);
										$pdf->MultiCell(22, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
									}else{
										$pdf->SetFillColor(255, 255, 255);
										$pdf->MultiCell(22, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
									}
								}
							} elseif ($date->format('j') == $lastcount) {
								$pdf->SetFillColor(255, 255, 255);
								$pdf->MultiCell($col1/2, $row1, '', '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
							} else {
								if($heikin_jikan > '09:00'){
									$pdf->SetFillColor(176, 224, 230);
									$pdf->MultiCell($col1, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
								}else{
									$pdf->SetFillColor(255, 255, 255);
									$pdf->MultiCell($col1, $row1, $heikin_jikan, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'B');
								}
							}
						}
						$pdf->SetFontSize(10);
						$pdf->MultiCell($col4, $row1, '', '1', 'R', 0, 1, '', '', true, 0, false, true, $row1, 'B');
						$i = $i + 1;
			

		}
	}

$pdf->lastPage();
$pdf->Output('kousoku.pdf', 'I');
