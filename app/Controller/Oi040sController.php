<?php
App::uses('AppController', 'Controller');
/**
 * Oi040s Controller
 * @copy Host010
 */
class Oi040sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form','Session','Js' => ['Jquery']);
	public $uses = array('User','Kaisya','Branchmaster','Departmentmaster','Kintaitran','Flgmaster','Holiday','Ordertran','Processtran','Calendarmaster');
	public $components = ['Search.Prg','RequestHandler','Timediff','SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 1;
		$this->page = 'Oi040s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 20; //事務職（石油）
				case 24; //事務職（全て）
				$this->set('user', $user);
				return true;
				break;
			}
		}
		return false;
		break;
	}
	/**
	 * index method
	 *
	 * @return void
	 */
	public function index($did = null) {
		if(isset($did)){
			$this->Session->write('did',$did);
		}else{
			$did = $this->Session->read('did');
		}
		// $page = 'Oi040s';
		$page = $this->page;

		$flgmasters = $this->Flgmaster->flgchk(1);
		$this->set('flgmasters',$flgmasters);
		$branchmasters_id = $this->branchmasters_id;
		$branchmasters = $this->SelectData->branch_select_conditions($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);
		$departmentmasters = $this->Departmentmaster->SelectDepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('branchmasters','departmentmasters'));

		$date = new DateTime();
		$departmentmasters_id = $did;

		if ($this->request->is('post')) {
			$date = $this->SelectData->date_select($this->request->data['selectym1']);
			$branchmasters_id = $this->request->data['branchmasters_id'];
			// $departmentmasters_id = $did;
			$departmentmasters_id = $this->request->data('departmentmasters_id');
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
			}else{
				$this->Session->write('BackData1.Page',$page);
			}
		}

		$paramsDate = $date->format('Y-m-d');
		$paramsDate2 = $date->modify('+6 day')->format('Y-m-d');
		$week = ['日','月','火','水','木','金','土'];
		$this->set(compact('paramsDate','week','departmentmasters_id','branchmasters_id'));

		//$users = $this->SelectData->userdata_select_conditions($paramsDate,$paramsDate2,$departmentmasters_id,$branchmasters_id);
		$users = $this->SelectData->data_select_conditionsHost010s($paramsDate,$paramsDate2,$branchmasters_id,$departmentmasters_id,$this->kaisyas_id);
		$this->set('users', $users);
	}

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
	public function add(){
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
		if(($users['Kaisya']['summertimestart'] >= $mmchk)and($users['Kaisya']['summertimeend'] <= $mmchk)) {
			// サマータイムのとき
			$teiji = $users['Departmentmaster']['jobtermsummertime'];
			$date1 = $users['Departmentmaster']['jobstartsummertime'];
			$date2 = $users['Departmentmaster']['jobendsummertime'];
		}else{
			$teiji = $users['Departmentmaster']['jobtermtime'];
			$date1 = $users['Departmentmaster']['jobstarttime'];
			$date2 = $users['Departmentmaster']['jobendtime'];
		}
		$this->set('teiji',$teiji);
		$teiji_sec = $this->Timediff->sec_change($teiji);

		//自分が運転手のデータを抽出
		$ordertrans1 = $this->Ordertran->SelectOrdertran040s1($uid,$paramsDate);
		$this->set('ordertrans1',$ordertrans1);

		// 自分が助手のデータを抽出
		$ordertrans2 = $this->Ordertran->SelectOrdertran040s2($uid,$paramsDate);
		$this->set('ordertrans2',$ordertrans2);

		if ($this->request->is(['patch', 'post', 'put'])) {
			$shinyazan_sec = 0;
			/* saveする前に残業計算をする */
			/* 勤務時間を計算 */

			$datestart = $this->request->data['Kintaitran']['datestart'];
			$timestart = $this->request->data['Kintaitran']['timestart'];
			$dateend = $this->request->data['Kintaitran']['dateend'];
			$timeend = $this->request->data['Kintaitran']['timeend'];
			$startdatetime = $this->Timediff->datetime($datestart,$timestart);
			$enddatetime = $this->Timediff->datetime($dateend,$timeend);
			//拘束時間（総勤務時間）
			$so_kinmu_jikan_sec = $this->Timediff->time_diff($startdatetime, $enddatetime);
			$so_kinmu_jikan = $this->Timediff->his_change($so_kinmu_jikan_sec);
			$this->request->data['Kintaitran']['so_kinmu_jikan'] = $so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'];
			//運転時間
			if($this->request->data['Kintaitran']['tako_datestart']){
				//日時が入力されていたら計算
				$startdatetime2 = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_datestart'],$this->request->data['Kintaitran']['tako_timestart']);
				$enddatetime2 = $this->Timediff->datetime($this->request->data['Kintaitran']['tako_dateend'],$this->request->data['Kintaitran']['tako_timeend']);
				$drivingtime_sec = $this->Timediff->time_diff($startdatetime2, $enddatetime2);
				$drivingtime = $this->Timediff->his_change($drivingtime_sec);
				$this->request->data['Kintaitran']['drivingtime'] = $drivingtime['h'].':'.$drivingtime['i'];
			}

			$kinmu_jikan_sec = $this->Timediff->time_diff_sec($this->request->data['Kintaitran']['kyukeitime'],$so_kinmu_jikan['h'].':'.$so_kinmu_jikan['i'].':'.$so_kinmu_jikan['s']);
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
			/* 勤務時間が定時以上の時の残業計算 */
			$kinmu_jikan = $this->Timediff->his_change($kinmu_jikan_sec);
			$zangyo = $this->Timediff->time_diff_sec($teiji,$kinmu_jikan['h'].':'.$kinmu_jikan['i'].':'.$kinmu_jikan['s']);
			/* 日曜なら法定休日残業にする（データが存在したら休日） */
			$w_kyukei = $this->Timediff->sec_change($this->request->data['Kintaitran']['kyukeitime']);
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
				// 平日の場合

				$heizan_sec = $kinmu_jikan_sec - $shinyazan_sec - $teiji_sec;

				if ($heizan_sec > 0) {
					$w_zan = $this->Timediff->his_change($heizan_sec);
					$this->request->data['Kintaitran']['heijitsuzangyo'] = $this->Timediff->sprintf($w_zan);
				} else {
					$this->request->data['Kintaitran']['heijitsuzangyo'] = '00:00';
				}
				$w_his = $this->Timediff->his_change($shinyazan_sec);
				$this->request->data['Kintaitran']['heijitsushinya'] = $this->Timediff->sprintf($w_his);
			}
			$this->Kintaitran->create();
			if($id){
				$this->request->data['Kintaitran']['id'] = $id;
			}
			$this->request->data['Kintaitran']['users_id'] = $this->params['named']['uid'];
			$selectdate = $this->params['named']['kyy'].'-'.$this->params['named']['kmm'].'-'.$this->params['named']['kdd'];
			$this->request->data['Kintaitran']['kintaidate'] = $selectdate;
			if ($this->Kintaitran->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return;
			}else{
				$this->Flash->error('保存失敗です。');
			}
		}else{
			// 登録ボタンを押してない状態
			if($id){
				// 既存データ用
				$kintaitrans = $this->Kintaitran->findById($id);
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
				$w_tako_timeend = $timeend->format('H:i');
				$this->request->data['Kintaitran']['tako_timeend'] = $w_tako_timeend;
			}else{
				// 新規登録時用
				$kintaitrans = null;
				$i=0;
				$ordertrans1flg = 0;
				$ordertrans2flg = 0;
				//運転手
				foreach((array)$ordertrans1 as $ordertran1){
					foreach((array)$ordertran1['Processtran'] as $process1){
						if($i==0){
							// 最初のレコードのみ取得
							$processwork1_first_date = $process1['processstartdate'];
							$processwork1_first_time = $process1['processstarttime'];
						}else{
							$processwork1_last_date = $process1['processenddate'];
							$processwork1_last_time = $process1['processendtime'];
						}
						$i++;
					}
					$ordertrans1flg = 1;
				}
				if($ordertrans1flg==1){
					// processデータが存在した場合
					$tako_date1 = new DateTime($processwork1_first_date.' '.$processwork1_first_time);
					$tako_date2 = new DateTime($processwork1_last_date.' '.$processwork1_last_time);
					$this->request->data['Kintaitran']['tako_datestart'] = $processwork1_first_date;
					$this->request->data['Kintaitran']['tako_timestart'] = $tako_date1->format('H:i');
					$this->request->data['Kintaitran']['tako_dateend'] = $processwork1_last_date;
					$this->request->data['Kintaitran']['tako_timeend'] = $tako_date2->format('H:i');
				}
				//助手
				foreach((array)$ordertrans2 as $ordertran2){
					foreach((array)$ordertran2['Processtran'] as $process2){
						if($i==0){
							// 最初のレコードのみ取得
							$processwork2_first_date = $process2['processstartdate'];
							$processwork2_first_time = $process2['processstarttime'];
						}else{
							$processwork2_last_date = $process2['processenddate'];
							$processwork2_last_time = $process2['processendtime'];
						}
						$i++;
					}
					$ordertrans2flg = 1;
				}
				if($ordertrans2flg==1){
					// processデータが存在した場合
					$tako_date1 = new DateTime($processwork2_first_date.' '.$processwork2_first_time);
					$tako_date2 = new DateTime($processwork2_last_date.' '.$processwork2_last_time);
					$this->request->data['Kintaitran']['tako_datestart'] = $processwork2_first_date;
					$this->request->data['Kintaitran']['tako_timestart'] = $tako_date1->format('H:i');
					$this->request->data['Kintaitran']['tako_dateend'] = $processwork2_last_date;
					$this->request->data['Kintaitran']['tako_timeend'] = $tako_date2->format('H:i');
				}
				if(($ordertrans1flg==0)&&($ordertrans2flg==0)){
					// オーダーがない場合
					$timestart = new DateTime($date1);
					$w_timestart = $timestart->format('H:i');
					$this->request->data['Kintaitran']['timestart'] = $w_timestart;
					$timeend = new DateTime($date2);
					$w_timeend = $timeend->format('H:i');
					$this->request->data['Kintaitran']['timeend'] = $w_timeend;
					$this->request->data['Kintaitran']['datestart'] = $paramsDate;
					$this->request->data['Kintaitran']['dateend'] = $paramsDate;
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
