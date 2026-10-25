<?php
App::uses('AppController', 'Controller');

class Dr010sController extends AppController {
	public $layout = 'layout_drive';
	public $helpers = array('Html','Form');
	public $uses = array('Employeeholiday', 'User', 'Kintaitran','Flgmaster');

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);
    }

    public function isAuthorized($user){
    	if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '30' && $user['rolemasters_id'] <= '39'){
    		$this->set('user',$user);
    		return true;
    	}
    	return false;
    	//break;
    }
/**
 * index method
 *
 * @return void
 */
	public function index() {
		if ($this->request->is('post')) {
		    $this->Employeeholiday->create();
			$this->request->data['Employeeholiday']['users_id'] = $this->Auth->user('id');
			$this->request->data['Employeeholiday']['flgmasters_id'] = 42;
			if ($this->Employeeholiday->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(['controller' => 'Dr010s','action' => 'index']);
			} else {
				//$this->Flash->error('失敗しました');
			}
		}
		$flgmasters = $this->Flgmaster->find('list',[
			'fields'=>[
				'id',
				'flgname'
			],
			'conditions'=>[
				'OR'=>[
					'flggroup'=>5,
					'Flgmaster.id'=>13
				]
			]
		]);
		$this->set('flgmasters',$flgmasters);
		$firstday = new DateTime('first day of '.date('Y').'-4');
		$lastday = new DateTime('last day of '.(date('Y')+1).'-3');

        $user_holidayzan = $this->Auth->user('holidayzan');
        $this->set('user_holidayzan',$user_holidayzan);

		$employeeholidays = $this->Employeeholiday->find('all',[
			'fields' => [
			    'Employeeholiday.id',
				'Employeeholiday.applicationdate',
				'Flgmaster.flgname',
				'Flgmaster2.flgname'
			],
			// 申請中だけを表示
			'conditions' => [
				['User.id' => $this->Auth->user('id')],
			    ['Employeeholiday.flgmasters_id >='=>41],
			],
		    'order' => [
		      'applicationdate'=>'asc'
		    ],
			'recursive' => 0
		]);

		$kintaitrans = $this->Kintaitran->find('all', [
			'fields' => ['Kintaitran.kintaidate'],
			'conditions' => [
				'AND' => [
					['Kintaitran.kintaidate >=' => $firstday->format('Y-m-d')],
					['Kintaitran.kintaidate <=' => $lastday->format('Y-m-d')],
					['Kintaitran.users_id' => $this->Auth->user('id')],
				    ['Kintaitran.flgmasters_id' => 41] // 確定済み
				]
			],
		    'order' => [
		        'Kintaitran.kintaidate'=>'asc'
		    ],
			'recursive' => 0
		]);

		$this->set(compact('employeeholidays', 'kintaitrans','user_holidayzan'));
	}

	public function delete($id = null){
	    $this->Employeeholiday->id = $id;
	    if($this->Employeeholiday->delete()){
	        $this->Flash->success('削除しました。');
	        return $this->redirect(['controller'=>'Dr010s','action'=>'index']);
	    }else{
	        $this->Flash->error('削除失敗です。');
	    }
	}

}
