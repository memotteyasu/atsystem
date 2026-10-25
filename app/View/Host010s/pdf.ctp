<?php
App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, 'A3', true, 'UTF-8', false);

$pdf->SetMargins(10, 10, 0);

// set document information
//$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('勤怠PDF');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('asahitrans, transfer');

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

	// ページを追加する
	$pdf->AddPage();

	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線,　テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	//今月の始めと終わりを取得
	//$firstday = new DateTime('first day of this month');
	//$lastday  = new DateTime('last day of this month');
	//$lastday->add(new DateInterval('P1D'));


	//$firstday = new DateTime('first day of '.$this->params['named']['kyy'].'-'.$this->params['named']['kmm']);
	//$lastday = new DateTime('last day of '.$this->params['named']['kyy'].'-'.$this->params['named']['kmm']);
	$lastday->add(new DateInterval('P1D'));

	//debug($users);

	//インターバルを設定
	$interval = new DateInterval('P1D');

	//期間を設定
	$period = new DatePeriod($firstday, $interval, $lastday);

	//印刷日時を取得
	$printday = new DateTime();

	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 12);

//	$pdf->SetLineStyle(array('width' => 0.2));
//	$pdf->Line(292.3, 10, 292.3, 36);

	$pdf->MultiCell(180, '', '勤怠一覧　　'.$firstday->format('Y年m月'), '0', 'R', 0, 0, '', '', true, 0, false, true, 6, 'B');
	$pdf->MultiCell(95, '', $user['Kaisya']['companyname'], '0', 'R', 0, 1, '', '', true, 0, false, true, 6, 'B');

	$pdf->MultiCell(180, '', '本社　現場　運転手　石油', '0', 'R', 0, 0, '', '', true, 0, false, true, 6, 'B');

	$pageno = $pdf->PageNo();
	$pdf->SetFontSize(8);
	$pdf->MultiCell(292.3-180, '', '印刷日　'.$printday->format('Y年m月d日　H:i　').$pageno.'P', '0', 'R', 0, 1, '', '', true, 0, false, true, 6, 'B');

	//$pdf->Ln(4);
	//debug ($period);

	$Column1 = 18;
	$Column2 = 8;
	$Column3 = 9;
	$Column4 = 12;
	$T = 20;
	$T2 = 7;

	// 所定労働日数
	$SYOG = 22;
	// 総出勤日数
	$SOUNG = 0;
	// 休業日数
	$KGYOG =8;
	// 通常・休日残
	$TUG = 1111;
	// 法定休日残業
	$HOG = 1027;
	// 普休法深夜残
	$HUKYUG = 1008;
	// 総労働時間
	$SOUR =102.1;

	$SYOGG = $SOUNGG = $SYUGG = $YGG = $KGG = $SIGG = $TKGG = $DKG = $KKGG = $KGYOGG = $TUGG = $HOGG = $HUKYUGG = $SOURG = 0;

	// 1行目

	$pdf->SetFontSize(8);

	$pdf->MultiCell($Column1, $T, '社員名', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');

	//for ($count = 1; $count <= 31; $count++) {
	foreach ($period as $date) {
		$pdf->MultiCell($Column2, $T, $date->format('j'), '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	}

	$pdf->MultiCell($Column3, $T, '所定労働日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, '総出勤日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, '出勤日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, '有給日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, '休日日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, '指定休日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, '特休日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, '代休日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, '欠勤日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column3, $T, '休業日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, '通常・休日残', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, '法定休日残業', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, '普休法深夜残', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
	$pdf->MultiCell($Column4, $T, '総労働時間', '1', 'C', 0, 1, '', '', true, 0, false, true, $T, 'B');

	$i = 1;
	foreach ($users as $userlist) {
		if ($i < 33) {
			$syukkin = $kekkin = $yukyu = $yasumi = $kyusyutu = $siteikyu = $tokukyu = $kyouiku = $daikyu = $taiki = 0;
			$pdf->MultiCell($Column1, $T2, $userlist['User']['usernameK'], '1', 'L', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			foreach ($period as $date) {
				if ($userlist['Kintaitran']) {
					foreach ($userlist['Kintaitran'] as $kintaitran) {
						if ($kintaitran['kintaidate'] == $date->format('Y-m-d')) {
							$flgname = $flgmasters[$kintaitran['flgmasters_id']];
							break;
						} else {
							$flgname = null;
						}
					}
				} else {
					$flgname = null;
				}
				$pdf->MultiCell($Column2, $T2, $flgname, '1', 'C', 0, 0, '', '', true, 0, false, true, $T2, 'M');
				switch ($flgname) {
					case '出勤':
						$syukkin = $syukkin + 1;
						break;
					case '欠勤':
						$kekkin = $kekkin +1 ;
						break;
					case '有休':
						$yukyu = $yukyu + 1;
						break;
					case '休み':
						$yasumi = $yasumi + 1;
						break;
					case '休出':
						$kyusyutu = $kyusyutu + 1;
						break;
					case '指定休':
						$siteikyu = $siteikyu + 1;
						break;
					case '特休':
						$tokukyu = $tokukyu + 1;
						break;
					case '教育':
						$kyouiku = $kyouiku + 1;
						break;
					case '代休':
						$daikyu = $daikyu + 1;
						break;
					case '待機':
						$taiki = $taiki + 1;
						break;
				}
			}

			$i = $i + 1;

			$pdf->MultiCell($Column3, $T2, $SYOG, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $SOUNG, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $syukkin, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $yukyu, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $yasumi, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $siteikyu, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $tokukyu, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $daikyu, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $kekkin, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column3, $T2, $KGYOG, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $TUG, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $HOG, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $HUKYUG, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
			$pdf->MultiCell($Column4, $T2, $SOUR, '1', 'R', 0, 1, '', '', true, 0, false, true, $T2, 'M');

			$SYOGG = $SYOGG + $SYOG;
			$SOUNGG = $SOUNGG + $SOUNG;

		} else {
			/*
			 * 2ページ以降ある場合の新規ページ追加・見出し処理
			 */
			$pdf->AddPage();
			$pdf->SetFont('kozminproregular', '', 12);
			$pdf->MultiCell(180, '', '勤怠一覧　　'.$firstday->format('Y年m月'), '0', 'R', 0, 0, '', '', true, 0, false, true, 6, 'B');
			$pdf->MultiCell(95, '', $user['Kaisya']['companyname'], '0', 'R', 0, 1, '', '', true, 0, false, true, 6, 'B');
			$pdf->MultiCell(180, '', '本社　現場　運転手　石油', '0', 'R', 0, 0, '', '', true, 0, false, true, 6, 'B');
			$pageno = $pdf->PageNo();
			$pdf->SetFontSize(8);
			$pdf->MultiCell(292.3-180, '', '印刷日　'.$printday->format('Y年m月d日　H:i　').$pageno.'P', '0', 'R', 0, 1, '', '', true, 0, false, true, 6, 'B');
			$pdf->MultiCell($Column1, $T, '社員名', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');

			foreach ($period as $date) {
				$pdf->MultiCell($Column2, $T, $date->format('j'), '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			}

			$pdf->MultiCell($Column3, $T, '所定労働日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, '総出勤日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, '出勤日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, '有給日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, '休日日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, '指定休日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, '特休日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, '代休日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, '欠勤日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column3, $T, '休業日数', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column4, $T, '通常・休日残', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column4, $T, '法定休日残業', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column4, $T, '普休法深夜残', '1', 'C', 0, 0, '', '', true, 0, false, true, $T, 'B');
			$pdf->MultiCell($Column4, $T, '総労働時間', '1', 'C', 0, 1, '', '', true, 0, false, true, $T, 'B');

			$i = 1;

		}

	}

	$pdf->SetFontSize(12);
	$pdf->MultiCell($Column1, $T2, '合計', '1', 'C', 0, 0, '', '', true, 0, false, true, $T2, 'M');

	$pdf->SetFontSize(8);
	foreach ($period as $date) {
		$pdf->MultiCell($Column2, $T2, '', 'B', 'C', 0, 0, '', '', true, 0, false, true, $T2, 'M');
	}

	$pdf->MultiCell($Column3, $T2, $SYOGG, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
	$pdf->MultiCell($Column3, $T2, $SOUNGG, '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');

	for ($count = 1; $count <= 8; $count++) {
		$pdf->MultiCell($Column3, $T2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
	}

	for ($count = 1; $count <= 4; $count++) {
		$pdf->MultiCell($Column4, $T2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $T2, 'M');
	}

$pdf->lastPage();
$pdf->Output('kintai.pdf', 'I');
