<?php
App::uses('AppController', 'Controller');
/**
 * Host002bs Controller
 * バックアップ勤怠用
 */
class Host002bsController extends AppController {
    public $layout = 'layout_host';
    public $helpers = ['Format','Html','Form','Session','Js' => ['Jquery']];
    public $uses = array('User','Kaisya','Branchmaster','Departmentmaster','Kintaitran','Flgmaster','Holiday','Ordertran','Processtran','Calendarmaster','Calendarusermaster','Workplaceteate','Workprice2','UsersEvaluationmaster','Summertime','Kobetsuteatemaster','KintaitranKobetsuteatemaster','Kousokutran');
    public $components = ['Search.Prg','RequestHandler','Timediff','SelectData'];

    public function beforeFilter(){
        parent::beforeFilter();
        $this->page = 'Host002bs';
    }

    public function isAuthorized($user){
        if (isset($user['rolemasters_id'])) {
            switch($user['rolemasters_id']){
                case 10: //管理者
                case 11: //管理者
                case 12: //管理者
                case 20: //事務職（石油）
                case 21: //事務職（化学品）
                case 22: //事務職（ガス）
                case 23: //事務職（コンテナ）
                case 24: //事務職（全て）
                case 25: //事務職（化学品コンテナ）
                case 26: //事務職（）
                case 27: //事務職（）
                case 28: //事務職兼運転手
                $this->set('user', $user);
                return true;
                break;
            }
        }
        return false;
    }
    /*
 * index method
 */
	public function index($did = null) {
	    $flglist = [
//	        '0' => '単独　',
	        '1' => '当日合算　',
//	        '2' => '翌日合算　',
//	        '3' => '前日合算　'
	    ];
	    $page = $this->page;
		$branchmasters_id = $this->branchmasters_id;
		$branchmasters = $this->SelectData->branch_select_conditions_kintai($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);
		$departmentmasters_id = $this->Auth->user('departmentmasters_id');

		$date = new DateTime();

		if ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				if ($this->Session->check('BackData2.date')) {
					$date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				}
				if ($this->Session->check('BackData2.bid')) {
					$branchmasters_id = $this->Session->read('BackData2.bid');
				}
				if ($this->Session->check('BackData2.did')) {
					if ($this->Session->read('BackData2.did') == 0) {
						$departmentmasters_id = null;
					} else {
						$departmentmasters_id = $this->Session->read('BackData2.did');
					}
				}
			}
			$this->Session->delete('BackData2');
		} elseif($this->Session->check('BackData1')){
			if ($this->Session->read('BackData1.Page') == $page) {
				if ($this->Session->check('BackData1.date')) {
					$date = $this->SelectData->date_select($this->Session->read('BackData1.date'));
				}
				if ($this->Session->check('BackData1.bid')) {
					$branchmasters_id = $this->Session->read('BackData1.bid');
				}
				if ($this->Session->check('BackData1.did')) {
					if ($this->Session->read('BackData1.did') == 0) {
						$departmentmasters_id = null;
					} else {
						$departmentmasters_id = $this->Session->read('BackData1.did');
					}
				}
			}
		}

		$flgmasters = $this->Flgmaster->flgchk(1);
		$this->set('flgmasters',$flgmasters);
/*
		if ($this->branchmasters_id == 1) {
			$branchmasters = $this->Branchmaster->BranchList_kintai($this->kaisyas_id);
		} else {
			$branchmasters = $this->Branchmaster->find('list', [
				'fields' => ['id','branchname'],
				'conditions' => [
					'kaisyas_id' => $this->kaisyas_id,
					'id' => $this->branchmasters_id
				],
				'order' => ['id' => 'asc']
			]);
		}
*/
		$departmentmasters = $this->Departmentmaster->SelectDepartmentList($this->kaisyas_id);
		$this->set(compact('branchmasters','departmentmasters'));

		if ($this->request->is('post')) {
			// $date = $this->SelectData->date_select($this->request->data['selectym1']);
			$date = new DateTime($this->request->data('selectym1'));
			$branchmasters_id = $this->request->data('branchmasters_id');
			$departmentmasters_id = $this->request->data('departmentmasters_id');

			if (isset($this->request->data['lastweek'])) {
				$date->modify('-1 weeks');
			}
			if (isset($this->request->data['nextweek'])) {
				$date->modify('+1 weeks');
			}
			$backdatas = ['date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')],'bid' => $branchmasters_id, 'did' => $departmentmasters_id, 'Page' => $page];
			$this->Session->write('BackData1', $backdatas);
			return $this->redirect(['action' => 'index']);
		}
		$paramsDate = $date->format('Y-m-d');
		$paramsDate2 = $date->modify('+6 day')->format('Y-m-d');
		$week = ['日','月','火','水','木','金','土'];
		$this->set(compact('paramsDate','week','departmentmasters_id','branchmasters_id'));

		$users = $this->SelectData->data_select_conditionsHost002bs($paramsDate,$paramsDate2,$branchmasters_id,$departmentmasters_id,$this->kaisyas_id);
		$this->set('users', $users);
		$this->set('did',$did);
	}

/*
 * pdf method
 */
	public function pdf() {
		$flgmasters = $this->Flgmaster->flgchk(1);
		$firstday = new DateTime('first day of '.$this->params['named']['kyy'].'-'.$this->params['named']['kmm']);
		$lastday = new DateTime('last day of '.$this->params['named']['kyy'].'-'.$this->params['named']['kmm']);
		$this->set(compact('flgmasters','firstday','lastday'));
		$departmentmasters_id = $this->params['named']['did'];
		$branchmasters_id = $this->params['named']['bid'];

		if ($departmentmasters_id == 0) {
			$dconditions = [];
		} else {
			$dconditions = ['User.departmentmasters_id' => $departmentmasters_id];
		}
		if ($branchmasters_id == 0) {
			$bconditions = [];
		} else {
			$bconditions = ['User.branchmasters_id' => $branchmasters_id];
		}

		$this->set('users', $this->User->find('all', [
			'order' => [
				'User.id' => 'asc',
			],
			'fields' => [
				'User.id',
				'User.usernameK',
			],
			'conditions' => [
				$dconditions,
				$bconditions
			],
			'contain' => [
				'Kintaitran' => [
					'conditions' => [
						'AND' => [
							['Kintaitran.kintaidate >= ' => $firstday->format('Y-m-d')],
							['Kintaitran.kintaidate <= ' => $lastday->format('Y-m-d')],
						]
					],
				]
			],
			//'recursive' => 0
		]));

		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';
	}

