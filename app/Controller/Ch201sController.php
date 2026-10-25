<?php
App::uses('AppController', 'Controller');
/**
 * Expensestrans Controller
 *
 */
class Ch201sController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Expensestran','Shippermaster','Branchmaster','Departmentmaster');
	public $components = array('Search.Prg','RequestHandler','Timediff','SelectData');
    public $presetVars = array();

    public function beforeFilter(){
    	parent::beforeFilter();
		$this->did = 2;
		$this->page = 'Ch201s';

    }

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 21; //事務職（化学品）
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
/**
 * index method
 *
 * @return void
 */
    public function index() {

		if ($this->request->is('post')) {
			list($date,$date2) = $this->SelectData->date_select_month($this->request->data('selectym1'));

			$paramsDate1 = $date->format('Y-m-d');
			$week = ['日','月','火','水','木','金','土'];
			$this->set('paramsDate1',$paramsDate1);
		}else{
			$date = new DateTime();
			$date->modify('first day of this month');
			$date2 = new DateTime();
			$date2->modify('last day of this month');

			$paramsDate1 = $date->format('Y-m-d');
			$week = ['日','月','火','水','木','金','土'];
			$this->set('paramsDate1',$paramsDate1);
		}

		$expensestrans = $this->Expensestran->find('all',[
			'conditions'=>[
				'Expensestran.departmentmasters_id'=>$this->did,
				'Expensestran.branchmasters_id'=>$this->branchmasters_id,
				'Expensestran.billconfirmdate >=' => $date->format('Y-m-d'),
				'Expensestran.billconfirmdate <=' => $date2->format('Y-m-d')
			],
			'order'=>[
				'Expensestran.billconfirmdate'=>'asc',
				'Expensestran.shippermasters_id'=>'asc'
			]
		]);
		$this->set('expensestrans', $expensestrans);

	}


	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$expensestran = $this->Expensestran->find('first', [
			'conditions' => [
				'Expensestran.id' => $id,
				'Expensestran.departmentmasters_id' => $this->did,
				'Expensestran.branchmasters_id' => $this->branchmasters_id,
			],
			'recursive' => -1,
		]);
		if (!$expensestran) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Expensestran->id = $id;
			$this->request->data['Expensestran']['branchmasters_id'] = $this->branchmasters_id;
			$this->request->data['Expensestran']['departmentmasters_id'] = $this->did;
			$this->request->data['Expensestran']['kaisyas_id'] = $this->kaisyas_id;
			if ($this->Expensestran->save($this->request->data)) {
				$this->Flash->success('経費を保存しました。');
				return $this->redirect(array('action' => 'edit',$id));
			}
		}

		if (!$this->request->data) {
			$this->request->data = $expensestran;
		}

		$shippermasters = $this->Shippermaster->find('list',[
			'fields' => ['Shippermaster.id','Shippermaster.shippershortname'],
			'conditions'=>[
				'Shippermaster.departmentmasters_id'=>$this->did
			]
		]);
		$date = $this->request->data('Expensestran.billconfirmdate');
		$this->set(compact('shippermasters', 'date'));
		$this->render('add');
	}
	public function add() {
		$date = date('Y-m-d');
		$this->set('date',$date);

	    $shippermasters = $this->Shippermaster->find('list',[
	        'fields' => ['Shippermaster.id','Shippermaster.shippershortname'],
			'conditions'=>[
				'Shippermaster.departmentmasters_id'=>$this->did
			]
	    ]);

	    $this->set(compact('shippermasters'));

		if ($this->request->is(array('post', 'put'))) {
			if(empty($this->request->data['Expensestran']['expensesname'])){
					$this->Flash->error('入力してください');
					return;
			}
			$this->request->data['Expensestran']['branchmasters_id'] = $this->branchmasters_id;
			$this->request->data['Expensestran']['departmentmasters_id'] = $this->did;
			$this->request->data['Expensestran']['kaisyas_id'] = $this->kaisyas_id;
	        if ($this->Expensestran->save($this->request->data)) {
	            $this->Flash->success('経費を保存しました。');
	            return $this->redirect(array('action' => 'index'));
	        }
	    }

	}
	public function delete($id = null) {
		$this->Expensestran->id = $id;

		if($this->Expensestran->delete()){
			$this->redirect(array('action'=>'index'));
		}

	}

}
