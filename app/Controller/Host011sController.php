<?php
App::uses('AppController', 'Controller');
/**
 * Host011s Controller
 *
 * @property Host011 $Host011s
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Host011sController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('User','Kaisya','Kintaitran','Flgmaster','Holiday','Processtran');
    public $components = ['Timediff'];

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);
    }

    public function isAuthorized($user){
        // 管理者(10-)事務職(20-)
    	if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '29'){
    		$this->set('user',$user);
    		return true;
    	}
    	return false;
    	break;
    }
/**
 * index method
 *
 * @return void
 */

    public function add() {
    	$users = $this->User->findById($this->params['named']['uid']);
    	$this->set('userdata', $users);

    	$flgmasters = $this->Flgmaster->flgchk(1);
    	$this->set('flgmasters', $flgmasters);

    	$paramsDate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
    	$uid = $this->params['named']['uid'];

    	$this->set('processtrans', $this->Processtran->SelectProcesstran($paramsDate,$uid));

    	/*
    	$this->set('processtrans', $this->Processtran->find('all', [
    		'conditions' => [
    			'AND' => [
    				//['Processtran.users_id' => $this->params['named']['uid']],
    				//['Processtran.processstartdate' => $paramsDate],
    				'OR' => [
    					['Processtran.delflg' => ''],
    					['Processtran.delflg' => 0],
    				]
    			]
    		],
    		'contain' => [
    			'Ordertran' => [
    				'Loadingmaster' => [
    					'fields' => 'id,loadingshortname',
    				],
    				'Deliverymaster' => [
    					'fields' => 'id,deliveryshortname',
    				],
    				'Productdetailmaster' => [
    					'fields' => 'id,pdshortname',
    				],
    				'Shippermaster' => [
    					'fields' => 'id,shippershortname',
    				],
    				'Carmaster' => [
    					'fields' => 'id,plateno',
    				],
    				'Processtran' => [
    					'conditions' => [
    						'AND' => [
    							['Processtran.users_id' => $this->params['named']['uid']],
    							['Processtran.processstartdate' => $paramsDate],
    							'OR' => [
    								['Processtran.delflg' => ''],
    								['Processtran.delflg' => 0],
    							]
    						]
    					],
    					'order' => [
    						'Processtran.processstartdate' => 'asc',
    						'Processtran.processstarttime' => 'asc',
    					],
    					'Workdetailmaster' => [
    						'fields' => 'id,workname',
    					],
    				],
    				'order' => [
    					'Ordertran.kaisen' => 'asc',
    					'Ordertran.loadingdate' => 'asc',
    					'Ordertran.loadingtime' => 'asc'
    				]
    			],
    		],
    		'group' => ['Processtran.ordertrans_id'],
    		'recursive' => 2
    	]));
    	*/

    	/* 選択した社員の部署の定時時間取得 */
    	$mmdd = $this->params['named']['kmm'].$this->params['named']['kdd'];
    	if(($users['Kaisya']['summertimestart'] >= $mmdd)and($users['Kaisya']['summertimeend'] <= $mmdd)) {
    		// サマータイムのとき
    		$teiji = $users['Departmentmaster']['jobtermsummertime'];
    	}else{
    		$teiji = $users['Departmentmaster']['jobtermtime'];
    	}
    	$this->set('teiji',$teiji);
    	$teiji_sec = $this->Timediff->sec_change($teiji);

    	if ($this->request->is('post')) {
    		if(isset($this->request->data['confirm'])) {
    			$shinyazan_sec = 0;
    			/* saveする前に残業計算をする */
    			/* 勤務時間を計算 */
    			//$startdatetime = $this->request->data['Kintaitran']['datestart'].' '.$this->request->data['Kintaitran']['timestart'];
    			//$enddatetime = $this->request->data['Kintaitran']['dateend'].' '.$this->request->data['Kintaitran']['timeend'];

    			$datestart = $this->request->data['Kintaitran']['datestart'];
    			$timestart = $this->request->data['Kintaitran']['timestart'];
    			$dateend = $this->request->data['Kintaitran']['dateend'];
    			$timeend = $this->request->data['Kintaitran']['timeend'];
    			$startdatetime = $this->Timediff->datetime($datestart,$timestart);
    			$enddatetime = $this->Timediff->datetime($dateend,$timeend);

    			//$so_kinmu_jikan_sec = $this->Timediff->time_diff($this->request->data['Kintaitran']['datestart'],$this->request->data['Kintaitran']['dateend']);
    			$so_kinmu_jikan_sec = $this->Timediff->time_diff($startdatetime, $enddatetime);
    			$so_kinmu_jikan = $this->Timediff->his_change($so_kinmu_jikan_sec);

    			$this->request->data['Kintaitran']['so_kinmu_jikan'] = $so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'].':'.$so_kinmu_jikan['s'];
    			$kinmu_jikan_sec = $this->Timediff->time_diff_sec($this->request->data['Kintaitran']['kyukeitime'],$so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'].':'.$so_kinmu_jikan['s']);
    			/* 比較要素 */
    			//$w_date = explode('-',date('Y-m-d-H-i-s',strtotime($this->request->data['Kintaitran']['datestart'])));
    			//$w_date = explode('-',date('Y-m-d-H-i-s',strtotime($startdatetime)));

    			//$w5 = strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'5:0:0');
    			//$w22 = strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'22:0:0');
    			//$w5n = strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'5:0:0')+(24*60*60); // 翌朝5時

    			$w5 = $this->Timediff->w5($startdatetime);
    			$w22 = $this->Timediff->w22($startdatetime);
    			$w5n = $this->Timediff->w5n($startdatetime);

    			//$w_sigyo_sec = strtotime($this->request->data['Kintaitran']['datestart']);
    			//$w_syugyo_sec = strtotime($this->request->data['Kintaitran']['dateend']);

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
    			/* 勤務時間が定時以上の時の残業計算 */
    			$kinmu_jikan = $this->Timediff->his_change($kinmu_jikan_sec);
    			$zangyo = $this->Timediff->time_diff_sec($teiji,$kinmu_jikan['h'].':'.$kinmu_jikan['i'].':'.$kinmu_jikan['s']);
    			$w_holi = $this->Timediff->holiday_check($this->params['named']['kyy'],$this->params['named']['kmm'],$this->params['named']['kdd']);
    			/* 日曜なら法定休日残業にする（データが存在したら休日） */
    			$w_kyukei = $this->Timediff->sec_change($this->request->data['Kintaitran']['kyukeitime']);
    			if(empty($w_holi[0])){
    				/* 平日の場合 */
    				/* 休憩時間が１時間以上の場合 */
    				if($w_kyukei >= 3600){
    					//休憩時間差 ※1時間以上休んでいた場合
    					$kyukei_minus = $w_kyukei - 1 * 3600;
    					$kyukei_plus = 0;
    				}else{
    					//1時間未満の場合、平日残業時間へ加算
    					$kyukei_plus = 1 * 3600 - $w_kyukei;
    					$kyukei_minus = 0;
    				}

    				$heizan_sec = $kinmu_jikan_sec - $shinyazan_sec - $teiji_sec;

    				if ($heizan_sec > 0) {
    					$w_zan = $this->Timediff->his_change($heizan_sec);
    					//$this->request->data['Kintaitran']['heijitsuzangyo'] = sprintf("%02d",$w_zan['h']).':'.sprintf("%02d",$w_zan['i']).':'.sprintf("%02d",$w_zan['s']);
    					$this->request->data['Kintaitran']['heijitsuzangyo'] = $this->Timediff->sprintf($w_zan);
    				} else {
    					$this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00:00';
    				}
    				$w_his = $this->Timediff->his_change($shinyazan_sec);
    				//$this->request->data['Kintaitran']['heijitsushinya'] = sprintf("%02d",$w_his['h']).':'.sprintf("%02d",$w_his['i']).':'.sprintf("%02d",$w_his['s']);
    				$this->request->data['Kintaitran']['heijitsushinya'] = $this->Timediff->sprintf($w_his);

    			}else{
    				// 法定休日の場合
    				$houteizan_sec = $kinmu_jikan_sec - $shinyazan_sec;
    				$w_houteizan = $this->Timediff->his_change($houteizan_sec);
    				//$this->request->data['Kintaitran']['houteikyujitsu'] = sprintf("%02d",$w_houteizan['h']).':'.sprintf("%02d",$w_houteizan['i']).':'.sprintf("%02d",$w_houteizan['s']);
    				$this->request->data['Kintaitran']['houteikyujitsu'] = $this->Timediff->sprintf($w_houteizan);

    				$w_his = $this->Timediff->his_change($shinyazan_sec);
    				//$this->request->data['Kintaitran']['houteikyujitsushinya'] = sprintf("%02d",$w_his['h']).':'.sprintf("%02d",$w_his['i']).':'.sprintf("%02d",$w_his['s']);
    				$this->request->data['Kintaitran']['houteikyujitsushinya'] = $this->Timediff->sprintf($w_his);
    			}
    			$this->render('add_confirm');
    		} else {
    			$this->Kintaitran->create();
    			$this->request->data['Kintaitran']['users_id'] = $this->params['named']['uid'];
    			$selectdate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
    			$this->request->data['Kintaitran']['kintaidate'] = $selectdate;
    			if ($this->Kintaitran->save($this->request->data)) {
    				$this->Flash->success('保存しました。');
    				return $this->redirect(['controller' => 'Host010s', 'action' => 'index']);
    			}else{
    				$this->Flash->error('保存失敗です。');
    			}
    		}
    	}
    }

    public function edit($id = null) {
    	if (!$id) {
    		throw new NotFoundException(__('Invalid post'));
    	}

    	$post = $this->Kintaitran->findById($id);
    	if (!$post) {
    		throw new NotFoundException(__('Invalid post'));
    	}

    	$users = $this->User->findById($this->params['named']['uid']);
    	$this->set('userdata', $users);

    	/* 選択した社員の部署の定時時間取得 */
    	$mmdd = $this->params['named']['kmm'].$this->params['named']['kdd'];
    	if(($users['Kaisya']['summertimestart'] >= $mmdd)and($users['Kaisya']['summertimeend'] <= $mmdd)) {
    		// サマータイムのとき
    		$teiji = $users['Departmentmaster']['jobtermsummertime'];
    	}else{
    		$teiji = $users['Departmentmaster']['jobtermtime'];
    	}
    	$this->set('teiji',$teiji);
    	$teiji_sec = $this->Timediff->sec_change($teiji);

    	if ($this->request->is(['post', 'put'])) {
    		$shinyazan_sec = 0;
    		/* saveする前に残業計算をする */
    		/* 勤務時間を計算 */
    		//$this->request->data['Kintaitran']['datestart'] = $this->request->data['Kintaitran']['Sdate'].' '.$this->request->data['Kintaitran']['Stime'];
    		//$this->request->data['Kintaitran']['dateend'] = $this->request->data['Kintaitran']['Edate'].' '.$this->request->data['Kintaitran']['Etime'];

    		//$startdatetime = $this->request->data['Kintaitran']['datestart'].' '.$this->request->data['Kintaitran']['timestart'];
    		//$enddatetime = $this->request->data['Kintaitran']['dateend'].' '.$this->request->data['Kintaitran']['timeend'];

    		$datestart = $this->request->data['Kintaitran']['datestart'];
    		$timestart = $this->request->data['Kintaitran']['timestart'];
    		$dateend = $this->request->data['Kintaitran']['dateend'];
    		$timeend = $this->request->data['Kintaitran']['timeend'];
    		$startdatetime = $this->Timediff->datetime($datestart,$timestart);
    		$enddatetime = $this->Timediff->datetime($dateend,$timeend);

    		//$so_kinmu_jikan_sec = $this->Timediff->time_diff($this->request->data['Kintaitran']['datestart'],$this->request->data['Kintaitran']['dateend']);
    		$so_kinmu_jikan_sec = $this->Timediff->time_diff($startdatetime, $enddatetime);
    		$so_kinmu_jikan = $this->Timediff->his_change($so_kinmu_jikan_sec);

    		$this->request->data['Kintaitran']['so_kinmu_jikan'] = $so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'].':'.$so_kinmu_jikan['s'];
    		$kinmu_jikan_sec = $this->Timediff->time_diff_sec($this->request->data['Kintaitran']['kyukeitime'],$so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'].':'.$so_kinmu_jikan['s']);
    		/* 比較要素 */
    		//$w_date = explode('-',date('Y-m-d-H-i-s',strtotime($this->request->data['Kintaitran']['datestart'])));
    		//$w_date = explode('-',date('Y-m-d-H-i-s',strtotime($startdatetime)));

    		//$w5 = strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'5:0:0');
    		//$w22 = strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'22:0:0');
    		//$w5n = strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'5:0:0')+(24*60*60); // 翌朝5時

    		$w5 = $this->Timediff->w5($startdatetime);
    		$w22 = $this->Timediff->w22($startdatetime);
    		$w5n = $this->Timediff->w5n($startdatetime);

    		//$w_sigyo_sec = strtotime($this->request->data['Kintaitran']['datestart']);
    		//$w_syugyo_sec = strtotime($this->request->data['Kintaitran']['dateend']);
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
    		/* 勤務時間が定時以上の時の残業計算 */

    		$kinmu_jikan = $this->Timediff->his_change($kinmu_jikan_sec);
    		$zangyo = $this->Timediff->time_diff_sec($teiji,$kinmu_jikan['h'].':'.$kinmu_jikan['i'].':'.$kinmu_jikan['s']);
    		$w_holi = $this->Timediff->holiday_check($this->params['named']['kyy'],$this->params['named']['kmm'],$this->params['named']['kdd']);
    		/* 日曜なら法定休日残業にする（データが存在したら休日） */
    		$w_kyukei = $this->Timediff->sec_change($this->request->data['Kintaitran']['kyukeitime']);
    		if(empty($w_holi[0])){
    			/* 平日の場合 */
    			/* 休憩時間が１時間以上の場合 */
    			if($w_kyukei >= 3600){
    				//休憩時間差 ※1時間以上休んでいた場合
    				$kyukei_minus = $w_kyukei - 1 * 3600;
    				$kyukei_plus = 0;
    			}else{
    				//1時間未満の場合、平日残業時間へ加算
    				$kyukei_plus = 1 * 3600 - $w_kyukei;
    				$kyukei_minus = 0;
    			}

    			$heizan_sec = $kinmu_jikan_sec - $shinyazan_sec - $teiji_sec;

    			if ($heizan_sec > 0) {
    				$w_zan = $this->Timediff->his_change($heizan_sec);
    				//$this->request->data['Kintaitran']['heijitsuzangyo'] = sprintf("%02d",$w_zan['h']).':'.sprintf("%02d",$w_zan['i']).':'.sprintf("%02d",$w_zan['s']);
    				$this->request->data['Kintaitran']['heijitsuzangyo'] = $this->Timediff->sprintf($w_zan);
    			} else {
    				$this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00:00';
    			}
    			$w_his = $this->Timediff->his_change($shinyazan_sec);
    			//$this->request->data['Kintaitran']['heijitsushinya'] = sprintf("%02d",$w_his['h']).':'.sprintf("%02d",$w_his['i']).':'.sprintf("%02d",$w_his['s']);
    			$this->request->data['Kintaitran']['heijitsushinya'] = $this->Timediff->sprintf($w_his);

    		}else{
    			// 法定休日の場合
    			$houteizan_sec = $kinmu_jikan_sec - $shinyazan_sec;
    			$w_houteizan = $this->Timediff->his_change($houteizan_sec);
    			//$this->request->data['Kintaitran']['houteikyujitsu'] = sprintf("%02d",$w_houteizan['h']).':'.sprintf("%02d",$w_houteizan['i']).':'.sprintf("%02d",$w_houteizan['s']);
    			$this->request->data['Kintaitran']['houteikyujitsu'] = $this->Timediff->sprintf($w_houteizan);

    			$w_his = $this->Timediff->his_change($shinyazan_sec);
    			//$this->request->data['Kintaitran']['houteikyujitsushinya'] = sprintf("%02d",$w_his['h']).':'.sprintf("%02d",$w_his['i']).':'.sprintf("%02d",$w_his['s']);
    			$this->request->data['Kintaitran']['houteikyujitsushinya'] = $this->Timediff->sprintf($w_his);
    		}

    		//$this->Kintaitran->create();
    		$this->request->data['Kintaitran']['users_id'] = $this->params['named']['uid'];
    		//$selectdate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
    		//$this->request->data['Kintaitran']['kintaidate'] = $selectdate;
    		$this->Kintaitran->id = $id;
    		//$this->Kintaitran->eigyokiro = $this->request->data['Kintaitran']['eigyokiro'];
    		if ($this->Kintaitran->save($this->request->data)) {
    			$this->Flash->success('保存しました。');
    			//return $this->redirect(['action' => 'edit',$this->Kintaitran->id,'uid'=>$this->params['named']['uid'],'kyy'=>$this->params['named']['kyy'],'kmm'=>$this->params['named']['kmm'],'kdd'=>$this->params['named']['kdd']]);
    			return $this->redirect(['controller' => 'Host010s', 'action' => 'index']);
    		}else{
    			$this->Flash->error('保存失敗です。');
    		}
    	}

    	if (!$this->request->data) {
    		$this->request->data = $post;
    	}

    	$flgmasters = $this->Flgmaster->flgchk(1);
    	$this->set('flgmasters', $flgmasters);
    }

	public function edit2($id = null) {
	    if (!$this->Kintaitran->exists($id)) {
	        // 新規追加
	    }
	    $users = $this->User->findById($this->params['named']['uid']);
	    $this->set('userdata',$users);
	    /* 選択した社員の部署の定時時間取得 */
	    $mmdd = $this->params['named']['kmm'].$this->params['named']['kdd'];
	    if(($users['Kaisya']['summertimestart'] >= $mmdd)and($users['Kaisya']['summertimeend'] <= $mmdd))
	    {
	        // サマータイムのとき
	        $teiji = $users['Departmentmaster']['jobtermsummertime'];
	    }else{
	        $teiji = $users['Departmentmaster']['jobtermtime'];
	    }
	    $this->set('teiji',$teiji);
	    $teiji_sec = $this->Timediff->sec_change($teiji);


	    if ($this->request->is(array('post', 'put'))) {
	        $shinyazan_sec = 0;
	        /* saveする前に残業計算をする */
	        /* 勤務時間を計算 */
	        $so_kinmu_jikan_sec = $this->Timediff->time_diff($this->request->data['Kintaitran']['datestart'],$this->request->data['Kintaitran']['dateend']);
	        $so_kinmu_jikan = $this->Timediff->his_change($so_kinmu_jikan_sec);

            $this->request->data['Kintaitran']['so_kinmu_jikan'] = $so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'].':'.$so_kinmu_jikan['s'];
            $kinmu_jikan_sec = $this->Timediff->time_diff_sec($this->request->data['Kintaitran']['kyukeitime'],$so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'].':'.$so_kinmu_jikan['s']);
            /* 比較要素 */
	        $w_date = explode('-',date('Y-m-d-H-i-s',strtotime($this->request->data['Kintaitran']['datestart'])));

	        $w5 = strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'5:0:0');
	        $w22 = strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'22:0:0');
	        $w5n = strtotime($w_date[0].'-'.$w_date[1].'-'.$w_date[2].' '.'5:0:0')+(24*60*60); // 翌朝5時

	        $w_sigyo_sec = strtotime($this->request->data['Kintaitran']['datestart']);
	        $w_syugyo_sec = strtotime($this->request->data['Kintaitran']['dateend']);
	        /* 出勤が5時より前の場合 */
	        if($w_sigyo_sec < $w5){
	            $shinyazan_sec = $shinyazan_sec + ($w5 - $w_sigyo_sec);
	        }
            /* 22時以降の退社 */
	        if($w_syugyo_sec > $w22){
	            $shinyazan_sec = $shinyazan_sec + ($w_syugyo_sec - $w22);
	        }
            /* 勤務時間が定時以上の時の残業計算 */

	        $kinmu_jikan = $this->Timediff->his_change($kinmu_jikan_sec);
	        $zangyo = $this->Timediff->time_diff_sec($teiji,$kinmu_jikan['h'].':'.$kinmu_jikan['i'].':'.$kinmu_jikan['s']);
	        $w_holi = $this->Timediff->holiday_check($this->params['named']['kyy'],$this->params['named']['kmm'],$this->params['named']['kdd']);
            /* 日曜なら法定休日残業にする（データが存在したら休日） */
	        $w_kyukei = $this->Timediff->sec_change($this->request->data['Kintaitran']['kyukeitime']);
	        if(empty($w_holi[0])){
	            /* 平日の場合 */
	            /* 休憩時間が１時間以上の場合 */
	            if($w_kyukei >= 3600){
	                //休憩時間差 ※1時間以上休んでいた場合
	                $kyukei_minus = $w_kyukei - 1 * 3600;
	                $kyukei_plus = 0;
	            }else{
	                //1時間未満の場合、平日残業時間へ加算
	                $kyukei_plus = 1 * 3600 - $w_kyukei;
	                $kyukei_minus = 0;
	            }

	            $heizan_sec = $kinmu_jikan_sec - $shinyazan_sec - $teiji_sec;

	            if ($heizan_sec > 0) {
	            	$w_zan = $this->Timediff->his_change($heizan_sec);
	            	$this->request->data['Kintaitran']['heijitsuzangyo'] = sprintf("%02d",$w_zan['h']).':'.sprintf("%02d",$w_zan['i']).':'.sprintf("%02d",$w_zan['s']);
	            } else {
	            	$this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00:00';
	            }
	            $w_his = $this->Timediff->his_change($shinyazan_sec);
	            $this->request->data['Kintaitran']['heijitsushinya'] = sprintf("%02d",$w_his['h']).':'.sprintf("%02d",$w_his['i']).':'.sprintf("%02d",$w_his['s']);

	        }else{
	            // 法定休日の場合
	            $houteizan_sec = $kinmu_jikan_sec - $shinyazan_sec;
	            $w_houteizan = $this->Timediff->his_change($houteizan_sec);
	            $this->request->data['Kintaitran']['houteikyujitsu'] = sprintf("%02d",$w_houteizan['h']).':'.sprintf("%02d",$w_houteizan['i']).':'.sprintf("%02d",$w_houteizan['s']);

	            $w_his = $this->Timediff->his_change($shinyazan_sec);
	            $this->request->data['Kintaitran']['houteikyujitsushinya'] = sprintf("%02d",$w_his['h']).':'.sprintf("%02d",$w_his['i']).':'.sprintf("%02d",$w_his['s']);
	        }

	        $this->request->data['Kintaitran']['users_id']=$this->params['named']['uid'];
	        $selectdate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
	        $this->request->data['Kintaitran']['kintaidate']=$selectdate;
    	        $this->Kintaitran->id=$this->request->data['Kintaitran']['id'];
    	        $this->Kintaitran->eigyokiro=$this->request->data['Kintaitran']['eigyokiro'];
    	        if ($this->Kintaitran->save($this->request->data)) {
    	            $this->Flash->success('保存しました。');
    	            return $this->redirect(array('action' => 'edit',$this->Kintaitran->id,'uid'=>$this->params['named']['uid'],'kyy'=>$this->params['named']['kyy'],'kmm'=>$this->params['named']['kmm'],'kdd'=>$this->params['named']['kdd']));
    	        }else{
    	            $this->Flash->error('保存失敗です。');
    	        }
	    }else{
	        if($id){
        	        $options = array('conditions' => array('Kintaitran.' . $this->Kintaitran->primaryKey => $id));
        	        $this->request->data = $this->Kintaitran->find('first', $options);
        	        $selectdate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
	        }
	    }

	    $flgmasters = $this->Flgmaster->flgchk(1);
	    $this->set('flgmasters',$flgmasters);
	}
}
