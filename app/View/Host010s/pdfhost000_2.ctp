<?php
// debug($reservetran);

App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetMargins(5, 10, 5);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Asahi');
$pdf->SetTitle('receipt');
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

	$printday = new DateTime();

	// ページを追加する
	$pdf->AddPage();

	// Write( $h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh )
	// 行の高さ, テキスト, URL, 背景塗り潰し, テキスト位置, 改行, テキストの伸縮モード, 一行で収まる部分のみ出力, 行の先頭から出力, 最大の高さ)

	// MultiCell( $w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell )
	// 横幅, 高さ, テキスト, 枠線,　テキスト位置, 背景塗り潰し, カーソル移動位置, X座標, Y座標, 前回セル高さ引き継ぎ, テキストの伸縮モード, テキストがHTML, 行幅自動調整, 高さ上限, 垂直方向テキスト位置

	// フォントの設定
	$pdf->SetFont('kozminproregular', '', 14);

//	$pdf->SetLineStyle(array('width' => 0.2));
//	$pdf->Line(291, 10, 291, 36);

	$pdf->SetFontSize(14);

	$pdf->MultiCell('', '', '運転日報一覧　'.$paramsDate->format('Y年m月d日').'（'.$week[$paramsDate->format('w')].'）', '0', 'L', 0, 1, '', '', true, 0, false, true, 10, 'B');

	$pdf->MultiCell(100, 8, '出光　本社', '0', 'L', 0, 0, '', '', true, 0, false, true, 8, 'B');
	$pdf->SetFontSize(9);
	$pdf->MultiCell('', 8, '印刷日　'.$printday->format('Y年m月d日　H:i').'　P.'.$pdf->PageNo(), '0', 'R', 0, 1, '', '', true, 0, false, true, 8, 'B');

	//$pdf->Ln(4);

	$col1 = 12;
	$col2 = 20;
	$col3 = 11.5;
	$col4 = 11.5;
	$col5 = 15.2;
	$row1 = 5;
	$row2 = 6;

	// 1行目
	$pdf->SetFontSize(9);

	$pdf->MultiCell($col1, $row1, '', 'LT', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col2, $row1, '', 'LT', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col3*2, $row1, '＜出退勤＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col4*7, $row1, '＜時間外勤務＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col5*10, $row1, '＜手当＞', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');

	$pdf->MultiCell($col1, $row1, '社番', 'LB', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col2, $row1, '乗務員', 'LB', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

	$pdf->MultiCell($col3, $row1, '始業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col3, $row1, '終業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

	$pdf->MultiCell($col4, $row1, '休憩', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col4, $row1, '普残', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col4, $row1, '普深', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col4, $row1, '休残', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col4, $row1, '休深', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col4, $row1, '法残', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col4, $row1, '法深', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

	$pdf->MultiCell($col5, $row1, '総走行', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col5, $row1, '長距離', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col5, $row1, '回次', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col5, $row1, '多ヶ所', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col5, $row1, 'ﾄﾚ1', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col5, $row1, '乗務', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col5, $row1, 'ﾁｪｰﾝ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col5, $row1, '営業ｷﾛ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col5, $row1, '支払数量', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	$pdf->MultiCell($col5, $row1, '高稼働', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');

	$i = 0;
	foreach ($users as $user) {
		// debug($user);
		$syukkin = $taikin = '';
		$kyukeitime = $heijitsuzangyo_text = $heijitsushinya = $kyujitsuzangyo = $kyujitsushinya = $houteikyujitsu = $houteikyujitsushinya = '';
		$soukoukiro = $chokyori_teate = $kaiji_teate = $takasyo_teate = $trailer_teate = $jyoumu_teate = $chain_teate = $eigyokiro = $shiharaisuryo = $koukadou_teate = '';
		if ($i < 27) {
			$pdf->MultiCell($col1, $row2, If_null($user['User']['syain_cd']), '1', 'L', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col2, $row2, $user['User']['usernameK'], '1', 'L', 0, 0, '', '', true, 0, false, true, $row2, 'M');

			if ($user['Kintaitran']) {
				$syukkin = TimeFormat($user['Kintaitran'][0]['timestart']);
				$taikin = TimeFormat($user['Kintaitran'][0]['timeend']);

				$kyukeitime = TimeFormat($user['Kintaitran'][0]['kyukeitime']);

				$time = AddVtime($user['Kintaitran'][0]['heijitsuzangyo'],$user['Kintaitran'][0]['heijitsuzangyo_nashi']);
				$heijitsuzangyo = $time[0];
				$heijitsuzangyo_text = $time[1];

				$heijitsushinya = TimeFormat($user['Kintaitran'][0]['heijitsushinya']);
				$kyujitsuzangyo = TimeFormat($user['Kintaitran'][0]['kyujitsuzangyo']);
				$kyujitsushinya = TimeFormat($user['Kintaitran'][0]['kyujitsushinya']);
				$houteikyujitsu = TimeFormat($user['Kintaitran'][0]['houteikyujitsu']);
				$houteikyujitsushinya = TimeFormat($user['Kintaitran'][0]['houteikyujitsushinya']);

				$soukoukiro = If_null($user['Kintaitran'][0]['soukoukiro']);
				$chokyori_teate = If_null($user['Kintaitran'][0]['chokyori_teate']);
				$kaiji_teate = If_null($user['Kintaitran'][0]['kaiji_teate']);
				$takasyo_teate = If_null($user['Kintaitran'][0]['takasyo_teate']);
				$trailer_teate = If_null($user['Kintaitran'][0]['trailer_teate']);
				$jyoumu_teate = If_null($user['Kintaitran'][0]['jyoumu_teate']);
				$chain_teate = If_null($user['Kintaitran'][0]['chain_teate']);
				$eigyokiro = If_null($user['Kintaitran'][0]['eigyokiro']);
				$shiharaisuryo = If_null($user['Kintaitran'][0]['shiharaisuryo']);
				$koukadou_teate = If_null($user['Kintaitran'][0]['koukadou_teate']);
			}
			$pdf->MultiCell($col3, $row2, $syukkin, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col3, $row2, $taikin, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');

			$pdf->MultiCell($col4, $row2, $kyukeitime, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $heijitsuzangyo_text, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col3, $row2, $heijitsushinya, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $kyujitsuzangyo, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $kyujitsushinya, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $houteikyujitsu, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $houteikyujitsushinya, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');

			$pdf->MultiCell($col5, $row2, $soukoukiro, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $chokyori_teate, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $kaiji_teate, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $takasyo_teate, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $trailer_teate, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $jyoumu_teate, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $chain_teate, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $eigyokiro, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $shiharaisuryo, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $koukadou_teate, '1', 'R', 0, 1, '', '', true, 0, false, true, $row2, 'M');


			$i = $i + 1;
		} else {
			$i = 0;
			$pdf->AddPage();

			$pdf->SetFontSize(14);

			$pdf->MultiCell('', '', '運転日報一覧　'.$paramsDate->format('Y年m月d日').'（'.$week[$paramsDate->format('w')].'）', '0', 'L', 0, 1, '', '', true, 0, false, true, 10, 'B');

			$pdf->MultiCell(100, 8, '出光　本社', '0', 'L', 0, 0, '', '', true, 0, false, true, 8, 'B');
			$pdf->SetFontSize(9);
			$pdf->MultiCell('', 8, '配車日　2016年12月05日　印刷日　'.$printday->format('Y年m月d日　H:i').'　P.'.$pdf->PageNo(), '0', 'R', 0, 1, '', '', true, 0, false, true, 8, 'B');

			$pdf->SetFontSize(9);

			$pdf->MultiCell($col1, $row1, '', 'LT', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col2, $row1, '', 'LT', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col3*2, $row1, '＜出退勤＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col4*7, $row1, '＜時間外勤務＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5*10, $row1, '＜手当＞', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');

			$pdf->MultiCell($col1, $row1, '社番', 'LB', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col2, $row1, '乗務員', 'LB', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

			$pdf->MultiCell($col3, $row1, '始業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col3, $row1, '終業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

			$pdf->MultiCell($col4, $row1, '休憩', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col4, $row1, '普残', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col4, $row1, '普深', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col4, $row1, '休残', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col4, $row1, '休深', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col4, $row1, '法残', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col4, $row1, '法深', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

			$pdf->MultiCell($col5, $row1, '総走行', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, '長距離', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, '回次', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, '多ヶ所', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, 'ﾄﾚ1', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, '乗務', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, 'ﾁｪｰﾝ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, '営業ｷﾛ', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, '支払数量', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
			$pdf->MultiCell($col5, $row1, '高稼働', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');
		}
	}

$pdf->lastPage();
$pdf->Output('HelloWorld.pdf', 'I');


function AddVtime($a,$b) {
	if (isset($a)) {
	} else {
		$a = '00:00';
	}
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


function TimeFormat($a) {
	if ($a) {
		$date = new DateTime($a);
		$time = $date->format('H:i');
	} else {
		$time = '';
	}

	return $time;
}

function If_null($value) {
	if($value) {
		$data = $value;
	} else {
		$data = '';
	}

	return $data;
}
