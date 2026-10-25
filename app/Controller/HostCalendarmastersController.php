<?php
App::uses('AppController', 'Controller');
/**
 * HostCalendarmasters Controller
 *
 */
class HostCalendarmastersController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = ['Calendarmaster','Calendarusermaster','User'];
	public $components = array('Search.Prg');
	public $presetVars = array();

	public function beforeFilter(){
		parent::beforeFilter();
		//$this->set('auth',$this->Auth);
	}

	public function isAuthorized($user){
		if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '19'){
			$this->set('user',$user);
			return true;
		}
		return false;
		break;
	}

	public function index($tyy1=null,$tmm1=null) {
		if ($this->request->is('post')) {
			if (!$this->params->data['selectym1']) {
				$tyy1 = date('Y');
				$tmm1 = date('m');
			} else {
				$tyy1 = $this->params->data['selectym1']['year'];
				$tmm1 = $this->params->data['selectym1']['month'];
			}
		}else{
			if ($this->Session->read('paramsDate')) {
				$sessiondate = new DateTime($this->Session->read('paramsDate'));
				$tyy1 = $sessiondate->format('Y');
				$tmm1 = $sessiondate->format('m');
				$this->Session->delete('paramsDate');
			} else {
				$tyy1 = date('Y');
				$tmm1 = date('m');
			}
		}
		$paramsDate = $tyy1.'-'.$tmm1.'-01';

		$array=array(
			date("Y/m/d",mktime(0,0,0,$tmm1+0,1,$tyy1)),
			date("Y/m/d",mktime(0,0,0,$tmm1+1,0,$tyy1))
		);

		/*
		 * カレンダーマスタ読み込み
		 */
		$calendarmasters = $this->Calendarmaster->find('all', [
		    'coditions'=>[
		        'Calendarmaster.kaisyas_id'=>$this->kaisyas_id
		    ],
			'recursive' => 0,
		]);
		$this->set('calendarmasters',$calendarmasters);

		$tsta=$array[0];
		$tend=$array[1];

		$i=0;
		while(strcmp(date('N',strtotime($tsta)),"7")) {
			$tsta=date("Y/m/d",strtotime($tsta." -1 day"));
		}
		while(strcmp(date('N',strtotime($tend)),"6")) {
			$tend=date("Y/m/d",strtotime($tend." +1 day"));
		}

		$calendar=array();
		$week=array();
		$header=array();
		$temp=$tsta;
		$weekja = [
			'日', //0
			'月', //1
			'火', //2
			'水', //3
			'木', //4
			'金', //5
			'土', //6
		];
		while(1==1) {
			if(count($week)==7) {
				array_push($calendar,$week);
				if(count($header)!=7) {
					foreach($week as $key=>$value) {
						array_push($header,$weekja[date("w",strtotime($value))]);
					}
				}
				$week=array();
			}
			array_push($week,$temp);
			if(strtotime($tend)==strtotime($temp)) {
				array_push($calendar,$week);
				break;
			}
			$temp=date("Y/m/d",strtotime($temp." +1 day"));
		};

		$url = Router::url(['controller' => 'HostCalendarmasters', 'action' => 'add']);
		foreach($calendar as $key=>$value) {
			foreach($value as $key2=>$value2) {
				$conditionDate = new DateTime($value2);
				$checkData = $this->Calendarmaster->find('first', [
					'conditions' => [
						'restdate' => $conditionDate->format('Y-m-d'),
					    'kaisyas_id' => $this->kaisyas_id
					]
				]);
				if ($checkData) {
					$id = $checkData['Calendarmaster']['id'];
					$restflg = (int)$checkData['Calendarmaster']['restflg'];
				} else {
					$id = null;
					$restflg = null;
				}
				$selected0 = $selected1 = $selected2 = null;
				if ($restflg == 0) {
					$selected0 = ' selected';
				} elseif ($restflg == 1) {
					$selected1 = ' selected';
				} elseif ($restflg == 2) {
					$selected2 = ' selected';
				}

				$calendar[$key][$key2]=intval(date("m",strtotime($value2)))!=intval($tmm1)?"&nbsp;":date("d",strtotime($value2)).
				//'<form action="/HostCalendarmasters/add" method="post" onchange="submit(this.form)">'.
				'<form action="'.$url.'" method="post" onchange="submit(this.form)">'.
				'<input type="hidden" name="id" value="'.$id.'">'.
				'<input type="hidden" name="restdate" value="'.$conditionDate->format('Y-m-d').'">'.
				//'<select name="'.$tyy1.$tmm1.date("d",strtotime($value2)).'"><option value=0>通常</option><option value=1>法定外休日</option><option value=2>法定休日</option></select>'.
				'<select name="restflg" data-rel="chosen"><option value=0'.$selected0.'>通常</option><option value=1'.$selected1.'>法定外休日</option><option value=2'.$selected2.'>法定休日</option></select>'.
				'</form>';
			}
		}
		$this->set("calendar",$calendar);

		$this->set("header",$header);
		$this->set('paramsDate',$paramsDate);
	}

	public function add($id = null){
		if ($this->request->is('post')) {
			if (!$this->request->data['id']) {
				$this->Calendarmaster->create();
			} else {
				$this->request->data['Calendarmaster']['id'] = $this->request->data['id'];
			}
			$this->request->data['Calendarmaster']['restdate'] = $this->request->data['restdate'];
			$this->request->data['Calendarmaster']['restflg'] = $this->request->data['restflg'];
			$this->request->data['Calendarmaster']['kaisyas_id'] = $this->Auth->user('kaisyas_id');

			if(($this->request->data['Calendarmaster']['restflg']==0)and($this->request->data['id'])){
				//平日（０）かつ、IDがある場合は削除
				$this->Calendarmaster->delete($this->request->data['id']);
				return $this->redirect(['controller' => 'HostCalendarmasters', 'action' => 'index']);
			}else{
				if ($this->Calendarmaster->save($this->request->data)) {
					$this->Session->write('paramsDate',$this->request->data['restdate']);
					$this->Flash->success('保存しました');
					return $this->redirect(['controller' => 'HostCalendarmasters', 'action' => 'index']);
				} else {
					$this->Flash->error('失敗しました');
				}
			}
		}
	}
	/* newの方 */
	public function alladd($paramsDate) {
		$date = new DateTime($paramsDate);
		$date2 = new DateTime($paramsDate);

		$date->modify('first day of this months');
		$date2->modify('last day of this months');

		$firstday = $date->format('Y-m-d');
		$lastday = $date2->format('Y-m-d');

		$monthcount = $date2->format('d');

		$calendars = $this->Calendarmaster->find('all', [
			'fields' => [
				'Calendarmaster.id',
				'Calendarmaster.restdate',
				'Calendarmaster.restflg',
			],
			'conditions' => [
				'Calendarmaster.restdate >=' => $firstday,
				'Calendarmaster.restdate <=' => $lastday,
			    'Calendarmaster.kaisyas_id' => $this->kaisyas_id
			],
			'recursive' => -1
		]);

		$usercalendars = $this->Calendarusermaster->find('count', [
			'conditions' => [
				'Calendarusermaster.month' => $firstday,
			    'Calendarusermaster.kaisyas_id' => $this->kaisyas_id
			]
		]);

		if ($usercalendars) {
			$this->Flash->error('既にカレンダーが作成されています。');
			return $this->redirect(['action' => 'index']);
		}

		$holidaycount = $this->Calendarmaster->find('count', [
			'conditions' => [
				'Calendarmaster.restdate >=' => $firstday,
				'Calendarmaster.restdate <=' => $lastday,
			    'Calendarmaster.kaisyas_id' => $this->kaisyas_id
			]
		]);

		if (!$holidaycount) {
			$this->Flash->error('法定休日及び法定外休日が一件も登録されていません。');
			return $this->redirect(['action' => 'index']);
		}

		$baseday = intval($monthcount - $holidaycount);
		$userlists = ['1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6]; //A,B,C,D,E,F
		$i = 0;
		foreach ($calendars as $calendar) {
			$restday = new DateTime($calendar['Calendarmaster']['restdate']);
			$restflg = $calendar['Calendarmaster']['restflg'];
			$holidays[intval($restday->format('d'))] = $restflg;
		}
		foreach ($userlists as $userlist) {
		    $data['Calendarusermaster'][$i]['users_id'] = 0;
		    $data['Calendarusermaster'][$i]['calendartype'] = $userlist;
		    $data['Calendarusermaster'][$i]['kaisyas_id'] = $this->kaisyas_id;
			$data['Calendarusermaster'][$i]['month'] = $firstday;
			$data['Calendarusermaster'][$i]['baseday'] = $baseday;

			for ($j = 1; $j <= 31; $j++) {
				$k = sprintf('%02d', $j);
				if (isset($holidays[$j])) {
					$restflg2 = $holidays[$j];
				} else {
					$restflg2 = 0;
				}
				if ($restflg2 != 0) {
					$data['Calendarusermaster'][$i]['day'.$k] = $restflg2;
				}
			}

			$i = $i + 1;
		}

		if ($this->Calendarusermaster->saveAll($data['Calendarusermaster'])) {
			$this->Flash->success('全タイプのデータを作成しました。');
		} else {
			$this->Flash->error('データの作成に失敗しました。');
		}
		return $this->redirect(['action' => 'index']);

	}
//もとの方
/*
	public function alladd($paramsDate) {
		$date = new DateTime($paramsDate);
		$date2 = new DateTime($paramsDate);

		$date->modify('first day of this months');
		$date2->modify('last day of this months');

		$firstday = $date->format('Y-m-d');
		$lastday = $date2->format('Y-m-d');

		$monthcount = $date2->format('d');

		$calendars = $this->Calendarmaster->find('all', [
			'fields' => [
				'Calendarmaster.id',
				'Calendarmaster.restdate',
				'Calendarmaster.restflg',
			],
			'conditions' => [
				'Calendarmaster.restdate >=' => $firstday,
				'Calendarmaster.restdate <=' => $lastday,
			],
			'recursive' => -1
		]);

		$usercalendars = $this->Calendarusermaster->find('count', [
			'conditions' => [
				'Calendarusermaster.month' => $firstday,
			]
		]);

		if ($usercalendars) {
			$this->Flash->error('既に全員分が作成されています。');
			return $this->redirect(['action' => 'index']);
		}

		$holidaycount = $this->Calendarmaster->find('count', [
			'conditions' => [
				'Calendarmaster.restdate >=' => $firstday,
				'Calendarmaster.restdate <=' => $lastday,
			]
		]);

		if (!$holidaycount) {
			$this->Flash->error('法定休日及び法定外休日が一件も登録されていません。');
			return $this->redirect(['action' => 'index']);
		}

		$baseday = intval($monthcount - $holidaycount);

		$userlists = $this->User->find('all', [
			'fields' => [
				'User.id',
				'User.usernameK',
				'User.kaisyas_id',
			],
			'conditions' => [
				'User.zaisekiflg' => 0,
			],
			'recursive' => -1
		]);

		$i = 0;
		foreach ($calendars as $calendar) {
			$restday = new DateTime($calendar['Calendarmaster']['restdate']);
			$restflg = $calendar['Calendarmaster']['restflg'];
			$holidays[intval($restday->format('d'))] = $restflg;
		}

		foreach ($userlists as $userlist) {
			$data['Calendarusermaster'][$i]['users_id'] = $userlist['User']['id'];
			$data['Calendarusermaster'][$i]['kaisyas_id'] = $userlist['User']['kaisyas_id'];
			$data['Calendarusermaster'][$i]['month'] = $firstday;
			$data['Calendarusermaster'][$i]['baseday'] = $baseday;

			for ($j = 1; $j <= 31; $j++) {
				$k = sprintf('%02d', $j);
				if (isset($holidays[$j])) {
					$restflg2 = $holidays[$j];
				} else {
					$restflg2 = 0;
				}
				if ($restflg2 != 0) {
					$data['Calendarusermaster'][$i]['day'.$k] = $restflg2;
				}
			}

			$i = $i + 1;
		}

		// debug($data);

		if ($this->Calendarusermaster->saveAll($data['Calendarusermaster'])) {
			$this->Flash->success('全員分のデータを作成しました。');
		} else {
			$this->Flash->error('データの作成に失敗しました。');
		}
		return $this->redirect(['action' => 'index']);

	}
*/
	public function alldelete($paramsDate) {
		$date = new DateTime($paramsDate);
		$date2 = new DateTime($paramsDate);

		$date->modify('first day of this months');
		$date2->modify('last day of this months');

		$firstday = $date->format('Y-m-d');
		$lastday = $date2->format('Y-m-d');

		$usercalendars = $this->Calendarusermaster->find('count', [
			'conditions' => [
				'Calendarusermaster.month' => $firstday,
			    'Calendarusermaster.kaisyas_id' => $this->kaisyas_id
			]
		]);

		if (!$usercalendars) {
			$this->Flash->error($date->format('Y年m月').'の個人データは存在していません。既に削除されたか作成されていません。');
			return $this->redirect(['action' => 'index']);
		}

		$delconditions = [
			'Calendarusermaster.month' => $firstday,
		    'Calendarusermaster.kaisyas_id' => $this->kaisyas_id
		];

		if ($this->Calendarusermaster->deleteAll($delconditions, false)) {
			$this->Flash->success($date->format('Y年m月').'の作成済み個人データを全削除しました。');
		} else {
			$this->Flash->error('個人データの削除に失敗しました。');
		}
		return $this->redirect(['action' => 'index']);

	}
}
