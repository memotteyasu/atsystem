<?php
App::uses('AppController', 'Controller');

class Dr040sController extends AppController {
	public $layout = 'layout_drive';
	public $helpers = array('Html','Form');
	public $uses = array('Employeepoint','Employeegetpoint', 'User');

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
		//debug ($this->Auth->user());
		$users = $this->User->find('first',[
			'conditions' => ['User.id'=>$this->Auth->user('id')]
		]);
		$this->set(compact('users'));

		if ($this->request->is('post')) {
			$this->Employeegetpoint->create();
			$employeepoints = $this->Employeepoint->findById($this->Auth->user('Employeepoint.id'),null,null,-1);
			if ($this->request->data['Employeegetpoint']['users_id']==null) {
				$userlists = $this->User->find('list', [
					'fields' => ['id','usernameK'],
					'conditions'=>['NOT'=>['User.id'=>$this->Auth->user('id')]],
				]);
				$this->set(compact('userlists'));
				$this->Flash->error('付与する相手を選んでください。');
				$this->render();
			} elseif ($this->request->data['Employeegetpoint']['point']==null) {
				$userlists = $this->User->find('list', [
					'fields' => ['id','usernameK'],
					'conditions'=>['NOT'=>['User.id'=>$this->Auth->user('id')]],
				]);
				$this->set(compact('userlists'));
				$this->Flash->error('付与するコイン数を選んでください。');
				$this->render();
			} else {
			//付与コインが残高以下である場合、付与させない
				if ($employeepoints['Employeepoint']['pointzan'] >= $this->request->data['Employeegetpoint']['point']) {
					$this->request->data['Employeegetpoint']['sendeduserid'] = $this->Auth->user('id');
					$ppp_id = $this->request->data['Employeegetpoint']['users_id'];
					$point = $this->request->data['Employeegetpoint']['point'];
					$now = date('Y-m-d H:i:s');
					$this->request->data['Employeegetpoint']['getdate'] = $now;
					//debug ($this->request->data);
					if ($this->Employeegetpoint->save($this->request->data)) {
						$this->Flash->success('保存しました');
					}
					//送信側のポイント残高処理
					//$this->Employeepoint->id = $this->Auth->user('Employeepoint.id');
					//$this->request->data = $this->Employeepoint->findById($this->Employeepoint->id,null,null,-1);
					//$this->request->data['Employeepoint']['pointzan'] = $this->request->data['Employeepoint']['pointzan'] - $point;
					$this->request->data = $employeepoints;
					$this->request->data['Employeepoint']['pointzan'] = $employeepoints['Employeepoint']['pointzan'] - $point;
					if($this->Employeepoint->save($this->request->data,false,['pointzan'])){
						$this->Flash->success('保存しました。');
					}
					//受け取り側のポイント残高処理
					$this->request->data = $this->Employeepoint->findByUsersId($ppp_id,null,null,-1);
					$this->request->data['Employeepoint']['pointzan'] = $this->request->data['Employeepoint']['pointzan'] + $point;
					if($this->Employeepoint->save($this->request->data,false,['pointzan'])){
						$this->Flash->success('保存しました。');
						return $this->redirect(array('action' => 'index'));
					}
				} else {
					$this->Flash->error('コインが足りません。');
				}
			}
		}

		$userlists = $this->User->find('list', [
			'fields' => ['id','usernameK'],
			'conditions'=>['NOT'=>['User.id'=>$this->Auth->user('id')]],
		]);
		$sendeduserlists = $this->User->find('list', [
			'fields' => ['id','usernameK'],
		]);
		$this->set(compact('userlists','sendeduserlists'));

	}

}
