<?php
App::uses('AppController', 'Controller');

class Dr030sController extends AppController {
	public $layout = 'layout_drive';
	public $helpers = array('Html','Form');
	public $uses = ['User', 'Processtran', 'Ordertran'];

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
		$paramsDate = new DateTime();
		$week = ['日','月','火','水','木','金','土'];
		$this->set(compact('paramsDate','week'));

		$ordertrans1 = $this->Ordertran->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.dr1confirmflg',
				'Ordertran.dr2confirmflg',
				'Carmaster.plateno',
				'Productdetailmaster.pdname',
				'Shippermaster.shippershortname',
				'Loadingmaster.loadingname',
				'Deliverymaster.deliveryname'
			],
			'conditions' => [
				'AND' => [
					['Ordertran.deliverydate' => $paramsDate->format('Y-m-d')],
					['Ordertran.delflg !=' => 1],
					['Ordertran.confirmflg' => 1],
					['Ordertran.driver1id' => $this->Auth->user('id')]
				],
			],
			'contain' => [
				'Carmaster',
				'Productdetailmaster',
				'Shippermaster',
				'Loadingmaster',
				'Deliverymaster'
			]
		]);
		$ordertrans2 = $this->Ordertran->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.dr1confirmflg',
				'Ordertran.dr2confirmflg',
				'Carmaster.plateno',
				'Productdetailmaster.pdname',
				'Shippermaster.shippershortname',
				'Loadingmaster.loadingname',
				'Deliverymaster.deliveryname'
			],
			'conditions' => [
				'AND' => [
					['Ordertran.deliverydate' => $paramsDate->format('Y-m-d')],
					['Ordertran.delflg !=' => 1],
					['Ordertran.confirmflg' => 1],
					['Ordertran.driver2id' => $this->Auth->user('id')]
				],
			],
			'contain' => [
				'Carmaster',
				'Productdetailmaster',
				'Shippermaster',
				'Loadingmaster',
				'Deliverymaster'
			]
		]);

		$confirmorder1 = $this->Ordertran->find('all', [
			'fields' => [
				'Ordertran.id'
			],
			'conditions' => [
				'AND' => [
					['Ordertran.deliverydate' => $paramsDate->format('Y-m-d')],
					['Ordertran.delflg !=' => 1],
					['Ordertran.confirmflg' => 1],
					['Ordertran.driver1id' => $this->Auth->user('id')],
					['Ordertran.dr1confirmflg' => 1],
				],
			],
			'recursive' => -1
		]);

		$confirmorder2 = $this->Ordertran->find('all', [
			'fields' => [
				'Ordertran.id'
			],
			'conditions' => [
				'AND' => [
					['Ordertran.deliverydate' => $paramsDate->format('Y-m-d')],
					['Ordertran.delflg !=' => 1],
					['Ordertran.confirmflg' => 1],
					['Ordertran.driver2id' => $this->Auth->user('id')],
					['Ordertran.dr2confirmflg' => 1],
				],
			],
			'recursive' => -1
		]);
		$this->set(compact('ordertrans1','ordertrans2','confirmorder1','confirmorder2'));
	}

	public function drconfirm() {
		$paramsDate = new DateTime();

		$this->Ordertran->updateAll([
			'dr1confirmflg' => 1,
			'dr1confirmdate' => "'".date('Y-m-d H:i:s')."'"
		], [
			'deliverydate' => $paramsDate->format('Y-m-d'),
			'confirmflg' => 1,
			'driver1id' => $this->Auth->user('id'),
			'delflg !=' => 1,
		]);

		$this->Ordertran->updateAll([
			'dr2confirmflg' => 1,
			'dr2confirmdate' => "'".date('Y-m-d H:i:s')."'"
		], [
			'deliverydate' => $paramsDate->format('Y-m-d'),
			'confirmflg' => 1,
			'driver2id' => $this->Auth->user('id'),
			'delflg !=' => 1,
		]);
		$this->Flash->success(__('確認しました。'));
		return $this->redirect(['action' => 'index', 'controller' => 'Dr030s']);
	}

}
