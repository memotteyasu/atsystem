<?php
App::uses('AppController', 'Controller');
/**
 * Ch010 Controller
 *
 * @property Ch010
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Ch010sController extends AppController {
    public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('User','Kaisya','Ordertran','Shippermaster','Productdetailmaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran');

    public function beforeFilter(){
    	parent::beforeFilter();
    	$this->set('auth',$this->Auth);
    }

    public function isAuthorized($user){
    	if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '19'){
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

	}

	public function add() {
	    if ($this->request->is('post')) {
	        $this->Ordertran->create();
	        $this->request->data['Ordertran']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
	        //$this->request->data['Ordertran']['carmasters_id'] = $this->params['named']['cid'];
	        if ($this->Ordertran->save($this->request->data)) {
	            $this->Processtran->create();
	            //debug ($this->request->data);
	            for ($i = 1; $i <= 2; $i++) {
	                if ($i == 1) {
	                    $processstartdate = $this->request->data['Ordertran']['loadingdate'].' '.$this->request->data['Ordertran']['loadingtime'];
	                    $processenddate = $this->request->data['Ordertran']['loadingdate'].' '.'12:00:00';
	                } else {
	                    $processstartdate = $this->request->data['Ordertran']['deliverydate'].' '.$this->request->data['Ordertran']['deliverytime'];
	                    $processenddate = $this->request->data['Ordertran']['deliverydate'].' '.'12:00:00';
	                }
	                $procesdata = [
	                    'ordertrans_id' => $this->Ordertran->getLastInsertID(),
	                    'loadingmasters_id' => $this->request->data['Ordertran']['loadingmasters_id'],
	                    'deliverymasters_id' => $this->request->data['Ordertran']['deliverymasters_id'],
	                    'processstartdate' => $processstartdate,
	                    'processenddate' => $processenddate,
	                    'workdetailmasters_id' => $i,
	                ];
	                $this->Processtran->saveAll($procesdata);
	            }
	            $this->Flash->success('保存しました');
	            return $this->redirect(['controller' => 'Oi000s','action' => 'index']);
	        } else {
	            //$this->Flash->error('失敗しました');
	        }
	    }

	    $shippermasters = $this->Shippermaster->find('list', [
	        'fields' => ['Shippermaster.id','Shippermaster.shippername'],
	    ]);
	    $productdetailmasters = $this->Productdetailmaster->find('list', [
	        'fields' => ['Productdetailmaster.id','Productdetailmaster.pdname'],
	    ]);
	    $loadingmasters = $this->Loadingmaster->find('list',[
	        'fields' => ['Loadingmaster.id','Loadingmaster.loadingname']
	    ]);
	    $deliverymasters = $this->Deliverymaster->find('list',[
	        'fields' => ['Deliverymaster.id','Deliverymaster.deliveryname']
	    ]);
	    $this->Truckkind->virtualFields = ['name' => 'concat(Carmaster.plateno ," ", Shippermaster.shippername, "/", Productdetailmaster.pdname)'];
	    $truckkinds = $this->Truckkind->find('list', [
	        'fields' => [
	            'Truckkind.id',
	            'name'
	        ],
	        'recursive' => 0,
	    ]);
	    $this->set(compact('shippermasters','productdetailmasters','loadingmasters','deliverymasters','truckkinds'));
	}

	public function edit($id = null) {
	    if (!$id) {
	        throw new NotFoundException(__('Invalid post'));
	    }

	    $post = $this->Ordertran->findById($id);
	    if (!$post) {
	        throw new NotFoundException(__('Invalid post'));
	    }

	    if ($this->request->is(array('post', 'put'))) {
	        $this->Ordertran->id = $id;
	        $this->request->data['Ordertran']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
	        if ($this->Ordertran->save($this->request->data)) {
	            $this->Flash->success('保存しました。');
	            return $this->redirect(['controller' => 'Ch000s', 'action' => 'index']);
	        }
	        //$this->Flash->error('失敗しました。');
	    }

	    if (!$this->request->data) {
	        $this->request->data = $post;
	    }

	    $shippermasters = $this->Shippermaster->find('list', [
	        'fields' => ['Shippermaster.id','Shippermaster.shippername'],
	    ]);
	    $productdetailmasters = $this->Productdetailmaster->find('list', [
	        'fields' => ['Productdetailmaster.id','Productdetailmaster.pdname'],
	    ]);
	    $loadingmasters = $this->Loadingmaster->find('list',[
	        'fields' => ['Loadingmaster.id','Loadingmaster.loadingname']
	    ]);
	    $deliverymasters = $this->Deliverymaster->find('list',[
	        'fields' => ['Deliverymaster.id','Deliverymaster.deliveryname']
	    ]);
	    $this->Truckkind->virtualFields = ['name' => 'concat(Carmaster.plateno ," " ,Productdetailmaster.pdname, "/", Shippermaster.shippername)'];
	    $truckkinds = $this->Truckkind->find('list', [
	        'fields' => [
	            'Truckkind.id',
	            'name'
	        ],
	        'recursive' => 0,
	    ]);
	    $this->set(compact('shippermasters','productdetailmasters','loadingmasters','deliverymasters', 'truckkinds'));
	}

	public function ajax_truckkind() {
		$this->layout = false;
		//ajax送信でない場合はアクセスしないようにする
		if (!$this->request->is('ajax')) {
			$this->redirect(['controller' => 'Ch000s','action' => 'index']);
		}

		$this->Truckkind->virtualFields = ['name' => 'concat(Carmaster.plateno ," " ,Productdetailmaster.pdname, "/", Shippermaster.shippername)'];
		$this->set('truckkinds',
			$this->Truckkind->find('list',[
				'conditions' => [
					'Truckkind.productdetailmasters_id' => $this->params['url']['data']['Ordertran']['productdetailmasters_id']
				],
				'fields' => ['Truckkind.id','name'],
				'recursive' => 0,
			])
		);
	}

	public function ajax_productdetail() {
		$this->layout = false;
		//ajax送信でない場合はアクセスしないようにする
		if (!$this->request->is('ajax')) {
			$this->redirect(['controller' => 'Ch000s','action' => 'index']);
		}

		$this->set('productdetailmasters',
			$this->Productdetailmaster->find('list',[
				'conditions' => ['Productdetailmaster.shippermasters_id' => $this->params['url']['data']['Ordertran']['shippermasters_id']],
				'fields' => ['Productdetailmaster.id','pdname'],
				'recursive' => 0,
			])
		);
	}

}
