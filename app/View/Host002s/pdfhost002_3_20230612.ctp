<?php
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
$col5 = 16;
$col6 = 14;
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

		$pdf->SetFontSize(8);

		$pdf->MultiCell($col1, $row1, '', 'LT', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		// 出勤等印字
		$pdf->MultiCell($col1, $row1, '', 'LT', 'L', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3*2, $row1, '＜出退勤＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4*5, $row1, '＜労働時間＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4*2, $row1, '＜時間外労働＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4*4, $row1, '＜歩合＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '<走行>', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4*6, $row1, '＜歩合金額＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4*4, $row1, '＜職員＞', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5*1, $row1, '', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col1, $row1, '日', 'LB', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col1, $row1, '', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3, $row1, '始業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3, $row1, '終業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col4, $row1, '走行', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '作業', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '待機', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '点呼', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '合計', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col4, $row1, '時間外', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '深夜', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col4, $row1, '時間外', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '60超', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '深夜', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '夜勤', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		//2023-03-17 col5->col4に変更
		$pdf->MultiCell($col4, $row1, 'Km', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '時間外', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '60超', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '深夜', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '夜勤', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '個別', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '距離', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->SetFontSize(7);
		//2023-03-17 col6->col4に変更
		$pdf->MultiCell($col4, $row1, '通常歩合', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '乗務歩合', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '乗務深夜', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, '電話歩合', '1', 'C', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col5, $row1, '合計金額', '1', 'C', 0, 1, '', '', true, 0, false, true, $row1, 'M');

		$pdf->SetFontSize(9);

		$sou_tenko = $sou_soukou = $sou_sagyou = $sou_taiki = $sou_roudou = '00:00';
		$total_jyoumushinyahour = $total_teigakuzangyojikan = $total_teigakuzangyoshinya = $sou_hei_zan = $sou_hei_shin =$sou_kyu_zan = $sou_kyu_shin = $sou_hou_zan = $sou_hou_shin = '00:00';
		$teigakujikangai_kei = $teigakushinya_kei = $untin_buai_kei = $trailer_teate_kei = $tako_gokei_km_kei = $soukoukiro_kei = $sagyoteate2_kei = $sougou_kei = 0;
		//2023-03-17 add
		$w_half_kei = $w_half_shinya_kei = $jyoumushinya2_kei = $zangyoflgkingaku_kei = $jyoumuflgkingaku_kei = $jyoumushinyaflgkingaku_kei = $telflgkingaku_kei = $syukkin_cnt = 0;
		$zangyou_goukei = '00:00';
		$w_kintai = '';
		foreach ($period as $perioddate) {
			$syukkin = $taikin =  $syukkin2 = $taikin2 = $syukkin3 = $taikin3 = '';
			$kinmu_jikan = $kinmu_jikan2 = $kinmu_jikan3 ='';
			$teigakuzangyojikan = $teigakuzangyoshinya = $heijitsuzangyo = $heijitsushinya = $kyujitsuzangyo = $kyujitsushinya = $houteikyujitsu = $houteikyujitsushinya = '';
			$tako_gokei_tenko_h = $tako_gokei_soukou_h = $tako_gokei_sagyou_h = $tako_gokei_taiki_h = $sagyoteate2 = $tako_gokei_km = $soukoukiro = $chokyori_teate = $trailer_teate = $chain_teate = $eigyokiro = $shiharaisuryo = '';
			$w_rinjicalendar = $teigakujikangai = $teigakushinya = $untin_buai = $buaiwarimashi = $goukei = 0;
			$jyoumushinya2 = $jyoumushinyahour = $jyoumushinyaflgkingaku = $jyoumuflgkingaku = $telflgkingaku = $zangyoflgkingaku = 0;
			$roudou_goukei = $zangyou_kei = '';
			$heijitsuzangyo_2 = $roudou_goukei_2 = '';
			$w_kintai = '';

			$pdf->MultiCell($col1, $row2, $perioddate->format('d').'('.$week[$perioddate->format('w')].')', '1', 'C', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			//基準単価から残業単価を計算
			//2022-05-07 ceilを追加
			$basetanka125 = ceil($user['User']['basetanka'] * 1.25);
			$basetanka135 = ceil($user['User']['basetanka'] * 1.35);
			$basetanka025 = ceil($user['User']['basetanka'] * 0.25);

			if ($user['Kintaitran']) {
				foreach ($user['Kintaitran'] as $kintaitran) {
					if ($kintaitran['kintaidate'] == $perioddate->format('Y-m-d')) {
						//臨時カレンダーを退避
						$w_rinjicalendar = $kintaitran['rinjicalendarflg'];
						$w_kintai = $kintaitran['Flgmaster']['flgname'];
						switch($kintaitran['Flgmaster']['id']){
							case 11:
								if($kintaitran['rinjicalendarflg']==1){
									$w_kintai = '法定外';
								}
								if($kintaitran['rinjicalendarflg']==2){
									$w_kintai = '法定';
								}
								if($kintaitran['rinjicalendarflg']==0){
									$syukkin_cnt++;
								}
								break;
							case 13:
							case 15:
							case 17:
							case 18:
							case 21:
							case 22:
								//$syukkin_cnt++;
								break;
						}
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
						if($tako_gokei_soukou_h > '00:00'){
							$tako_gokei_tenko_h = '00:30';
						}else{
							$tako_gokei_tenko_h = '00:00';
						}
						$roudou_goukei_2 = $this->Format->Addtime($kintaitran['kinmu_jikan'],'00:00:00');

						$heijitsuzangyo = $this->Format->TimeFormat($kintaitran['heijitsuzangyo_ori']);
						$heijitsushinya = $this->Format->TimeFormat($kintaitran['heijitsushinya_ori']);

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

						//2022-04-27　無条件で深夜2時間分をつける（職員のみ）
						if($kintaitran['jyoumuflg']==1){
							if($kintaitran['rinjicalendarflg']==1){ //法定外休日
								//20210806$jyoumuflgkingaku = $basetanka135 * $user['User']['jyoumuhour'];
								$jyoumuflgkingaku = $basetanka125 * $user['User']['jyoumuhour'];
								$jyoumushinya2 = $basetanka025 * 2;
							}
							if($kintaitran['rinjicalendarflg']==2){ //法定休日
								//20210806 $jyoumuflgkingaku = $basetanka135 * $user['User']['jyoumuhour'];
								$jyoumuflgkingaku = $basetanka125 * $user['User']['jyoumuhour'];
								$jyoumushinya2 = $basetanka025 * 2;
							}
							if($kintaitran['rinjicalendarflg']==0){ //通常
								$jyoumuflgkingaku = $basetanka125 * $user['User']['jyoumuhour'];
								$jyoumushinya2 = $basetanka025 * 2;
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
							if(($jyoumushinyaflgkingaku > 0)and($user['User']['jyoumushinyahour'] > 0)){
								$jyoumushinyahour = $this->Format->chg_time($user['User']['jyoumushinyahour']);
								$total_jyoumushinyahour = $this->Format->Addtime($total_jyoumushinyahour,$jyoumushinyahour);
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


						$sou_soukou = $this->Format->Addtime($sou_soukou,$kintaitran['tako_gokei_soukou_h']);
						$sou_sagyou = $this->Format->Addtime($sou_sagyou,$kintaitran['tako_gokei_sagyou_h']);
						$sou_taiki = $this->Format->Addtime($sou_taiki,$kintaitran['tako_gokei_taiki_h']);
						if($tako_gokei_tenko_h > '00:00'){
							$sou_tenko = $this->Format->Addtime($sou_tenko,$tako_gokei_tenko_h);
						}
						$sou_roudou = $this->Format->Addtime($sou_roudou,$roudou_goukei_2);

						$sou_hei_zan = $this->Format->Addtime($sou_hei_zan,$heijitsuzangyo);
						$sou_hei_shin = $this->Format->Addtime($sou_hei_shin,$heijitsushinya);
						$sou_kyu_zan = $this->Format->Addtime($sou_kyu_zan,$kintaitran['kyujitsuzangyo']);
						$sou_kyu_shin = $this->Format->Addtime($sou_kyu_shin,$kintaitran['kyujitsushinya']);
						$sou_hou_zan = $this->Format->Addtime($sou_hou_zan,$kintaitran['houteikyujitsu']);
						$sou_hou_shin = $this->Format->Addtime($sou_hou_shin,$kintaitran['houteikyujitsushinya']);

						$zangyou_kei = $this->Format->Addtime($zangyou_kei,$heijitsuzangyo);
						$zangyou_kei = $this->Format->Addtime($zangyou_kei,$kyujitsuzangyo);
						$w_kyujitsuzangyo = $kyujitsuzangyo;
						$zangyou_kei = $this->Format->Addtime($zangyou_kei,$houteikyujitsu);
						$w_houteikyujitsu = $houteikyujitsu;

			//ここに移動
						//if($w_rinjicalendar == 0){
						$teigakujikangai = $this->Format->If_null($kintaitran['teigakujikangai']);
						$teigakushinya = $this->Format->If_null($kintaitran['teigakushinya']);
						if($user['User']['rolemasters_id']==28){
							$teigakujikangai = $teigakujikangai * 0.1;
							$teigakushinya = $teigakushinya * 0.1;
						}
						$teigakujikangai_kei = $teigakujikangai_kei + $teigakujikangai;
						$teigakushinya_kei = $teigakushinya_kei + $teigakushinya;
						//}else{
						/*
							if($w_rinjicalendar==1){ //法定外休日
								$teigakujikangai = $basetanka125 * $this->Format->chg_integer($w_kyujitsuzangyo);
								$teigakushinya = 0;
								$teigakujikangai_kei = $teigakujikangai_kei + $teigakujikangai;
								$teigakushinya_kei = $teigakushinya_kei + $teigakushinya;
							}
							if($w_rinjicalendar==2){ //法定休日
								$teigakujikangai = $basetanka135 * $this->Format->chg_integer($w_houteikyujitsu);
								$teigakushinya = 0;
								$teigakujikangai_kei = $teigakujikangai_kei + $teigakujikangai;
								$teigakushinya_kei = $teigakushinya_kei + $teigakushinya;
							}
						}
						*/
			//ここに移動
						$goukei = $goukei + $soukoukiro + $teigakujikangai + $teigakushinya + $jyoumushinyaflgkingaku + $zangyoflgkingaku + $jyoumuflgkingaku + $telflgkingaku + $sagyoteate2 + $jyoumushinya2;


					}
				}
			}
			//km合計から作業歩合テーブルを読む
			//if($w_rinjicalendar==0){ //通常
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
					$teigakuzangyojikan = $this->Format->Addtime($workprice2data['Workprice2']['zangyojikan'],'00:00:00');
					$teigakuzangyoshinya = $this->Format->Addtime($workprice2data['Workprice2']['shinyajikan'],'00:00:00');
					$total_teigakuzangyojikan = $this->Format->Addtime($total_teigakuzangyojikan,$teigakuzangyojikan);
					$total_teigakuzangyoshinya = $this->Format->Addtime($total_teigakuzangyoshinya,$teigakuzangyoshinya);
				}
			/*
			}else{
				if($w_rinjicalendar==1){ //法定外休日
			//		$teigakuzangyojikan = $this->Format->Addtime($kintaitran['kyujitsuzangyo'],'00:00:00');
					$teigakuzangyojikan = $w_kyujitsuzangyo;
					$teigakuzangyoshinya = '00:00:00';
				}
				if($w_rinjicalendar==2){ //法定休日
			//		$teigakuzangyojikan = $this->Format->Addtime($kintaitran['houteikyujitsu'],'00:00:00');
					$teigakuzangyojikan = $w_houteikyujitsu;
					$teigakuzangyoshinya = '00:00:00';
				}
				$total_teigakuzangyojikan = $this->Format->Addtime($total_teigakuzangyojikan,$teigakuzangyojikan);
				$total_teigakuzangyoshinya = $this->Format->Addtime($total_teigakuzangyoshinya,$teigakuzangyoshinya);
			}
			*/
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
			if($tako_gokei_tenko_h=='00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $tako_gokei_tenko_h, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
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
			if($teigakuzangyojikan=='00:00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $teigakuzangyojikan, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}
			$mins = $half_mins = $half_mins_h = $half_mins_m = 0;
			$tArry = [];
			if(($teigakuzangyoshinya=='00:00')||($teigakuzangyoshinya == '')){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				//H:ｍ
				$tArry=explode(":",$teigakuzangyoshinya);
				$hour=$tArry[0]*60;//時間→分
				$mins=$hour+$tArry[1];
				$half_mins = $mins / 2;
				$half_mins_h = floor($half_mins / 60);
				$half_mins_m = $half_mins - ($half_mins_h * 60);
				/*
				$w_sec = strtotime('1970-01-01 '.$teigakuzangyoshinya.':00') - strtotime('1970-01-01 0:0:0');
				$w_half_time = round($w_sec / 2); //秒になっている
				$w_half_time_shinya = round($w_sec / 2); //秒になっている
				$w_hour = floor($w_half_time / 3600);
				$w_minutes = floor(($w_half_time / 60) % 60);
				$w_hour_shinya = floor($w_half_time_shinya / 3600);
				$w_minutes_shinya = floor(($w_half_time_shinya / 60) % 60);
				*/		
				$pdf->MultiCell($col4, $row2, substr('00'.$half_mins_h,-2).':'.substr('00'.$half_mins_m,-2), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				//2023-03-17 $pdf->MultiCell($col4, $row2, $teigakuzangyoshinya, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
				$pdf->MultiCell($col4, $row2, substr('00'.$half_mins_h,-2).':'.substr('00'.$half_mins_m,-2), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}
			if($jyoumushinyahour=='00:00'){
				$pdf->MultiCell($col4, $row2, '', '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}else{
				$pdf->MultiCell($col4, $row2, $jyoumushinyahour, '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			}
			//2023-03-17 col5,6->col4に変更
			$pdf->MultiCell($col4, $row2, $this->Format->NumberFormat($tako_gokei_km), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $this->Format->NumberFormat($teigakujikangai), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$w_half_shinya = $w_half = 0;
			$w_half = round($teigakushinya / 2);
			$pdf->MultiCell($col4, $row2, $this->Format->NumberFormat($w_half), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$w_half_shinya = round($teigakushinya / 2);
			//$pdf->MultiCell($col4, $row2, $this->Format->NumberFormat($teigakushinya), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $this->Format->NumberFormat($w_half_shinya), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $this->Format->NumberFormat($jyoumushinyaflgkingaku), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $this->Format->NumberFormat($sagyoteate2), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $this->Format->NumberFormat($soukoukiro), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $this->Format->NumberFormat($zangyoflgkingaku), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $this->Format->NumberFormat($jyoumuflgkingaku), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $this->Format->NumberFormat($jyoumushinya2), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col4, $row2, $this->Format->NumberFormat($telflgkingaku), '1', 'R', 0, 0, '', '', true, 0, false, true, $row2, 'M');
			$pdf->MultiCell($col5, $row2, $this->Format->NumberFormat($goukei), '1', 'R', 0, 1, '', '', true, 0, false, true, $row2, 'M');
			//2023-03-17 add
			$w_half_kei = $w_half_kei + $w_half;
			$w_half_shinya_kei = $w_half_shinya_kei + $w_half_shinya;

			$tako_gokei_km_kei = $tako_gokei_km_kei + $tako_gokei_km;
			$soukoukiro_kei = $soukoukiro_kei + $soukoukiro;
			$sagyoteate2_kei = $sagyoteate2_kei + $sagyoteate2;
			$sougou_kei = $sougou_kei + $goukei;
			$untin_buai_kei = $untin_buai_kei + $untin_buai;
			$zangyoflgkingaku_kei = $zangyoflgkingaku_kei + $zangyoflgkingaku;
			$jyoumuflgkingaku_kei = $jyoumuflgkingaku_kei + $jyoumuflgkingaku;
			$jyoumushinya2_kei = $jyoumushinya2_kei + $jyoumushinya2;
			$jyoumushinyaflgkingaku_kei = $jyoumushinyaflgkingaku_kei + $jyoumushinyaflgkingaku;
			$telflgkingaku_kei = $telflgkingaku_kei + $telflgkingaku;
		}
		$cc = ($untin_buai_kei + $soukoukiro_kei);
		//2023-03-31 
		$cc = $soukoukiro_kei;
		//2023-03-31 深夜分を追加 かつ法定、法定外も加算（元は入れてなかった）
		$total_zangyo = $this->Format->Addtime($sou_hei_zan,$sou_hei_shin);
		//60hを超えているかを判断
		$sou_zan_sa = '00:00';
		if($sou_hei_zan >= '60:00'){
			$sou_zan_sa = $this->Format->Addtime($sou_hei_zan,'-60:00');
		}
		//嘱託の場合、出勤日数×7:45をベースとする
		switch($user['User']['roudouflg']){
			case 1: //正社員
				//2023-03-31 $dd = $this->Format->Addtime('173:30',$total_zangyo);
				$dd = $sou_roudou;
				break;
			case 2: //嘱託
			case 3: //パート
			case 5: //バイト
				$basetime = '7:45';
				$timearray = explode(":",$basetime);
				$hour = $timearray[0] * 60 * $syukkin_cnt; //分に換算
				$secound = $timearray[1] * $syukkin_cnt;
				$totaltime = ($hour + $secound) * 60; //合計の分を秒に変換
				$hours = floor( $totaltime / 3600 );
				$minutes = floor( ( $totaltime / 60 ) % 60 );
				$syokutakutime = $hours.':'.$minutes;
				//2023-03-31 $dd = $this->Format->Addtime($syokutakutime,$total_zangyo);
				$dd = $this->Format->Addtime($syokutakutime,$sou_roudou);
				break;
			case 4: //季節
				//2023-03-31 $dd = $this->Format->Addtime('173:30',$total_zangyo);
				$dd = $sou_roudou;
				break;
		}
//		debug('dd='.$dd);
		$times2 = explode(":",$dd);
		$hour2 = $times2[0];
		$second2 = round($times2[1] / 60, 3);
		if($second2 >= 0.5){
			$second2 = 1;
		}else{
			if($second2 == 0){
				$second2 = 0;
			}else{
				$second2 = 0.5;
			}
		}
		$gg = $hour2 + $second2;
		if($gg){
			$tanka = ($cc / $gg) * 0.25;
		}else{
			$tanka = 0;
		}
/*
debug('dd='.$dd);
debug('cc='.$cc);
debug('hour2='.$hour2);
debug('seconde2='.$second2);
debug('tanka='.$tanka);
debug('total_zangyo='.$total_zangyo);
debug('sou_zan_sa='.$sou_zan_sa);
*/
		if($sou_zan_sa == '00:00'){
			$total_zan_total = $total_zangyo;
		}else{
			$total_zan_total = $this->Format->Addtime($total_zangyo,$sou_zan_sa);
		}
		$times1 = explode(":",$total_zan_total);
		//$times1 = explode(":",$buai_total);
		$hour1 = $times1[0];
		$second1 = round($times1[1] / 60, 3);
		if($second1 >= 0.5){
			$second1 = 1;
		}else{
			if($second1 == 0){
				$second1 = 0;
			}else{
				$second1 = 0.5;
			}
		}
		$result = round(($hour1 + $second1) * $tanka);
/*
		debug('hour1='.$hour1);
debug('second1='.$second1);
debug('result='.$result);
*/
		$pdf->SetFontSize(7);
		$pdf->MultiCell($col1+$col1+$col3, $row1, '歩合割増', '0', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col3, $row1, $result, '0', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col4, $row1, $sou_soukou, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_sagyou, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_taiki, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_tenko, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_roudou, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->SetFillColor(200,-1,-1,-1);
		$pdf->MultiCell($col4, $row1, $sou_hei_zan, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $sou_hei_shin, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $total_teigakuzangyojikan, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		//2023-03-17 $pdf->MultiCell($col4, $row1, $total_teigakuzangyoshinya, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$w_sec = explode(":",$total_teigakuzangyoshinya);
		$w_half_h = floor($w_sec[0] / 2);
		$w_half_m = floor($w_sec[1] / 2);

		$pdf->MultiCell($col4, $row1, substr('00'.$w_half_h,-2).':'.substr('00'.$w_half_m,-2), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, substr('00'.$w_half_h,-2).':'.substr('00'.$w_half_m,-2), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $total_jyoumushinyahour, '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');

		$pdf->MultiCell($col4, $row1, $this->Format->NumberFormat($tako_gokei_km_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$zangyou_goukei = $this->Format->Addtime($zangyou_goukei,$sou_hei_zan);
		$zangyou_goukei = $this->Format->Addtime($zangyou_goukei,$sou_kyu_zan);
		$zangyou_goukei = $this->Format->Addtime($zangyou_goukei,$sou_hou_zan);

		$pdf->MultiCell($col4, $row1, $this->Format->NumberFormat($teigakujikangai_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $this->Format->NumberFormat($w_half_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		//2023-03-17 $pdf->MultiCell($col4, $row1, $this->Format->NumberFormat($teigakushinya_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $this->Format->NumberFormat($w_half_shinya_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $this->Format->NumberFormat($jyoumushinyaflgkingaku_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $this->Format->NumberFormat($sagyoteate2_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $this->Format->NumberFormat($soukoukiro_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $this->Format->NumberFormat($zangyoflgkingaku_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $this->Format->NumberFormat($jyoumuflgkingaku_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $this->Format->NumberFormat($jyoumushinya2_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col4, $row1, $this->Format->NumberFormat($telflgkingaku_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
		$pdf->MultiCell($col5, $row1, $this->Format->NumberFormat($sougou_kei), '1', 'R', 0, 0, '', '', true, 0, false, true, $row1, 'M');
	  } //kintaitranがない場合の読み飛ばしカッコ
} //foreach end
	  $pdf->lastPage();
	  $pdf->Output('Untennippou.pdf', 'I');
