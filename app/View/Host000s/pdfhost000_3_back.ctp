<?php
// debug($reservetran);

App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetMargins(5, 10, 5);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('残業手当明細');
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

	// 印刷日時
	$printday = new DateTime();

	// lastday+1
	$lastday->add(new DateInterval('P1D'));

	//インターバルを設定
	$interval = new DateInterval('P1D');

	//期間を設定
	$period = new DatePeriod($firstday, $interval, $lastday);

	// ページを追加する
	// $pdf->AddPage();

	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線,　テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 14);

	// カラムの高さ・幅の設定
	$col1 = 12;
	$col2 = 20;
	$col3 = 12.5;
	$col4 = 12.5;
	$col5 = 15.2;
	$row1 = 5;
	$row2 = 5;

	// debug($users);

	foreach ($users as $user) {
		// ページを追加する
		$pdf->AddPage();

		$pdf->SetFontSize(14);

		$pdf->MultiCell(35, '', '残業手当明細', '0', 'L', 0, 0, '', '', true, 0, false, true, 8, 'B');
		$pdf->MultiCell('60', '', $paramsDate->format('Y年m月'), '0', 'L', 0, 0, '', '', true, 0, false, true, 8, 'B');
		$pdf->MultiCell('', '', $user['Kaisya']['companyname'], '0', 'R', 0, 1, '', '', true, 0, false, true, 8, 'B');
		$pdf->SetFontSize(12);
		$pdf->MultiCell('200', '', $user['Departmentmaster']['departmentname'].' '.$user['Branchmaster']['branchname'].' '.$user['User']['usernameK'], '0', 'L', 0, 0, '', '', true, 0, false, true, 6, 'B');
		$pdf->SetFontSize(8);
		$pdf->MultiCell(87, '', '印刷日　'.$printday->format('Y年m月d日　H:i　').'P.'.$pdf->PageNo(), '0', 'R', 0, 1, '', '', true, 0, false, true, 6, 'B');

		$pdf->Ln(2);

		$pdf->SetFontSize(9);

		$pdf->MultiCell($col1, $row1, '', 'LT', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3*3, $row1, '＜出退勤＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3*4, $row1, '＜労働時間＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4*6, $row1, '＜時間外勤務＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5*7, $row1, '＜手当＞', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col1, $row1, '日', 'LB', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col3, $row1, '始業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3, $row1, '終業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3, $row1, '拘束', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col3, $row1, '走行', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3, $row1, '作業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3, $row1, '待機', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3, $row1, '合計', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col4, $row1, '普残', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '普深', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '休残', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '休深', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '法残', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '法深', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col5, $row1, 'ﾄﾚ-ﾗ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, '走行km', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, '距離歩合', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, '作業歩合', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col5, $row1, '合計金額', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');

		// debug($user);

		$sou_soukou = $sou_sagyou = $sou_taiki = $sou_roudou = '00:00';
		$sou_hei_zan = $sou_hei_shin =$sou_kyu_zan = $sou_kyu_shin = $sou_hou_zan = $sou_hou_shin = '00:00';
		$zangyou_goukei = '00:00';
		foreach ($period as $perioddate) {
			$syukkin = $taikin = '';
			$kinmu_jikan = $heijitsuzangyo = $heijitsushinya = $kyujitsuzangyo = $kyujitsushinya = $houteikyujitsu = $houteikyujitsushinya = '';
			$tako_gokei_soukou_h = $tako_gokei_sagyou_h = $tako_gokei_taiki_h = $sagyoteate = $tako_gokei_km = $soukoukiro = $chokyori_teate = $kaiji_teate = $takasyo_teate = $trailer_teate = $jyoumu_teate = $chain_teate = $eigyokiro = $shiharaisuryo = $koukadou_teate = '';
			$goukei = 0;
			$roudou_goukei = '';
			$heijitsuzangyo_2 = $roudou_goukei_2 = '';

			$pdf->MultiCell($col1, $row2, $perioddate->format('d').'('.$week[$perioddate->format('w')].')', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');

			if ($user['Kintaitran']) {
				foreach ($user['Kintaitran'] as $kintaitran) {
					// debug($kintaitran);
					if ($kintaitran['kintaidate'] == $perioddate->format('Y-m-d')) {
						$syukkin = $this->Format->TimeFormat($kintaitran['timestart']);
						$taikin = $this->Format->TimeFormat($kintaitran['timeend']);
						$kinmu_jikan = $this->Format->TimeFormat($kintaitran['roumu_kousoku']);

						$tako_gokei_soukou_h = $this->Format->TimeFormat($kintaitran['tako_gokei_soukou_h']);
						$tako_gokei_sagyou_h = $this->Format->TimeFormat($kintaitran['tako_gokei_sagyou_h']);
						$tako_gokei_taiki_h = $this->Format->TimeFormat($kintaitran['tako_gokei_taiki_h']);

						$time = $this->Format->AddVtime($kintaitran['tako_gokei_soukou_h'],$kintaitran['tako_gokei_sagyou_h']);
						$time_2 = $this->Format->AddVtime($time[0],$kintaitran['tako_gokei_taiki_h']);
						$roudou_goukei = $time_2[1];
						$roudou_goukei_2 = $time_2[0];

						$time = $this->Format->AddVtime($kintaitran['heijitsuzangyo'],$kintaitran['heijitsuzangyo_nashi']);
						$heijitsuzangyo = $time[1];
						$heijitsuzangyo_2 = $time[0];

						$heijitsushinya = $this->Format->TimeFormat($kintaitran['heijitsushinya']);
						$kyujitsuzangyo = $this->Format->TimeFormat($kintaitran['kyujitsuzangyo']);
						$kyujitsushinya = $this->Format->TimeFormat($kintaitran['kyujitsushinya']);
						$houteikyujitsu = $this->Format->TimeFormat($kintaitran['houteikyujitsu']);
						$houteikyujitsushinya = $this->Format->TimeFormat($kintaitran['houteikyujitsushinya']);

						$soukoukiro = $this->Format->If_null($kintaitran['soukoukiro']);
						$chokyori_teate = $this->Format->If_null($kintaitran['chokyori_teate']);
						$kaiji_teate = $this->Format->If_null($kintaitran['kaiji_teate']);
						$takasyo_teate = $this->Format->If_null($kintaitran['takasyo_teate']);
						$trailer_teate = $this->Format->If_null($kintaitran['trailer_teate']);
						$jyoumu_teate = $this->Format->If_null($kintaitran['jyoumu_teate']);
						$chain_teate = $this->Format->If_null($kintaitran['chain_teate']);
						$eigyokiro = $this->Format->If_null($kintaitran['eigyokiro']);
						$sagyoteate = $this->Format->If_null($kintaitran['sagyoteate']);
						$tako_gokei_km = $this->Format->If_null($kintaitran['tako_gokei_km']);
						$shiharaisuryo = $this->Format->If_null($kintaitran['shiharaisuryo']);
						$koukadou_teate = $this->Format->If_null($kintaitran['koukadou_teate']);

						$goukei = $goukei + $chokyori_teate + $kaiji_teate + $takasyo_teate + $trailer_teate + $jyoumu_teate + $chain_teate + $koukadou_teate + $sagyoteate + $soukoukiro;

						$time = $this->Format->AddVtime($sou_soukou,$kintaitran['tako_gokei_soukou_h']);
						$sou_soukou = $time[0];
						$time = $this->Format->AddVtime($sou_sagyou,$kintaitran['tako_gokei_sagyou_h']);
						$sou_sagyou = $time[0];
						$time = $this->Format->AddVtime($sou_taiki,$kintaitran['tako_gokei_taiki_h']);
						$sou_taiki = $time[0];
						$time = $this->Format->AddVtime($sou_roudou,$roudou_goukei_2);
						$sou_roudou = $time[0];

						$time = $this->Format->AddVtime($sou_hei_zan,$heijitsuzangyo_2);
						$sou_hei_zan = $time[0];

						$time = $this->Format->AddVtime($sou_hei_shin,$kintaitran['heijitsushinya']);
						$sou_hei_shin = $time[0];

						$time = $this->Format->AddVtime($sou_kyu_zan,$kintaitran['kyujitsuzangyo']);
						$sou_kyu_zan = $time[0];

						$time = $this->Format->AddVtime($sou_kyu_shin,$kintaitran['kyujitsushinya']);
						$sou_kyu_shin = $time[0];

						$time = $this->Format->AddVtime($sou_hou_zan,$kintaitran['houteikyujitsu']);
						$sou_hou_zan = $time[0];

						$time = $this->Format->AddVtime($sou_hou_shin,$kintaitran['houteikyujitsushinya']);
						$sou_hou_shin = $time[0];
					}
				}
			}

			$pdf->MultiCell($col3, $row2, $syukkin, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col3, $row2, $taikin, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col3, $row2, $kinmu_jikan, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');

			$pdf->MultiCell($col3, $row2, $tako_gokei_soukou_h, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col3, $row2, $tako_gokei_sagyou_h, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col3, $row2, $tako_gokei_taiki_h, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col3, $row2, $roudou_goukei, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');

			$pdf->MultiCell($col4, $row2, $heijitsuzangyo, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $heijitsushinya, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $kyujitsuzangyo, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $kyujitsushinya, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $houteikyujitsu, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $houteikyujitsushinya, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');

			$pdf->MultiCell($col5, $row2, $trailer_teate, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $tako_gokei_km, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $soukoukiro, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $sagyoteate, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $kaiji_teate, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $takasyo_teate, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
//			$pdf->MultiCell($col5, $row2, $jyoumu_teate, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
//			$pdf->MultiCell($col5, $row2, $eigyokiro, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
//			$pdf->MultiCell($col5, $row2, $shiharaisuryo, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $goukei, '1', 'R', 0, 1, '', '', true, 0, false, true, $row2, 'M');
		}

		$pdf->MultiCell($col1+$col3*3, $row1, '', '0', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col3, $row1, $sou_soukou, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3, $row1, $sou_sagyou, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3, $row1, $sou_taiki, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3, $row1, $sou_roudou, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col4, $row1, $sou_hei_zan, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_hei_shin, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_kyu_zan, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_kyu_shin, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_hou_zan, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_hou_shin, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$zangyou_goukei = $this->Format->AddVtime3($zangyou_goukei,$sou_hei_zan);
		$zangyou_goukei = $this->Format->AddVtime3($zangyou_goukei,$sou_hei_shin);
		$zangyou_goukei = $this->Format->AddVtime3($zangyou_goukei,$sou_kyu_zan);
		$zangyou_goukei = $this->Format->AddVtime3($zangyou_goukei,$sou_kyu_shin);
		$zangyou_goukei = $this->Format->AddVtime3($zangyou_goukei,$sou_hou_zan);
		$zangyou_goukei = $this->Format->AddVtime3($zangyou_goukei,$sou_hou_shin);

		$pdf->MultiCell($col5, $row1, $zangyou_goukei, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	}

$pdf->lastPage();
$pdf->Output('Untennippou.pdf', 'I');
