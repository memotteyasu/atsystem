<?php
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
		'fields'=>[
			'Kaisya.gasprice',
			'Kaisya.dieselprice',
			'Kaisya.oilprice'
		],
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
	  $basetanka100 = $data['User']['basetanka'];
	  $basetanka125 = $data['User']['basetanka'] * 1.25;
	  $basetanka025 = $data['User']['basetanka'] * 0.25;
	  $basetanka135 = $data['User']['basetanka'] * 1.35;

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

		$syukkin = $kekkin = $yukyu = $tokukyu = $daikyu = $kyujitsusyukkin = 0;
		$daitokukyu = 0;
		$total_teigakuzangyojikan = $total_teigakuzangyoshinya = $teigakuzangyojikan = $teigakuzangyoshinya = $zangyototal = $so_heijitsuzangyo = $so_heijitsushinya = $chisou_kei = $chisou_sa = $heijitsuzangyo_nashi = $heijitsuzangyo = $heijitsushinya = $kyujitsuzangyo = $kyujitsushinya = $houteikyujitsu = $houteikyujitsushinya = $heijitsuzangyo_goukei = '00:00';
		$jyoumushinyahour_total = $hougaikingaku = $houteikingaku = $kyujitsu_cnt = $shikakukingaku = $telkingaku = $jyoumukingaku = $zangyokingaku = $jyoumushinyakingaku = $sagyoteate2_kei = $shiteikyu = $chisou = $kyugyo = $sagyoteate = $teigakujikangai = $teigakushinya = $untin_buai_kei = $koukadou_teate = $soukoukiro = $soukoukiro_kei = $jyoumu_teate = 0;
		$heijitsushinya_ori_kei = $heijitsuzangyo_ori_kei = $kinmu_jikan_kei = $sou_hei_zan = $kinmu_jikan = '00:00';
		foreach ($data['Kintaitran'] as $kintaitran) {
			switch ($kintaitran['flgmasters_id']) {
				case 11: // 出勤
				case 15: // 待機は出勤と同じ
				case 18: // 教育は出勤と同じ
				case 22: // 検診は出勤と同じ
					$syukkin = $syukkin + 1;
					if($kintaitran['rinjicalendarflg']==1){
						$kyujitsu_cnt = $kyujitsu_cnt + 1;
					}
					if($kintaitran['rinjicalendarflg']==2){
						$kyujitsu_cnt = $kyujitsu_cnt + 1;
					}
					break;
				case 12: // 欠勤
				case 23: // 傷病欠勤
				case 24: // 労災欠勤
					$kekkin = $kekkin + 1 ;
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
					$chisou = $chisou + 1;
					//正社員の事務職だけ定時から労働時間を引いたものを集計
					if($data['User']['roudouflg']==1){
						$chisou_sa = $this->Format->Subtime_csv('7:45',$kintaitran['kinmu_jikan']);
						$chisou_kei = $this->Format->Addtime($chisou_kei,$chisou_sa);
					}else{
						$chisou_kei = '00:00';
					}
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
			$heijitsuzangyo_ori_kei = $this->Format->Addtime($heijitsuzangyo_ori_kei,$kintaitran['kyujitsuzangyo']);
			$heijitsuzangyo_ori_kei = $this->Format->Addtime($heijitsuzangyo_ori_kei,$kintaitran['houteikyujitsu']);
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
			if($workprice2data){
				if($kintaitran['rinjicalendarflg']==1){ //法定外休日
					$teigakuzangyojikan = $kintaitran['kyujitsuzangyo'];
				}
				if($kintaitran['rinjicalendarflg']==2){ //法定休日
					$teigakuzangyojikan = $kintaitran['houteikyujitsu'];
				}
				if($kintaitran['rinjicalendarflg']==0){ //通常
					$teigakuzangyojikan = $this->Format->Addtime($workprice2data['Workprice2']['zangyojikan'],'00:00:00');
					$teigakuzangyoshinya = $this->Format->Addtime($workprice2data['Workprice2']['shinyajikan'],'00:00:00');
				}
				$total_teigakuzangyojikan = $this->Format->Addtime($total_teigakuzangyojikan,$teigakuzangyojikan);
				$total_teigakuzangyoshinya = $this->Format->Addtime($total_teigakuzangyoshinya,$teigakuzangyoshinya);
			}
			$soukoukiro_kei = $soukoukiro_kei + $kintaitran['soukoukiro'];
			$teigakujikangai = $teigakujikangai + $kintaitran['teigakujikangai'];
			$teigakushinya = $teigakushinya + $kintaitran['teigakushinya'];
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
			}
			//深夜歩合
			if($kintaitran['jyoumushinyaflg']==1){
				$jyoumushinyakingaku = $jyoumushinyakingaku + ($jyoumushinyahour * $basetanka025);
				$jyoumushinyahour_total = $jyoumushinyahour_total + $jyoumushinyahour;
			}
			$hougaikingaku = $hougaikingaku + $kintaitran['hougaikingaku'];
			$houteikingaku = $houteikingaku + $kintaitran['houteikingaku'];
		}
		$this->Csv->addField($syukkin - $kyujitsu_cnt); // 5-出勤日数 - 休日のカウント
		$this->Csv->addField($kekkin); // 6-欠勤日数
		$this->Csv->addField($kyugyo); // 7-休業日数
		$this->Csv->addField($daikyu); // 7-代休日数
		$this->Csv->addField($kyujitsu_cnt); // 8-休日出勤日数
		$this->Csv->addField($yukyu); // 9-有給消化日数
		$this->Csv->addField($chisou); // 10-遅刻早退回数
		$this->Csv->addField($chisou_kei); // 11-遅刻早退時間

		$so_heijitsuzangyo = $this->Format->Addtime($heijitsuzangyo_nashi,$heijitsuzangyo);
		$so_heijitsuzangyo = $this->Format->Addtime($so_heijitsuzangyo,$kyujitsuzangyo);
		$so_heijitsuzangyo = $this->Format->Addtime($so_heijitsuzangyo,$houteikyujitsu);
		$so_heijitsushinya = $this->Format->Addtime($so_heijitsushinya,$heijitsushinya);
		$so_heijitsushinya = $this->Format->Addtime($so_heijitsushinya,$kyujitsushinya);
		$so_heijitsushinya = $this->Format->Addtime($so_heijitsushinya,$houteikyujitsushinya);
		$this->Csv->addField($so_heijitsuzangyo); // 12-平日残業（法定休日、法定外休日も合算）
		$this->Csv->addField($so_heijitsushinya); // 13-平日深夜割増（法定休日、法定外休日も合算）
