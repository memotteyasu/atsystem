<?php
App::uses('AppController', 'Controller');
/**
 * Host010s Controller
 *
 * @property Host010 $Host010s
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Host010sController extends AppController {
    public $layout = 'layout_host';
    public $helpers = array('Html','Form','Session','Js' => ['Jquery']);
    public $uses = array('User','Kaisya','Branchmaster','Departmentmaster','Kintaitran','Flgmaster','Holiday','Ordertran','Processtran','Calendarmaster','Workplaceteate','Workprice','Summertime');
    public $components = ['Search.Prg','RequestHandler','Timediff','SelectData'];

    public function beforeFilter(){
        parent::beforeFilter();
        $this->page = 'Host010s';
    }

    public function isAuthorized($user){
        if (isset($user['rolemasters_id'])) {
            switch($user['rolemasters_id']){
                case 10: //管理者
                case 11: //管理者
                case 12: //管理者
                case 20; //事務職（石油）
                case 21; //事務職（化学品）
                case 22; //事務職（ガス）
                case 23; //事務職（コンテナ）
                case 24; //事務職（全て）
                case 25; //事務職（化学品コンテナ）
                $this->set('user', $user);
                return true;
                break;
            }
        }
        return false;
        break;
    }
    /*
 * index method
 */
	public function index($did = null) {
		$page = $this->page;
		$branchmasters_id = $this->branchmasters_id;
		$branchmasters = $this->SelectData->branch_select_conditions($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);
		$departmentmasters_id = $this->Auth->user('departmentmasters_id');

		$flgmasters = $this->Flgmaster->flgchk(1);
		$this->set('flgmasters',$flgmasters);
		if ($this->branchmasters_id == 1) {
			// 本社所属の場合、部署の全てを表示
			$branchmasters = $this->Branchmaster->BranchList($this->kaisyas_id);
		} else {
			// 本社以外の場合、自身の所属部署のみを表示
			$branchmasters = $this->Branchmaster->find('list', [
				'fields' => ['id','branchname'],
				'conditions' => [
					'kaisyas_id' => $this->kaisyas_id,
					'id' => $this->branchmasters_id
				],
				'order' => ['id' => 'asc']
			]);
		}

		$departmentmasters = $this->Departmentmaster->SelectDepartmentList($this->kaisyas_id);
		$this->set(compact('branchmasters','departmentmasters'));

		$date = new DateTime();

		if ($this->request->is('post')) {
			$date = $this->SelectData->date_select($this->request->data['selectym1']);
			$branchmasters_id = $this->request->data['branchmasters_id'];
			$departmentmasters_id = $this->request->data['departmentmasters_id'];
			$backdatas = ['date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')],'bid' => $branchmasters_id, 'did' => $departmentmasters_id, 'Page' => $page];
			$this->Session->write('BackData1', $backdatas);
		}else{
			if($this->Session->read('BackData1.Page')==$page){
				if($this->Session->read('BackData1.date')){
					$date = $this->SelectData->date_select($this->Session->read('BackData1.date'));
				}
				if($this->Session->read('BackData1.bid')){
					$branchmasters_id = $this->Session->read('BackData1.bid');
				}
				if($this->Session->read('BackData1.did')){
					$departmentmasters_id = $this->Session->read('BackData1.did');
				}
			}else{
				// 初期画面
				// $branchmasters_id = 1;
				// $departmentmasters_id = 1;
				$backdatas = ['date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')],'bid' => $branchmasters_id, 'did' => $departmentmasters_id, 'Page' => $page];
				$this->Session->write('BackData1',$backdatas);
			}
		}
		$paramsDate = $date->format('Y-m-d');
		$paramsDate2 = $date->modify('+6 day')->format('Y-m-d');
		$week = ['日','月','火','水','木','金','土'];
		$this->set(compact('paramsDate','week','departmentmasters_id','branchmasters_id'));

		$users = $this->SelectData->data_select_conditionsHost010s($paramsDate,$paramsDate2,$branchmasters_id,$departmentmasters_id,$this->kaisyas_id);
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
				$bconditions,
			    'User.zaisekiflg'=>0
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

	public function edit($id = null) {
	    $users = $this->User->findById($this->params['named']['uid'],null,null,0);
	    $this->set('userdata', $users);
	    $flgmasters = $this->Flgmaster->flgchk(1);
	    $this->set('flgmasters', $flgmasters);

	    $paramsDate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
	    $uid = $this->params['named']['uid'];
	    $week = ['日','月','火','水','木','金','土'];
	    $this->set('date',$paramsDate);
	    $this->set('week',$week);
	    $calen = $this->Calendarmaster->calenlist($paramsDate,$this->Auth->user('kaisyas_id'));
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
	    if(($summer['Summertime']['summerstartmonth'] >= $mmchk)and($summer['Summertime']['summerendmonth'] <= $mmchk)) {
	        // サマータイムのとき
	        $teiji = $summer['Summertime']['summertermtime'];
	        $date1 = $summer['Summertime']['summerstarttime'];
	        $date2 = $summer['Summertime']['summerendtime'];
	        $this->Session->write('BackData1.season','summer');
	    }else{
	        $teiji = $summer['Summertime']['termtime'];
	        $date1 = $summer['Summertime']['starttime'];
	        $date2 = $summer['Summertime']['endtime'];
	        $this->Session->write('BackData1.season','winter');
	    }

	    $teiji = substr($teiji,0,5);
	    $this->set('teiji',$teiji);
	    $teiji_sec = $this->Timediff->sec_change($teiji);

	    //自分が運転手のデータを取得（processtran)
	    $processtran1 = $this->Processtran->SelectProcesstran040_1($uid,$paramsDate);
	    $this->set('processtran1',$processtran1);
	    //自分が助手のデータを取得（processtran)
	    $processtran2 = $this->Processtran->SelectProcesstran040_2($uid,$paramsDate);
	    $this->set('processtran2',$processtran2);

	    //自分が運転手のデータを抽出
//	    $ordertrans1 = $this->Ordertran->SelectOrdertran040s1($uid,$paramsDate);
//	    $this->set('ordertrans1',$ordertrans1);
	    $processtrandriver1 = $this->Processtran->SelectProcesstrandriver1($paramsDate,$uid);
	    $this->set('processtrandriver1',$processtrandriver1);
	    // 自分が助手のデータを抽出
//	    $ordertrans2 = $this->Ordertran->SelectOrdertran040s2($uid,$paramsDate);
//	    $this->set('ordertrans2',$ordertrans2);
	    $processtrandriver2 = $this->Processtran->SelectProcesstrandriver2($paramsDate,$uid);
	    $this->set('processtrandriver2',$processtrandriver2);
	    if ($this->request->is(['patch', 'post', 'put'])) {
	        switch($this->request->data['Kintaitran']['flgmasters_id']){
	            case '11'; // 出勤
    	            // ここから
    	            $shinyazan_sec = 0;
    	            /* saveする前に残業計算をする */
    	            /* タコメータの時間から、前後15分(900)追加した時間を出退勤時間にセット */
    	            if($users['User']['departmentmasters_id']==5){ //事務職用
    	                $startdatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['datestart'],$this->request->data['Kintaitran']['timestart']);
    	                $enddatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['dateend'],$this->request->data['Kintaitran']['timeend']);
    	                $sd = $this->Timediff->time_diff_minus($startdatetime,0);
    	                $ed = $this->Timediff->time_diff_add($enddatetime,0);
    	                $so_kinmu_jikan_sec = $ed - $sd;
    	                $so_kinmu_jikan = $this->Timediff->his_change($so_kinmu_jikan_sec);
    	                $this->request->data['Kintaitran']['so_kinmu_jikan'] = $so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'];
    	                $kinmu_jikan = $this->Timediff->subvtime($so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'],$this->request->data['Kintaitran']['kyukeitime']);
    	                $this->request->data['Kintaitran']['kinmu_jikan'] = $kinmu_jikan;
    	                $kinmu_jikan_sec = $this->Timediff->sec_change($kinmu_jikan);
    	            }else{
        	            $tako_startdatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_datestart'],$this->request->data['Kintaitran']['tako_timestart']);
        	            $tako_enddatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_dateend'],$this->request->data['Kintaitran']['tako_timeend']);
        	            $sd = $this->Timediff->time_diff_minus($tako_startdatetime,900);
        	            $ed = $this->Timediff->time_diff_add($tako_enddatetime,900);
        	            $startdatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['datestart'],$this->request->data['Kintaitran']['timestart']);
        	            $enddatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['dateend'],$this->request->data['Kintaitran']['timeend']);
    	            }
    	            $this->request->data['Kintaitran']['datestart'] = date('Y-m-d',$sd);
    	            $this->request->data['Kintaitran']['timestart'] = date('H:i',$sd);
    	            $this->request->data['Kintaitran']['dateend'] = date('Y-m-d',$ed);
    	            $this->request->data['Kintaitran']['timeend'] = date('H:i',$ed);
    	            $roumu_kousoku_sec = $ed - $sd;
    	            $roumu_kousoku = $this->Timediff->his_change($roumu_kousoku_sec);
    	            $this->request->data['Kintaitran']['roumu_kousoku'] = $roumu_kousoku['h'].':'.$roumu_kousoku['i'];

    	            //拘束時間（総勤務時間）
    	            //15分加味された始業終業時間で計算
    	            $so_kinmu_jikan_sec = $ed - $sd;
    	            $so_kinmu_jikan = $this->Timediff->his_change($so_kinmu_jikan_sec);
    	            $this->request->data['Kintaitran']['so_kinmu_jikan'] = $so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'];
    	            $kinmu_jikan = $this->Timediff->subvtime($so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'].':'.$so_kinmu_jikan['s'],$this->request->data['Kintaitran']['kyukeitime']);
    	            $this->request->data['Kintaitran']['kinmu_jikan'] = $kinmu_jikan;
    	            $kinmu_jikan_sec = $this->Timediff->sec_change($kinmu_jikan);

    	            /* 比較要素 */
    	            $w5 = $this->Timediff->w5($startdatetime);
    	            $w22 = $this->Timediff->w22($startdatetime);
    	            $w5n = $this->Timediff->w5n($startdatetime);

    	            $w_sigyo_sec = strtotime($startdatetime);
    	            $w_syugyo_sec = strtotime($enddatetime);
    	            /* 出勤が5時より前の場合 */
    	            if($w_sigyo_sec < $w5){
    	                $shinyazan_sec = $shinyazan_sec + ($w5 - $w_sigyo_sec);
    	            }
    	            /* 22時以降の退社 */
    	            if($w_syugyo_sec > $w22){
    	                $shinyazan_sec = $shinyazan_sec + ($w_syugyo_sec - $w22);
    	            }

    	            // 平日、休日、法定休日に分けて処理
    	            if($calen){
    	                switch($calen['Calendarmaster']['restflg']){
    	                    case 1: // 法定外休日
    	                        $houteigaizan_sec = $kinmu_jikan_sec - $shinyazan_sec;
    	                        $w_houteigaizan = $this->Timediff->his_change($houteigaizan_sec);
    	                        $this->request->data['Kintaitran']['kyujitsuzangyo'] = $this->Timediff->sprintf($w_houteigaizan);
    	                        $w_his = $this->Timediff->his_change($shinyazan_sec);
    	                        $this->request->data['Kintaitran']['kyujitsushinya'] = $this->Timediff->sprintf($w_his);
    	                        break;
    	                    case 2: // 法定休日
    	                        $houteizan_sec = $kinmu_jikan_sec - $shinyazan_sec;
    	                        $w_houteizan = $this->Timediff->his_change($houteizan_sec);
    	                        $this->request->data['Kintaitran']['houteikyujitsu'] = $this->Timediff->sprintf($w_houteizan);
    	                        $w_his = $this->Timediff->his_change($shinyazan_sec);
    	                        $this->request->data['Kintaitran']['houteikyujitsushinya'] = $this->Timediff->sprintf($w_his);
    	                        break;
    	                }
    	            }else{
    	                //平日通常残業（割増あり）時間を計算（法定内残業時間を差し引く）
    	                $zangyonashi = 0;
    	                $zangyotime = 0;
    	                if($kinmu_jikan_sec > $teiji_sec){
    	                    $zangyotime = $kinmu_jikan_sec - $teiji_sec;
    	                    //法定時間から定時をひく
    	                    $houtei = $this->Timediff->sec_change('08:00:00');
    	                    $zangyonashi = $houtei - $teiji_sec;
    	                    $zangyonashi2 = $this->Timediff->his_change($zangyonashi);
    	                    $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = $zangyonashi2['h'].':'.$zangyonashi2['i'];
    	                }
    	                $heijitsuzangyo = $zangyotime - $zangyonashi;
    	                $heijitsuzangyo2 = $this->Timediff->his_change($heijitsuzangyo);
    	                $this->request->data['Kintaitran']['heijitsuzangyo'] = $heijitsuzangyo2['h'].':'.$heijitsuzangyo2['i'];

    	                $heizan_sec = $kinmu_jikan_sec - $shinyazan_sec - $teiji_sec - $zangyonashi;
    	                $w_his = $this->Timediff->his_change($shinyazan_sec);
    	                $this->request->data['Kintaitran']['heijitsushinya'] = $this->Timediff->sprintf($w_his);
    	            }
    	            if(isset($this->params['named']['keyid'])){
    	                $this->Kintaitran->id = $this->params['named']['keyid'];
    	            }else{
    	                $this->Kintaitran->create();
    	            }
    	            $this->request->data['Kintaitran']['users_id'] = $this->params['named']['uid'];
    	            $selectdate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
    	            $this->request->data['Kintaitran']['kintaidate'] = $selectdate;
    	            if ($this->Kintaitran->save($this->request->data)) {
    	                $this->Flash->success('保存しました。');
    	                return $this->redirect(['controller'=>'Host010s','action'=>'edit','keyid'=>$this->Kintaitran->id,'uid'=>$this->params['named']['uid'],'kyy'=>$this->params['named']['kyy'],'kmm'=>$this->params['named']['kmm'],'kdd'=>$this->params['named']['kdd']]);
    	                return;
    	            }else{
    	                $this->Flash->error('保存失敗です。');
    	            }
    	            //ここまで
    	            break;
	            case '13'; // 有休
	            case '15'; // 待機
	            case '17'; // 特休
	            case '18'; // 教育
	               // 定時で計算（前後15分は不要）
    	            /* タコメータの時間を出退勤時間にセット */
    	            $tako_startdatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_datestart'],$this->request->data['Kintaitran']['tako_timestart']);
    	            $tako_enddatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_dateend'],$this->request->data['Kintaitran']['tako_timeend']);
    	            $this->request->data['Kintaitran']['datestart'] = $this->request->data['Kintaitran']['tako_datestart'];
    	            $this->request->data['Kintaitran']['timestart'] = $this->request->data['Kintaitran']['tako_timestart'];
    	            $this->request->data['Kintaitran']['dateend'] = $this->request->data['Kintaitran']['tako_dateend'];
    	            $this->request->data['Kintaitran']['timeend'] = $this->request->data['Kintaitran']['tako_timeend'];
    	            $startdatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['datestart'],$this->request->data['Kintaitran']['timestart']);
    	            $enddatetime = $this->Timediff->datetime($this->request->data['Kintaitran']['dateend'],$this->request->data['Kintaitran']['timeend']);
    	            $aa = $this->Timediff->time_diff_minus($tako_startdatetime,0);
    	            $bb = $this->Timediff->time_diff_add($tako_enddatetime,0);
    	            $so_kinmu_jikan_sec = $bb - $aa;
    	            $so_kinmu_jikan = $this->Timediff->his_change($so_kinmu_jikan_sec);
    	            $this->request->data['Kintaitran']['so_kinmu_jikan'] = $so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'];
    	            $kinmu_jikan_sec = $this->Timediff->time_diff_sec($this->request->data['Kintaitran']['kyukeitime'],$so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'].':'.$so_kinmu_jikan['s']);
    	            // 平日、休日、法定休日に分けて処理
    	            if($calen){
    	                switch($calen['Calendarmaster']['restflg']){
    	                    case 1: // 法定外休日
    	                        $w_houteigaizan = $this->Timediff->his_change($kinmu_jikan_sec);
    	                        $this->request->data['Kintaitran']['kyujitsuzangyo'] = $this->Timediff->sprintf($w_houteigaizan);
    	                        break;
    	                    case 2: // 法定休日
    	                        $w_houteizan = $this->Timediff->his_change($kinmu_jikan_sec);
    	                        $this->request->data['Kintaitran']['houteikyujitsu'] = $this->Timediff->sprintf($w_houteizan);
    	                        break;
    	                }
    	            }
    	            if(isset($this->params['named']['keyid'])){
    	                $this->Kintaitran->id = $this->params['named']['keyid'];
    	            }else{
    	                $this->Kintaitran->create();
    	            }
    	            $this->request->data['Kintaitran']['users_id'] = $this->params['named']['uid'];
    	            $selectdate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
    	            $this->request->data['Kintaitran']['kintaidate'] = $selectdate;
    	            if ($this->Kintaitran->save($this->request->data)) {
    	                $this->Flash->success('保存しました。');
    	                return $this->redirect(['controller'=>'Host010s','action'=>'edit','keyid'=>$this->Kintaitran->id,'uid'=>$this->params['named']['uid'],'kyy'=>$this->params['named']['kyy'],'kmm'=>$this->params['named']['kmm'],'kdd'=>$this->params['named']['kdd']]);
    	                return;
    	            }else{
    	                $this->Flash->error('保存失敗です。');
    	            }
	               break;
	            case '12'; // 欠勤
	            case '19'; // 代休
    	            $this->request->data['Kintaitran']['datestart'] = $this->request->data['Kintaitran']['tako_datestart'];
    	            $this->request->data['Kintaitran']['timestart'] = '00:00:00';
    	            $this->request->data['Kintaitran']['tako_timestart'] = '00:00:00';
    	            $this->request->data['Kintaitran']['dateend'] = $this->request->data['Kintaitran']['tako_dateend'];
    	            $this->request->data['Kintaitran']['timestart'] = '00:00:00';
    	            $this->request->data['Kintaitran']['tako_timeend'] = '00:00:00';
    	            if(isset($this->params['named']['keyid'])){
    	                $this->Kintaitran->id = $this->params['named']['keyid'];
    	            }else{
    	                $this->Kintaitran->create();
    	            }
    	            $this->request->data['Kintaitran']['users_id'] = $this->params['named']['uid'];
    	            $selectdate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
    	            $this->request->data['Kintaitran']['kintaidate'] = $selectdate;
    	            if ($this->Kintaitran->save($this->request->data)) {
    	                $this->Flash->success('保存しました。');
    	                return $this->redirect(['controller'=>'Host010s','action'=>'edit','keyid'=>$this->Kintaitran->id,'uid'=>$this->params['named']['uid'],'kyy'=>$this->params['named']['kyy'],'kmm'=>$this->params['named']['kmm'],'kdd'=>$this->params['named']['kdd']]);
    	                return;
    	            }else{
    	                $this->Flash->error('保存失敗です。');
    	            }
    	            break;
	        }
	    }else{
	        // 登録ボタンを押してない状態
	        if(isset($this->params['named']['keyid'])){
	            // 既存データ用
	            $kintaitrans = $this->Kintaitran->findById($this->params['named']['keyid']);
	            $this->request->data = $kintaitrans;
	            $timestart = new DateTime($kintaitrans['Kintaitran']['timestart']);
	            $w_timestart = $timestart->format('H:i');
	            $this->request->data['Kintaitran']['timestart'] = $w_timestart;
	            $timeend = new DateTime($kintaitrans['Kintaitran']['timeend']);
	            $w_timeend = $timeend->format('H:i');
	            $this->request->data['Kintaitran']['timeend'] = $w_timeend;

	            $tako_timestart = new DateTime($kintaitrans['Kintaitran']['tako_timestart']);
	            $w_tako_timestart = $tako_timestart->format('H:i');
	            $this->request->data['Kintaitran']['tako_timestart'] = $w_tako_timestart;
	            $tako_timeend = new DateTime($kintaitrans['Kintaitran']['tako_timeend']);
	            $w_tako_timeend = $tako_timeend->format('H:i');
	            $this->request->data['Kintaitran']['tako_timeend'] = $w_tako_timeend;

	            $this->request->data['Kintaitran']['soukoutime'] = substr($kintaitrans['Kintaitran']['soukoutime'],0,5);

	            $this->request->data['Kintaitran']['sagyoutime'] = substr($kintaitrans['Kintaitran']['sagyoutime'],0,5);

	            $this->request->data['Kintaitran']['kyukeitime'] = substr($kintaitrans['Kintaitran']['kyukeitime'],0,5);
	            $this->request->data['Kintaitran']['so_kinmu_jikan'] = substr($kintaitrans['Kintaitran']['so_kinmu_jikan'],0,5);
	            $this->request->data['Kintaitran']['kinmu_jikan'] = substr($kintaitrans['Kintaitran']['kinmu_jikan'],0,5);
	            $this->request->data['Kintaitran']['heijitsuzangyo'] = substr($kintaitrans['Kintaitran']['heijitsuzangyo'],0,5);
	            $this->request->data['Kintaitran']['heijitsushinya'] = substr($kintaitrans['Kintaitran']['heijitsushinya'],0,5);
	            $this->request->data['Kintaitran']['houteikyujitsu'] = substr($kintaitrans['Kintaitran']['houteikyujitsu'],0,5);
	            $this->request->data['Kintaitran']['houteikyujitsushinya'] = substr($kintaitrans['Kintaitran']['houteikyujitsushinya'],0,5);
	            $this->request->data['Kintaitran']['kyujitsuzangyo'] = substr($kintaitrans['Kintaitran']['kyujitsuzangyo'],0,5);
	            $this->request->data['Kintaitran']['kyujitsushinya'] = substr($kintaitrans['Kintaitran']['kyujitsushinya'],0,5);
	            $this->request->data['Kintaitran']['heijitsuzangyo_nashi'] = substr($kintaitrans['Kintaitran']['heijitsuzangyo_nashi'],0,5);
	            $this->set('keyid',$this->params['named']['keyid']);
	        }else{
	            // 新規登録時用
	            // 事務職用
	            $this->set('comment_gokei','');
	            if($users['User']['departmentmasters_id']==5){
	                $this->request->data['Kintaitran']['datestart'] = $paramsDate;
	                $this->request->data['Kintaitran']['dateend'] = $paramsDate;
	                $this->request->data['Kintaitran']['timestart'] = substr($date1,0,5);
	                $this->request->data['Kintaitran']['timeend'] = substr($date2,0,5);
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
    	                $this->request->data['Kintaitran']['tako_timestart'] = $w_timestart;
    	                $timeend = new DateTime($date2);
    	                $w_timeend = $timeend->format('H:i');
    	                $this->request->data['Kintaitran']['tako_timeend'] = $w_timeend;
    	                $this->request->data['Kintaitran']['tako_datestart'] = $paramsDate;
    	                $this->request->data['Kintaitran']['tako_dateend'] = $paramsDate;
    	                $this->request->data['Kintaitran']['kyukeitime'] = '01:00';
    	            }else{
    	                $this->request->data['Kintaitran']['datestart'] = $this->request->data['Kintaitran']['tako_datestart'];
    	                $this->request->data['Kintaitran']['timestart'] = $this->request->data['Kintaitran']['tako_timestart'];
    	                $this->request->data['Kintaitran']['dateend'] = $this->request->data['Kintaitran']['tako_dateend'];
    	                $this->request->data['Kintaitran']['timeend'] = $this->request->data['Kintaitran']['tako_timeend'];
    	                $this->request->data['Kintaitran']['kyukeitime'] = '01:00';
    	            }
	            }
	        }
	    }
	}
}
