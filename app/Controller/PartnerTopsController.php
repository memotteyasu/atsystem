<?php
App::uses('AppController', 'Controller');
/**
 * ClerkTops Controller
 */
class PartnerTopsController extends AppController {
	public $layout = 'layout_partner';
    public $helpers = array('Html','Form');
    public $uses = array('Personalmessage','Gyoumumessage','Employeeholiday','Flgmaster','Receptionmessage');

    public function beforeFilter(){
        parent::beforeFilter();
        //$this->set('auth',$this->Auth);
    }

    public function isAuthorized($user){
    	if(isset($user['rolemasters_id']) and $user['rolemasters_id'] == 29 ){
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
//	                'Gyoumumessage.created >='=>$date->format('Y-m-d')
	            ]
	        ],
	        'order'=>[
	            'Gyoumumessage.created'=>'desc'
	        ]
	    ]);

	    $date = date('Y-m-d');
	    $employeeholidays = $this->Employeeholiday->find('all',[
		    'recursive' => 2,
		    'fields'=>['id','applicationdate','created','users_id','flgmasters_id'],
		    'conditions' => [
		        'User.kaisyas_id' => $this->Auth->user('Kaisya.id'),
		    ],
		    'order' => [
		        'Employeeholiday.applicationdate' => 'asc'
		    ]
		]);


	    $this->set(compact('personalmessages','gyoumumessages','employeeholidays','receptionmessages'));
	}

	public function addpersonalmessage(){
		if($this->request->is('POST')) {
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

	public function delete($id = null) {
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


}