//2021-06-04		$this->Csv->addField($heijitsushinya); // 13-平日深夜割増
		$this->Csv->addField(0);
//2021-06-04		$this->Csv->addField($kyujitsuzangyo); // 14-休日残業
		$this->Csv->addField(0);
//2021-06-04		$this->Csv->addField($kyujitsushinya); // 15-休日深夜割増
//2021-06-04		$this->Csv->addField($houteikyujitsu); // 16-法定休日残業
		$this->Csv->addField(0);
//2021-06-04		$this->Csv->addField($houteikyujitsushinya); // 17-法定休日深夜割増
		$this->Csv->addField(0);
		$this->Csv->addField($soukoukiro_kei); // 19-（ｋｍ　距離歩合 4月は少ない数字で給与を払っているかも（soukoukiro)を代入してた。

		$this->Csv->addField($sagyoteate2_kei); // 20 個別歩合
		//電話歩合計算
		$this->Csv->addField(round($telkingaku)); // 21 電話歩合
		$this->Csv->addField(round($jyoumushinyakingaku)); // 22 夜勤歩合
		$this->Csv->addField(round($zangyokingaku)); // 23 通常残業（事務兼運転手の）
		$this->Csv->addField(round($jyoumukingaku)); // 24 乗務残業
		if($shikakuhour>0){
			$shikakukingaku = $shikakuhour * $basetanka125;
		}else{
			$shikakukingaku = 0;
		}
		$this->Csv->addField(round($shikakukingaku)); // 25 資格歩合
		$this->Csv->addField($hougaikingaku); // 26 法定外歩合
		$this->Csv->addField($houteikingaku); // 27 法定休日歩合
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
		if($data['User']['rolemasters_id']==28){
			//事務職兼運転手の場合は１０％にする
			$this->Csv->addField(round($teigakujikangai*0.1)); // 32 定額時間外
			$this->Csv->addField(round($teigakushinya*0.1)); // 33 定額深夜割増
		}else{
			$this->Csv->addField($teigakujikangai); // 32 定額時間外
			$this->Csv->addField($teigakushinya); // 33 定額深夜
		}
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
			$zangyogoukei2 = $this->Format->chg_integer($heijitsushinya) * $basetanka025; // 13-平日深夜割増
			$zangyogoukei3 = 0; // 14-休日残業
			$zangyogoukei4 = $this->Format->chg_integer($kyujitsushinya) * $basetanka025; // 15-休日深夜割増
			$zangyogoukei5 = 0; // 16-法定休日残業
			$zangyogoukei6 = $this->Format->chg_integer($houteikyujitsushinya) * $basetanka025; // 17-法定休日深夜割増
		}

		$this->Csv->addField(round($zangyogoukei1)); //
		$this->Csv->addField(round($zangyogoukei2)); //
		$this->Csv->addField(round($zangyogoukei3)); //
		$this->Csv->addField(round($zangyogoukei4)); //
		$this->Csv->addField(round($zangyogoukei5)); //
		$this->Csv->addField(round($zangyogoukei6)); //

		if($heijitsuzangyo_ori_kei){
			$cc = ($untin_buai_kei + $soukoukiro_kei);
			$dd = $this->Format->Addtime('173:30',$heijitsuzangyo_ori_kei);
	$times2 = array();
			$times2 = explode(":",$dd);
			$hour2 = $times2[0];
			$second2 = round($times2[1] / 60, 3);
			$gg = $hour2 + $second2;
			$tanka = ($cc / $gg) * 0.25;
	$times1 = array();
			$times1 = explode(":",$heijitsuzangyo_ori_kei);
			$hour1 = $times1[0];
			$second1 = round($times1[1] / 60, 3);
			$result = floor(($hour1 + $second1) * $tanka);
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

		$this->Csv->endRow();
	  }
	}

	$this->Csv->setFilename($filename);
	echo $this->Csv->render(true, 'sjis', 'utf-8');
?>
