<?php
// debug($reservetran);

App::import('Vendor', 'tcpdf/tcpdf');
$pdf = new TCPDF('L', PDF_UNIT, 'B4', true, 'UTF-8', false);

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
	$col3 = 11;
	$col4 = 12.5;
//	$col5 = 15.2;
	$col5 = 13;
	$row1 = 6;
	$row2 = 6;


	foreach ($users as $user) {
		// kintaitranが存在しない場合は読み飛ばし
	  if($user['Kintaitran']){
		// ページを追加する
		$pdf->AddPage();

		$pdf->SetFontSize(14);

		$pdf->MultiCell(35, '', '残業手当明細', '0', 'L', 0, 0, '', '', true, 0, false, true, 8, 'B');
		$pdf->MultiCell('60', '', $paramsDate->format('Y年m月'), '0', 'L', 0, 0, '', '', true, 0, false, true, 8, 'B');
		$pdf->MultiCell('', '', $user['Kaisya']['companyname'], '0', 'R', 0, 1, '', '', true, 0, false, true, 8, 'B');
		$pdf->SetFontSize(12);
		$pdf->MultiCell('200', '', $user['Departmentmaster']['departmentname'].' '.$user['Branchmaster']['branchname'].' '.$user['User']['usernameK'], '0', 'L', 0, 0, '', '', true, 0, false, true, 6, 'B');
		$pdf->SetFontSize(8);
		$pdf->MultiCell('', '', '印刷日　'.$printday->format('Y年m月d日　H:i　').'P.'.$pdf->PageNo(), '0', 'R', 0, 1, '', '', true, 0, false, true, 6, 'B');

		$pdf->Ln(2);

		$pdf->SetFontSize(9);

		$pdf->MultiCell($col1, $row1, '', 'LT', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		// 出勤等印字
		$pdf->MultiCell($col1, $row1, '', 'LT', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3*2, $row1, '＜出退勤＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4*4, $row1, '＜労働時間＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4*7, $row1, '＜時間外勤務＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4*2, $row1, '＜作業歩合＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5*10, $row1, '＜手当＞', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col1, $row1, '日', 'LB', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col1, $row1, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3, $row1, '始業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3, $row1, '終業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col4, $row1, '走行', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '作業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '待機', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '合計', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col4, $row1, '普残', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '深割増', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '休残', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '深割増', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '法残', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '深割増', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '残業計', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '定額残', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '定額深', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->SetFontSize(7);

		$pdf->MultiCell($col5, $row1, '走行km', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, '走行歩合', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, '定額時間', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, '定額深夜', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, '夜勤歩合', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, '通常残業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, '乗務歩合', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, '電話歩合', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, '個別歩合', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col5, $row1, '合計金額', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');

		$pdf->SetFontSize(9);

		$sou_soukou = $sou_sagyou = $sou_taiki = $sou_roudou = '00:00';
		$total_teigakuzangyojikan = $total_teigakuzangyoshinya = $sou_hei_zan = $sou_hei_shin =$sou_kyu_zan = $sou_kyu_shin = $sou_hou_zan = $sou_hou_shin = '00:00';
		$teigakujikangai_kei = $teigakushinya_kei = $untin_buai_kei = $trailer_teate_kei = $tako_gokei_km_kei = $soukoukiro_kei = $sagyoteate2_kei = $sougou_kei = 0;
		$zangyoflgkingaku_kei = $jyoumuflgkingaku_kei = $jyoumushinyaflgkingaku_kei = $telflgkingaku_kei = 0;
		$zangyou_goukei = '00:00';
		$w_kintai = '';
		foreach ($period as $perioddate) {
			$syukkin = $taikin =  $syukkin2 = $taikin2 = $syukkin3 = $taikin3 = '';
			$kinmu_jikan = $kinmu_jikan2 = $kinmu_jikan3 ='';
			$teigakuzangyojikan = $teigakuzangyoshinya = $heijitsuzangyo = $heijitsushinya = $kyujitsuzangyo = $kyujitsushinya = $houteikyujitsu = $houteikyujitsushinya = '';
			$tako_gokei_soukou_h = $tako_gokei_sagyou_h = $tako_gokei_taiki_h = $sagyoteate2 = $tako_gokei_km = $soukoukiro = $chokyori_teate = $trailer_teate = $chain_teate = $eigyokiro = $shiharaisuryo = '';
			$teigakujikangai = $teigakushinya = $untin_buai = $buaiwarimashi = $goukei = $syukkin_cnt = 0;
			$jyoumushinyaflgkingaku = $jyoumuflgkingaku = $telflgkingaku = $zangyoflgkingaku = 0;
			$roudou_goukei = $zangyou_kei = '';
			$heijitsuzangyo_2 = $roudou_goukei_2 = '';
			$w_kintai = '';

			$pdf->MultiCell($col1, $row2, $perioddate->format('d').'('.$week[$perioddate->format('w')].')', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');

			if ($user['Kintaitran']) {
				foreach ($user['Kintaitran'] as $kintaitran) {

					if ($kintaitran['kintaidate'] == $perioddate->format('Y-m-d')) {
						$w_kintai = $kintaitran['Flgmaster']['flgname'];
						$syukkin_cnt++; //出勤日数カウント
						$syukkin = $this->Format->TimeFormat($kintaitran['timestart']);
						$taikin = $this->Format->TimeFormat($kintaitran['timeend']);
						$kinmu_jikan = $this->Format->SubtractTime(
							$kintaitran['datestart'],$syukkin,$kintaitran['dateend'],$taikin
						);

						$syukkin2 = $this->Format->TimeFormat($kintaitran['timestart2']);
						$taikin2 = $this->Format->TimeFormat($kintaitran['timeend2']);
						$kinmu_jikan2 = $this->Format->SubtractTime(
							$kintaitran['datestart2'],$syukkin2,$kintaitran['dateend2'],$taikin2
						);

						$syukkin3 = $this->Format->TimeFormat($kintaitran['timestart3']);
						$taikin3 = $this->Format->TimeFormat($kintaitran['timeend3']);
						$kinmu_jikan3 = $this->Format->SubtractTime(
							$kintaitran['datestart3'],$syukkin3,$kintaitran['dateend3'],$taikin3
						);

						$tako_gokei_soukou_h = $this->Format->TimeFormat($kintaitran['tako_gokei_soukou_h']);
						$tako_gokei_sagyou_h = $this->Format->TimeFormat($kintaitran['tako_gokei_sagyou_h']);
						$tako_gokei_taiki_h = $this->Format->TimeFormat($kintaitran['tako_gokei_taiki_h']);

//足さない						$roudou_goukei = $this->Format->Addtime($kintaitran['tako_gokei_soukou_h'],$kintaitran['tako_gokei_sagyou_h']);
//足さない						$roudou_goukei_2 = $this->Format->Addtime($roudou_goukei,$kintaitran['tako_gokei_taiki_h']);
						$roudou_goukei_2 = $this->Format->Addtime($kintaitran['kinmu_jikan'],'00:00:00');

						$heijitsuzangyo = $this->Format->TimeFormat($kintaitran['heijitsuzangyo_ori']);
						$heijitsushinya = $this->Format->TimeFormat($kintaitran['heijitsushinya_ori']);
/*
						$w_heijitsuzangyo = '00:00';
						$array1 = [];
						$array2 = [];
						$array1 = explode(':',$kintaitran['heijitsuzangyo_nashi']);
        				$array2 = explode(':',$kintaitran['heijitsuzangyo']);
        				$w_heijitsuzangyo = date('H:i',mktime($array1[0]+$array2[0],$array1[1]+$array2[1]));
						$heijitsuzangyo = $this->Format->TimeFormat($w_heijitsuzangyo);
						$heijitsushinya = $this->Format->TimeFormat($kintaitran['heijitsushinya']);
*/

						$kyujitsuzangyo = $this->Format->TimeFormat($kintaitran['kyujitsuzangyo']);
						$kyujitsushinya = $this->Format->TimeFormat($kintaitran['kyujitsushinya']);
						$houteikyujitsu = $this->Format->TimeFormat($kintaitran['houteikyujitsu']);
						$houteikyujitsushinya = $this->Format->TimeFormat($kintaitran['houteikyujitsushinya']);

						$soukoukiro = $this->Format->If_null($kintaitran['soukoukiro']);
						$trailer_teate = $this->Format->If_null($kintaitran['trailer_teate']);
						$chain_teate = $this->Format->If_null($kintaitran['chain_teate']);
						$sagyoteate2 = $this->Format->If_null($kintaitran['sagyoteate2']);
						$tako_gokei_km = $this->Format->If_null($kintaitran['tako_gokei_km']);

						$shiharaisuryo = $this->Format->If_null($kintaitran['shiharaisuryo']);
						$untin_buai = $this->Format->If_null($kintaitran['untinbuai']);
						$teigakujikangai = $this->Format->If_null($kintaitran['teigakujikangai']);
						$teigakushinya = $this->Format->If_null($kintaitran['teigakushinya']);
						if($user['User']['rolemasters_id']==28){
							$teigakujikangai = $teigakujikangai * 0.1;
							$teigakushinya = $teigakushinya * 0.1;
						}
						$teigakujikangai_kei = $teigakujikangai_kei + $teigakujikangai;
						$teigakushinya_kei = $teigakushinya_kei + $teigakushinya;
//基準単価から残業単価を計算
$basetanka125 = $user['User']['basetanka'] * 1.25;
$basetanka135 = $user['User']['basetanka'] * 1.35;
$basetanka025 = $user['User']['basetanka'] * 0.25;

						if($kintaitran['jyoumuflg']==1){
							if($kintaitran['rinjicalendarflg']==1){ //法定外休日
								$jyoumuflgkingaku = $basetanka135 * $user['User']['jyoumuhour'];
							}
							if($kintaitran['rinjicalendarflg']==2){ //法定休日
								$jyoumuflgkingaku = $basetanka135 * $user['User']['jyoumuhour'];
							}
							if($kintaitran['rinjicalendarflg']==0){ //通常
								$jyoumuflgkingaku = $basetanka125 * $user['User']['jyoumuhour'];
							}
						}
						if($kintaitran['jyoumushinyaflg']==1){
							if($kintaitran['rinjicalendarflg']==1){ //法定外休日
								$jyoumushinyaflgkingaku = $basetanka025 * $user['User']['jyoumushinyahour'];
							}
							if($kintaitran['rinjicalendarflg']==2){ //法定休日
								$jyoumushinyaflgkingaku = $basetanka025 * $user['User']['jyoumushinyahour'];
							}
							if($kintaitran['rinjicalendarflg']==0){ //通常
								$jyoumushinyaflgkingaku = $basetanka025 * $user['User']['jyoumushinyahour'];
							}
						}
						if($kintaitran['telflg']==1){
							if($kintaitran['rinjicalendarflg']==1){ //法定外休日
								$telflgkingaku = $basetanka135 * $user['User']['telhour'];
							}
							if($kintaitran['rinjicalendarflg']==2){ //法定休日
								$telflgkingaku = $basetanka135 * $user['User']['telhour'];
							}
							if($kintaitran['rinjicalendarflg']==0){ //通常
								$telflgkingaku = $basetanka125 * $user['User']['telhour'];
							}
						}
						if($kintaitran['zangyoflg']==1){
							if($kintaitran['rinjicalendarflg']==1){ //法定外休日
								$zangyoflgkingaku = $basetanka135 * $user['User']['zangyohour'];
							}
							if($kintaitran['rinjicalendarflg']==2){ //法定休日
								$zangyoflgkingaku = $basetanka135 * $user['User']['zangyohour'];
							}
							if($kintaitran['rinjicalendarflg']==0){ //通常
								$zangyoflgkingaku = $basetanka125 * $user['User']['zangyohour'];
							}
						}
						$goukei = $goukei + $soukoukiro + $teigakujikangai + $teigakushinya + $jyoumushinyaflgkingaku + $zangyoflgkingaku + $jyoumuflgkingaku + $telflgkingaku + $sagyoteate2;


						$sou_soukou = $this->Format->Addtime($sou_soukou,$kintaitran['tako_gokei_soukou_h']);
						$sou_sagyou = $this->Format->Addtime($sou_sagyou,$kintaitran['tako_gokei_sagyou_h']);
						$sou_taiki = $this->Format->Addtime($sou_taiki,$kintaitran['tako_gokei_taiki_h']);
						$sou_roudou = $this->Format->Addtime($sou_roudou,$roudou_goukei_2);

						$sou_hei_zan = $this->Format->Addtime($sou_hei_zan,$heijitsuzangyo);
						$sou_hei_shin = $this->Format->Addtime($sou_hei_shin,$heijitsushinya);
						$sou_kyu_zan = $this->Format->Addtime($sou_kyu_zan,$kintaitran['kyujitsuzangyo']);
						$sou_kyu_shin = $this->Format->Addtime($sou_kyu_shin,$kintaitran['kyujitsushinya']);
						$sou_hou_zan = $this->Format->Addtime($sou_hou_zan,$kintaitran['houteikyujitsu']);
						$sou_hou_shin = $this->Format->Addtime($sou_hou_shin,$kintaitran['houteikyujitsushinya']);

						$zangyou_kei = $this->Format->Addtime($zangyou_kei,$heijitsuzangyo);
						$zangyou_kei = $this->Format->Addtime($zangyou_kei,$kyujitsuzangyo);
						$zangyou_kei = $this->Format->Addtime($zangyou_kei,$houteikyujitsu);

					}
				}
			}
			//km合計から作業歩合テーブルを読む
			$workprice2 = ClassRegistry::init('Workprice2');
			$workprice2data = [];
			$workprice2data = $workprice2->find('first',[
				'fields'=>[
					'Workprice2.zangyojikan',
					'Workprice2.shinyajikan',
				],
				'conditions'=>[
					'Workprice2.startkilo <='=>$tako_gokei_km,
					'Workprice2.endkilo >='=>$tako_gokei_km
				],
				'recursive'=>-1
			]);
			if($workprice2data){
				if($kintaitran['rinjicalendarflg']==1){ //法定外休日
					$teigakuzangyojikan = $kyujitsuzangyo;
				}
				if($kintaitran['rinjicalendarflg']==2){ //法定休日
					$teigakuzangyojikan = $houteikyujitsu;
				}
				if($kintaitran['rinjicalendarflg']==0){ //通常
					$teigakuzangyojikan = $this->Format->Addtime($workprice2data['Workprice2']['zangyojikan'],'00:00:00');
					$teigakuzangyoshinya = $this->Format->Addtime($workprice2data['Workprice2']['shinyajikan'],'00:00:00');
				}
				$total_teigakuzangyojikan = $this->Format->Addtime($total_teigakuzangyojikan,$teigakuzangyojikan);
				$total_teigakuzangyoshinya = $this->Format->Addtime($total_teigakuzangyoshinya,$teigakuzangyoshinya);
			}

			// 勤怠区分
			$pdf->MultiCell($col1, $row2, $w_kintai, '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');

			if($kinmu_jikan=='00:00'){
				$pdf->MultiCell($col3, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col3, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col3, $row2, $syukkin, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				if($kinmu_jikan3 != '00:00'){
					$pdf->MultiCell($col3, $row2, $taikin3, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				}else{
					if($kinmu_jikan2 != '00:00'){
						$pdf->MultiCell($col3, $row2, $taikin2, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
					}else{
						$pdf->MultiCell($col3, $row2, $taikin, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
					}
				}
			}

			if($tako_gokei_soukou_h=='00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $tako_gokei_soukou_h, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}
			if($tako_gokei_sagyou_h=='00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $tako_gokei_sagyou_h, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}
			if($tako_gokei_taiki_h=='00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $tako_gokei_taiki_h, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}
			if($roudou_goukei_2=='00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $roudou_goukei_2, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}

			if($heijitsuzangyo=='00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $heijitsuzangyo, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}
			if($heijitsushinya=='00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $heijitsushinya, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}
			if($kyujitsuzangyo=='00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $kyujitsuzangyo, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}
			if($kyujitsushinya=='00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $kyujitsushinya, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}
			if($houteikyujitsu=='00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $houteikyujitsu, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}
			if($houteikyujitsushinya=='00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $houteikyujitsushinya, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}

			if($zangyou_kei=='00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $zangyou_kei, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}
			if($teigakuzangyojikan=='00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $teigakuzangyojikan, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}
			if($teigakuzangyoshinya=='00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $teigakuzangyoshinya, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}

			$pdf->MultiCell($col5, $row2, $this->Format->NumberFormat($tako_gokei_km), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $this->Format->NumberFormat($soukoukiro), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $this->Format->NumberFormat($teigakujikangai), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $this->Format->NumberFormat($teigakushinya), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $this->Format->NumberFormat($jyoumushinyaflgkingaku), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $this->Format->NumberFormat($zangyoflgkingaku), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $this->Format->NumberFormat($jyoumuflgkingaku), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $this->Format->NumberFormat($telflgkingaku), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $this->Format->NumberFormat($sagyoteate2), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $this->Format->NumberFormat($goukei), '1', 'R', 0, 1, '', '', true, 0, false, true, $row2, 'M');
			$trailer_teate_kei = $trailer_teate_kei + $trailer_teate;
			$tako_gokei_km_kei = $tako_gokei_km_kei + $tako_gokei_km;
			$soukoukiro_kei = $soukoukiro_kei + $soukoukiro;
			$sagyoteate2_kei = $sagyoteate2_kei + $sagyoteate2;
			$sougou_kei = $sougou_kei + $goukei;
			$untin_buai_kei = $untin_buai_kei + $untin_buai;
			$zangyoflgkingaku_kei = $zangyoflgkingaku_kei + $zangyoflgkingaku;
			$jyoumuflgkingaku_kei = $jyoumuflgkingaku_kei + $jyoumuflgkingaku;
			$jyoumushinyaflgkingaku_kei = $jyoumushinyaflgkingaku_kei + $jyoumushinyaflgkingaku;
			$telflgkingaku_kei = $telflgkingaku_kei + $telflgkingaku;
		}
		$cc = ($untin_buai_kei + $soukoukiro_kei);
		$total_zangyo = $this->Format->Addtime($sou_hei_zan,$sou_kyu_zan);
		$total_zangyo = $this->Format->Addtime($total_zangyo,$sou_hou_zan);
//嘱託の場合、出勤日数×7:45をベースとする
		switch($user['User']['roudouflg']){
			case 1: //正社員
				$dd = $this->Format->Addtime('173:30',$total_zangyo);
				break;
			case 2: //嘱託
			case 3: //パート
			case 5: //バイト
				$basetime = '7:45';
				$timearray = explode(":",$basetime);
				$hour = $timearray[0] * 60 * $syukkin_cnt; //分に換算
				$secound = $timearray[1] * $syukkin_cnt;
				$totaltime = ($hour + $secound) * 60; //合計の分を秒に変換
				$syokutakutime = date('H:i',mktime(0,0,$totaltime));
				$dd = $this->Format->Addtime($syokutakutime,$total_zangyo);
				break;
			case 4: //季節
				$dd = $this->Format->Addtime('173:30',$total_zangyo);
				break;
		}
		$times2 = explode(":",$dd);
		$hour2 = $times2[0];
		$second2 = round($times2[1] / 60, 3);
		$gg = $hour2 + $second2;
		if($gg){
			$tanka = ($cc / $gg) * 0.25;
		}else{
			$tanka = 0;
		}

		$times1 = explode(":",$total_zangyo);
		$hour1 = $times1[0];
		$second1 = round($times1[1] / 60, 3);
		$result = floor(($hour1 + $second1) * $tanka);

		$pdf->MultiCell($col1+$col1+$col3, $row1, '歩合割増', '0', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3, $row1, $result, '0', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col4, $row1, $sou_soukou, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_sagyou, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_taiki, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_roudou, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->SetFillColor(200,-1,-1,-1);
		$pdf->MultiCell($col4, $row1, $sou_hei_zan, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_hei_shin, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_kyu_zan, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_kyu_shin, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_hou_zan, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_hou_shin, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$zangyou_goukei = $this->Format->Addtime($zangyou_goukei,$sou_hei_zan);
		$zangyou_goukei = $this->Format->Addtime($zangyou_goukei,$sou_kyu_zan);
		$zangyou_goukei = $this->Format->Addtime($zangyou_goukei,$sou_hou_zan);

		$pdf->MultiCell($col4, $row1, $zangyou_goukei, '1', 'R', 1, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $total_teigakuzangyojikan, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $total_teigakuzangyoshinya, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->SetFontSize(7);
		$pdf->MultiCell($col5, $row1, $this->Format->NumberFormat($tako_gokei_km_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, $this->Format->NumberFormat($soukoukiro_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, $this->Format->NumberFormat($teigakujikangai_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, $this->Format->NumberFormat($teigakushinya_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, $this->Format->NumberFormat($jyoumushinyaflgkingaku_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, $this->Format->NumberFormat($zangyoflgkingaku_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, $this->Format->NumberFormat($jyoumuflgkingaku_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, $this->Format->NumberFormat($telflgkingaku_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, $this->Format->NumberFormat($sagyoteate2_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, $this->Format->NumberFormat($sougou_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	  } //kintaitranがない場合の読み飛ばしカッコ
	}

$pdf->lastPage();
$pdf->Output('Untennippou.pdf', 'I');
