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
    public $uses = array('Personalmessage','Gyoumumessage','Employeeholiday','Receptionmessage','Calendarmaster');

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

		$this->set(compact('personalmessages','gyoumumessages','employeeholidays','receptionmessages','calendarmasters'));
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
