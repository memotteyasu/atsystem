<?php
// 20250627 教育時間を最終カラムに追加
/*
 * 20240331 定時時間と８ｈの差分は１．０倍で計算
 */
// 2022-12-01 守衛のフラグを加味
/*
2022-04-27 乗務深夜を追加（乗務員用）
*/
/*
交通費精算を追加（課税、非課税）
*/
//2021-11-01 heijitsuzangyo_nashiは1.0ではなく1.25
	$paramsDate->modify('+1 months');
	$this->Csv->addrow($th);
//テーブル読み込み
	$datacheck = 0;
	$calenderusermaster = ClassRegistry::init('Calendarusermaster');
$i=0;
	//会社マスタからガソリン等の単価を取得
	$kaisya = ClassRegistry::init('Kaisya');
	$kaisyadata = [];
	$kaisyadata = $kaisya->find('first',[
		'conditions'=>[
			'Kaisya.id'=>$user['kaisyas_id']
		],
		'recursive'=>-1
	]);
	foreach ($datas as $data) {
	  $basetanka125 = $basetanka135 = $telhour = $zangyohour = $jyoumuhour = $jyoumushinyahour = $shikakuhour = 0;
	  $telhour = $data['User']['telhour'];
	  $zangyohour = $data['User']['zangyohour'];
	  $jyoumuhour = $data['User']['jyoumuhour'];
	  $jyoumushinyahour = $data['User']['jyoumushinyahour'];
	  $shikakuhour = $data['User']['shikakuhour'];
	  //2021-11-01 $basetanka100 = $data['User']['basetanka'];
	  //小数点は整数にする（切り上げ）
	  //2022-05-07 一旦切り上げをやめる
	  //20240331 $basetanka100 = ceil($data['User']['basetanka'] * 1.25);
	  $basetanka100 = ceil($data['User']['basetanka'] * 1.00);
	  $basetanka125 = ceil($data['User']['basetanka'] * 1.25);
	  $basetanka025 = ceil($data['User']['basetanka'] * 0.25);
	  $basetanka135 = ceil($data['User']['basetanka'] * 1.35);

	  if((isset($data['Kintaitran']))&&($data['Kintaitran'])){
		$this->Csv->addField(substr(sprintf('%04d',$data['User']['syain_cd']),-4)); // 1-社員コード
		$this->Csv->addField($paramsDate->format('n')); // 2-支給回数月
		$this->Csv->addField($paramsDate2->format('Ymd')); // 3-支給日

		// 要勤務日数
		$posts = $calenderusermaster->find('first',[
			'fields'=>[
				'baseday'
			],
			'conditions'=>[
				'Calendarusermaster.calendartype'=>$data['User']['calendarflg'],
				'Calendarusermaster.month'=>$selectmm->format('Y-m-d'),
			]
		]);
		$this->Csv->addField($posts['Calendarusermaster']['baseday']); //4-要勤務日数
		//dummy test $this->Csv->addField(23); //4-要勤務日数

		$syukkinday = $syukkin_cnt = $syukkin = $kekkin = $yukyu = $tokukyu = $daikyu = $kyujitsusyukkin = 0;
		$guard_1 = $guard_2 = $guard_3 = 0;
		$daitokukyu = 0;
		$total_teigakuzangyojikan = $total_teigakuzangyoshinya = $teigakuzangyojikan = $teigakuzangyoshinya = $zangyototal = $zangyototal_shinya = $so_heijitsuzangyo = $chisou_kei = $chisou_sa = $heijitsuzangyo_nashi = $heijitsuzangyo = $heijitsushinya = $kyujitsuzangyo = $kyujitsushinya = $houteikyujitsu = $houteikyujitsushinya = $heijitsuzangyo_goukei = '00:00';
		$jyoumushinya2time = $jyoumushinya2 = $jyoumushinyahour_total = $hougaikingaku = $houteikingaku = $kyujitsu_cnt = $shikakukingaku = $telkingaku = $jyoumukingaku = $zangyokingaku = $jyoumushinyakingaku = $sagyoteate2_kei = $shiteikyu = $chisou = $kyugyo = $sagyoteate = $teigakujikangai = $teigakushinya = $untin_buai_kei = $koukadou_teate = $soukoukiro = $soukoukiro_kei = $jyoumu_teate = $w_half = $w_half_shinya = 0;
		//20250627 add
		$kyouiku_time = $heijitsushinya_ori_kei = $heijitsuzangyo_ori_kei = $kinmu_jikan_kei = $sou_hei_zan = $kinmu_jikan = '00:00';
		foreach ($data['Kintaitran'] as $kintaitran) {
			switch ($kintaitran['flgmasters_id']) {
				case 11: // 出勤
				case 15: // 待機は出勤と同じ
				case 18: // 教育は出勤と同じ
				case 22: // 検診は出勤と同じ
				case 25: // 出張は出勤と同じ
					// 出勤・待機・教育した日だけをカウント
					//2022-05-09 出張もカウントする
					if(($kintaitran['flgmasters_id']==11)or($kintaitran['flgmasters_id']==15)or($kintaitran['flgmasters_id']==18)or($kintaitran['flgmasters_id']==22)or($kintaitran['flgmasters_id']==25)){
						$syukkinday = $syukkinday + 1;
					}
					$syukkin = $syukkin + 1;
					if($kintaitran['rinjicalendarflg']==1){
						$kyujitsu_cnt = $kyujitsu_cnt + 1;
					}
					if($kintaitran['rinjicalendarflg']==2){
						$kyujitsu_cnt = $kyujitsu_cnt + 1;
					}
					if($kintaitran['rinjicalendarflg']==0){
						$syukkin_cnt++;
					}
					//20250627 教育の場合
					if($kintaitran['flgmasters_id']==18){
						$kyouiku_time = $this->Format->Addtime($kyouiku_time, $kintaitran['kinmu_jikan']);
					}
					break;
				case 12: // 欠勤
				case 23: // 傷病欠勤
				case 24: // 労災欠勤
					$kekkin = $kekkin + 1 ;
					break;
				case 45: // 守衛・・・出勤と同じ扱いにする(守衛の日、翌日分もカウント)
					$syukkin_cnt = $syukkin_cnt + 2;
					$syukkinday = $syukkinday + 2;
					$syukkin = $syukkin + 2; //2023-01-10追加

					if($kintaitran['guardflg']==1){
						//平日
						$guard_1 = $guard_1 + 1;
					}
					if($kintaitran['guardflg']==2){
						//祝日前
						$guard_2 = $guard_2 + 1;
					}
					if($kintaitran['guardflg']==3){
						//祝日
						$guard_3 = $guard_3 + 1;
					}
					break;
				case 13: // 有給
					$yukyu = $yukyu + 1;
					break;
				case 14: // 休業
					$kyugyo = $kyugyo + 1;
					break;
				case 17: // 特休
					$tokukyu = $tokukyu + 1;
					break;
				case 19: // 代休
					$daikyu = $daikyu + 1;
					break;
				case 20: // 指定休
					$shiteikyu = $shiteikyu + 1;
					break;
				case 21: // 遅刻早退 - 7.75に満たない時間の合計を遅刻早退時間としてセット
					//交通費用のカウントを追加
					$syukkinday = $syukkinday + 1;
					$chisou = $chisou + 1;
					//正社員の事務職だけ定時から労働時間を引いたものを集計
					//2022-12-08 中村さんの指示で正社員に限らず全員計算する（運転手は遅早を使わないようにする前提）
					//if($data['User']['roudouflg']==1){
						$chisou_sa = $this->Format->Subtime_csv('7:45',$kintaitran['kinmu_jikan']);
						$chisou_kei = $this->Format->Addtime($chisou_kei,$chisou_sa);
					//}else{
					//	$chisou_kei = '00:00';
					//}
					break;
			}
			//残業時間を集計
			$heijitsuzangyo_nashi = $this->Format->Addtime($heijitsuzangyo_nashi,$kintaitran['heijitsuzangyo_nashi']);
			$heijitsuzangyo = $this->Format->Addtime($heijitsuzangyo,$kintaitran['heijitsuzangyo']);
			$heijitsushinya = $this->Format->Addtime($heijitsushinya,$kintaitran['heijitsushinya']);
			$kyujitsuzangyo = $this->Format->Addtime($kyujitsuzangyo,$kintaitran['kyujitsuzangyo']);
			$kyujitsushinya = $this->Format->Addtime($kyujitsushinya,$kintaitran['kyujitsushinya']);
			$houteikyujitsu = $this->Format->Addtime($houteikyujitsu,$kintaitran['houteikyujitsu']);
			$houteikyujitsushinya = $this->Format->Addtime($houteikyujitsushinya,$kintaitran['houteikyujitsushinya']);

			$soukoukiro = $soukoukiro + $kintaitran['soukoukiro'];
			//労働時間合計 （勤務時間は待機・有休等の場合はkinmu_jikanを00:00で入れている
			$kinmu_jikan_kei = $this->Format->Addtime($kinmu_jikan_kei,$kintaitran['kinmu_jikan']);
			//歩合割増のための総労働時間の取得
			$heijitsuzangyo_ori_kei = $this->Format->Addtime($heijitsuzangyo_ori_kei,$kintaitran['heijitsuzangyo_ori']);
//			$heijitsuzangyo_ori_kei = $this->Format->Addtime($heijitsuzangyo_ori_kei,$kintaitran['kyujitsuzangyo']);
//			$heijitsuzangyo_ori_kei = $this->Format->Addtime($heijitsuzangyo_ori_kei,$kintaitran['houteikyujitsu']);
			$heijitsushinya_ori_kei = $this->Format->Addtime($heijitsushinya_ori_kei,$kintaitran['heijitsushinya_ori']);

			// 集計
			$workprice2 = ClassRegistry::init('Workprice2');
			$workprice2data = [];
			$workprice2data = $workprice2->find('first',[
				'fields'=>[
					'Workprice2.zangyojikan',
					'Workprice2.shinyajikan',
				],
				'conditions'=>[
					'Workprice2.startkilo <='=>$kintaitran['tako_gokei_km'],
					'Workprice2.endkilo >='=>$kintaitran['tako_gokei_km']
				],
				'recursive'=>-1
			]);
			$w_hour = $w_minutes = $w_hour_shinya = $w_minutes_shinya = '00';
			if($workprice2data){
				$teigakuzangyojikan = '00:00';
				$teigakuzangyoshinya = '00:00';
				// 法定・法定外を無視してみる 2021-06-08
/*
				if($kintaitran['rinjicalendarflg']==1){ //法定外休日
					$teigakuzangyojikan = $this->Format->Addtime($kintaitran['kyujitsuzangyo'],'00:00:00');
					$teigakuzangyoshinya = '00:00:00';
				}
				if($kintaitran['rinjicalendarflg']==2){ //法定休日
					$teigakuzangyojikan = $this->Format->Addtime($kintaitran['houteikyujitsu'],'00:00:00');
					$teigakuzangyoshinya = '00:00:00';
				}
				if($kintaitran['rinjicalendarflg']==0){ //通常
*/
					$teigakuzangyojikan = $this->Format->Addtime($workprice2data['Workprice2']['zangyojikan'],'00:00:00');
					$teigakuzangyoshinya = $this->Format->Addtime($workprice2data['Workprice2']['shinyajikan'],'00:00:00');
/*
				}
*/
				$total_teigakuzangyojikan = $this->Format->Addtime($total_teigakuzangyojikan,$teigakuzangyojikan);
				$total_teigakuzangyoshinya = $this->Format->Addtime($total_teigakuzangyoshinya,$teigakuzangyoshinya);
			}
			$soukoukiro_kei = $soukoukiro_kei + $kintaitran['soukoukiro'];
			$teigakujikangai = $teigakujikangai + $kintaitran['teigakujikangai'];
			//１日単位で半分にして四捨五入したものをサマリーするように変更2023-04-25
			$w_half = $w_half + round($kintaitran['teigakushinya'] / 2);
			$w_half_shinya = $w_half_shinya + round($kintaitran['teigakushinya'] / 2);
			// $teigakushinya = $teigakushinya + $kintaitran['teigakushinya'];
			//個別歩合計算
			$sagyoteate2_kei = $sagyoteate2_kei + $kintaitran['sagyoteate2'];
			//電話歩合
			if($kintaitran['telflg']==1){
				$telkingaku = $telkingaku + ($telhour * $basetanka125);
			}
			//通常残業
			if($kintaitran['zangyoflg']==1){
				$zangyokingaku = $zangyokingaku + ($zangyohour * $basetanka125);
			}
			//乗務歩合
			if($kintaitran['jyoumuflg']==1){
				$jyoumukingaku = $jyoumukingaku + ($jyoumuhour * $basetanka125);
				//2022-04-27 乗務したら無条件で深夜2時間分
				$jyoumushinya2 = $jyoumushinya2 + (2 * $basetanka025);
				//2023-04 上記の時間も集計する
				$jyoumushinya2time = $jyoumushinya2time + 2;
			}
			//深夜歩合
			if($kintaitran['jyoumushinyaflg']==1){
				$jyoumushinyakingaku = $jyoumushinyakingaku + ($jyoumushinyahour * $basetanka025);
				$jyoumushinyahour_total = $jyoumushinyahour_total + $jyoumushinyahour;
			}
			//2023-06-12 星さん(id=107)だけ通常残業にチェックがあったら、２ｈ深夜割増を加算
			/* 20241204
			if(($kintaitran['zangyoflg']==1)&&($data['User']['id']=='107')){
				$jyoumushinya2 = $jyoumushinya2 + ($basetanka025 * 2);
			}
			*/

			$hougaikingaku = $hougaikingaku + $kintaitran['hougaikingaku'];
			$houteikingaku = $houteikingaku + $kintaitran['houteikingaku'];
		}
		//20230-612 $this->Csv->addField($syukkin - $kyujitsu_cnt); // 5-出勤日数 - 休日のカウント
		$this->Csv->addField($syukkinday - $kyujitsu_cnt); // 5-出勤日数 - 休日のカウント
		$this->Csv->addField($kekkin); // 6-欠勤日数
		$this->Csv->addField($kyugyo); // 7-休業日数
		$this->Csv->addField($daikyu); // 7-代休日数
		$this->Csv->addField($kyujitsu_cnt); // 8-休日出勤日数
		$this->Csv->addField($yukyu); // 9-有給消化日数
		$this->Csv->addField($chisou); // 10-遅刻早退回数
		$this->Csv->addField($chisou_kei); // 11-遅刻早退時間

		$so_heijitsuzangyo = $this->Format->Addtime($heijitsuzangyo_nashi,$heijitsuzangyo);
		//2021-06-07 残業時間のすべての時間を合算
		$zangyototal = $this->Format->Addtime($zangyototal,$so_heijitsuzangyo);
		$zangyototal = $this->Format->Addtime($zangyototal,$kyujitsuzangyo);
		$zangyototal = $this->Format->Addtime($zangyototal,$houteikyujitsu);

		$zangyototal_shinya = $this->Format->Addtime($zangyototal_shinya,$heijitsushinya);
		$zangyototal_shinya = $this->Format->Addtime($zangyototal_shinya,$kyujitsushinya);
		$zangyototal_shinya = $this->Format->Addtime($zangyototal_shinya,$houteikyujitsushinya);

		if($data['User']['rolemasters_id']>=30){
			//運転手の場合、定額で払い込んでいる時間より少ない場合は残業時間を０にする
/*
			if($data['User']['id']==4){
				debug($this->Format->chg_integer($zangyototal));
				debug($this->Format->chg_integer($total_teigakuzangyojikan));
			}
*/
			if($this->Format->chg_integer($zangyototal) <= $this->Format->chg_integer($total_teigakuzangyojikan)){
/*
				if($data['User']['id']==4){
					debug('aa');
					exit;
				}
*/
				$heijitsuzangyo_nashi = '00:00';
				$heijitsuzangyo = '00:00';
				$so_heijitsuzangyo = '00:00';
				$heijitsushinya = '00:00';
//				$kyujitsuzangyo = '00:00';
				$kyujitsushinya = '00:00';
//				$houteikyujitsu = '00:00';
				$houteikyujitsushinya = '00:00';
				$this->Csv->addField($so_heijitsuzangyo); // 12-平日残業
				$this->Csv->addField($heijitsushinya); // 13-平日深夜割増
				$this->Csv->addField($kyujitsuzangyo); // 14-休日残業
				$this->Csv->addField($kyujitsushinya); // 15-休日深夜割増
				$this->Csv->addField($houteikyujitsu); // 16-法定休日残業
				$this->Csv->addField($houteikyujitsushinya); // 17-法定休日深夜割増
			}else{
				$this->Csv->addField($so_heijitsuzangyo); // 12-平日残業
				$this->Csv->addField($heijitsushinya); // 13-平日深夜割増
				$this->Csv->addField($kyujitsuzangyo); // 14-休日残業
				$this->Csv->addField($kyujitsushinya); // 15-休日深夜割増
				$this->Csv->addField($houteikyujitsu); // 16-法定休日残業
				$this->Csv->addField($houteikyujitsushinya); // 17-法定休日深夜割増
			}
		}else{
			$this->Csv->addField($so_heijitsuzangyo); // 12-平日残業
			$this->Csv->addField($heijitsushinya); // 13-平日深夜割増
			$this->Csv->addField($kyujitsuzangyo); // 14-休日残業
			$this->Csv->addField($kyujitsushinya); // 15-休日深夜割増
			$this->Csv->addField($houteikyujitsu); // 16-法定休日残業
			$this->Csv->addField($houteikyujitsushinya); // 17-法定休日深夜割増
		}
		$this->Csv->addField($soukoukiro_kei); // 19-（ｋｍ　距離歩合 4月は少ない数字で給与を払っているかも（soukoukiro)を代入してた。

		$this->Csv->addField($sagyoteate2_kei); // 20 個別歩合
		//電話歩合計算
		//2022-05-07 ROUNDを外した->ceilに変更
		$this->Csv->addField(ceil($telkingaku)); // 21 電話歩合
		$this->Csv->addField(ceil($jyoumushinyakingaku)); // 22 夜勤歩合
		$this->Csv->addField(ceil($zangyokingaku)); // 23 通常残業（事務兼運転手の）
		$this->Csv->addField(ceil($jyoumukingaku)); // 24 乗務残業
		if($shikakuhour>0){
			$shikakukingaku = $shikakuhour * $basetanka125;
		}else{
			$shikakukingaku = 0;
		}
		//2022-05-07 ceilを追加
		$this->Csv->addField(ceil($shikakukingaku)); // 25 資格歩合
		$this->Csv->addField(ceil($hougaikingaku)); // 26 法定外歩合
		$this->Csv->addField(ceil($houteikingaku)); // 27 法定休日歩合
		//ガソリン代計算
		$gasusetran = ClassRegistry::init('Gasusetran');
		$gasusetrandata = [];
		$gasusetrandata = $gasusetran->find('all',[
			'fields'=>[
				'Gasusetran.amount',
				'Gasusetran.gaskind'
			],
			'conditions'=>[
				'AND'=>[
	 				'Gasusetran.chargedate >=' => $w_firstdate,
	 				'Gasusetran.chargedate <=' => $w_lastdate
				],
				'Gasusetran.users_id'=>$kintaitran['users_id']
			],
			'recursive'=>0
		]);
		$gaskingaku = 0;
		if($gasusetrandata){
			foreach($gasusetrandata as $gasdata){
				if($gasdata['Gasusetran']['gaskind']==31){ //ガソリン
					$gaskingaku = $gaskingaku + $kaisyadata['Kaisya']['gasprice'] * $gasdata['Gasusetran']['amount'];
				}
				if($gasdata['Gasusetran']['gaskind']==32){ //経由
					$gaskingaku = $gaskingaku + $kaisyadata['Kaisya']['dieselprice'] * $gasdata['Gasusetran']['amount'];
				}
				if($gasdata['Gasusetran']['gaskind']==34){ //オイル
					$gaskingaku = $gaskingaku + $kaisyadata['Kaisya']['oilprice'] * $gasdata['Gasusetran']['amount'];
				}
			}
		}
		$this->Csv->addField(round($gaskingaku)); // 29 ガソリン代
		$this->Csv->addField($shiteikyu); //　30 指定休日数
		//パートだけ、総勤務時間から残業時間合計を引いたものをセットかつ、有休のカウント分だけ社員マスタの有休時間をかけたものを足す。
		if($data['User']['roudouflg']==3){
			if($so_heijitsuzangyo > '00:00:00'){
				$kinmu_jikan_kei = $this->Format->Subtime($kinmu_jikan_kei,$so_heijitsuzangyo);
			}
			if($kyujitsuzangyo > '00:00:00'){
				$kinmu_jikan_kei = $this->Format->Subtime($kinmu_jikan_kei,$kyujitsuzangyo);
			}
			if($houteikyujitsu > '00:00:00'){
				$kinmu_jikan_kei = $this->Format->Subtime($kinmu_jikan_kei,$houteikyujitsu);
			}
			if($yukyu>0){
				$aa = 0;
				$aa = $yukyu * $data['User']['yuukyuhour'];
				$kinmu_jikan_kei = $this->Format->Addtime($kinmu_jikan_kei,$this->Format->chg_time($aa));
			}
			$this->Csv->addField($kinmu_jikan_kei); // 31 総勤務時間 - 残業時間
		}else{
			$this->Csv->addField($kinmu_jikan_kei); // 31 総勤務時間
		}
		//teigakushinyaは最後に別の項目に入れているので、０代入に変更する
		$teigakushinya = $w_half + $w_half_shinya;
		if($data['User']['rolemasters_id']==28){
			//事務職兼運転手の場合は１０％にする
			$teigakushinya = $teigakushinya*0.1;
			$this->Csv->addField(round($teigakujikangai*0.1)); // 32 定額時間外
			//$this->Csv->addField(round($teigakushinya)); // 33 定額深夜割増
		}else{
			$this->Csv->addField($teigakujikangai); // 32 定額時間外
			//$this->Csv->addField($teigakushinya); // 33 定額深夜
		}
		$this->Csv->addField(0); // 33 定額深夜
		//残業金額
		$zangyogoukei1 = $this->Format->chg_integer($heijitsuzangyo_nashi) * $basetanka100 + $this->Format->chg_integer($heijitsuzangyo) * $basetanka125; // 12-平日残業
//2021-06-04
		if($data['User']['rolemasters_id']<=28){
			$zangyogoukei2 = $this->Format->chg_integer($heijitsushinya) * $basetanka025; // 13-平日深夜割増
			$zangyogoukei3 = $this->Format->chg_integer($kyujitsuzangyo) * $basetanka125; // 14-休日残業
			$zangyogoukei4 = $this->Format->chg_integer($kyujitsushinya) * $basetanka025; // 15-休日深夜割増
			$zangyogoukei5 = $this->Format->chg_integer($houteikyujitsu) * $basetanka135; // 16-法定休日残業
			$zangyogoukei6 = $this->Format->chg_integer($houteikyujitsushinya) * $basetanka025; // 17-法定休日深夜割増
		}else{
			//2021-06-07 定額よりも深夜が多い場合
			if($this->Format->chg_integer($zangyototal_shinya) > $this->Format->chg_integer($total_teigakuzangyoshinya)){
				$zangyogoukei2 = $this->Format->chg_integer($heijitsushinya) * $basetanka025; // 13-平日深夜割増
				$zangyogoukei3 = 0; // 14-休日残業
				$zangyogoukei4 = $this->Format->chg_integer($kyujitsushinya) * $basetanka025; // 15-休日深夜割増
				$zangyogoukei5 = 0; // 16-法定休日残業
				$zangyogoukei6 = $this->Format->chg_integer($houteikyujitsushinya) * $basetanka025; // 17-法定休日深夜割増
			}else{
				$zangyogoukei2 = 0; // 13-平日深夜割増
				$zangyogoukei3 = 0; // 14-休日残業
				$zangyogoukei4 = 0; // 15-休日深夜割増
				$zangyogoukei5 = 0; // 16-法定休日残業
				$zangyogoukei6 = 0; // 17-法定休日深夜割増
			}
		}

		$this->Csv->addField(round($zangyogoukei1)); //
		$this->Csv->addField(round($zangyogoukei2)); //
		$this->Csv->addField(round($zangyogoukei3)); //
		$this->Csv->addField(round($zangyogoukei4)); //
		$this->Csv->addField(round($zangyogoukei5)); //
		$this->Csv->addField(round($zangyogoukei6)); //
//歩合割増計算
		$sou_roudou = $kinmu_jikan_kei;
		if($heijitsuzangyo_ori_kei){
			switch($data['User']['roudouflg']){
				case 1: //正社員
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
					$syokutakutime = $hours.':'.$minutes.':00';
					$dd = $this->Format->Addtime($syokutakutime,$sou_roudou);
					break;
				case 4: //季節
					$dd = $sou_roudou;
					break;
			}

			//歩合割増計算
			$total_zangyo = $sou_zan_sa = '00:00';
			
			$cc = ($untin_buai_kei + $soukoukiro_kei);
			//2023-03-31 
			$cc = $soukoukiro_kei;
			//2023-03-31 深夜分を追加 かつ法定、法定外も加算（元は入れてなかった）
			$total_zangyo = $this->Format->Addtime($heijitsuzangyo_ori_kei,$heijitsushinya_ori_kei);
			//60hを超えているかを判断
			if($heijitsuzangyo_ori_kei >= '60:00'){
				$sou_zan_sa = $this->Format->Addtime($heijitsuzangyo_ori_kei,'-60:00');
			}

			//$dd = $this->Format->Addtime('173:30',$heijitsuzangyo_ori_kei);
			$times2 = array();
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
	
		}else{
			$result = 0;
		}

		$this->Csv->addField($result); // 18 歩合割増
		$this->Csv->addField($this->Format->chg_time($jyoumushinyahour_total)); // 夜勤歩合時間
		$this->Csv->addField($total_teigakuzangyojikan); // 定額時間外歩合時間
		$this->Csv->addField($total_teigakuzangyoshinya); // 定額深夜歩合時間
		$this->Csv->addField(round($this->Format->chg_integer($heijitsuzangyo_ori_kei),2)); // 実際の残業時間
		$this->Csv->addField(round($this->Format->chg_integer($heijitsushinya_ori_kei),2)); // 実際の深夜残業時間
		$this->Csv->addField($tokukyu); // 7-特休日数
		//交通費（非課税、課税）
		$kingaku=0;
		switch($data['User']['yusyu']){
		case 1: //レギュラー
			if($data['User']['nenpi']>0){
				//50円未満の人は強制的に50円にする
				//2022-05-09 roundをceilに変更かつ計算後の値を切り上げに。
				$traffictanka = 0;
				$traffictanka = ceil((($data['User']['katamichi']*2)/$data['User']['nenpi']) * $kaisyadata['Kaisya']['regularprice']);

				if($traffictanka < 50){
					$kingaku = $syukkinday * 50;
				}else{
					$kingaku = $syukkinday * ceil((($data['User']['katamichi']*2)/$data['User']['nenpi']) * $kaisyadata['Kaisya']['regularprice']);
				}
			}
			break;
		case 2: //軽油
			if($data['User']['nenpi']>0){
				//50円未満の人は強制的に50円にする
				//2022-04-09 dieselpriceではなくkeiyuprice
				$traffictanka = 0;
				$traffictanka = ceil((($data['User']['katamichi']*2)/$data['User']['nenpi']) * $kaisyadata['Kaisya']['keiyuprice']);
				if($traffictanka < 50){
					$kingaku = $syukkinday * 50;
				}else{
					$kingaku = $syukkinday * ceil((($data['User']['katamichi']*2)/$data['User']['nenpi']) * $kaisyadata['Kaisya']['keiyuprice']);
				}
			}
			break;
		case 3: //電気の場合ー＞出勤日数 x (往復の距離÷燃費) x 会社マスタの燃料単価
			if($data['User']['nenpi']>0){
				//50円未満の人は強制的に50円にする
				$traffictanka = 0;
				$traffictanka = ceil((($data['User']['katamichi']*2) * $data['User']['nenpi']) * $kaisyadata['Kaisya']['electroprice']);
				if($traffictanka < 50){
					$kingaku = $syukkinday * 50;
				}else{
					$kingaku = $syukkinday * ceil((($data['User']['katamichi']*2) * $data['User']['nenpi']) * $kaisyadata['Kaisya']['electroprice']);
				}
			}
			break;
		case 4: //水素の場合ー＞出勤日数 x (往復の距離÷燃費) x 会社マスタの燃料単価
			if($data['User']['nenpi']>0){
				//50円未満の人は強制的に50円にする
				$traffictanka = 0;
				$traffictanka = ceil((($data['User']['katamichi']*2) * $data['User']['nenpi']) * $kaisyadata['Kaisya']['hydrogenprice']);
				if($traffictanka < 50){
					$kingaku = $syukkinday * 50;
				}else{
					$kingaku = $syukkinday * ceil((($data['User']['katamichi']*2) * $data['User']['nenpi']) * $kaisyadata['Kaisya']['hydrogenprice']);
				}
			}
			break;
		case 5: //自転車の場合ー＞出勤日数 x 会社マスタの自転車単価
			$kingaku = $syukkinday * $kaisyadata['Kaisya']['bicycleprice'];
			break;
		default:
			$kingaku = 0;
		}
		//課税非課税計算
		$hikazeikoutuhi = 0;
		$kazeikoutuhi = 0;
		$katamichi = 0;
		$katamichi = $data['User']['katamichi'];
		switch($katamichi){
			case $katamichi >= 55:
				if($kingaku >= 38700){
					$kazeikoutuhi = $kingaku - 38700;
					$hikazeikoutuhi = 38700;
				}else{
					$hikazeikoutuhi = $kingaku;
				}
				break;
			case $katamichi >= 45 && $katamichi < 55:
				if($kingaku >= 32300){
					$kazeikoutuhi = $kingaku - 32300;
					$hikazeikoutuhi = 32300;
				}else{
					$hikazeikoutuhi = $kingaku;
				}
				break;
			case $katamichi >= 35 && $katamichi < 45:
				if($kingaku >= 25900){
					$kazeikoutuhi = $kingaku - 25900;
					$hikazeikoutuhi = 25900;
				}else{
					$hikazeikoutuhi = $kingaku;
				}
				break;
			case $katamichi >= 25 && $katamichi < 35:
				if($kingaku >= 19700){
					$kazeikoutuhi = $kingaku - 19700;
					$hikazeikoutuhi = 19700;
				}else{
					$hikazeikoutuhi = $kingaku;
				}
				break;
			case $katamichi >= 15 && $katamichi < 25:
				if($kingaku >= 13500){
					$kazeikoutuhi = $kingaku - 13500;
					$hikazeikoutuhi = 13500;
				}else{
					$hikazeikoutuhi = $kingaku;
				}
				break;
			case $katamichi >= 10 && $katamichi < 15:
				if($kingaku >= 7300){
					$kazeikoutuhi = $kingaku - 7300;
					$hikazeikoutuhi = 7300;
				}else{
					$hikazeikoutuhi = $kingaku;
				}
				break;
			case $katamichi >= 2 && $katamichi < 10:
				if($kingaku >= 4200){
					$kazeikoutuhi = $kingaku - 4200;
					$hikazeikoutuhi = 4200;
				}else{
					$hikazeikoutuhi = $kingaku;
				}
				break;
			case $katamichi < 2:
				$kazeikoutuhi = $kingaku;
		}
		$this->Csv->addField($hikazeikoutuhi); //非課税交通費
		$this->Csv->addField($kazeikoutuhi);  //課税交通費
		//2022-04-27 add
		$this->Csv->addField($jyoumushinya2);  //乗務深夜
		//2022-12-01 add
		$this->Csv->addField($guard_1);  //守衛1
		$this->Csv->addField($guard_2);  //守衛2
		$this->Csv->addField($guard_3);  //守衛3

		$shinyazangyo = $mins = $half_mins = $half_mins_h = $half_mins_m = 0;
		$tArry=[];
		if($total_teigakuzangyoshinya <> '00:00'){
			$tArry=explode(":",$total_teigakuzangyoshinya);
			$hour=$tArry[0]*60;//時間→分
			$mins=$hour+$tArry[1];
			$half_mins = round($mins / 2);
			$half_mins_h = floor($half_mins / 60);
			$half_mins_m = $half_mins - ($half_mins_h * 60);
		}
		$this->Csv->addField($half_mins_h.':'.$half_mins_m);  //60超
		$this->Csv->addField($half_mins_h.':'.$half_mins_m);  //60超残業

		$half_teigakushinya1 = $half_teigakushinya2 = 0;
		$half_teigakushinya1 = $w_half;
		$half_teigakushinya2 = $w_half_shinya;
		$this->Csv->addField($half_teigakushinya1);  //60歩合深夜残業
		$this->Csv->addField($half_teigakushinya2);  //60深夜残業
		$this->Csv->addField($jyoumushinya2time);  //乗務時間(数値)
		$this->Csv->addField($sou_zan_sa); //残業時間から６０をひいたもの：残業差
		//20250627 add
		$this->Csv->addField($kyouiku_time);  //教育時間
		
		$this->Csv->endRow();
	  }
	}

	$this->Csv->setFilename($filename);
	echo $this->Csv->render(true, 'sjis', 'utf-8');
?>
