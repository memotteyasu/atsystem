<?php
App::uses('AppController', 'Controller');
/**
 * HostTops Controller
 *
 * @property HostTop $HostTops
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class HostTopsController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Personalmessage','Gyoumumessage','Employeeholiday','Receptionmessage','Calendarmaster','User','Schedule');

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
    }
/**
 * index method
 *
 * @return void
 */
	public function index() {
	    $date = new DateTime();
	    $weekOffset = isset($this->request->query['week_offset']) ? (int)$this->request->query['week_offset'] : 0;
	    $weekOffset = max(-11, min($weekOffset, 11));
	    $weekStart = new DateTime('today');
	    $weekStart->modify('+' . ($weekOffset * 7) . ' days');
	    $weekEnd = clone $weekStart;
	    $weekEnd->modify('+6 days');
	    $weekDates = array();
	    $weekOptions = array();
	    $weekdayLabels = array('月', '火', '水', '木', '金', '土', '日');
	    for ($i = -11; $i < 12; $i++) {
		$optionStart = new DateTime('today');
		$optionStart->modify('+' . ($i * 7) . ' days');
		$optionEnd = clone $optionStart;
		$optionEnd->modify('+6 days');
		$weekOptions[$i] = $optionStart->format('n/j') . '〜' . $optionEnd->format('n/j');
	    }
	    for ($i = 0; $i < 7; $i++) {
		$weekDate = clone $weekStart;
		$weekDate->modify('+' . $i . ' days');
		$weekDates[] = array(
			'date' => $weekDate->format('Y-m-d'),
			'label' => $weekDate->format('n/j') . '（' . $weekdayLabels[$weekDate->format('N') - 1] . '）',
			'weekday' => (int)$weekDate->format('N')
		);
	    }

	    $scheduleUsers = $this->User->find('all', array(
		'fields' => array('User.id', 'User.usernameK', 'User.displayno'),
		'conditions' => array(
			'User.kaisyas_id' => $this->Auth->user('kaisyas_id'),
			'User.zaisekiflg' => 0,
			'User.rolemasters_id <=' => 28
		),
		'order' => array('User.displayno' => 'asc', 'User.usernameK' => 'asc'),
		'recursive' => -1
	    ));

	    $weekSchedules = $this->Employeeholiday->find('all', array(
		'fields' => array('Employeeholiday.users_id', 'Employeeholiday.applicationdate', 'Flgmaster.flgname', 'Flgmaster2.flgname'),
		'conditions' => array(
			'User.kaisyas_id' => $this->Auth->user('kaisyas_id'),
			'User.rolemasters_id <=' => 28,
			'Employeeholiday.applicationdate >=' => $weekStart->format('Y-m-d'),
			'Employeeholiday.applicationdate <=' => $weekEnd->format('Y-m-d'),
			'Employeeholiday.flgmasters_id2' => 13,
			'Employeeholiday.flgmasters_id' => array(41, 42)
		),
		'recursive' => 0
	    ));
	    $calendarEntriesByUserAndDate = array();
	    foreach ($weekSchedules as $weekSchedule) {
		$userId = $weekSchedule['Employeeholiday']['users_id'];
		$scheduleDate = $weekSchedule['Employeeholiday']['applicationdate'];
		$scheduleName = $weekSchedule['Flgmaster2']['flgname'];
		if ($weekSchedule['Flgmaster']['flgname'] === '申請中') {
			$scheduleName .= '（申請中）';
		}
		$calendarEntriesByUserAndDate[$userId][$scheduleDate][] = array(
			'name' => $scheduleName,
			'color' => '#d9edf7'
		);
	    }

	    $employeeSchedules = $this->Schedule->find('all', array(
		'fields' => array('Schedule.users_id', 'Schedule.scheduledate', 'Schedule.schedulename', 'Schedule.displaycolor'),
		'conditions' => array(
			'User.kaisyas_id' => $this->Auth->user('kaisyas_id'),
			'User.rolemasters_id <=' => 28,
			'Schedule.scheduledate >=' => $weekStart->format('Y-m-d'),
			'Schedule.scheduledate <=' => $weekEnd->format('Y-m-d'),
			'Schedule.delflg' => 0
		),
		'recursive' => 0
	    ));
	    foreach ($employeeSchedules as $employeeSchedule) {
		$userId = $employeeSchedule['Schedule']['users_id'];
		$scheduleDate = $employeeSchedule['Schedule']['scheduledate'];
		$calendarEntriesByUserAndDate[$userId][$scheduleDate][] = array(
			'name' => $employeeSchedule['Schedule']['schedulename'],
			'color' => $employeeSchedule['Schedule']['displaycolor']
		);
	    }
	    $companySchedules = $this->Schedule->find('all', array(
		'fields' => array('Schedule.scheduledate', 'Schedule.schedulename', 'Schedule.displaycolor'),
		'conditions' => array(
			'Schedule.kaisyas_id' => $this->Auth->user('kaisyas_id'),
			'Schedule.scheduledate >=' => $weekStart->format('Y-m-d'),
			'Schedule.scheduledate <=' => $weekEnd->format('Y-m-d'),
			'Schedule.delflg' => 0
		),
		'recursive' => -1
	    ));
	    $companyCalendarEntriesByDate = array();
	    foreach ($companySchedules as $companySchedule) {
		$scheduleDate = $companySchedule['Schedule']['scheduledate'];
		$companyCalendarEntriesByDate[$scheduleDate][] = array(
			'name' => $companySchedule['Schedule']['schedulename'],
			'color' => $companySchedule['Schedule']['displaycolor']
		);
	    }

	    $receptionmessages = $this->Receptionmessage->find('all',[
	        'fields'=>['id','receptioncomment','receptiondate','personalname'],
	        'conditions'=>[
	            'AND'=>[
	                'Receptionmessage.delflg'=>0,
	                'Receptionmessage.kaisyas_id'=>$this->Auth->user('kaisyas_id'),
	                'Receptionmessage.receptiondate >='=>$date->format('Y-m-d 00:00:00'),
	            ]
	        ],
	        'order'=>[
	            'Receptionmessage.receptiondate'=>'asc'
	        ]
	    ]);

	    $personalmessages = $this->Personalmessage->find('all',[
	        'fields'=>['id','personalcomment','created'],
	        'conditions'=>[
	            'AND'=>[
	                'Personalmessage.delflg'=>0,
	                'Personalmessage.users_id'=>$this->Auth->user('id')
	            ]
	        ],
	        'order'=>[
	            'Personalmessage.created'=>'desc'
	        ]
	    ]);

	    $gyoumumessages = $this->Gyoumumessage->find('all',[
			'fields'=>['id','gyoumucomment','created'],
			'conditions'=>[
				'AND'=>[
					'Gyoumumessage.delflg'=>0,
					'Gyoumumessage.kaisyas_id'=>$this->Auth->user('kaisyas_id'),
//				    'Gyoumumessage.created >='=>$date->format('Y-m-d')
				]
			],
			'order'=>[
				'Gyoumumessage.created'=>'desc'
			]
		]);

		$date = date('Y-m-d');

		$employeeholidays = $this->Employeeholiday->find('all',[
			'fields' => [
				'Employeeholiday.id',
				'Employeeholiday.applicationdate',
				'Employeeholiday.created',
				'Employeeholiday.users_id',
				'Employeeholiday.flgmasters_id',
				'Flgmaster.id',
				'Flgmaster.flgname',
				'User.usernameK',
				'Flgmaster2.flgname'
			],
			'conditions' => [
				'User.kaisyas_id' => $this->Auth->user('Kaisya.id'),
				'Employeeholiday.applicationdate >= ' => $date
			],
			'order' => [
				'Employeeholiday.applicationdate' => 'asc'
			],
			'recursive' => 1,
		]);
		//カレンダーが登録してあるかどうか確認（２ヶ月先）
		$date2 = new DateTime();
		$calendarcheck = $date2->modify('+2 months');
		$calendar_firstday = $date2->modify('first day of');
		$firstday = $calendar_firstday->format('Y-m-d');
		$calendar_lastday = $date2->modify('last day of');
		$lastday = $calendar_lastday->format('Y-m-d');

		$calendarmasters = $this->Calendarmaster->find('first',[
		    'conditions'=>[
		        'restdate between ? AND ?'=>[$firstday,$lastday]
		    ]
		]);

		$this->set(compact('personalmessages','gyoumumessages','employeeholidays','receptionmessages','calendarmasters','weekStart','weekEnd','weekDates','weekOptions','weekOffset','scheduleUsers','calendarEntriesByUserAndDate','companyCalendarEntriesByDate'));
	}

	public function addpersonalmessage(){
		if($this->request->is('POST'))
		{
			$this->Personalmessage->create();
			$this->request->data['Personalmessage']['users_id'] = $this->Auth->user('id');
			if ($this->Personalmessage->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error('メモを入力してください。');
				return $this->redirect(['action' => 'index']);
			}
			//return true;
		}
	}

	public function addgyoumumessage(){
		if($this->request->is('POST'))
		{
			$this->Gyoumumessage->create();
			$this->request->data['Gyoumumessage']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
			if ($this->Gyoumumessage->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error('メッセージを入力してください。');
				return $this->redirect(['action' => 'index']);
			}
			//return true;
		}
	}

	public function addreceptionmessage(){
	    if($this->request->is('POST'))
	    {
	        $this->Receptionmessage->create();
	        $this->request->data['Receptionmessage']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
	        if ($this->Receptionmessage->save($this->request->data)) {
	            $this->Flash->success('保存しました');
	            return $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Flash->error('メッセージを入力してください。');
	            return $this->redirect(['action' => 'index']);
	        }
	        //return true;
	    }
	}

	public function deletepersonal($id = null) {
		$this->Personalmessage->id = $id;
		if(!$this->Personalmessage->exists()){
			throw new NotfoundException(__('データが存在しません'));
		}
		$this->request->allowMethod('post','delete');
		if($this->Personalmessage->delete()){
			$this->Flash->success('削除しました');
		}else{
			$this->Flash->error('削除できません！');
		}
		return $this->redirect(array('action'=>'index'));
	}

	public function deletegyoumu($id = null) {
		$this->Gyoumumessage->id = $id;
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			$this->request->data['Gyoumumessage']['delflg'] = 1;
			$this->Gyoumumessage->id = $id;
			if ($this->Gyoumumessage->save($this->request->data,false,array('delflg'))){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('削除できません！'));
			}
		}
		return $this->redirect(array('action' => 'index'));

	}

	public function deletereception($id = null) {
	    $this->Receptionmessage->id = $id;
	    if ($this->request->is('get')){
	        throw new MethodNotAllowedException();
	    }else{
	        $this->request->data['Receptionmessage']['delflg'] = 1;
	        $this->Receptionmessage->id = $id;
	        if ($this->Receptionmessage->save($this->request->data,false,array('delflg'))){
	            $this->Flash->success(__('削除しました'));
	        }else{
	            $this->Flash->error(__('削除できません！'));
	        }
	    }
	    return $this->redirect(array('action' => 'index'));

	}

}
