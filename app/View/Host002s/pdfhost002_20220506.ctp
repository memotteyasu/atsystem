<?php
// debug($reservetran);

App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, 'A2', true, 'UTF-8', false);

$pdf->SetMargins(10, 10, 10);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('勤怠一覧');
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

	$pdf->MultiCell(30, '', '勤怠一覧', '0', 'L', 0, 0, '', '', true, 0, false, true, 10, 'B');
	$pdf->MultiCell('40', '', $firstday->format('Y年m月'), '0', 'L', 0, 0, '', '', true, 0, false, true, 10, 'B');
	$pdf->MultiCell('', '', $user['Kaisya']['companyname'], '0', 'R', 0, 1, '', '', true, 0, false, true, 10, 'B');
	$pdf->MultiCell(300, '', $departmentmasters['Departmentmaster']['departmentname'].' '.$branchmasters['Branchmaster']['branchname'], '0', 'L', 0, 0, '', '', true, 0, false, true, 10, 'B');

	$pdf->SetFontSize(11);
	$pdf->MultiCell('', '', '印刷日　'.$printday->format('Y年m月d日　H:i　').'P.'.$pdf->PageNo(), '0', 'R', 0, 1, '', '', true, 0, false, true, 10, 'B');

	$pdf->Ln(4);

	$Column1 = 24;
	$Column2 = 10.8;
	$Column3 = 11;
	$Column4 = 15;
	$T = 20;
	$T2 = 10;
	$T3 = 10;

	// 1行目

	$pdf->MultiCell($Column1, $T, '社員名', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');

	foreach ($period as $perioddate) {
		$pdf->MultiCell($Column2, $T, $perioddate->format('j'), '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	}
	$pdf->MultiCell($Column3, $T, "所定\n労働\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "総\n出勤\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "出勤\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "待機\n教育\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "有休\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "休業\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "公出\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "代休\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "欠勤\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, "遅早\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, "平日\n残業\n(1.0)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, "平日\n残業\n(1.25)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, "平日\n深夜\n(0.25)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, "法外\n残業\n(1.25)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, "法外\n深夜\n(0.25)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, "法定\n残業\n(1.35)", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, "法定\n深夜\n(0.25)", '1', 'C', 0, 1, '', '', true, 0, false, true, $T, 'B');

	$i = 0;
	$sosyukkin = 0;
	$zangyo_total = array();
	$sosyukkingoukei = 0;
	$heijitsuzangyo_nashi_gou = $heijitsuzangyo_gou = $heijitsushinya_gou = $kyujitsuzangyo_gou = $kyujitsushinya_gou = $houteikyujitsu_gou = $houteikyujitsushinya_gou = '00:00';
	foreach ($users as $userlist) {
		if ($i < 50) {
			$sosyukkingoukei = $sosyukkingoukei + $sosyukkin;
			$koude = $chiso = $syukkin = $kekkin = $yukyu = $yasumi = $kyusyutu = $siteikyu = $kyouiku = $daikyu = $taiki = $kyugyo = $sosyukkin = 0;
			$heijitsuzangyo_nashi = $heijitsuzangyo = $heijitsushinya = $kyujitsuzangyo = $kyujitsushinya = $houteikyujitsu = $houteikyujitsushinya = '00:00';
			//calendarusermasterの読み込み
	 		$calendarusermaster = ClassRegistry::init('Calendarusermaster');
	 		$clumdata = [];
	 		$clumdata = $calendarusermaster->find('first',[
	 			'conditions'=>[
	 				'Calendarusermaster.calendartype'=>$userlist['User']['calendarflg'],
	 				'Calendarusermaster.month'=>$firstday->format('Y-m-d')
	 			],
	 			'recursive'=>-1
	 		]);
			if ($clumdata) {
				$baseday = $clumdata['Calendarusermaster']['baseday'];
			} else {
				$baseday = 0;
			}
			$pdf->MultiCell($Column1, $T2, $userlist['User']['usernameK'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T2, 'M');

			foreach ($period as $date) {
				$flgname = '';
				$flgmasters_id = null;
				if ($userlist['Kintaitran']) {
					foreach ($userlist['Kintaitran'] as $kintaitran) {
						if ($kintaitran['kintaidate'] == $date->format('Y-m-d')) {
							$flgname = $kintaitran['Flgmaster']['flgname'];
							//法定、法定外かのデータを取る
							$wdayname = 'day'.substr('00'.$date->format('d'),-2);
							switch($kintaitran['rinjicalendarflg']){
								case 0: // 平日
									$flgname=$flgname.'　';
									break;
								case 1: // 法定外
									$flgname=$flgname.'法外';
									break;
								case 2: // 法定
									$flgname=$flgname.'法日';
									break;
							}
							//calendarusermasterの
							switch ($kintaitran['flgmasters_id']) {
								case 11: // 出勤
								case 25: // 出張
								case 22: // 検診
									$syukkin = $syukkin + 1;
									$sosyukkin = $sosyukkin + 1;
									if(($kintaitran['rinjicalendarflg']==1)or($kintaitran['rinjicalendarflg']==2)){
										$koude = $koude + 1;
									}
									break;
								case 12: // 欠勤
									$kekkin = $kekkin +1 ;
									break;
								case 13: // 有休
									$yukyu = $yukyu + 1;
									$sosyukkin = $sosyukkin + 1;
									break;
								case 14: // 休業
									$kyugyo = $kyugyo + 1;
									break;
								case 15: // 待機
									$taiki = $taiki + 1;
									$sosyukkin = $sosyukkin + 1;
									break;
								case 18: // 教育
									$taiki = $taiki + 1;
									$sosyukkin = $sosyukkin + 1;
									break;
								case 19: // 代休
									$daikyu = $daikyu + 1;
									break;
								case 20: // 指定休
								case 17: // 特休
									$siteikyu = $siteikyu + 1;
									break;
								case 21: // 遅早
									$chiso = $chiso + 1;
									break;
								case 23: // 傷病欠
									$kekkin = $kekkin + 1;
									break;
								case 24: // 労災欠
									$kekkin = $kekkin + 1;
									break;
							}

							// 平日残業などの計算
							$heijitsuzangyo_nashi = $this->Format->Addtime($heijitsuzangyo_nashi,$kintaitran['heijitsuzangyo_nashi']);
							$heijitsuzangyo = $this->Format->Addtime($heijitsuzangyo,$kintaitran['heijitsuzangyo']);
							$heijitsushinya = $this->Format->Addtime($heijitsushinya,$kintaitran['heijitsushinya']);
							$kyujitsuzangyo = $this->Format->Addtime($kyujitsuzangyo,$kintaitran['kyujitsuzangyo']);
							$kyujitsushinya = $this->Format->Addtime($kyujitsushinya,$kintaitran['kyujitsushinya']);
							$houteikyujitsu = $this->Format->Addtime($houteikyujitsu,$kintaitran['houteikyujitsu']);
							$houteikyujitsushinya = $this->Format->Addtime($houteikyujitsushinya,$kintaitran['houteikyujitsushinya']);
						}
					}
				}
				$pdf->MultiCell($Column2, $T2, $flgname, '1', 'C', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			}

			$heijitsuzangyo_nashi_gou = $this->Format->Addtime($heijitsuzangyo_nashi_gou,$heijitsuzangyo_nashi);
			$heijitsuzangyo_gou = $this->Format->Addtime($heijitsuzangyo_gou,$heijitsuzangyo);
			$heijitsushinya_gou = $this->Format->Addtime($heijitsushinya_gou,$heijitsushinya);
			$kyujitsuzangyo_gou = $this->Format->Addtime($kyujitsuzangyo_gou,$kyujitsuzangyo);
			$kyujitsushinya_gou = $this->Format->Addtime($kyujitsushinya_gou,$kyujitsushinya);
			$houteikyujitsu_gou = $this->Format->Addtime($houteikyujitsu_gou,$houteikyujitsu);
			$houteikyujitsushinya_gou = $this->Format->Addtime($houteikyujitsushinya_gou,$houteikyujitsushinya);

			$pdf->MultiCell($Column3, $T2, $baseday, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $sosyukkin, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
//20210807			$pdf->MultiCell($Column3, $T2, $syukkin, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $syukkin - $koude, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $taiki, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $yukyu, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $kyugyo, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
//20210807			$pdf->MultiCell($Column3, $T2, $siteikyu, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $koude, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $daikyu, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $kekkin, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $chiso, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');

			$pdf->MultiCell($Column4, $T2, $this->Format->TimeFormat_2($heijitsuzangyo_nashi), '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $this->Format->TimeFormat_2($heijitsuzangyo), '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $this->Format->TimeFormat_2($heijitsushinya), '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $this->Format->TimeFormat_2($kyujitsuzangyo), '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $this->Format->TimeFormat_2($kyujitsushinya), '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $this->Format->TimeFormat_2($houteikyujitsu), '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $this->Format->TimeFormat_2($houteikyujitsushinya), '1', 'R', 0, 1, '', '', true, 0, false, true, $T2, 'M');

			$i = $i + 1;
		} else {
			$i = 0;
			$pdf->AddPage();

			$pdf->SetFontSize(16);

			$pdf->MultiCell(30, '', '勤怠一覧', '0', 'L', 0, 0, '', '', true, 0, false, true, 10, 'B');
			$pdf->MultiCell('40', '', $today->format('Y年m月'), '0', 'L', 0, 0, '', '', true, 0, false, true, 10, 'B');
			$pdf->MultiCell('', '', $user['Kaisya']['companyname'], '0', 'R', 0, 1, '', '', true, 0, false, true, 10, 'B');
			$pdf->MultiCell(300, '', $departmentmasters['Departmentmaster']['departmentname'].' '.$branchmasters['Branchmaster']['branchname'], '0', 'L', 0, 0, '', '', true, 0, false, true, 10, 'B');

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
			$pdf->MultiCell($Column3, $T, "有休\n日数", '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
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

	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column3, $T3, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');

	$pdf->MultiCell($Column4, $T3, $heijitsuzangyo_nashi_gou, '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column4, $T3, $heijitsuzangyo_gou, '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column4, $T3, $heijitsushinya_gou, '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column4, $T3, $kyujitsuzangyo_gou, '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column4, $T3, $kyujitsushinya_gou, '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column4, $T3, $houteikyujitsu_gou, '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');
	$pdf->MultiCell($Column4, $T3, $houteikyujitsushinya_gou, '1', 'R', 0, 0, '', '', true, 0, false, true, $T3, 'M');

$pdf->lastPage();
$pdf->Output('kintaitran.pdf', 'I');
