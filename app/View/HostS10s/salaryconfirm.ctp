<?php
	$paramsDate->modify('+1 months');
	$this->Csv->addrow($th);
//テーブル読み込み
	$datacheck = 0;
	$calenderusermaster = ClassRegistry::init('Calendarusermaster');

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
		$heijitsuzangyo_nashi = $heijitsuzangyo = $heijitsushinya = $kyujitsuzangyo = $kyujitsushinya = $houteikyujitsu = $houteikyujitsushinya = $heijitsuzangyo_goukei = '00:00';
		$chisou = $kyugyo = $sagyoteate = $teigakujikangai = $teigakushinya = $untin_buai_kei = $koukadou_teate = $soukoukiro = $soukoukiro_kei = $chokyori_teate = $syaryou_teate = $jyoumu_teate = $trailer_teate = $chain_teate = $shiharaisuryo = 0;
		$heijitsushinya_ori_kei = $heijitsuzangyo_ori_kei = $kinmu_jikan_kei = $sou_hei_zan = $kinmu_jikan = '00:00';

	$times = explode(":",'15:30');
    $hour = $times[0];
    $second = round( $times[1] / 60, 3);
    $chisou = ( $hour + $second) * 1000;

		echo $chisou;
		exit;
		$this->Csv->addField($syukkin); // 5-出勤日数
		$this->Csv->addField($kekkin); // 6-欠勤日数
		$this->Csv->addField($daitokukyu); // 7-代休特休日数
		$this->Csv->addField($kyujitsusyukkin); // 8-休日出勤日数
		$this->Csv->addField($yukyu); // 9-有給消化日数
		$this->Csv->addField($chisou); // 10-遅刻早退回数
		$this->Csv->addField('00:00'); // 11-遅刻早退時間

		$this->Csv->addField($heijitsuzangyo_goukei); // 12-平日残業
		$this->Csv->addField($heijitsushinya); // 13-平日深夜
		$this->Csv->addField($kyujitsuzangyo); // 14-休日残業
		$this->Csv->addField($kyujitsushinya); // 15-休日深夜
		$this->Csv->addField($houteikyujitsu); // 16-法定休日残業
		$this->Csv->addField($houteikyujitsushinya); // 17-法定休日深夜
		$this->Csv->addField($soukoukiro); // 19-（ｋｍ　距離歩合

		$this->Csv->addField('0'); // 20 個別歩合
		$this->Csv->addField('0'); // 21 電話歩合
		$this->Csv->addField('0'); // 22 夜勤歩合
		$this->Csv->addField('0'); // 23 通常残業（事務兼運転手の）
		$this->Csv->addField('0'); // 24 乗務残業
		$this->Csv->addField('0'); // 25 資格歩合
		$this->Csv->addField('0'); // 26 法定外歩合
		$this->Csv->addField('0'); // 27 法定休日歩合
		$this->Csv->addField('0'); // 28 休業時間 （休業日数＊7.75)
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
		$this->Csv->addField($gaskingaku); // 29 ガソリン代
		$this->Csv->addField($shiteikyu); // 　30 指定休日数
		$this->Csv->addField($kinmu_jikan_kei); // 31 総労働時間
		$this->Csv->addField($teigakujikangai); // 32 定額時間外
		$this->Csv->addField($teigakushinya); // 33 定額深夜割増
		$this->Csv->addField(''); //
		$this->Csv->addField(''); //

		$this->Csv->addField(''); // なし（未設定）
/*
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
		$this->Csv->addField($result); // 18 歩合割増
*/

		$this->Csv->endRow();
	  }
	}

	$this->Csv->setFilename($filename);
	echo $this->Csv->render(true, 'sjis', 'utf-8');