/*
 * add method
 */
	public function add() {
		$this->edit();
		$this->render('edit');
	}


	public function edit() {
	    $rinjicalendar = ['0'=>'通常','1'=>'法定外休日','2'=>'法定休日'];
	    $this->set('rinjicalendar',$rinjicalendar);

        if ($this->Session->check('BackData1')) {
            $backdata2 = $this->Session->read('BackData1');
			$this->Session->write('BackData2', $backdata2);
        } else {
			$date = new DateTime();
			$backdata2 = [
				'date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')],
				'bid' => $this->branchmasters_id,
				'did' => $this->Auth->user('departmentmasters_id'),
				'Page' => $this->page,
			];
			$this->Session->write('BackData2', $backdata2);
		}
        //個別手当マスタ読み込み
        $kobetsuteatemasters = $this->Kobetsuteatemaster->Selectlist($this->Auth->user('kaisyas_id'));
        $this->set('kobetsuteatemasters',$kobetsuteatemasters);

        //kintaitran_kobetsuteatemaster読み込み
        if(isset($this->params['named']['keyid'])){
            $kintaitrans_kobetsuteatemasters = $this->KintaitranKobetsuteatemaster->find('all',[
                'fields'=>[
                    'KintaitranKobetsuteatemaster.kintaitrans_id',
                    'Kobetsuteatemaster.kobetsukilo',
                    'Kobetsuteatemaster.teatename'
                ],
                'conditions'=>[
                    'KintaitranKobetsuteatemaster.kintaitrans_id'=>$this->params['named']['keyid']
                ]
            ]);
            $this->set('kintaitranskobetsuteatemasters',$kintaitrans_kobetsuteatemasters);
        }else{
            $this->set('kintaitranskobetsuteatemasters','');
        }

	    $paramsDate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
	    $flglist = [
//	        '0' => '単独　',
	        '1' => '当日合算　',
//	        '2' => '翌日合算　',
//	        '3' => '前日合算　'
	    ];
	    $comment_gokei = null;
	    $this->set('flglist',$flglist);
		$guardlist = [
	        '1' => '守衛平日',
			'2' => '守衛日祝前　',
	        '3' => '守衛日祝　',
		];
	    $this->set('guardlist',$guardlist);
	    $users = $this->User->findById($this->params['named']['uid'],null,null,0);
	    $this->set('userdata', $users);
	    $flgmasters = $this->Flgmaster->flgchk(1);
	    $this->set('flgmasters', $flgmasters);

	    $uid = $this->params['named']['uid'];
	    $week = ['日','月','火','水','木','金','土'];
	    $this->set('date',$paramsDate);
	    $this->set('week',$week);
	    $calen = $this->Calendarusermaster->usercalenlist($paramsDate,$users['User']['calendarflg']);
	    $this->set('calen',$calen);

	    /* 選択した社員の部署の定時時間取得 */
	    $mmchk = $this->params['named']['kmm'];
	    // summmertimes読み込み
	    $summer = $this->Summertime->find('first',[
	        'conditions'=>[
	            'Summertime.kaisyas_id'=>$this->kaisyas_id,
	            'Summertime.branchmasters_id' => $users['User']['branchmasters_id'],
	            'Summertime.departmentmasters_id' => $users['User']['departmentmasters_id'],
	        ]
		]);
		$teiji = null;
		$date1 = null;
		$date2 = null;
        if ($summer) {
            if (($summer['Summertime']['summerstartmonth'] <= $mmchk)and($summer['Summertime']['summerendmonth'] >= $mmchk)) {
                // サマータイムのとき
                $teiji = $summer['Summertime']['summertermtime'];
                $date1 = $summer['Summertime']['summerstarttime'];
                $date2 = $summer['Summertime']['summerendtime'];
                $this->Session->write('BackData2.season', 'summer');
            } else {
                $teiji = $summer['Summertime']['termtime'];
                $date1 = $summer['Summertime']['starttime'];
                $date2 = $summer['Summertime']['endtime'];
                $this->Session->write('BackData2.season', 'winter');
            }
        }
        /*
         * 2021-11-01 パートの場合はusersのzangyocounttimeを使う
		 * 20240331 嘱託(2)も追加
         */
        if(($users['User']['roudouflg']==3)||($users['User']['roudouflg']==2)){ //パート
            $teiji = $users['User']['zangyocounttime'];
        }

	    $teiji = substr($teiji,0,5);
	    $this->set('teiji',$teiji);
	    $teiji_sec = $this->Timediff->sec_change($teiji);

	    //自分が運転手のデータを抽出
	    $processtrandriver1 = $this->Processtran->SelectProcesstrandriver1($paramsDate,$uid);
	    $this->set('processtrandriver1',$processtrandriver1);
	    //自分が助手のデータを抽出
	    $processtrandriver2 = $this->Processtran->SelectProcesstrandriver2($paramsDate,$uid);
	    $this->set('processtrandriver2',$processtrandriver2);

	    if ($this->request->is(['patch', 'post', 'put'])) {
	        $teigakujikangai_base = $teigakushinya_base = $z_jikan = 0;
	        $teigakujikangai_base = $users['User']['basetanka'] * 1.25;
	        $teigakushinya_base = $users['User']['basetanka'] * 0.25;
            $this->request->data['Kintaitran']['kintaidate'] = $paramsDate;

            //2020-11-04 法定外休日、法定休日のカムラを０クリア
            $this->request->data['Kintaitran']['kyujitsuzangyo']='00:00';
            $this->request->data['Kintaitran']['kyujitsushinya']='00:00';
            $this->request->data['Kintaitran']['houteikyujitsu']='00:00';
            $this->request->data['Kintaitran']['houteikyujitsushinya']='00:00';
            $this->request->data['Kintaitran']['heijitsuzangyo_nashi']='00:00';
            $this->request->data['Kintaitran']['heijitsuzangyo']='00:00';
            $this->request->data['Kintaitran']['heijitsushinya']='00:00';
            $this->request->data['Kintaitran']['heijitsuzangyo_ori']='00:00';
            $this->request->data['Kintaitran']['heijitsushinya_ori']='00:00';
            $this->request->data['Kintaitran']['teigakujikangai']=0;
            $this->request->data['Kintaitran']['teigakushinya']=0;
            $this->request->data['Kintaitran']['hougaikingaku']=0;
            $this->request->data['Kintaitran']['houteikingaku']=0;
            //2020-11-04 法定外休日、法定休日のカムラを０クリア

	        switch($this->request->data['Kintaitran']['flgmasters_id']){
	            case '11': // 出勤
				case '45': // 守衛
	            case '21': // 遅刻早退
    	            // ここから
    	            $shinyazan_sec = 0;
    	            $so_kinmu_jikan = $kinmu_jikan = '00:00';
    	            /* saveする前に残業計算をする */
    	            /* タコメータの時間から、前後15分(900)追加した時間を出退勤時間にセット（労務用 */
    	            if(($users['User']['departmentmasters_id']==5)||($users['User']['departmentmasters_id']==6)){ //事務職用 20230201 守衛も追加した
    	                $startdatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['datestart'],$this->request->data['Kintaitran']['timestart']);
    	                $enddatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['dateend'],$this->request->data['Kintaitran']['timeend']);
    	                $this->request->data['Kintaitran']['tako_datestart'] = $this->request->data['Kintaitran']['datestart'];
    	                $this->request->data['Kintaitran']['tako_dateend'] = $this->request->data['Kintaitran']['dateend'];
    	                $this->request->data['Kintaitran']['tako_timestart'] = $this->request->data['Kintaitran']['timestart'];
    	                $this->request->data['Kintaitran']['tako_timeend'] = $this->request->data['Kintaitran']['timeend'];
    	                $tako_startdatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_datestart'],$this->request->data['Kintaitran']['tako_timestart']);
    	                $tako_enddatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_dateend'],$this->request->data['Kintaitran']['tako_timeend']);
    	                $sd = $this->Timediff->time_diff_minus($startdatetime,0);
	                    $ed = $this->Timediff->time_diff_add($enddatetime,0);
    	                $so_kinmu_jikan_sec = $ed - $sd;
    	                $so_kinmu_jikan = $this->Timediff->his_change($so_kinmu_jikan_sec);
    	                $this->request->data['Kintaitran']['so_kinmu_jikan'] = $so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'];
    	                $kinmu_jikan = $this->Timediff->subvtime($so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'].':'.$so_kinmu_jikan['s'],$this->request->data['Kintaitran']['kyukeitime']);
    	                //28用のワーク
    	                $so_kinmu_jikan_28 = $this->request->data['Kintaitran']['so_kinmu_jikan'];
    	                $kinmu_jikan_28 = $kinmu_jikan;

    	                $this->request->data['Kintaitran']['kinmu_jikan'] = $kinmu_jikan;
    	                $kinmu_jikan_sec = $this->Timediff->sec_change($kinmu_jikan);
    	                if($startdatetime > $enddatetime){
    	                    $this->Flash->error('開始より終了の日時が小さいです。（事務）');
    	                    return;
    	                }
    	            }else{
    	                //運転手の場合
        	            $tako_startdatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_datestart'],$this->request->data['Kintaitran']['tako_timestart']);
        	            $tako_enddatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_dateend'],$this->request->data['Kintaitran']['tako_timeend']);
    	                $sd = $this->Timediff->time_diff_minus($tako_startdatetime,900);
    	                $ed = $this->Timediff->time_diff_add($tako_enddatetime,900);
    	                // 終了より開始が大きい場合エラーとする
    	                if($tako_startdatetime > $tako_enddatetime){
    	                    $this->Flash->error('開始より終了の日時が小さいです。（乗務員）');
    	                    return;
    	                }
    	            }
    	            // 深夜計算用
    	            $shinya1t = '00:00';
    	            $shinya2t = '00:00';
    	            $shinya3t = '00:00';

    	            //１枚目のタコメーター時間
    	            $this->request->data['Kintaitran']['datestart'] = date('Y-m-d',$sd);
    	            $this->request->data['Kintaitran']['timestart'] = date('H:i',$sd);
    	            $this->request->data['Kintaitran']['dateend'] = date('Y-m-d',$ed);
    	            $this->request->data['Kintaitran']['timeend'] = date('H:i',$ed);
                    //深夜計算用ワーク
    	            $w22 = new DateTime($this->request->data['Kintaitran']['datestart'].' 22:00:00');
    	            $w22n = new DateTime($this->request->data['Kintaitran']['dateend'].' 22:00:00');
    	            $w5n = new DateTime($this->request->data['Kintaitran']['dateend'].' 5:00:00');
    	            $w5 = new DateTime($this->request->data['Kintaitran']['datestart'].' 5:00:00');
    	            $w0 = new DateTime($this->request->data['Kintaitran']['datestart'].' 00:00:00');

    	            $roumu_kousoku_sec = $ed - $sd;
    	            $roumu_kousoku = $this->Timediff->his_change($roumu_kousoku_sec);
    	            $this->request->data['Kintaitran']['roumu_kousoku'] = $roumu_kousoku['h'].':'.$roumu_kousoku['i'];
    	            $total_roumu_kousoku = $roumu_kousoku['h'].':'.$roumu_kousoku['i'];
    	            // 深夜の計算
    	            $shinya1a = $shinya1b = $shinya1c = $shinya1d = $shinya1e = [];
    	            $sdt1 = new DateTime($this->request->data['Kintaitran']['datestart'].' '.$this->request->data['Kintaitran']['timestart']);
    	            $edt1 = new DateTime($this->request->data['Kintaitran']['dateend'].' '.$this->request->data['Kintaitran']['timeend']);

    	            if($this->request->data['Kintaitran']['datestart']==$this->request->data['Kintaitran']['dateend']){
    	                //開始と終了が同じ日
    	                if(($this->request->data['Kintaitran']['timestart'] >= '00:00')and($this->request->data['Kintaitran']['timeend'] <= '05:00')){
    	                    $shinya1a = $edt1->diff($sdt1);
    	                }
    	                if(($this->request->data['Kintaitran']['timestart'] >= '00:00')and($this->request->data['Kintaitran']['timestart'] < '05:00')and($this->request->data['Kintaitran']['timeend'] > '05:00')and($this->request->data['Kintaitran']['timeend'] <= '22:00')){
    	                    $shinya1b = $w5->diff($sdt1);
    	                }
    	                if(($this->request->data['Kintaitran']['timestart'] >= '00:00')and($this->request->data['Kintaitran']['timestart'] < '05:00')and($this->request->data['Kintaitran']['timeend'] > '05:00')and($this->request->data['Kintaitran']['timeend'] > '22:00')){
    	                    $shinya1b = $w5->diff($sdt1);
    	                    $shinya1c = $edt1->diff($w22);
    	                }else{
    	                    if($this->request->data['Kintaitran']['timeend'] > '22:00'){
    	                        $shinya1c = $edt1->diff($w22);
    	                    }
    	                }
    	                if($shinya1a){
    	                    $shinya1t = $shinya1a->h.':'.$shinya1a->i;
    	                }
    	                if($shinya1b){
    	                    $shinya1t = $this->Timediff->addvtime($shinya1t,$shinya1b->h.':'.$shinya1b->i);
    	                }
    	                if($shinya1c){
    	                    $shinya1t = $this->Timediff->addvtime($shinya1t,$shinya1c->h.':'.$shinya1c->i);
    	                }
    	            }else{
    	                // 開始と終了が違う日
    	                if(($this->request->data['Kintaitran']['timestart'] >= '22:00')and($this->request->data['Kintaitran']['timeend'] <= '05:00')){
    	                    $shinya1a = $edt1->diff($sdt1);
    	                }
    	                if(($this->request->data['Kintaitran']['timestart'] >= '22:00')and($this->request->data['Kintaitran']['timeend'] > '05:00')){
    	                    $shinya1b = $w5n->diff($sdt1);
    	                }
    	                if(($this->request->data['Kintaitran']['timestart'] < '22:00')and($this->request->data['Kintaitran']['timeend'] > '05:00')){
    	                    $shinya1c = $w5n->diff($w22);
    	                }
    	                if(($this->request->data['Kintaitran']['timestart'] < '22:00')and($this->request->data['Kintaitran']['timeend'] <= '05:00')){
    	                    $shinya1d = $edt1->diff($w22);
    	                }
    	                if($shinya1a){
    	                    $shinya1t = $shinya1a->h.':'.$shinya1a->i;
    	                }
    	                if($shinya1b){
    	                    $shinya1t = $this->Timediff->addvtime($shinya1t,$shinya1b->h.':'.$shinya1b->i);
    	                }
    	                if($shinya1c){
    	                    $shinya1t = $this->Timediff->addvtime($shinya1t,$shinya1c->h.':'.$shinya1c->i);
    	                }
    	                if($shinya1d){
    	                    $shinya1t = $this->Timediff->addvtime($shinya1t,$shinya1d->h.':'.$shinya1d->i);
    	                }
    	            }

    	            // ２枚めのタコメータ時間の入力があった場合
    	            if($this->request->data['Kintaitran']['tako_timestart2']){
    	                $tako_startdatetime2 = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_datestart2'],$this->request->data['Kintaitran']['tako_timestart2']);
    	                $tako_enddatetime2 = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_dateend2'],$this->request->data['Kintaitran']['tako_timeend2']);
    	                $sd2 = $this->Timediff->time_diff_minus($tako_startdatetime2,900);
    	                $ed2 = $this->Timediff->time_diff_add($tako_enddatetime2,900);
    	                // 終了より開始が大きい場合エラーとする
    	                if($tako_startdatetime2 > $tako_enddatetime2){
    	                    $this->Flash->error('開始より終了の日時が小さいです。2枚目（乗務員）');
    	                    return;
    	                }
    	                $this->request->data['Kintaitran']['datestart2'] = date('Y-m-d',$sd2);
    	                $this->request->data['Kintaitran']['timestart2'] = date('H:i',$sd2);
    	                $this->request->data['Kintaitran']['dateend2'] = date('Y-m-d',$ed2);
    	                $this->request->data['Kintaitran']['timeend2'] = date('H:i',$ed2);
    	                $roumu_kousoku_sec2 = $ed2 - $sd2;
    	                $roumu_kousoku2 = $this->Timediff->his_change($roumu_kousoku_sec2);
    	                $total_roumu_kousoku = $this->Timediff->addvtime($this->request->data['Kintaitran']['roumu_kousoku'],$roumu_kousoku2['h'].':'.$roumu_kousoku2['i']);
    	                $this->request->data['Kintaitran']['roumu_kousoku'] = $total_roumu_kousoku;
    	                // 深夜の計算
    	                $shinya2a = $shinya2b = $shinya2c = $shinya2d = $shinya2e = [];
    	                $sdt2 = new DateTime($this->request->data['Kintaitran']['datestart2'].' '.$this->request->data['Kintaitran']['timestart2']);
    	                $edt2 = new DateTime($this->request->data['Kintaitran']['dateend2'].' '.$this->request->data['Kintaitran']['timeend2']);

    	                if($this->request->data['Kintaitran']['datestart2']==$this->request->data['Kintaitran']['dateend2']){
    	                    //開始と終了が同じ日
    	                    if(($this->request->data['Kintaitran']['timestart2'] >= '00:00')and($this->request->data['Kintaitran']['timeend2'] <= '05:00')){
    	                        $shinya2a = $edt2->diff($sdt2);
    	                    }
    	                    if(($this->request->data['Kintaitran']['timestart2'] >= '00:00')and($this->request->data['Kintaitran']['timestart2'] < '05:00')and($this->request->data['Kintaitran']['timeend2'] > '05:00')and($this->request->data['Kintaitran']['timeend2'] <= '22:00')){
    	                        $shinya2b = $w5n->diff($sdt2);
    	                    }
    	                    if(($this->request->data['Kintaitran']['timestart2'] >= '00:00')and($this->request->data['Kintaitran']['timestart2'] < '05:00')and($this->request->data['Kintaitran']['timeend2'] > '05:00')and($this->request->data['Kintaitran']['timeend2'] > '22:00')){
    	                        $shinya2b = $w5n->diff($sdt2);
    	                        $shinya2c = $edt2->diff($w22);
    	                    }else{
    	                        if($this->request->data['Kintaitran']['timeend2'] > '22:00'){
    	                            $shinya2c = $edt2->diff($w22);
    	                        }
    	                    }
    	                    if($shinya2a){
    	                        $shinya2t = $shinya2a->h.':'.$shinya2a->i;
    	                    }
    	                    if($shinya2b){
    	                        $shinya2t = $this->Timediff->addvtime($shinya2t,$shinya2b->h.':'.$shinya2b->i);
    	                    }
    	                    if($shinya2c){
    	                        $shinya2t = $this->Timediff->addvtime($shinya2t,$shinya2c->h.':'.$shinya2c->i);
    	                    }
    	                }else{
    	                    // 開始と終了が違う日
    	                    if(($this->request->data['Kintaitran']['timestart2'] >= '22:00')and($this->request->data['Kintaitran']['timeend2'] <= '05:00')){
    	                        $shinya2a = $edt2->diff($sdt2);
    	                    }
    	                    if(($this->request->data['Kintaitran']['timestart2'] >= '22:00')and($this->request->data['Kintaitran']['timeend2'] > '05:00')){
    	                        $shinya2b = $w5n->diff($sdt2);
    	                    }
    	                    if(($this->request->data['Kintaitran']['timestart2'] < '22:00')and($this->request->data['Kintaitran']['timeend2'] > '05:00')){
    	                        $shinya2c = $w5n->diff($w22);
    	                    }
    	                    if(($this->request->data['Kintaitran']['timestart2'] < '22:00')and($this->request->data['Kintaitran']['timeend2'] <= '05:00')){
    	                        $shinya2d = $edt2->diff($w22);
    	                    }
    	                    if($shinya2a){
    	                        $shinya2t = $shinya2a->h.':'.$shinya2a->i;
    	                    }
    	                    if($shinya2b){
    	                        $shinya2t = $this->Timediff->addvtime($shinya2t,$shinya2b->h.':'.$shinya2b->i);
    	                    }
    	                    if($shinya2c){
    	                        $shinya2t = $this->Timediff->addvtime($shinya2t,$shinya2c->h.':'.$shinya2c->i);
    	                    }
    	                    if($shinya2d){
    	                        $shinya2t = $this->Timediff->addvtime($shinya2t,$shinya2d->h.':'.$shinya2d->i);
    	                    }
    	                }

    	            }
    	            // 3枚めのタコメータ時間の入力があった場合
    	            if($this->request->data['Kintaitran']['tako_timestart3']){
    	                $tako_startdatetime3 = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_datestart3'],$this->request->data['Kintaitran']['tako_timestart3']);
    	                $tako_enddatetime3 = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_dateend3'],$this->request->data['Kintaitran']['tako_timeend3']);
    	                $sd3 = $this->Timediff->time_diff_minus($tako_startdatetime3,900);
    	                $ed3 = $this->Timediff->time_diff_add($tako_enddatetime3,900);
    	                // 終了より開始が大きい場合エラーとする
    	                if($tako_startdatetime3 > $tako_enddatetime3){
    	                    $this->Flash->error('開始より終了の日時が小さいです。3枚目（乗務員）');
    	                    return;
    	                }
    	                $this->request->data['Kintaitran']['datestart3'] = date('Y-m-d',$sd3);
    	                $this->request->data['Kintaitran']['timestart3'] = date('H:i',$sd3);
    	                $this->request->data['Kintaitran']['dateend3'] = date('Y-m-d',$ed3);
    	                $this->request->data['Kintaitran']['timeend3'] = date('H:i',$ed3);
    	                $roumu_kousoku_sec3 = $ed3 - $sd3;
    	                $roumu_kousoku3 = $this->Timediff->his_change($roumu_kousoku_sec3);
    	                $total_roumu_kousoku = $this->Timediff->addvtime($this->request->data['Kintaitran']['roumu_kousoku'],$roumu_kousoku3['h'].':'.$roumu_kousoku3['i']);
    	                $this->request->data['Kintaitran']['roumu_kousoku'] = $total_roumu_kousoku;
    	                // 深夜の計算
    	                $shinya3a = $shinya3b = $shinya3c = $shinya3d = $shinya3e = [];
    	                $sdt3 = new DateTime($this->request->data['Kintaitran']['datestart3'].' '.$this->request->data['Kintaitran']['timestart3']);
    	                $edt3 = new DateTime($this->request->data['Kintaitran']['dateend3'].' '.$this->request->data['Kintaitran']['timeend3']);

    	                if($this->request->data['Kintaitran']['datestart3']==$this->request->data['Kintaitran']['dateend3']){
    	                    //開始と終了が同じ日
    	                    if(($this->request->data['Kintaitran']['timestart3'] >= '00:00')and($this->request->data['Kintaitran']['timeend3'] <= '05:00')){
    	                        $shinya3a = $edt3->diff($sdt3);
    	                    }
    	                    if(($this->request->data['Kintaitran']['timestart3'] >= '00:00')and($this->request->data['Kintaitran']['timestart3'] < '05:00')and($this->request->data['Kintaitran']['timeend3'] > '05:00')and($this->request->data['Kintaitran']['timeend3'] <= '22:00')){
    	                        $shinya3b = $w5n->diff($sdt3);
    	                    }
    	                    if(($this->request->data['Kintaitran']['timestart3'] >= '00:00')and($this->request->data['Kintaitran']['timestart3'] < '05:00')and($this->request->data['Kintaitran']['timeend3'] > '05:00')and($this->request->data['Kintaitran']['timeend3'] > '22:00')){
    	                        $shinya3b = $w5n->diff($sdt3);
    	                        $shinya3c = $edt3->diff($w22);
    	                    }else{
    	                        if($this->request->data['Kintaitran']['timeend3'] > '22:00'){
    	                            $shinya3c = $edt3->diff($w22);
    	                        }
    	                    }
    	                    if($shinya3a){
    	                        $shinya3t = $shinya3a->h.':'.$shinya3a->i;
    	                    }
    	                    if($shinya3b){
    	                        $shinya3t = $this->Timediff->addvtime($shinya3t,$shinya3b->h.':'.$shinya3b->i);
    	                    }
    	                    if($shinya3c){
    	                        $shinya3t = $this->Timediff->addvtime($shinya3t,$shinya3c->h.':'.$shinya3c->i);
    	                    }
    	                }else{
    	                    // 開始と終了が違う日
    	                    if(($this->request->data['Kintaitran']['timestart3'] >= '22:00')and($this->request->data['Kintaitran']['timeend3'] <= '05:00')){
    	                        $shinya3a = $edt3->diff($sdt3);
    	                    }
    	                    if(($this->request->data['Kintaitran']['timestart3'] >= '22:00')and($this->request->data['Kintaitran']['timeend3'] > '05:00')){
    	                        $shinya3b = $w5n->diff($sdt3);
    	                    }
    	                    if(($this->request->data['Kintaitran']['timestart3'] < '22:00')and($this->request->data['Kintaitran']['timeend3'] > '05:00')){
    	                        $shinya3c = $w5n->diff($w22);
    	                    }
    	                    if(($this->request->data['Kintaitran']['timestart3'] < '22:00')and($this->request->data['Kintaitran']['timeend3'] <= '05:00')){
    	                        $shinya3d = $edt3->diff($w22);
    	                    }
    	                    if($shinya3a){
    	                        $shinya3t = $shinya3a->h.':'.$shinya3a->i;
    	                    }
    	                    if($shinya3b){
    	                        $shinya3t = $this->Timediff->addvtime($shinya3t,$shinya3b->h.':'.$shinya3b->i);
    	                    }
    	                    if($shinya3c){
    	                        $shinya3t = $this->Timediff->addvtime($shinya3t,$shinya3c->h.':'.$shinya3c->i);
    	                    }
    	                    if($shinya3d){
    	                        $shinya3t = $this->Timediff->addvtime($shinya3t,$shinya3d->h.':'.$shinya3d->i);
    	                    }
    	                }
    	            }
    	            if(($users['User']['rolemasters_id'] >= 28)&&($users['User']['rolemasters_id']<=39)){ //運転手用,事務職兼運転手の場合
        	            $w_km = $w_km1 = $w_km2 = $w_km3 = $w_km4 = $w_today_km = $w_km_yoku = $w_km_zen = 0;
        	            $w_sagyoteate1 = $w_sagyoteate2 = $w_sagyoteate3 = $w_sagyoteate4 = 0;
     	                $originaltime = $w_soukou_h = $w_sagyou_h = $w_taiki_h = $w_kyukei_h = $so_kinmu_jikan = $kinmu_jikan = '00:00';
        	            $pin_zangyojikan = $w_zangyojikan1 = $w_zangyojikan2 = $w_zangyojikan3 = $w_zangyojikan4 = '00:00';
        	            $zangyojikan_gokei = $shinyajikan_gokei = $pin_shinyajikan = $w_shinyajikan1 = $w_shinyajikan2 = $w_shinyajikan3 = $w_shinyajikan4 = '00:00';
        	            $sagyoteate_gokei = $teigakujikangai_gokei = $teigakushinya_gokei = $untinbuai_gokei = 0;
        	            $w_teigakujikangai1 = $w_teigakujikangai2 = $w_teigakujikangai3 = $w_teigakujikangai4 = 0;
        	            $w_teigakushinya1 = $w_teigakushinya2 = $w_teigakushinya3 = $w_teigakushinya4 = 0;
        	            $w_untinbuai1 = $w_untinbuai2 = $w_untinbuai3 = $w_untinbuai4 = 0;
        	            $km_gokei = 0;
        	            //タコ１枚め
        	            if($this->request->data['Kintaitran']['tako1_soukou_h']){
        	                $w_soukou_h = $this->Timediff->addvtime($w_soukou_h,$this->request->data['Kintaitran']['tako1_soukou_h']);
        	            }
        	            if($this->request->data['Kintaitran']['tako1_sagyou_h']){
        	                $w_sagyou_h = $this->Timediff->addvtime($w_sagyou_h,$this->request->data['Kintaitran']['tako1_sagyou_h']);
        	            }
        	            if($this->request->data['Kintaitran']['tako1_taiki_h']){
        	                $w_taiki_h = $this->Timediff->addvtime($w_taiki_h,$this->request->data['Kintaitran']['tako1_taiki_h']);
        	            }
        	            if($this->request->data['Kintaitran']['tako1_km_e']){
        	               $w_km1 = $this->request->data['Kintaitran']['tako1_km_e'] - $this->request->data['Kintaitran']['tako1_km_s'];
        	            }
        	            switch($this->request->data['Kintaitran']['tako1flg']){
        	                case 0: //単独の場合は、都度作業手当を読み込む
        	                    $sagyoteate1 = $this->Workprice2->Selectprice($w_km1,$users['User']['kaisyas_id']);
            	                if($sagyoteate1){
            	                    $w_sagyoteate1 = 0;
            	                    $z_jikan = 0;
            	                    $z_jikan = $this->Timediff->chg_integer($sagyoteate1['Workprice2']['zangyojikan']);
            	                    $w_teigakujikangai1 = $teigakujikangai_base * $z_jikan;
            	                    $w_teigakushinya1 = $teigakushinya_base * $z_jikan;
            	                    $w_untinbuai1 = 0;
            	                    $w_zangyojikan1 = $sagyoteate1['Workprice2']['zangyojikan'];
            	                    $w_shinyajikan1 = $sagyoteate1['Workprice2']['shinyajikan'];
            	                }
            	                $w_km += $w_km1;
            	                break;
        	                case 2: //翌日合算
        	                    $w_km_yoku += $w_km1;
        	                    break;
        	                case 3: //前日合算
            	                $w_km_zen += $w_km1;
            	                break;
        	                case 1: //当日合算
        	                    $w_today_km += $w_km1;
            	                break;
        	            }
        	            //タコ2枚め
        	            if($this->request->data['Kintaitran']['tako2_soukou_h']){
        	                $w_soukou_h = $this->Timediff->addvtime($w_soukou_h,$this->request->data['Kintaitran']['tako2_soukou_h']);
        	            }
        	            if($this->request->data['Kintaitran']['tako2_sagyou_h']){
        	                $w_sagyou_h = $this->Timediff->addvtime($w_sagyou_h,$this->request->data['Kintaitran']['tako2_sagyou_h']);
        	            }
        	            if($this->request->data['Kintaitran']['tako2_taiki_h']){
        	                $w_taiki_h = $this->Timediff->addvtime($w_taiki_h,$this->request->data['Kintaitran']['tako2_taiki_h']);
        	            }
        	            if($this->request->data['Kintaitran']['tako2_km_e']){
        	            $w_km2 = $this->request->data['Kintaitran']['tako2_km_e'] - $this->request->data['Kintaitran']['tako2_km_s'];
        	            }
        	            switch($this->request->data['Kintaitran']['tako2flg']){
        	                case 0: //単独の場合は、都度作業手当を読み込む
        	                $sagyoteate2 = $this->Workprice2->Selectprice($w_km2,$users['User']['kaisyas_id']);
            	                if($sagyoteate2){
            	                    $w_sagyoteate2 = 0;
            	                    $z_jikan = 0;
            	                    $z_jikan = $this->Timediff->chg_integer($sagyoteate2['Workprice2']['zangyojikan']);
            	                    $w_teigakujikangai2 = $teigakujikangai_base * $z_jikan;
            	                    $w_teigakushinya2 = $teigakushinya_base * $z_jikan;
            	                    $w_untinbuai2 = 0;
            	                    $w_zangyojikan2 = $sagyoteate2['Workprice2']['zangyojikan'];
            	                    $w_shinyajikan2 = $sagyoteate2['Workprice2']['shinyajikan'];
            	                }
            	                $w_km += $w_km2;
            	                break;
        	                case 2: //翌日合算
        	                    $w_km_yoku += $w_km2;
        	                    break;
        	                case 3: //前日合算
            	                $w_km_zen += $w_km2;
            	                break;
        	                case 1: //当日合算
            	                $w_today_km += $w_km2;
            	                break;
        	            }
        	            //タコ3枚め
        	            if($this->request->data['Kintaitran']['tako3_soukou_h']){
        	                $w_soukou_h = $this->Timediff->addvtime($w_soukou_h,$this->request->data['Kintaitran']['tako3_soukou_h']);
        	            }
        	            if($this->request->data['Kintaitran']['tako3_sagyou_h']){
        	                $w_sagyou_h = $this->Timediff->addvtime($w_sagyou_h,$this->request->data['Kintaitran']['tako3_sagyou_h']);
        	            }
        	            if($this->request->data['Kintaitran']['tako3_taiki_h']){
        	                $w_taiki_h = $this->Timediff->addvtime($w_taiki_h,$this->request->data['Kintaitran']['tako3_taiki_h']);
        	            }
        	            if($this->request->data['Kintaitran']['tako3_km_e']){
        	            $w_km3 = $this->request->data['Kintaitran']['tako3_km_e'] - $this->request->data['Kintaitran']['tako3_km_s'];
        	            }
        	            switch($this->request->data['Kintaitran']['tako3flg']){
        	                case 0: //単独の場合は、都度作業手当を読み込む
        	                $sagyoteate3 = $this->Workprice2->Selectprice($w_km3,$users['User']['kaisyas_id']);
            	                if($sagyoteate3){
            	                    $w_sagyoteate3 = 0;
            	                    $z_jikan = 0;
            	                    $z_jikan = $this->Timediff->chg_integer($sagyoteate3['Workprice2']['zangyojikan']);
            	                    $w_teigakujikangai3 = $teigakujikangai_base * $z_jikan;
            	                    $w_teigakushinya3 = $teigakushinya_base * $z_jikan;
            	                    $w_untinbuai3 = 0;
            	                    $w_zangyojikan3 = $sagyoteate3['Workprice2']['zangyojikan'];
            	                    $w_shinyajikan3 = $sagyoteate3['Workprice2']['shinyajikan'];
            	                }
            	                $w_km += $w_km3;
            	                break;
        	                case 2: //翌日合算
        	                    $w_km_yoku += $w_km3;
        	                    break;
        	                case 3: //前日合算
            	                $w_km_zen += $w_km3;
            	                break;
        	                case 1: //当日合算
            	                $w_today_km += $w_km3;
            	                break;
        	            }
        	            // 合計を合計用カラムに代入
        	            if($w_soukou_h){
        	                $this->request->data['Kintaitran']['tako_gokei_soukou_h'] = $w_soukou_h;
        	            }
        	            if($w_sagyou_h){
        	                $this->request->data['Kintaitran']['tako_gokei_sagyou_h'] = $w_sagyou_h;
        	            }
        	            if($w_taiki_h){
        	                $this->request->data['Kintaitran']['tako_gokei_taiki_h'] = $w_taiki_h;
        	            }
                        //単独の場合の作業手当の合計
                        $pin_teigakujikangai = $w_teigakujikangai1 + $w_teigakujikangai2 + $w_teigakujikangai3 + $w_teigakujikangai4;
                        $pin_teigakushinya = $w_teigakushinya1 + $w_teigakushinya2 + $w_teigakushinya3 + $w_teigakushinya4;
                        $pin_untinbuai = $w_untinbuai1 + $w_untinbuai2 + $w_untinbuai3 + $w_untinbuai4;
                        $pin_sagyoteate = $w_sagyoteate1 + $w_sagyoteate2 + $w_sagyoteate3 + $w_sagyoteate4;
                        //作業歩合テーブルの残業時間合計
                        $zangyojikan_gokei = $this->Timediff->addvtime($zangyojikan_gokei, $w_zangyojikan1);
                        $zangyojikan_gokei = $this->Timediff->addvtime($zangyojikan_gokei, $w_zangyojikan2);
                        $zangyojikan_gokei = $this->Timediff->addvtime($zangyojikan_gokei, $w_zangyojikan3);
						//          $zangyojikan_gokei = $this->Timediff->addvtime($zangyojikan_gokei, $w_zangyojikan4);
                        $shinyajikan_gokei = $this->Timediff->addvtime($shinyajikan_gokei, $w_shinyajikan1);
                        $shinyajikan_gokei = $this->Timediff->addvtime($shinyajikan_gokei, $w_shinyajikan2);
                        $shinyajikan_gokei = $this->Timediff->addvtime($shinyajikan_gokei, $w_shinyajikan3);
						//         $shinyajikan_gokei = $this->Timediff->addvtime($shinyajikan_gokei, $w_shinyajikan4);

                        $teigakujikangai_gokei += $pin_teigakujikangai;
                        $teigakushinya_gokei += $pin_teigakushinya;
                        $untinbuai_gokei += $pin_untinbuai;
                        $sagyoteate_gokei += $pin_sagyoteate;
                        if($w_km){
                            $km_gokei += $w_km;
                        }
                        //当日合算の合計
                        if($w_today_km){
                            $km_gokei += $w_today_km;
                            $todaysagyoteate = $this->Workprice2->Selectprice($w_today_km,$users['User']['kaisyas_id']);
                            if($todaysagyoteate){
                                $today_sagyoteate = 0;
                                $z_jikan = $this->Timediff->chg_integer($todaysagyoteate['Workprice2']['zangyojikan']);
                                $today_teigakujikangai = $teigakujikangai_base * $z_jikan;
                                $today_teigakushinya = $teigakushinya_base * $z_jikan;
                                $today_untinbuai = 0;

                                // 加算していたので代入に変更
                                $teigakujikangai_gokei = $today_teigakujikangai;
                                $teigakushinya_gokei = $today_teigakushinya;
                                $untinbuai_gokei = $today_untinbuai;
                                $sagyoteate_gokei = $today_sagyoteate;
                                $zangyojikan_gokei = $todaysagyoteate['Workprice2']['zangyojikan'];
                                $shinyajikan_gokei = $todaysagyoteate['Workprice2']['shinyajikan'];
                            }
                        }

                        $this->request->data['Kintaitran']['tako_gokei_km'] = $km_gokei;
                        // キロ単価を取得
                        $kilotanka = 0;
                        $kilotanka = $this->UsersEvaluationmaster->Selectkilotanka($users['User']['id'],$this->request->data['Kintaitran']['tako_datestart']);
                        if($users['User']['departmentmasters_id'] == 5){ //事務職は走行歩合をつけない
                            $this->request->data['Kintaitran']['soukoukiro'] = 0;
                        }else{
                            if($kilotanka){
                                $this->request->data['Kintaitran']['soukoukiro'] = $km_gokei * $kilotanka['UsersEvaluationmaster']['evaluationkeisu'];
                            }else{
                                debug('キロ単価が登録されていません。社員管理から登録してください。');
                                exit;
                            }
                        }
						//20240331 定額時間外から15分だけ残業金額(割増なし）を減額する
						// teigakushinya_baseと同じ金額（0.25倍）,これに15分を掛けた金額を減額＜ー川崎の嘱託は定時を７：３０にしたので、
						// 30分(0.5)が1.0で計算する
						//20240416 金子さんたちの権限の条件を追加。運転したときだけ減額するようにした。
						if($paramsDate >= '2024-04-01'){
							if(($users['User']['rolemasters_id']==28)and($this->request->data['Kintaitran']['jyoumuflg']==0)){
								$this->request->data['Kintaitran']['teigakujikangai'] = $teigakujikangai_gokei;
							}else{
								//roudouflgで減額する時間を判断(嘱託２and川崎、パート３)
								if(($users['User']['roudouflg']==2)and($users['User']['branchmasters_id']==3)){
									$gengaku = floor($teigakushinya_base * 0.5);
								}else{
									$gengaku = floor($teigakushinya_base * 0.25);
								}
								//20250124 出勤で法定外、法定休日の場合は減額しない（距離が０の場合）
								if($km_gokei > 0){
									$teigakujikangai_gokei = $teigakujikangai_gokei - $gengaku;
								}
								$this->request->data['Kintaitran']['teigakujikangai'] = $teigakujikangai_gokei;
							}
						}else{
							$this->request->data['Kintaitran']['teigakujikangai'] = $teigakujikangai_gokei;
						}
                        $this->request->data['Kintaitran']['teigakushinya'] = $teigakushinya_gokei;
                        /* 深夜時間のチェック 23時以降翌１時の間、稼働していたら、基礎単価 x 0.25 x 3.5h */
                        /* 取りやめ
                        if($shinyateate_sw>0){
                            $this->request->data['Kintaitran']['untinbuai'] = $shinyateate_sw * $users['User']['basetanka'];
                        }else{
                            $this->request->data['Kintaitran']['untinbuai'] = 0;
                        }
                        */
                        $this->request->data['Kintaitran']['sagyoteate'] = $teigakujikangai_gokei+$teigakushinya_gokei;
                        //$this->set('comment_gokei',$comment_gokei);
        	            //拘束時間（総勤務時間）
                        $so_kinmu_jikan = $this->Timediff->addvtime($so_kinmu_jikan,$w_soukou_h);
        	            $so_kinmu_jikan = $this->Timediff->addvtime($so_kinmu_jikan,$w_sagyou_h);
        	            $so_kinmu_jikan = $this->Timediff->addvtime($so_kinmu_jikan,$w_taiki_h);
        	            $kinmu_jikan = $so_kinmu_jikan;
						//休憩はないので。      $so_kinmu_jikan = $this->Timediff->addvtime($so_kinmu_jikan,$w_kyukei_h);
        	            /* 拘束時間に、前後15分計30分追加した時間を出退勤時間にセット */
        	            if($so_kinmu_jikan > 0){ //作業時間等があるかないか
        	                if($users['User']['rolemasters_id']==28){
        	                    $so_kinmu_jikan = $so_kinmu_jikan_28;
        	                    $kinmu_jikan = $kinmu_jikan_28;
        	                }else{
            	                $so_kinmu_jikan = $this->Timediff->addvtime($so_kinmu_jikan,'00:30:00');
            	                $kinmu_jikan = $this->Timediff->addvtime($kinmu_jikan,'00:30:00');
        	                }
        	                $this->request->data['Kintaitran']['so_kinmu_jikan'] = $so_kinmu_jikan;
            	            $this->request->data['Kintaitran']['kinmu_jikan'] = $kinmu_jikan;
            	            //運転手は拘束時間から労働時間を引いたものをセット・事務所兼運転手はkyukeitimeを加味
            	            if($users['User']['rolemasters_id'] == 28){
            	                $this->request->data['Kintaitran']['tako_gokei_kyukei_h'] = $this->request->data['Kintaitran']['kyukeitime'];
            	            }else{
            	                $this->request->data['Kintaitran']['tako_gokei_kyukei_h'] = $this->Timediff->subvtime($total_roumu_kousoku,$kinmu_jikan);
            	                $this->request->data['Kintaitran']['kyukeitime'] = $this->request->data['Kintaitran']['tako_gokei_kyukei_h'];
            	            }
        	            }else{
        	                //運転しなかった場合
        	                if($users['User']['rolemasters_id'] == 28){
        	                    $this->request->data['Kintaitran']['so_kinmu_jikan'] = $so_kinmu_jikan_28;
        	                    $this->request->data['Kintaitran']['kinmu_jikan'] = $kinmu_jikan_28;
        	                    $this->request->data['Kintaitran']['tako_gokei_kyukei_h'] = $this->request->data['Kintaitran']['kyukeitime'];
        	                }else{
        	                    //運転手が運転しなかった場合の
        	                    $this->request->data['Kintaitran']['so_kinmu_jikan'] = $so_kinmu_jikan;
        	                    $this->request->data['Kintaitran']['kinmu_jikan'] = $kinmu_jikan;
        	                    $this->request->data['Kintaitran']['tako_gokei_kyukei_h'] = $this->Timediff->subvtime($total_roumu_kousoku,$kinmu_jikan);
        	                    $this->request->data['Kintaitran']['kyukeitime'] = $this->request->data['Kintaitran']['tako_gokei_kyukei_h'];
        	                }
        	            }

        	            // 平日、休日、法定休日に分けて処理
        	            if($calen){
        	                //switch($calen['Calendarusermaster']['restflg']){
        	                //デフォルトはカレンダーマスタと同じだが、臨時に変える場合がある（乗り換え）
        	                switch($this->request->data['Kintaitran']['rinjicalendarflg']){
        	                   case 1: // 法定外休日
    	                           $chgtime = 0;
    	                           $selecthour = '00:00';
    	                           switch($km_gokei){
    	                               case 0:
    	                                   $chgtime = $this->Timediff->chg_integer('0:00');
    	                                   $selecthour = '0:00';
    	                                   break;
    	                               case $km_gokei > 0 && $km_gokei <= 100:
    	                                   $chgtime = $this->Timediff->chg_integer('4:00');
    	                                   $selecthour = '4:00';
    	                                   break;
    	                               case 101 <= $km_gokei && $km_gokei <= 200:
    	                                   $chgtime = $this->Timediff->chg_integer('4:30');
    	                                   $selecthour = '4:30';
    	                                   break;
    	                               case 201 <= $km_gokei && $km_gokei <= 300:
    	                                   $chgtime = $this->Timediff->chg_integer('5:00');
    	                                   $selecthour = '5:00';
    	                                   break;
    	                               case 301 <= $km_gokei && $km_gokei <= 400:
    	                                   $chgtime = $this->Timediff->chg_integer('5:30');
    	                                   $selecthour = '5:30';
    	                                   break;
    	                               case 401 <= $km_gokei && $km_gokei <= 500:
    	                                   $chgtime = $this->Timediff->chg_integer('6:00');
    	                                   $selecthour = '6:00';
    	                                   break;
    	                               case 501 <= $km_gokei && $km_gokei <= 600:
    	                                   $chgtime = $this->Timediff->chg_integer('6:30');
    	                                   $selecthour = '6:30';
    	                                   break;
    	                               case 601 <= $km_gokei && $km_gokei <= 700:
    	                                   $chgtime = $this->Timediff->chg_integer('7:00');
    	                                   $selecthour = '7:00';
    	                                   break;
    	                               case 701 <= $km_gokei:
    	                                   $chgtime = $this->Timediff->chg_integer('7:30');
    	                                   $selecthour = '7:30';
    	                                   break;
    	                           }
    	                           $this->request->data['Kintaitran']['hougaikingaku'] = $chgtime * $users['User']['basetanka'] * 1.25;
    	                           //        	                           $this->request->data['Kintaitran']['kyujitsuzangyo'] = $kinmu_jikan;
    	                           //事務職兼運転手の場合は、手当の時間を差し引いた時間をセット・運転手はそのまま
    	                           if($users['User']['rolemasters_id'] == 28){
									   if(($kinmu_jikan_28 > $selecthour)and($km_gokei>0)){
    	                               	$this->request->data['Kintaitran']['kyujitsuzangyo'] = $this->Timediff->subvtime($kinmu_jikan_28,$selecthour);
									   }else{
										   if($km_gokei>0){
											$this->request->data['Kintaitran']['kyujitsuzangyo'] = '00:00';
										   }else{
											$this->request->data['Kintaitran']['kyujitsuzangyo'] = $kinmu_jikan_28;
										   }
									   }
    	                               //残業手当明細に出力するために休日分の時間をoriに退避
    	                               $this->request->data['Kintaitran']['heijitsuzangyo_ori'] = $kinmu_jikan_28;
    	                           }else{
    	                               $this->request->data['Kintaitran']['kyujitsuzangyo'] = $selecthour;
    	                               //残業手当明細に出力するために休日分の時間をoriに退避
    	                               $this->request->data['Kintaitran']['heijitsuzangyo_ori'] = $selecthour;
    	                           }
    	                           //定時に関係なく深夜時間分の割増を算出
        	                        $shinyatotal = '00:00';
        	                        if($shinya1t){
        	                            $shinyatotal = $this->Timediff->addvtime($shinyatotal,$shinya1t);
        	                        }
        	                        if($shinya2t){
        	                            $shinyatotal = $this->Timediff->addvtime($shinyatotal,$shinya2t);
        	                        }
        	                        if($shinya3t){
        	                            $shinyatotal = $this->Timediff->addvtime($shinyatotal,$shinya3t);
        	                        }
        	                        //勤怠上の深夜時間を比較
        	                        if($shinyatotal > '00:00'){
        	                            /* 法定外休日は、深夜残業をまるまるつける。作業歩合マスタ分は引かない。
        	                            */
        	                            $this->request->data['Kintaitran']['kyujitsushinya'] = $shinyatotal;
        	                        }
        	                        break;
        	                    case 2: // 法定休日
    	                            $chgtime = 0;
    	                            $selecthour = '00:00';
    	                            switch($km_gokei){
    	                                case 0:
    	                                    $chgtime = $this->Timediff->chg_integer('0:00');
    	                                    $selecthour = '0:00';
    	                                    break;
    	                                case 1 <= $km_gokei && $km_gokei <= 100:
    	                                    $chgtime = $this->Timediff->chg_integer('4:00');
    	                                    $selecthour = '4:00';
    	                                    break;
    	                                case 101 <= $km_gokei && $km_gokei <= 200:
    	                                    $chgtime = $this->Timediff->chg_integer('4:30');
    	                                    $selecthour = '4:30';
    	                                    break;
    	                                case 201 <= $km_gokei && $km_gokei <= 300:
    	                                    $chgtime = $this->Timediff->chg_integer('5:00');
    	                                    $selecthour = '5:00';
    	                                    break;
    	                                case 301 <= $km_gokei && $km_gokei <= 400:
    	                                    $chgtime = $this->Timediff->chg_integer('5:30');
    	                                    $selecthour = '5:30';
    	                                    break;
    	                                case 401 <= $km_gokei && $km_gokei <= 500:
    	                                    $chgtime = $this->Timediff->chg_integer('6:00');
    	                                    $selecthour = '6:00';
    	                                    break;
    	                                case 501 <= $km_gokei && $km_gokei <= 600:
    	                                    $chgtime = $this->Timediff->chg_integer('6:30');
    	                                    $selecthour = '6:30';
    	                                    break;
    	                                case 601 <= $km_gokei && $km_gokei <= 700:
    	                                    $chgtime = $this->Timediff->chg_integer('7:00');
    	                                    $selecthour = '7:00';
    	                                    break;
    	                                case 701 <= $km_gokei:
    	                                    $chgtime = $this->Timediff->chg_integer('7:30');
    	                                    $selecthour = '7:30';
    	                                    break;
    	                            }
    	                            $this->request->data['Kintaitran']['houteikingaku'] = $chgtime * $users['User']['basetanka'] * 1.35;
    	                            if($users['User']['rolemasters_id'] == 28){
										if(($kinmu_jikan_28 > $selecthour)and($km_gokei>0)){
	    	                                $this->request->data['Kintaitran']['houteikyujitsu'] = $this->Timediff->subvtime($kinmu_jikan_28,$selecthour);
										}else{
											if($km_gokei>0){
												$this->request->data['Kintaitran']['houteikyujitsu'] = '00:00';
											}else{
												$this->request->data['Kintaitran']['houteikyujitsu'] = $kinmu_jikan_28;
											}
										}
    	                                //残業手当明細に出力するために休日分の時間をoriに退避
    	                                $this->request->data['Kintaitran']['heijitsuzangyo_ori'] = $kinmu_jikan_28;
    	                            }else{
    	                                $this->request->data['Kintaitran']['houteikyujitsu'] = $selecthour;
    	                                //残業手当明細に出力するために休日分の時間をoriに退避
    	                                $this->request->data['Kintaitran']['heijitsuzangyo_ori'] = $selecthour;
    	                            }
    	                        //定時に関係なく深夜時間分の割増を算出
        	                        $shinyatotal = '00:00';
        	                        if($shinya1t){
        	                            $shinyatotal = $this->Timediff->addvtime($shinyatotal,$shinya1t);
        	                        }
        	                        if($shinya2t){
        	                            $shinyatotal = $this->Timediff->addvtime($shinyatotal,$shinya2t);
        	                        }
        	                        if($shinya3t){
        	                            $shinyatotal = $this->Timediff->addvtime($shinyatotal,$shinya3t);
        	                        }
        	                        //勤怠上の深夜時間を比較
        	                        if($shinyatotal > '00:00'){
        	                            /*
        	                             //作業歩合マスタの深夜時間分の方が大きい場合
        	                            if($shinyatotal <= $shinyajikan_gokei){
        	                                $shinyatotal = '00:00';
        	                            }else{
        	                                $shinyatotal = $this->Timediff->subvtime($shinyatotal,$shinyajikan_gokei);
        	                            }
        	                            $this->request->data['Kintaitran']['hoteikyujitsushinya'] = $shinyatotal;
        	                            */
        	                            $this->request->data['Kintaitran']['houteikyujitsushinya'] = $shinyatotal;
        	                        }
        	                        break;
        	                    case 0: // 平日
        	                        //平日通常残業（割増あり）時間を計算（法定内残業時間を差し引く）
        	                        if($users['User']['rolemasters_id'] == 28){
        	                            if($kinmu_jikan_28 >= $teiji){
        	                                //法定時間より大きい場合、割増なし残業時間をセット
        	                                $zangyotime = $this->Timediff->subvtime($kinmu_jikan_28,$teiji);
        	                                //作業歩合マスタの残業時間より小さい場合はnull
        	                                if($zangyojikan_gokei >= $zangyotime){
        	                                    $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = '00:00';
        	                                    $this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00';
        	                                }else{
        	                                    $zangyotime = $this->Timediff->subvtime($zangyotime,$zangyojikan_gokei);
        	                                    $zangyonashi = $this->Timediff->subvtime('08:00',$teiji);
        	                                    if($zangyotime <= $zangyonashi){
        	                                        //法定時間内の場合
        	                                        //2021-06-03 $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = $zangyotime;
        	                                        $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = '00:00';
        	                                        $this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00';
        	                                    }else{
        	                                        //法定時間外の場合
        	                                        //2021-06-03 $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = $zangyonashi;
        	                                        //2021-06-03 $zangyoari = $this->Timediff->subvtime($zangyotime,$zangyonashi);
        	                                        //勤務時間から法定時間を引いたものを割増残業にセット
        	                                        //2021-06-03 $this->request->data['Kintaitran']['heijitsuzangyo'] = $zangyoari;
        	                                        $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = '00:00';
        	                                        $this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00';
        	                                    }
        	                                }
        	                            }

        	                        }else{
            	                        if($kinmu_jikan >= $teiji){
            	                        //法定時間より大きい場合、割増なし残業時間をセット
            	                            $zangyotime = $this->Timediff->subvtime($kinmu_jikan,$teiji);
            	                            //作業歩合マスタの残業時間より小さい場合はnull
            	                            if($zangyojikan_gokei >= $zangyotime){
            	                                $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = '00:00';
            	                                $this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00';
            	                            }else{
            	                                $zangyotime = $this->Timediff->subvtime($zangyotime,$zangyojikan_gokei);
            	                                $zangyonashi = $this->Timediff->subvtime('08:00',$teiji);
            	                                if($zangyotime <= $zangyonashi){
            	                                    //法定時間内の場合
            	                                    $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = $zangyotime;
            	                                    $this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00';
            	                                }else{
            	                                    //法定時間外の場合
            	                                    $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = $zangyonashi;

            	                                    $zangyoari = $this->Timediff->subvtime($zangyotime,$zangyonashi);
            	                                    //勤務時間から法定時間を引いたものを割増残業にセット
            	                                    $this->request->data['Kintaitran']['heijitsuzangyo'] = $zangyoari;
            	                                }
            	                            }
            	                        }
        	                        }
        	                        $heijitsuzangyo_ori = '00:00';
									//        	                        $calc_roumu_kousoku = $this->Timediff->subvtime($total_roumu_kousoku,$w_kyukei_h);
        	                        if($kinmu_jikan > $teiji){
									//        	                        if($calc_roumu_kousoku > $teiji){
        	                        //作業歩合マスタを引く前の時間で残業時間を持つ（運転手画面用）
        	                            $heijitsuzangyo_ori = $this->Timediff->subvtime($kinmu_jikan,$teiji);
        	                            $this->request->data['Kintaitran']['heijitsuzangyo_ori'] = $heijitsuzangyo_ori;
        	                        }else{
        	                            $this->request->data['Kintaitran']['heijitsuzangyo_ori'] = '00:00';
        	                        }
        	                        //定時に関係なく深夜時間分の割増を算出
        	                        $shinyatotal = '00:00';
        	                        if($shinya1t){
        	                            $shinyatotal = $this->Timediff->addvtime($shinyatotal,$shinya1t);
        	                        }
        	                        if($shinya2t){
        	                            $shinyatotal = $this->Timediff->addvtime($shinyatotal,$shinya2t);
        	                        }
        	                        if($shinya3t){
        	                            $shinyatotal = $this->Timediff->addvtime($shinyatotal,$shinya3t);
        	                        }
        	                        //勤怠上の深夜時間を比較
        	                        if($shinyatotal > '00:00'){
        	                            //workprice分の深夜残業時間を差し引く前の深夜残業時間をセット
        	                            $this->request->data['Kintaitran']['heijitsushinya_ori'] = $shinyatotal;
        	                            //作業歩合マスタの深夜時間分の方が大きい場合
        	                            if($shinyatotal <= $shinyajikan_gokei){
        	                                $shinyatotal = '00:00';
        	                            }else{
        	                                $shinyatotal = $this->Timediff->subvtime($shinyatotal,$shinyajikan_gokei);
        	                            }
        	                            /*
        	                             * 2021-06-03
        	                             * 事務所兼運転手は強制的に深夜をゼロにする
        	                             * 2021-06-25
        	                             * ２８の場合も深夜はそのまま付ける
        	                             */
        	                            if($users['User']['rolemasters_id']==28){
        	                                //$this->request->data['Kintaitran']['heijitsushinya'] = '00:00';
        	                                $this->request->data['Kintaitran']['heijitsushinya'] = $shinyatotal;
        	                            }else{
        	                               $this->request->data['Kintaitran']['heijitsushinya'] = $shinyatotal;
        	                            }
        	                        }
        	                        break;
        	                }
        	            }
    	            }else{
    	                //事務職用
    	                // 平日、休日、法定休日に分けて処理
    	                if($calen){
    	                    //switch($calen['Calendarusermaster']['restflg']){
    	                    switch($this->request->data['Kintaitran']['rinjicalendarflg']){
    	                       case 1: // 法定外休日
    	                            $houteigaizan_sec = $kinmu_jikan_sec - $shinyazan_sec;
    	                            $w_houteigaizan = $this->Timediff->his_change($houteigaizan_sec);
    	                            $this->request->data['Kintaitran']['kyujitsuzangyo'] = $this->Timediff->sprintf($w_houteigaizan);
    	                            $this->request->data['Kintaitran']['heijitsuzangyo_ori'] = $this->request->data['Kintaitran']['kyujitsuzangyo'];
    	                            break;
    	                        case 2: // 法定休日
    	                            $houteizan_sec = $kinmu_jikan_sec - $shinyazan_sec;
    	                            $w_houteizan = $this->Timediff->his_change($houteizan_sec);
    	                            $this->request->data['Kintaitran']['houteikyujitsu'] = $this->Timediff->sprintf($w_houteizan);
    	                            $this->request->data['Kintaitran']['heijitsuzangyo_ori'] = $this->request->data['Kintaitran']['houteikyujitsu'];
    	                            break;
    	                        case 0: //平日
        	                        //平日通常残業（割増あり）時間を計算（法定内残業時間を差し引く）
        	                        $zangyonashi = 0;
        	                        $zangyotime = 0;
        	                        if($kinmu_jikan_sec > $teiji_sec){
        	                            $zangyotime = $kinmu_jikan_sec - $teiji_sec;
        	                            //法定時間から定時をひく
        	                            /*
        	                             * 2021-11-01 パートの場合、
        	                             */
        	                            $houtei = $this->Timediff->sec_change('08:00:00');
    	                               $zangyonashi = $houtei - $teiji_sec;

    	                               if($zangyotime <= $zangyonashi){
    	                                   //15分以下の場合
    	                                   $zangyonashi2 = $this->Timediff->his_change($zangyotime);
    	                                   $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = $zangyonashi2['h'].':'.$zangyonashi2['i'];
    	                               }else{
            	                            $zangyonashi2 = $this->Timediff->his_change($zangyonashi);
            	                            $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = $zangyonashi2['h'].':'.$zangyonashi2['i'];
    	                               }
        	                        }
        	                        //2021-06-25
        	                        if($zangyotime >= $zangyonashi){
        	                           $heijitsuzangyo = $zangyotime - $zangyonashi;
        	                        }else{
        	                            $heijitsuzangyo = '00:00:00';
        	                        }
        	                        $heijitsuzangyo2 = $this->Timediff->his_change($heijitsuzangyo);
        	                        $this->request->data['Kintaitran']['heijitsuzangyo'] = $heijitsuzangyo2['h'].':'.$heijitsuzangyo2['i'];
        	                        $this->request->data['Kintaitran']['heijitsuzangyo_ori'] = $this->Timediff->addvtime($this->request->data['Kintaitran']['heijitsuzangyo'],$this->request->data['Kintaitran']['heijitsuzangyo_nashi']);
        	                        break;
    	                    }
    	                }
    	            }
    	            $this->request->data['Kintaitran']['users_id'] = $this->params['named']['uid'];
    	            $selectdate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
    	            $this->request->data['Kintaitran']['kintaidate'] = $selectdate;
    	            if(isset($this->params['named']['keyid'])){
    	                $this->Kintaitran->id = $this->params['named']['keyid'];
    	            }else{
    	                $this->Kintaitran->create();
    	            }

					//2023-04-25 守衛のときは残業をクリアする
					if(($this->request->data['Kintaitran']['flgmasters_id']==45)or($users['User']['departmentmasters_id']==6)){
						$this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = '00:00';
						$this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00';
						$this->request->data['Kintaitran']['heijitsushinya'] = '00:00';
						$this->request->data['Kintaitran']['kyujitsuzangyo'] = '00:00';
						$this->request->data['Kintaitran']['kyujitsushinya'] = '00:00';
						$this->request->data['Kintaitran']['houteikyujitsu'] = '00:00';
						$this->request->data['Kintaitran']['houteikyujitsushinya'] = '00:00';
						$this->request->data['Kintaitran']['heijitsuzangyo_ori']='00:00';
						$this->request->data['Kintaitran']['heijitsushinya_ori']='00:00';
					}
					//2023-04-25
    	            if ($this->Kintaitran->save($this->request->data)) {
    	                $this->Flash->success('保存しました。');
    	                // kobetsuteatemasterを登録
    	                if(isset($this->params['named']['keyid'])&&($this->params['named']['keyid'])){
    	                    //更新の場合、削除してから登録
    	                    $kobetsu_conditions = ['kintaitrans_id'=>$this->params['named']['keyid']];
    	                    if($this->KintaitranKobetsuteatemaster->deleteAll($kobetsu_conditions)){
    	                        //成功時は何もしない
    	                    }else{
    	                        //エラー時
    	                        $this->Flash->error('kintatrankobetsuteatemasterの削除失敗');
    	                    }
    	                    $kintaiid = $this->params['named']['keyid'];
    	                }else{
    	                    //新規
    	                   $kintaiid = $this->Kintaitran->getLastInsertID(); //kintaitranの登録したIDを取得
    	                }
    	                if($this->request->data['Kintaitran']['kobetsuteatemasters_id']){
    	                   $kobetsu_cnt = count($this->request->data['Kintaitran']['kobetsuteatemasters_id']);
    	                   $sagyoteate_gokei2 = 0;
    	                   for($x=0;$x<$kobetsu_cnt;$x++){

    	                       $this->KintaitranKobetsuteatemaster->create();
    	                       $this->request->data['KintaitranKobetsuteatemaster']['kintaitrans_id']=$kintaiid;
    	                       $this->request->data['KintaitranKobetsuteatemaster']['kobetsuteatemasters_id']= $this->request->data['Kintaitran']['kobetsuteatemasters_id'][$x];
    	                       $this->KintaitranKobetsuteatemaster->save($this->request->data['KintaitranKobetsuteatemaster']);
    	                       //個別手当ごとに手当マスタを読み集計する
    	                       $kkmid = $this->KintaitranKobetsuteatemaster->getLastInsertID();
    	                       $kkmdata = $this->KintaitranKobetsuteatemaster->find('first',[
    	                           'fields'=>[
    	                               'Kobetsuteatemaster.kobetsukilo'
    	                           ],
    	                           'conditions'=>[
    	                               'KintaitranKobetsuteatemaster.id'=>$kkmid
    	                           ]
    	                       ]);
    	                       $kobetsuteate = $this->Workprice2->Selectprice($kkmdata['Kobetsuteatemaster']['kobetsukilo'],$users['User']['kaisyas_id']);
    	                       if($kobetsuteate){
    	                           $z_jikan = $this->Timediff->chg_integer($kobetsuteate['Workprice2']['zangyojikan']);
    	                           $teigakujikangai_2 = $teigakujikangai_base * $z_jikan;
									//    	                           $teigakushinya_2 = $teigakushinya_base * $z_jikan;
    	                           $teigakushinya_2 = 0;

    	                           // 加算していたので代入に変更
    	                           $kobetu_gokei = $teigakujikangai_2 + $teigakushinya_2;
    	                           $sagyoteate_gokei2 += $kobetu_gokei;
    	                       }
    	                   }
    	                   if($sagyoteate_gokei2){
                                //Kintaitranのsagyoteate2を更新
                                $this->request->data['Kintaitran']['id']=$kintaiid;
                                $this->request->data['Kintaitran']['sagyoteate2'] = $sagyoteate_gokei2;
                                if($this->Kintaitran->save($this->request->data)){
                                    $this->Flash->success('保存しました(4)');
                                }else{
                                    $this->Flash->error('kintaitranの保存に失敗しました(4)');
                                }
    	                   }
    	                }else{
    	                    //kintaitrankobetsuteatemasterがブランクの時（選択されていない）、kintaitran-sagyoteate2をクリア
    	                    $this->request->data['Kintaitran']['id']=$kintaiid;
    	                    $this->request->data['Kintaitran']['sagyoteate2'] = 0;
    	                    if($this->Kintaitran->save($this->request->data)){
    	                        $this->Flash->success('保存しました(5)');
    	                    }else{
    	                        $this->Flash->error('kintaitranの保存に失敗しました(5)');
    	                    }
    	                }
    	                return $this->redirect(['controller'=>'Host002bs','action'=>'index','kyy'=>$this->params['named']['kyy'],'kmm'=>$this->params['named']['kmm'],'kdd'=>$this->params['named']['kdd']]);
    	            }else{
    	                $this->Flash->error('保存失敗です。11');
    	            }
    	            //ここまで
    	            break;
	            case '15': // 待機
	            case '22': // 検診
	            case '25': // 出張
	                //2021-02-15 0クリア
	                $this->request->data['Kintaitran']['tako1_soukou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako1_sagyou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako1_taiki_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako1_kyukei_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako1_km_s'] = 0;
	                $this->request->data['Kintaitran']['tako1_km_e'] = 0;
	                $this->request->data['Kintaitran']['tako2_soukou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako2_sagyou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako2_taiki_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako2_kyukei_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako2_km_s'] = 0;
	                $this->request->data['Kintaitran']['tako2_km_e'] = 0;
	                $this->request->data['Kintaitran']['tako3_soukou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako3_sagyou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako3_taiki_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako3_kyukei_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako3_km_s'] = 0;
	                $this->request->data['Kintaitran']['tako3_km_e'] = 0;
	                $this->request->data['Kintaitran']['tako_gokei_soukou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako_gokei_sagyou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako_gokei_taiki_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako_gokei_kyukei_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako_gokei_km'] = 0;
	                $this->request->data['Kintaitran']['kinmu_jikan'] = '00:00';
	                $this->request->data['Kintaitran']['roumu_kousoku'] = '00:00';
	                $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = '00:00';
	                $this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00';
	                $this->request->data['Kintaitran']['heijitsushinya'] = '00:00';
	                $this->request->data['Kintaitran']['kyujitsuzangyo'] = '00:00';
	                $this->request->data['Kintaitran']['kyujitsushinya'] = '00:00';
	                $this->request->data['Kintaitran']['houteikyujitsu'] = '00:00';
	                $this->request->data['Kintaitran']['houteikyujitsushinya'] = '00:00';
	                $this->request->data['Kintaitran']['sagyoteate'] = 0;
	                $this->request->data['Kintaitran']['teigakujikangai'] = 0;
	                $this->request->data['Kintaitran']['teigakushinya'] = 0;
	                $this->request->data['Kintaitran']['untinbuai'] = 0;
	                $this->request->data['Kintaitran']['sagyoteate2'] = 0;
	                $this->request->data['Kintaitran']['soukoukiro'] = 0;
	                $this->request->data['Kintaitran']['trailer_teate'] = 0;
	                $this->request->data['Kintaitran']['kousokudai'] = 0;
	                $this->request->data['Kintaitran']['chain_teate'] = 0;
	                $this->request->data['Kintaitran']['jyoumu_teate'] = 0;
					//2022-05-06 add
	                $this->request->data['Kintaitran']['kyukeitime'] = '01:00';
					

	                //作業歩合を５０ｋｍ付けるが作業歩合は計算しない
                    /*
    	            $sagyoteate = $this->Workprice->Selectprice('50',$users['User']['branchmasters_id'],$users['User']['kaisyas_id']);
    	            if($sagyoteate){
    	                $this->request->data['Kintaitran']['sagyoteate'] = $sagyoteate['Workprice']['price'];
    	            }
    	            */
	                // 定時で計算（前後15分は不要）
    	            /* タコメータの時間を出退勤時間にセット */
    	            $this->request->data['Kintaitran']['datestart'] = $paramsDate;
    	            $this->request->data['Kintaitran']['dateend'] = $paramsDate;
    	            $this->request->data['Kintaitran']['tako_datestart'] = $paramsDate;
    	            $this->request->data['Kintaitran']['tako_dateend'] = $paramsDate;
    	            $this->request->data['Kintaitran']['timestart'] = substr($date1,0,5);
    	            $this->request->data['Kintaitran']['timeend'] = substr($date2,0,5);
    	            $this->request->data['Kintaitran']['tako_timestart'] = substr($date1,0,5);
    	            $this->request->data['Kintaitran']['tako_timeend'] = substr($date2,0,5);
    	            $so_kinmu_jikan = $this->Timediff->addvtime($teiji,'01:00');
    	            $this->request->data['Kintaitran']['so_kinmu_jikan'] = $so_kinmu_jikan;
    	            $this->request->data['Kintaitran']['kinmu_jikan'] = $teiji;
					//2021-06-03    	if(($users['User']['departmentmasters_id']==5)||($users['User']['departmentmasters_id']==6)){ //事務職、守衛用
					$this->request->data['Kintaitran']['tako_gokei_km'] = 0;
					$this->request->data['Kintaitran']['soukoukiro'] = 0;
    	            $this->set('comment_gokei',$comment_gokei);
    	            $this->request->data['Kintaitran']['users_id'] = $this->params['named']['uid'];
    	            $selectdate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
    	            $this->request->data['Kintaitran']['kintaidate'] = $selectdate;
    	            if(isset($this->params['named']['keyid'])){
    	                $this->Kintaitran->id = $this->params['named']['keyid'];
    	            }else{
    	                $this->Kintaitran->create();
    	            }

					//2023-04-25
					if ($this->Kintaitran->save($this->request->data)) {
    	                $this->Flash->success('保存しました。');
    	                //2021-07-07 add
    	                if(isset($this->params['named']['keyid'])&&($this->params['named']['keyid'])){
    	                    $kobetsu_conditions = ['kintaitrans_id'=>$this->params['named']['keyid']];
    	                    if($this->KintaitranKobetsuteatemaster->deleteAll($kobetsu_conditions)){
    	                        //成功時は何もしない
    	                    }else{
    	                        //エラー時
    	                        $this->Flash->error('kintatrankobetsuteatemasterの削除失敗');
    	                    }
    	                }
    	                return $this->redirect(['controller'=>'Host002bs','action'=>'index','kyy'=>$this->params['named']['kyy'],'kmm'=>$this->params['named']['kmm'],'kdd'=>$this->params['named']['kdd']]);
    	            }else{
    	                $this->Flash->error('保存失敗です。13');
    	            }
    	            //kobetsuteatemasterのクリア
	               break;
				case '18': // 教育
	                $this->request->data['Kintaitran']['tako1_soukou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako1_sagyou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako1_taiki_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako1_kyukei_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako1_km_s'] = 0;
	                $this->request->data['Kintaitran']['tako1_km_e'] = 0;
	                $this->request->data['Kintaitran']['tako2_soukou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako2_sagyou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako2_taiki_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako2_kyukei_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako2_km_s'] = 0;
	                $this->request->data['Kintaitran']['tako2_km_e'] = 0;
	                $this->request->data['Kintaitran']['tako3_soukou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako3_sagyou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako3_taiki_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako3_kyukei_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako3_km_s'] = 0;
	                $this->request->data['Kintaitran']['tako3_km_e'] = 0;
	                $this->request->data['Kintaitran']['tako_gokei_soukou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako_gokei_sagyou_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako_gokei_taiki_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako_gokei_kyukei_h'] = '00:00';
	                $this->request->data['Kintaitran']['tako_gokei_km'] = 0;
	                $this->request->data['Kintaitran']['kinmu_jikan'] = '00:00';
	                $this->request->data['Kintaitran']['roumu_kousoku'] = '00:00';
	                $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = '00:00';
	                $this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00';
	                $this->request->data['Kintaitran']['heijitsushinya'] = '00:00';
	                $this->request->data['Kintaitran']['kyujitsuzangyo'] = '00:00';
	                $this->request->data['Kintaitran']['kyujitsushinya'] = '00:00';
	                $this->request->data['Kintaitran']['houteikyujitsu'] = '00:00';
	                $this->request->data['Kintaitran']['houteikyujitsushinya'] = '00:00';
	                $this->request->data['Kintaitran']['sagyoteate'] = 0;
	                $this->request->data['Kintaitran']['teigakujikangai'] = 0;
	                $this->request->data['Kintaitran']['teigakushinya'] = 0;
	                $this->request->data['Kintaitran']['untinbuai'] = 0;
	                $this->request->data['Kintaitran']['sagyoteate2'] = 0;
	                $this->request->data['Kintaitran']['soukoukiro'] = 0;
	                $this->request->data['Kintaitran']['trailer_teate'] = 0;
	                $this->request->data['Kintaitran']['kousokudai'] = 0;
	                $this->request->data['Kintaitran']['chain_teate'] = 0;
	                $this->request->data['Kintaitran']['jyoumu_teate'] = 0;
	                $this->request->data['Kintaitran']['kyukeitime'] = '00:00';

					$this->request->data['Kintaitran']['datestart'] = $this->request->data['Kintaitran']['tako_datestart'];
					$this->request->data['Kintaitran']['timestart'] = $this->request->data['Kintaitran']['tako_timestart'];
					$this->request->data['Kintaitran']['dateend'] = $this->request->data['Kintaitran']['tako_dateend'];
					$this->request->data['Kintaitran']['timeend'] = $this->request->data['Kintaitran']['tako_timeend'];
					
					$startdatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_datestart'],$this->request->data['Kintaitran']['tako_timestart']);
					$enddatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_dateend'],$this->request->data['Kintaitran']['tako_timeend']);
					$tako_startdatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_datestart'],$this->request->data['Kintaitran']['tako_timestart']);
					$tako_enddatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_dateend'],$this->request->data['Kintaitran']['tako_timeend']);
					$sd = $this->Timediff->time_diff_minus($startdatetime,0);
					$ed = $this->Timediff->time_diff_add($enddatetime,0);
					$so_kinmu_jikan_sec = $ed - $sd;
					$so_kinmu_jikan = $this->Timediff->his_change($so_kinmu_jikan_sec);
					$this->request->data['Kintaitran']['so_kinmu_jikan'] = $so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'];
					$kinmu_jikan = $this->Timediff->subvtime($so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'].':'.$so_kinmu_jikan['s'],$this->request->data['Kintaitran']['kyukeitime']);

    	            $this->request->data['Kintaitran']['kinmu_jikan'] = $kinmu_jikan;
					//2021-06-03    	if(($users['User']['departmentmasters_id']==5)||($users['User']['departmentmasters_id']==6)){ //事務職、守衛用
					$this->request->data['Kintaitran']['tako_gokei_km'] = 0;
					$this->request->data['Kintaitran']['soukoukiro'] = 0;
    	            $this->set('comment_gokei',$comment_gokei);
    	            $this->request->data['Kintaitran']['users_id'] = $this->params['named']['uid'];
    	            $selectdate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
    	            $this->request->data['Kintaitran']['kintaidate'] = $selectdate;
					$kinmu_jikan_sec = $this->Timediff->sec_change($kinmu_jikan);

					// 平日、休日、法定休日に分けて処理
					if($calen){
						//switch($calen['Calendarusermaster']['restflg']){
						switch($this->request->data['Kintaitran']['rinjicalendarflg']){
							case 1: // 法定外休日
								$houteigaizan_sec = $kinmu_jikan_sec;
								$w_houteigaizan = $this->Timediff->his_change($houteigaizan_sec);
								$this->request->data['Kintaitran']['kyujitsuzangyo'] = $this->Timediff->sprintf($w_houteigaizan);
								$this->request->data['Kintaitran']['heijitsuzangyo_ori'] = $this->request->data['Kintaitran']['kyujitsuzangyo'];
								break;
							case 2: // 法定休日
								$houteizan_sec = $kinmu_jikan_sec;
								$w_houteizan = $this->Timediff->his_change($houteizan_sec);
								$this->request->data['Kintaitran']['houteikyujitsu'] = $this->Timediff->sprintf($w_houteizan);
								$this->request->data['Kintaitran']['heijitsuzangyo_ori'] = $this->request->data['Kintaitran']['houteikyujitsu'];
								break;
							case 0: //平日
								//平日通常残業（割増あり）時間を計算（法定内残業時間を差し引く）
								$zangyonashi = 0;
								$zangyotime = 0;
								$heijitsuzangyo = '00:00:00';
								$heijitsuzangyo2 = $this->Timediff->his_change($heijitsuzangyo);
								$this->request->data['Kintaitran']['heijitsuzangyo'] = $heijitsuzangyo2['h'].':'.$heijitsuzangyo2['i'];
								$this->request->data['Kintaitran']['heijitsuzangyo_ori'] = $this->Timediff->addvtime($this->request->data['Kintaitran']['heijitsuzangyo'],$this->request->data['Kintaitran']['heijitsuzangyo_nashi']);
								break;
						}
					}

					if(isset($this->params['named']['keyid'])){
    	                $this->Kintaitran->id = $this->params['named']['keyid'];
    	            }else{
    	                $this->Kintaitran->create();
    	            }
					//2023-04-25
					if ($this->Kintaitran->save($this->request->data)) {
    	                $this->Flash->success('保存しました。');
    	                //2021-07-07 add
    	                if(isset($this->params['named']['keyid'])&&($this->params['named']['keyid'])){
    	                    $kobetsu_conditions = ['kintaitrans_id'=>$this->params['named']['keyid']];
    	                    if($this->KintaitranKobetsuteatemaster->deleteAll($kobetsu_conditions)){
    	                        //成功時は何もしない
    	                    }else{
    	                        //エラー時
    	                        $this->Flash->error('kintatrankobetsuteatemasterの削除失敗');
    	                    }
    	                }
    	                return $this->redirect(['controller'=>'Host002bs','action'=>'index','kyy'=>$this->params['named']['kyy'],'kmm'=>$this->params['named']['kmm'],'kdd'=>$this->params['named']['kdd']]);
    	            }else{
    	                $this->Flash->error('保存失敗です。18');
    	            }
					break;
	            case '12': // 欠勤
	            case '23': // 傷病欠勤
	            case '24': // 労災欠勤
	            case '12': // 欠勤
	            case '13': // 有休
	            case '17': // 特休
	            case '19': // 代休
	            case '20': // 指定休
	                //2021-02-15 0クリア
    	            $this->request->data['Kintaitran']['tako1_soukou_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako1_sagyou_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako1_taiki_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako1_kyukei_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako1_km_s'] = 0;
    	            $this->request->data['Kintaitran']['tako1_km_e'] = 0;
    	            $this->request->data['Kintaitran']['tako2_soukou_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako2_sagyou_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako2_taiki_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako2_kyukei_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako2_km_s'] = 0;
    	            $this->request->data['Kintaitran']['tako2_km_e'] = 0;
    	            $this->request->data['Kintaitran']['tako3_soukou_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako3_sagyou_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako3_taiki_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako3_kyukei_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako3_km_s'] = 0;
    	            $this->request->data['Kintaitran']['tako3_km_e'] = 0;
    	            $this->request->data['Kintaitran']['tako_gokei_soukou_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako_gokei_sagyou_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako_gokei_taiki_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako_gokei_kyukei_h'] = '00:00';
    	            $this->request->data['Kintaitran']['tako_gokei_km'] = 0;
    	            $this->request->data['Kintaitran']['kinmu_jikan'] = '00:00';
    	            $this->request->data['Kintaitran']['roumu_kousoku'] = '00:00';
    	            $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = '00:00';
    	            $this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00';
    	            $this->request->data['Kintaitran']['heijitsushinya'] = '00:00';
    	            $this->request->data['Kintaitran']['kyujitsuzangyo'] = '00:00';
    	            $this->request->data['Kintaitran']['kyujitsushinya'] = '00:00';
    	            $this->request->data['Kintaitran']['houteikyujitsu'] = '00:00';
    	            $this->request->data['Kintaitran']['houteikyujitsushinya'] = '00:00';
    	            $this->request->data['Kintaitran']['sagyoteate'] = 0;
    	            $this->request->data['Kintaitran']['teigakujikangai'] = 0;
    	            $this->request->data['Kintaitran']['teigakushinya'] = 0;
    	            $this->request->data['Kintaitran']['untinbuai'] = 0;
    	            $this->request->data['Kintaitran']['sagyoteate2'] = 0;
    	            $this->request->data['Kintaitran']['soukoukiro'] = 0;
    	            $this->request->data['Kintaitran']['trailer_teate'] = 0;
    	            $this->request->data['Kintaitran']['kousokudai'] = 0;
    	            $this->request->data['Kintaitran']['chain_teate'] = 0;
    	            $this->request->data['Kintaitran']['jyoumu_teate'] = 0;
    	            $this->request->data['Kintaitran']['zangyoflg'] = 0;
    	            $this->request->data['Kintaitran']['telflg'] = 0;
    	            $this->request->data['Kintaitran']['jyoumuflg'] = 0;
    	            $this->request->data['Kintaitran']['jyoumushinyaflg'] = 0;

    	            $this->set('comment_gokei',$comment_gokei);
    	            $selectdate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
    	            $this->request->data['Kintaitran']['datestart'] = $selectdate;
    	            $this->request->data['Kintaitran']['timestart'] = '00:00:00';
    	            $this->request->data['Kintaitran']['tako_timestart'] = '00:00:00';
    	            $this->request->data['Kintaitran']['dateend'] = $selectdate;
    	            $this->request->data['Kintaitran']['timeend'] = '00:00:00';
    	            $this->request->data['Kintaitran']['tako_timeend'] = '00:00:00';
    	            $this->request->data['Kintaitran']['users_id'] = $this->params['named']['uid'];
    	            $this->request->data['Kintaitran']['kintaidate'] = $selectdate;
    	            if(isset($this->params['named']['keyid'])){
    	                $this->Kintaitran->id = $this->params['named']['keyid'];
    	            }else{
    	                $this->Kintaitran->create();
    	            }

					//2023-04-25 守衛のときは残業をクリアする
					if($this->request->data['Kintaitran']['flgmasters_id']==45){
						$this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = '00:00';
						$this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00';
						$this->request->data['Kintaitran']['heijitsushinya'] = '00:00';
						$this->request->data['Kintaitran']['kyujitsuzangyo'] = '00:00';
						$this->request->data['Kintaitran']['kyujitsushinya'] = '00:00';
						$this->request->data['Kintaitran']['houteikyujitsu'] = '00:00';
						$this->request->data['Kintaitran']['houteikyujitsushinya'] = '00:00';
						$this->request->data['Kintaitran']['heijitsuzangyo_ori']='00:00';
						$this->request->data['Kintaitran']['heijitsushinya_ori']='00:00';
					}
					//2023-04-25
					if ($this->Kintaitran->save($this->request->data)) {
    	                //2021-07-07 add
    	                if(isset($this->params['named']['keyid'])&&($this->params['named']['keyid'])){
    	                    $kobetsu_conditions = ['kintaitrans_id'=>$this->params['named']['keyid']];
    	                    if($this->KintaitranKobetsuteatemaster->deleteAll($kobetsu_conditions)){
    	                        //成功時は何もしない
    	                    }else{
    	                        //エラー時
    	                        $this->Flash->error('kintatrankobetsuteatemasterの削除失敗');
    	                    }
    	                }
    	                $this->Flash->success('保存しました。');
    	                return $this->redirect(['controller'=>'Host002bs','action'=>'index','kyy'=>$this->params['named']['kyy'],'kmm'=>$this->params['named']['kmm'],'kdd'=>$this->params['named']['kdd']]);
    	            }else{
    	                $this->Flash->error('保存失敗です。19');
    	            }
    	            break;
	            case '14': // 休業
    	             //2021-02-15 0クリア
    	             $this->request->data['Kintaitran']['tako1_soukou_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako1_sagyou_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako1_taiki_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako1_kyukei_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako1_km_s'] = 0;
    	             $this->request->data['Kintaitran']['tako1_km_e'] = 0;
    	             $this->request->data['Kintaitran']['tako2_soukou_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako2_sagyou_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako2_taiki_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako2_kyukei_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako2_km_s'] = 0;
    	             $this->request->data['Kintaitran']['tako2_km_e'] = 0;
    	             $this->request->data['Kintaitran']['tako3_soukou_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako3_sagyou_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako3_taiki_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako3_kyukei_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako3_km_s'] = 0;
    	             $this->request->data['Kintaitran']['tako3_km_e'] = 0;
    	             $this->request->data['Kintaitran']['tako_gokei_soukou_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako_gokei_sagyou_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako_gokei_taiki_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako_gokei_kyukei_h'] = '00:00';
    	             $this->request->data['Kintaitran']['tako_gokei_km'] = 0;
    	             $this->request->data['Kintaitran']['kinmu_jikan'] = '00:00';
    	             $this->request->data['Kintaitran']['roumu_kousoku'] = '00:00';
    	             $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = '00:00';
    	             $this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00';
    	             $this->request->data['Kintaitran']['heijitsushinya'] = '00:00';
    	             $this->request->data['Kintaitran']['kyujitsuzangyo'] = '00:00';
    	             $this->request->data['Kintaitran']['kyujitsushinya'] = '00:00';
    	             $this->request->data['Kintaitran']['houteikyujitsu'] = '00:00';
    	             $this->request->data['Kintaitran']['houteikyujitsushinya'] = '00:00';
    	             $this->request->data['Kintaitran']['sagyoteate'] = 0;
    	             $this->request->data['Kintaitran']['teigakujikangai'] = 0;
    	             $this->request->data['Kintaitran']['teigakushinya'] = 0;
    	             $this->request->data['Kintaitran']['untinbuai'] = 0;
    	             $this->request->data['Kintaitran']['sagyoteate2'] = 0;
    	             $this->request->data['Kintaitran']['soukoukiro'] = 0;
    	             $this->request->data['Kintaitran']['trailer_teate'] = 0;
    	             $this->request->data['Kintaitran']['kousokudai'] = 0;
    	             $this->request->data['Kintaitran']['chain_teate'] = 0;
    	             $this->request->data['Kintaitran']['jyoumu_teate'] = 0;
    	             $selectdate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
	                $this->request->data['Kintaitran']['datestart'] = $selectdate;
    	            $this->request->data['Kintaitran']['timestart'] = '00:00:00';
    	            $this->request->data['Kintaitran']['tako_timestart'] = '00:00:00';
    	            $this->request->data['Kintaitran']['dateend'] = $selectdate;
    	            $this->request->data['Kintaitran']['timeend'] = '00:00:00';
    	            $this->request->data['Kintaitran']['tako_timeend'] = '00:00:00';
    	            $this->request->data['Kintaitran']['kyukeitime'] = '00:00:00';
    	            $this->request->data['Kintaitran']['users_id'] = $this->params['named']['uid'];
    	            $this->request->data['Kintaitran']['kintaidate'] = $selectdate;
    	            if(isset($this->params['named']['keyid'])){
    	                $this->Kintaitran->id = $this->params['named']['keyid'];
    	            }else{
    	                $this->Kintaitran->create();
                    }

					//2023-04-25 守衛のときは残業をクリアする
					if($this->request->data['Kintaitran']['flgmasters_id']==45){
						$this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = '00:00';
						$this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00';
						$this->request->data['Kintaitran']['heijitsushinya'] = '00:00';
						$this->request->data['Kintaitran']['kyujitsuzangyo'] = '00:00';
						$this->request->data['Kintaitran']['kyujitsushinya'] = '00:00';
						$this->request->data['Kintaitran']['houteikyujitsu'] = '00:00';
						$this->request->data['Kintaitran']['houteikyujitsushinya'] = '00:00';
						$this->request->data['Kintaitran']['heijitsuzangyo_ori']='00:00';
						$this->request->data['Kintaitran']['heijitsushinya_ori']='00:00';
					}
					//2023-04-25
					if ($this->Kintaitran->save($this->request->data)) {
   	                    //2021-07-07 add
   	                    if(isset($this->params['named']['keyid'])&&($this->params['named']['keyid'])){
   	                        $kobetsu_conditions = ['kintaitrans_id'=>$this->params['named']['keyid']];
   	                        if($this->KintaitranKobetsuteatemaster->deleteAll($kobetsu_conditions)){
   	                            //成功時は何もしない
   	                        }else{
   	                            //エラー時
   	                            $this->Flash->error('kintatrankobetsuteatemasterの削除失敗');
   	                        }
   	                    }
   	                    $this->Flash->success('保存しました。');
    	                return $this->redirect(['controller'=>'Host002bs','action'=>'index','kyy'=>$this->params['named']['kyy'],'kmm'=>$this->params['named']['kmm'],'kdd'=>$this->params['named']['kdd']]);
    	            }else{
    	                $this->Flash->error('保存失敗です。14');
    	            }
	                break;
	        }
			if($users['User']['rolemasters_id']==60){
				//守衛の場合残業系をクリア 2023-05-02
				$this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = '00:00';
				$this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00';
				$this->request->data['Kintaitran']['heijitsushinya'] = '00:00';
				$this->request->data['Kintaitran']['kyujitsuzangyo'] = '00:00';
				$this->request->data['Kintaitran']['kyujitsushinya'] = '00:00';
				$this->request->data['Kintaitran']['houteikyujitsu'] = '00:00';
				$this->request->data['Kintaitran']['houteikyujitsushinya'] = '00:00';
				$this->request->data['Kintaitran']['heijitsuzangyo_ori']='00:00';
				$this->request->data['Kintaitran']['heijitsushinya_ori']='00:00';
			}
	    }else{
	        // 登録ボタンを押してない状態
	        if(isset($this->params['named']['keyid'])){
	            // 既存データ用
	            $kintaitrans = $this->Kintaitran->findById($this->params['named']['keyid']);
	            $this->request->data = $kintaitrans;
	            $this->request->data['Kintaitran']['timestart'] = substr($kintaitrans['Kintaitran']['timestart'],0,5);
	            $this->request->data['Kintaitran']['timeend'] = substr($kintaitrans['Kintaitran']['timeend'],0,5);
	            $this->request->data['Kintaitran']['timestart2'] = substr($kintaitrans['Kintaitran']['timestart2'],0,5);
	            $this->request->data['Kintaitran']['timestart3'] = substr($kintaitrans['Kintaitran']['timestart3'],0,5);
	            $this->request->data['Kintaitran']['timeend2'] = substr($kintaitrans['Kintaitran']['timeend2'],0,5);
	            $this->request->data['Kintaitran']['timeend3'] = substr($kintaitrans['Kintaitran']['timeend3'],0,5);
	            $this->request->data['Kintaitran']['tako_timestart'] = substr($kintaitrans['Kintaitran']['tako_timestart'],0,5);
	            $this->request->data['Kintaitran']['tako_timeend'] = substr($kintaitrans['Kintaitran']['tako_timeend'],0,5);
	            $this->request->data['Kintaitran']['tako_timestart2'] = substr($kintaitrans['Kintaitran']['tako_timestart2'],0,5);
	            $this->request->data['Kintaitran']['tako_timestart3'] = substr($kintaitrans['Kintaitran']['tako_timestart3'],0,5);
	            $this->request->data['Kintaitran']['tako_timeend2'] = substr($kintaitrans['Kintaitran']['tako_timeend2'],0,5);
	            $this->request->data['Kintaitran']['tako_timeend3'] = substr($kintaitrans['Kintaitran']['tako_timeend3'],0,5);

	            $this->request->data['Kintaitran']['kyukeitime'] = substr($kintaitrans['Kintaitran']['kyukeitime'],0,5);
	            $this->request->data['Kintaitran']['so_kinmu_jikan'] = substr($kintaitrans['Kintaitran']['so_kinmu_jikan'],0,5);
	            $this->request->data['Kintaitran']['roumu_kousoku'] = substr($kintaitrans['Kintaitran']['roumu_kousoku'],0,5);
	            $this->request->data['Kintaitran']['kinmu_jikan'] = substr($kintaitrans['Kintaitran']['kinmu_jikan'],0,5);
	            $this->request->data['Kintaitran']['heijitsuzangyo'] = substr($kintaitrans['Kintaitran']['heijitsuzangyo'],0,5);
	            $this->request->data['Kintaitran']['heijitsushinya'] = substr($kintaitrans['Kintaitran']['heijitsushinya'],0,5);
	            $this->request->data['Kintaitran']['houteikyujitsu'] = substr($kintaitrans['Kintaitran']['houteikyujitsu'],0,5);
	            $this->request->data['Kintaitran']['houteikyujitsushinya'] = substr($kintaitrans['Kintaitran']['houteikyujitsushinya'],0,5);
	            $this->request->data['Kintaitran']['kyujitsuzangyo'] = substr($kintaitrans['Kintaitran']['kyujitsuzangyo'],0,5);
	            $this->request->data['Kintaitran']['kyujitsushinya'] = substr($kintaitrans['Kintaitran']['kyujitsushinya'],0,5);
	            $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = substr($kintaitrans['Kintaitran']['heijitsuzangyo_nashi'],0,5);

	            $this->request->data['Kintaitran']['tako1_soukou_h'] = substr($kintaitrans['Kintaitran']['tako1_soukou_h'],0,5);
	            $this->request->data['Kintaitran']['tako2_soukou_h'] = substr($kintaitrans['Kintaitran']['tako2_soukou_h'],0,5);
	            $this->request->data['Kintaitran']['tako3_soukou_h'] = substr($kintaitrans['Kintaitran']['tako3_soukou_h'],0,5);
	            $this->request->data['Kintaitran']['tako4_soukou_h'] = substr($kintaitrans['Kintaitran']['tako4_soukou_h'],0,5);
	            $this->request->data['Kintaitran']['tako1_sagyou_h'] = substr($kintaitrans['Kintaitran']['tako1_sagyou_h'],0,5);
	            $this->request->data['Kintaitran']['tako2_sagyou_h'] = substr($kintaitrans['Kintaitran']['tako2_sagyou_h'],0,5);
	            $this->request->data['Kintaitran']['tako3_sagyou_h'] = substr($kintaitrans['Kintaitran']['tako3_sagyou_h'],0,5);
	            $this->request->data['Kintaitran']['tako4_sagyou_h'] = substr($kintaitrans['Kintaitran']['tako4_sagyou_h'],0,5);
	            $this->request->data['Kintaitran']['tako1_taiki_h'] = substr($kintaitrans['Kintaitran']['tako1_taiki_h'],0,5);
	            $this->request->data['Kintaitran']['tako2_taiki_h'] = substr($kintaitrans['Kintaitran']['tako2_taiki_h'],0,5);
	            $this->request->data['Kintaitran']['tako3_taiki_h'] = substr($kintaitrans['Kintaitran']['tako3_taiki_h'],0,5);
	            $this->request->data['Kintaitran']['tako4_taiki_h'] = substr($kintaitrans['Kintaitran']['tako4_taiki_h'],0,5);
	            $this->request->data['Kintaitran']['tako1_kyukei_h'] = substr($kintaitrans['Kintaitran']['tako1_kyukei_h'],0,5);
	            $this->request->data['Kintaitran']['tako2_kyukei_h'] = substr($kintaitrans['Kintaitran']['tako2_kyukei_h'],0,5);
	            $this->request->data['Kintaitran']['tako3_kyukei_h'] = substr($kintaitrans['Kintaitran']['tako3_kyukei_h'],0,5);
	            $this->request->data['Kintaitran']['tako4_kyukei_h'] = substr($kintaitrans['Kintaitran']['tako4_kyukei_h'],0,5);
	            $this->request->data['Kintaitran']['tako_gokei_soukou_h'] = substr($kintaitrans['Kintaitran']['tako_gokei_soukou_h'],0,5);
	            $this->request->data['Kintaitran']['tako_gokei_sagyou_h'] = substr($kintaitrans['Kintaitran']['tako_gokei_sagyou_h'],0,5);
	            $this->request->data['Kintaitran']['tako_gokei_taiki_h'] = substr($kintaitrans['Kintaitran']['tako_gokei_taiki_h'],0,5);
	            $this->request->data['Kintaitran']['tako_gokei_kyukei_h'] = substr($kintaitrans['Kintaitran']['tako_gokei_kyukei_h'],0,5);
	            $this->set('keyid',$this->params['named']['keyid']);
	            //作業歩合マスタ読み込み
	            if($kintaitrans['Kintaitran']['tako_gokei_km']){
	                $sagyoteate = $this->Workprice2->Selectprice($kintaitrans['Kintaitran']['tako_gokei_km'],$users['User']['kaisyas_id']);
	            }
	        }else{
	            // 新規登録時用
	            // 事務職用
	            //$this->set('pin_zangyojikan','');
	            //$this->set('pin_shinyajikan','');
				//20230201 守衛追加
	            if(($users['User']['departmentmasters_id']==5)||($users['User']['departmentmasters_id']==6)){
	                $this->request->data['Kintaitran']['datestart'] = $paramsDate;
	                $this->request->data['Kintaitran']['dateend'] = $paramsDate;
	                $this->request->data['Kintaitran']['timestart'] = substr($date1,0,5);
	                $this->request->data['Kintaitran']['timeend'] = substr($date2,0,5);
	                $this->request->data['Kintaitran']['tako_datestart'] = $this->request->data['Kintaitran']['datestart'];
	                $this->request->data['Kintaitran']['tako_dateend'] = $this->request->data['Kintaitran']['dateend'];
	                $this->request->data['Kintaitran']['tako_timestart'] = $this->request->data['Kintaitran']['timestart'];
	                $this->request->data['Kintaitran']['tako_timeend'] = $this->request->data['Kintaitran']['timeend'];
	            }else{
	                // 事務職以外
	                $kintaitrans = null;
    	            $i=0;
    	            $ordertrans1flg = 0;
    	            $ordertrans2flg = 0;
    	            //運転手
    	            if($ordertrans1flg==1){
    	                // processデータが存在した場合
    	                $tako_date1 = new DateTime();
    	                $tako_date2 = new DateTime();
    	                $this->request->data['Kintaitran']['tako_datestart'] = $processwork1_first_date;
    	                $this->request->data['Kintaitran']['tako_timestart'] = $tako_date1->format('H:i');
    	                $this->request->data['Kintaitran']['tako_dateend'] = $processwork1_last_date;
    	                $this->request->data['Kintaitran']['tako_timeend'] = $tako_date2->format('H:i');
    	            }
    	            //助手
    	            if(($ordertrans1flg==0)&&($ordertrans2flg==0)){
    	                // オーダーがない場合、所属の部署の勤怠時間をセット
    	                $timestart = new DateTime($date1);
    	                $w_timestart = $timestart->format('H:i');
    	                //$this->request->data['Kintaitran']['tako_timestart'] = $w_timestart;
    	                $timeend = new DateTime($date2);
    	                $w_timeend = $timeend->format('H:i');
    	                //$this->request->data['Kintaitran']['tako_timeend'] = $w_timeend;
    	                $this->request->data['Kintaitran']['tako_datestart'] = $paramsDate;
    	                $this->request->data['Kintaitran']['tako_dateend'] = $paramsDate;
    	            }else{
    	                $this->request->data['Kintaitran']['datestart'] = $this->request->data['Kintaitran']['tako_datestart'];
    	                $this->request->data['Kintaitran']['timestart'] = $this->request->data['Kintaitran']['tako_timestart'];
    	                $this->request->data['Kintaitran']['dateend'] = $this->request->data['Kintaitran']['tako_dateend'];
    	                $this->request->data['Kintaitran']['timeend'] = $this->request->data['Kintaitran']['tako_timeend'];
    	                $this->request->data['Kintaitran']['datestart2'] = $this->request->data['Kintaitran']['tako_datestart2'];
    	                $this->request->data['Kintaitran']['timestart2'] = $this->request->data['Kintaitran']['tako_timestart2'];
    	                $this->request->data['Kintaitran']['dateend2'] = $this->request->data['Kintaitran']['tako_dateend2'];
    	                $this->request->data['Kintaitran']['timeend2'] = $this->request->data['Kintaitran']['tako_timeend2'];
    	                $this->request->data['Kintaitran']['datestart3'] = $this->request->data['Kintaitran']['tako_datestart3'];
    	                $this->request->data['Kintaitran']['timestart3'] = $this->request->data['Kintaitran']['tako_timestart3'];
    	                $this->request->data['Kintaitran']['dateend3'] = $this->request->data['Kintaitran']['tako_dateend3'];
    	                $this->request->data['Kintaitran']['timeend3'] = $this->request->data['Kintaitran']['tako_timeend3'];
    	            }
	            }
	        }
	    }
	}
	public function delete($id = null){
	    $this->Kintaitran->id = $id;
	    if($this->Kintaitran->delete()){
	        $this->Flash->success('削除しました。');
	        return $this->redirect(['controller'=>'Host002bs','action'=>'index']);
	    }else{
	        $this->Flash->error('削除失敗です。');
	    }
	}

	public function pdfHost002($did = null,$bid = null) {
	    $paramsDate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];	    // Content-Type
	    $this->RequestHandler->respondAs('application/pdf');

	    // レイアウトを使用しない
	    $this->layout = '';

	    $date = new DateTime($paramsDate);
	    $firstday = $date->modify('first day of this months');
	    $date2 = new DateTime($paramsDate);
	    $lastday = $date2->modify('last day of this months');
	    $this->set(compact('firstday','lastday'));

	    $this->User->Behaviors->load('EagerLoader.EagerLoader');

	    $users = $this->User->find('all', [
	        'order' => [
	            'User.id' => 'asc',
	        ],
	        'fields' => [
	            'User.id',
	            'User.usernameK',
	            'User.calendarflg'
	        ],
	        'conditions' => [
	            'User.zaisekiflg' => 0,
	            'User.departmentmasters_id'=>$this->params['named']['did'],
	            'User.branchmasters_id'=>$this->params['named']['bid'],
	        ],
	        'contain' => [
	            'Kintaitran' => [
	                'conditions' => [
	                    'Kintaitran.kintaidate >= ' => $firstday->format('Y-m-d'),
	                    'Kintaitran.kintaidate <= ' => $lastday->format('Y-m-d'),
	                ],
	                'Flgmaster' => [
	                    'fields' => [
	                        'id',
	                        'flgname'
	                    ]
	                ]
	            ],
	        ],
	    ]);
	    $branchmasters = $this->Branchmaster->findById($this->params['named']['bid']);
	    $departmentmasters = $this->Departmentmaster->findById($this->params['named']['did']);
	    $this->set(compact('users','kintaitrans','branchmasters','departmentmasters'));
	}

	public function pdfHost002_2($date,$did,$bid) {
		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';

		if ($did == 0) {
			$dconditions = [];
		} else {
			$dconditions = ['User.departmentmasters_id' => $did];
		}

		if ($bid == 0) {
			$bconditions = [];
		} else {
			$bconditions = ['User.branchmasters_id' => $bid];
		}

		$week = ['日','月','火','水','木','金','土'];
		$paramsDate = new DateTime($date);
		$this->set(compact('paramsDate','week'));

		$this->User->Behaviors->load('EagerLoader.EagerLoader');

		$users = $this->User->find('all', [
			'order' => [
				'User.id' => 'asc',
			],
			'fields' => [
				'User.id',
				'User.usernameK',
				'User.syain_cd'
			],
			'conditions' => [
				'User.zaisekiflg' => 0,
				$dconditions,
				$bconditions,
			],
			'contain' => [
				'Kintaitran' => [
					'conditions' => [
						'Kintaitran.kintaidate' => $paramsDate->format('Y-m-d'),
					],
					'Flgmaster' => [
						'fields' => [
							'id',
							'flgname'
						]
					]
				],
			],
		]);
		$branchmasters = $this->Branchmaster->findById($bid);
		$departmentmasters = $this->Departmentmaster->findById($did);
		$this->set(compact('users','branchmasters','departmentmasters'));
	}

	public function pdfHost002_3($date,$did,$bid) {
	    // Content-Type
	    $this->RequestHandler->respondAs('application/pdf');

	    // レイアウトを使用しない
	    $this->layout = '';

	    if ($did == 0) {
	        $dconditions = [];
	    } else {
	        $dconditions = ['User.departmentmasters_id' => $did];
	    }

	    if ($bid == 0) {
	        $bconditions = [];
	    } else {
	        $bconditions = ['User.branchmasters_id' => $bid];
	    }

	    $week = ['日','月','火','水','木','金','土'];
	    $paramsDate = new DateTime($date);
	    $paramsDate2 = new DateTime($date);
	    $paramsDate3 = new DateTime($date);

	    $firstday = $paramsDate2->modify('first day of this months');
	    $lastday = $paramsDate3->modify('last day of this months');
	    $this->set(compact('paramsDate','firstday','lastday','week'));

	    $this->User->Behaviors->load('EagerLoader.EagerLoader');
		// order => idを変更　2025.11.05
	    $users = $this->User->find('all', [
			'order' => [
				'User.username' => 'asc',
			],
	        'fields' => [
	            'User.id',
	            'User.usernameK',
	            'User.syain_cd',
	            'User.roudouflg',
	            'User.telhour',
	            'User.zangyohour',
	            'User.jyoumuhour',
	            'User.jyoumushinyahour',
	            'User.shikakuhour',
	            'User.basetanka',
	            'User.rolemasters_id'
	        ],
	        'conditions' => [
	            'User.zaisekiflg' => 0,
	            $dconditions,
	            $bconditions,
	        ],
	        'contain' => [
	            'Kintaitran' => [
	                'conditions' => [
	                    'Kintaitran.kintaidate >=' => $firstday->format('Y-m-d'),
	                    'Kintaitran.kintaidate <=' => $lastday->format('Y-m-d'),
	                ],
	                'Flgmaster' => [
	                    'fields' => [
	                        'flgname'
	                    ]
	                ],
	                'order'=>[
	                    'Kintaitran.kintaidate'
	                ]
	            ],
	            'Kaisya' => [
	                'fields' => [
	                    'Kaisya.companyname'
	                ]
	            ],
	            'Departmentmaster' => [
	                'fields' => [
	                    'Departmentmaster.departmentname'
	                ],
	                'conditions' => [
	                    'Departmentmaster.delflg' => 0
	                ]
	            ],
	            'Branchmaster' => [
	                'fields' => [
	                    'Branchmaster.branchname'
	                ],
	            ],
	        ],
	    ]);

	    $this->set(compact('users'));
	}

	public function pdfHost002_3_test($date,$did,$bid) {
	    // Content-Type
	    $this->RequestHandler->respondAs('application/pdf');

	    // レイアウトを使用しない
	    $this->layout = '';

	    if ($did == 0) {
	        $dconditions = [];
	    } else {
	        $dconditions = ['User.departmentmasters_id' => $did];
	    }

	    if ($bid == 0) {
	        $bconditions = [];
	    } else {
	        $bconditions = ['User.branchmasters_id' => $bid];
	    }

	    $week = ['日','月','火','水','木','金','土'];
	    $paramsDate = new DateTime($date);
	    $paramsDate2 = new DateTime($date);
	    $paramsDate3 = new DateTime($date);

	    $firstday = $paramsDate2->modify('first day of this months');
	    $lastday = $paramsDate3->modify('last day of this months');
	    $this->set(compact('paramsDate','firstday','lastday','week'));

	    $this->User->Behaviors->load('EagerLoader.EagerLoader');

	    $users = $this->User->find('all', [
	        'order' => [
	            'User.id' => 'asc'
	        ],
	        'fields' => [
	            'User.id',
	            'User.usernameK',
	            'User.syain_cd',
	            'User.roudouflg',
	            'User.telhour',
	            'User.zangyohour',
	            'User.jyoumuhour',
	            'User.jyoumushinyahour',
	            'User.shikakuhour',
	            'User.basetanka',
	            'User.rolemasters_id'
	        ],
	        'conditions' => [
	            'User.zaisekiflg' => 0,
	            $dconditions,
	            $bconditions,
	        ],
	        'contain' => [
	            'Kintaitran' => [
	                'conditions' => [
	                    'Kintaitran.kintaidate >=' => $firstday->format('Y-m-d'),
	                    'Kintaitran.kintaidate <=' => $lastday->format('Y-m-d'),
	                ],
	                'Flgmaster' => [
	                    'fields' => [
	                        'flgname'
	                    ]
	                ],
	                'order'=>[
	                    'Kintaitran.kintaidate'
	                ]
	            ],
	            'Kaisya' => [
	                'fields' => [
	                    'Kaisya.companyname'
	                ]
	            ],
	            'Departmentmaster' => [
	                'fields' => [
	                    'Departmentmaster.departmentname'
	                ],
	                'conditions' => [
	                    'Departmentmaster.delflg' => 0
	                ]
	            ],
	            'Branchmaster' => [
	                'fields' => [
	                    'Branchmaster.branchname'
	                ],
	            ],
	        ],
	    ]);

	    $this->set(compact('users'));
	}

	//拘束時間一覧印刷
	public function pdfHost002_5($date,$did,$bid) {

		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';

		if ($did == 0) {
			$dconditions = [];
		} else {
			$dconditions = ['User.departmentmasters_id' => $did];
		}

		if ($bid == 0) {
			$bconditions = [];
		} else {
			$bconditions = ['User.branchmasters_id' => $bid];
		}

		$week = ['日','月','火','水','木','金','土'];
		$paramsDate = new DateTime($date);
		$paramsDate2 = new DateTime($date);
		$paramsDate3 = new DateTime($date);

		$firstday = $paramsDate2->modify('first day of this months');
		$lastday = $paramsDate3->modify('last day of this months');
		$this->set(compact('paramsDate','firstday','lastday','week'));

		$this->User->Behaviors->load('EagerLoader.EagerLoader');

		$users = $this->User->find('all', [
			'order' => [
				'User.id' => 'asc',
			],
			'fields' => [
				'User.id',
				'User.usernameK',
				'User.syain_cd'
			],
			'conditions' => [
				'User.zaisekiflg' => 0,
				$dconditions,
				$bconditions,
			],
			'contain' => [
				'Kintaitran' => [
					'conditions' => [
						'Kintaitran.kintaidate >=' => $firstday->format('Y-m-d'),
						'Kintaitran.kintaidate <=' => $lastday->format('Y-m-d'),
					],
					'Flgmaster' => [
						'fields' => [
							'id',
							'flgname'
						]
					]
				],
				'Kaisya' => [
					'fields' => [
						'Kaisya.companyname'
					]
				],
				'Departmentmaster' => [
					'fields' => [
						'Departmentmaster.departmentname'
					],
					'conditions' => [
						'Departmentmaster.delflg' => 0
					]
				],
				'Branchmaster' => [
					'fields' => [
						'Branchmaster.branchname'
					],
				],
			],
		]);

		$this->set(compact('users'));

		//usersの内容で、拘束時間テーブルに一旦保存する。
		foreach ($users as $user) {
			$roumu_goukei = '00:00';
			$sou_hei_zan = '00:00';
			$sou_holiday = '00:00';
			$w_y = $firstday->format('Y');
			if ($user['Kintaitran']) {
				foreach ($user['Kintaitran'] as $kintaitran) {
					$roumu_goukei = $this->Timediff->addvtime_25($roumu_goukei,$kintaitran['roumu_kousoku']);
					//debug($kintaitran['kintaidate']."::".$roumu_goukei);
					//20241126 
					if(date('w',strtotime($kintaitran['kintaidate']))==0){
						//日曜日は残業時間から除く
						$sou_holiday = $this->Timediff->addvtime_25($sou_holiday,$kintaitran['heijitsuzangyo_ori']);
					}else{
						$sou_hei_zan = $this->Timediff->addvtime_25($sou_hei_zan,$kintaitran['heijitsuzangyo_ori']);
					}
					//debug($sou_hei_zan);
					//20240405 $roumu_goukei = $format->Addtime($roumu_goukei,$kintaitran['roumu_kousoku']);
				}
				//まずdelete
				$delete_condition = [
					'users_id' => $user['User']['id'],
					'kousokudate' => $firstday->format('Y-m-d')
				];
				if($this->Kousokutran->deleteAll($delete_condition)){
					//成功時は何もしない
				}else{
					//エラー時、無視する
					//$this->Flash->error('Kousokutranの削除失敗');
				}
				//20231129 roumu_goukeiの値をkousokutransへ保存する。
				$this->Kousokutran->create();
				$this->request->data['Kousokutran']['kousokuyear'] = $w_y;
				$this->request->data['Kousokutran']['kousokudate'] = $firstday->format('Y-m-d');
				$this->request->data['Kousokutran']['kousokutime'] = $roumu_goukei;
				$this->request->data['Kousokutran']['kousokuholiday'] = $sou_holiday;
				$this->request->data['Kousokutran']['kousokuzangyotime'] = $sou_hei_zan;
				$this->request->data['Kousokutran']['users_id'] = $user['User']['id'];
				$this->request->data['Kousokutran']['kaisyas_id'] = $user['User']['kaisyas_id'];
				$this->request->data['Kousokutran']['usernameK'] = $user['User']['usernameK'];
				$this->Kousokutran->save($this->request->data);

			}
			//exit;
		}
		$branchmasters = $this->Branchmaster->findById($bid);
		$departmentmasters = $this->Departmentmaster->findById($did);
		$this->set(compact('users','branchmasters','departmentmasters'));
	}
}
