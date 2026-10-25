<?php
// debug($reservetran);

App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, 'A2', true, 'UTF-8', false);

$pdf->SetMargins(10, 10, 10);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('receipt');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('tankback, transfer');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// ---------------------------------------------------------

	$today = new DateTime();

	// ページを追加する
	$pdf->AddPage();

	$lastday->add(new DateInterval('P1D'));

	//インターバルを設定
	$interval = new DateInterval('P1D');

	//期間を設定
	$period = new DatePeriod($firstday, $interval, $lastday);

	//印刷日時を取得
	$printday = new DateTime();

	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線,　テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 16);

//	$pdf->SetLineStyle(array('width' => 0.2));
	// $pdf->Line(594/2, 0, 594/2, 300);

	$pdf->MultiCell(290, '', '勤怠一覧', '0', 'R', 0, 0, '', '', true, 0, false, true, 10, 'B');
	$pdf->MultiCell(35, '', $today->format('Y年m月'), '0', 'R', 0, 0, '', '', true, 0, false, true, 10, 'B');
	$pdf->MultiCell('', '', '朝日運輸株式会社', '0', 'R', 0, 1, '', '', true, 0, false, true, 10, 'B');

	$pdf->MultiCell(330, '', '本社　現場　運転手　石油', '0', 'R', 0, 0, '', '', true, 0, false, true, 10, 'B');

	$pdf->SetFontSize(11);
	$pdf->MultiCell('', '', '印刷日　'.$printday->format('Y年m月d日　H:i　').'P.'.$pdf->PageNo(), '0', 'R', 0, 1, '', '', true, 0, false, true, 10, 'B');

	$pdf->Ln(4);

	$Column1 = 24;
	$Column2 = 11;
	$Column3 = 11;
	$Column4 = 14;
	$T = 20;
	$T2 = 7;
	$T3 = 10;

	// 1行目

	$pdf->MultiCell($Column1, $T, '社員名', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');

	foreach ($period as $perioddate) {
		$pdf->MultiCell($Column2, $T, $perioddate->format('j'), '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	}

	$pdf->MultiCell($Column3, $T, "所定\n労働\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "総\n出勤\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "出勤\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "有給\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "休日\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "指定休\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "特休\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "代休\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "欠勤\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "休業\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, "平日\n残業\n(1.0)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, "平日\n残業\n(1.25)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, "平日\n深夜\n(0.25)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, "法外\n残業\n(1.25)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, "法外\n深夜\n(0.25)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, "法定\n残業\n(1.35)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, "法定\n深夜\n(0.25)", '1', 'C', 0, 1, '', '', true, 0, false, true, $T, 'B');
	// debug($users);

	$i = 0;
	$sosyukkin = 0;
	$basedaygoukei = $sosyukkingoukei = 0;
	foreach ($users as $userlist) {
		if ($i < 50) {
			$sosyukkingoukei = $sosyukkingoukei + $sosyukkin;
			$heijitsuzangyo_nashi = $heijitsuzangyo = $heijitsushinya = $kyujitsuzangyo = $kyujitsushinya = $houteikyujitsu = $houteikyujitsushinya = '00:00';
			$heijitsuzangyo_nashi_text = $heijitsuzangyo_text = $heijitsushinya_text = $kyujitsuzangyo_text = $kyujitsushinya_text = $houteikyujitsu_text = $houteikyujitsushinya_text = '';
			$syukkin = $kekkin = $yukyu = $yasumi = $kyusyutu = $siteikyu = $tokukyu = $kyouiku = $daikyu = $taiki = $kyugyo = $sosyukkin = 0;
			if ($userlist['Calendarusermaster']) {
				$baseday = $userlist['Calendarusermaster'][0]['baseday'];
			} else {
				$baseday = 0;
			}
			$basedaygoukei = $basedaygoukei + $baseday;
			$pdf->MultiCell($Column1, $T2, $userlist['User']['usernameK'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T2, 'M');

			foreach ($period as $date) {
				$flgname = '';
				$flgmasters_id = null;
				if ($userlist['Kintaitran']) {
					foreach ($userlist['Kintaitran'] as $kintaitran) {
						if ($kintaitran['kintaidate'] == $date->format('Y-m-d')) {
							$flgname = $kintaitran['Flgmaster']['flgname'];

							switch ($kintaitran['flgmasters_id']) {
								case 11: // 出勤
									$syukkin = $syukkin + 1;
									$sosyukkin = $sosyukkin + 1;
									break;
								case 12: // 欠勤
									$kekkin = $kekkin +1 ;
									break;
								case 13: // 有給
									$yukyu = $yukyu + 1;
									$sosyukkin = $sosyukkin + 1;
									break;
								case 14: // 休み
									$yasumi = $yasumi + 1;
									break;
								case '休出':
									$kyusyutu = $kyusyutu + 1;
									break;
								case '指定休':
									$siteikyu = $siteikyu + 1;
									break;
								case 17: // 特休
									$tokukyu = $tokukyu + 1;
									break;
								case 18: // 教育
									$kyouiku = $kyouiku + 1;
									$sosyukkin = $sosyukkin + 1;
									break;
								case 19: // 代休
									$daikyu = $daikyu + 1;
									break;
								case 15: // 待機
									$taiki = $taiki + 1;
									$sosyukkin = $sosyukkin + 1;
									break;
							}

							// 平日残業などの計算
							// $heijitsuzangyo_nashi = AddVtime($heijitsuzangyo_nashi,$kintaitran['heijitsuzangyo_nashi']);
							$time = AddVtime($heijitsuzangyo_nashi,$kintaitran['heijitsuzangyo_nashi']);
							$heijitsuzangyo_nashi = $time[0];
							$heijitsuzangyo_nashi_text = $time[1];

							$time = AddVtime($heijitsuzangyo,$kintaitran['heijitsuzangyo']);
							$heijitsuzangyo = $time[0];
							$heijitsuzangyo_text = $time[1];

							$time = AddVtime($heijitsushinya,$kintaitran['heijitsushinya']);
							$heijitsushinya = $time[0];
							$heijitsushinya_text = $time[1];

							$time = AddVtime($kyujitsuzangyo,$kintaitran['kyujitsuzangyo']);
							$kyujitsuzangyo = $time[0];
							$kyujitsuzangyo_text = $time[1];

							$time = AddVtime($kyujitsushinya,$kintaitran['kyujitsushinya']);
							$kyujitsushinya = $time[0];
							$kyujitsushinya_text = $time[1];

							$time = AddVtime($houteikyujitsu,$kintaitran['houteikyujitsu']);
							$houteikyujitsu = $time[0];
							$houteikyujitsu_text = $time[1];

							$time = AddVtime($houteikyujitsushinya,$kintaitran['houteikyujitsushinya']);
							$houteikyujitsushinya = $time[0];
							$houteikyujitsushinya_text = $time[1];
						}
					}
				}
				$pdf->MultiCell($Column2, $T2, $flgname, '1', 'C', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			}
			// debug($userlist);

			$pdf->MultiCell($Column3, $T2, $baseday, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $sosyukkin, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $syukkin, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $yukyu, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $yasumi, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $siteikyu, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $tokukyu, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $daikyu, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $kekkin, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $kyugyo, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');

			$pdf->MultiCell($Column4, $T2, $heijitsuzangyo_nashi_text, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $heijitsuzangyo_text, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $heijitsushinya_text, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $kyujitsuzangyo_text, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $kyujitsushinya_text, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $houteikyujitsu_text, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $houteikyujitsushinya_text, '1', 'R', 0, 1, '', '', true, 0, false, true, $T2, 'M');

			$i = $i + 1;
		} else {
			$i = 0;
			$pdf->AddPage();

			$pdf->SetFontSize(16);
			$pdf->MultiCell(290, '', '勤怠一覧', '0', 'R', 0, 0, '', '', true, 0, false, true, 10, 'B');
			$pdf->MultiCell(35, '', $today->format('Y年m月'), '0', 'R', 0, 0, '', '', true, 0, false, true, 10, 'B');
			$pdf->MultiCell('', '', '朝日運輸株式会社', '0', 'R', 0, 1, '', '', true, 0, false, true, 10, 'B');

			$pdf->MultiCell(330, '', '本社　現場　運転手　石油', '0', 'R', 0, 0, '', '', true, 0, false, true, 10, 'B');

			$pdf->SetFontSize(11);
			$pdf->MultiCell('', '', '印刷日　'.$printday->format('Y年m月d日　H:i　').'P.'.$pdf->PageNo(), '0', 'R', 0, 1, '', '', true, 0, false, true, 10, 'B');

			$pdf->Ln(4);

			$pdf->MultiCell($Column1, $T, '社員名', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');

			foreach ($period as $perioddate) {
				$pdf->MultiCell($Column2, $T, $perioddate->format('j'), '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			}

			$pdf->MultiCell($Column3, $T, "所定\n労働\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, "総\n出勤\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, "出勤\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, "有給\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, "休日\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, "指定休\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, "特休\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, "代休\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, "欠勤\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, "休業\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column4, $T, "平日\n残業\n(1.0)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column4, $T, "平日\n残業\n(1.25)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column4, $T, "平日\n深夜\n(0.25)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column4, $T, "法外\n残業\n(1.25)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column4, $T, "法外\n深夜\n(0.25)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column4, $T, "法定\n残業\n(1.35)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column4, $T, "法定\n深夜\n(0.25)", '1', 'C', 0, 1, '', '', true, 0, false, true, $T, 'B');
		}
	}

	$pdf->SetFontSize(16);
	$pdf->MultiCell($Column1, $T3, '合計', '1', 'C', 0, 0, '', '', true, 0, false, true, $T3, 'M');

	$pdf->SetFontSize(11);
	foreach ($period as $perioddate) {
		$pdf->MultiCell($Column2, $T3, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	}

	$pdf->MultiCell($Column3, $T3, $basedaygoukei, '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, $sosyukkingoukei, '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');

	$pdf->MultiCell($Column4, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column4, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column4, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column4, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column4, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column4, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column4, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');

$pdf->lastPage();
$pdf->Output('pdf4.pdf', 'I');

function AddVtime($a,$b) {
	if (isset($b)) {
		$c = $b;
	} else {
		$c = '00:00';
	}

	$aArry = explode(':',$a);
	$bArry = explode(':',$c);

	$time = date('H:i',mktime($aArry[0]+$bArry[0],$aArry[1]+$bArry[1]));
	if ($time == '00:00') {
		$time2 = '';
	} else {
		$time2 = $time;
	}
	return [0 => $time, 1 => $time2];
}
