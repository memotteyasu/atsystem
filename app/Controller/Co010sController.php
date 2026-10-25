<?php
App::uses('AppController', 'Controller');
/**
 * Co010 Controller
 *
 * @copy Oi010
 */
class Co010sController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('User','Kaisya','Ordertran','Shippermaster','Productdetailmaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran','Workdetailmaster');

    public function beforeFilter(){
    	parent::beforeFilter();
    }

    public function isAuthorized($user){
        // 管理者(10-)事務職(20-)
        if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '29'){
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
    	$this->paginate = ['Ordertran' => [
    		'limit' => 10,
    		'conditions' => [
    			//'Processtran.carmasters_id' => $id,
    			'OR' => [
    				['Ordertran.delflg' => ''],
    				['Ordertran.delflg' => 0]
    			]
    		],
    		'recursive' => 2
    	]];
    	$this->set('ordertrans', $this->Paginator->paginate('Ordertran'));

    	//$this->set('ordertrans', $this->Ordertran->find('all', ['recursive' => 2]));
    }

	public function add() {
		if ($this->params['named'] == null) {
			$sid = null;
			$pid = null;
		} else {
			$sid = $this->params['named']['sid'];
			$pid = $this->params['named']['pid'];
		}
		$shippermasters = $this->Shippermaster->find('list', [
			'fields' => ['Shippermaster.id','Shippermaster.shippername'],
		    'conditions'=>['Shippermaster.departmentmasters_id'=>4]
		]);
		$productdetailmasters = $this->Productdetailmaster->find('list', [
			'fields' => ['Productdetailmaster.id','Productdetailmaster.pdname'],
			'conditions' => ['Productdetailmaster.shippermasters_id' => $sid]
		]);
		$loadingmasters = $this->Loadingmaster->find('list',[
			'fields' => ['Loadingmaster.id','Loadingmaster.loadingname'],
		    'conditions'=>['Loadingmaster.departmentmasters_id'=>4],
			'order' => ['Loadingmaster.loadingnamekana' => 'asc'],
		]);
		$deliverymasters = $this->Deliverymaster->find('list',[
			'fields' => ['Deliverymaster.id','Deliverymaster.deliveryname'],
		    'conditions'=>['Deliverymaster.departmentmasters_id'=>4],
			'order' => ['Deliverymaster.deliverynamekana' => 'asc'],
		]);
		$this->Truckkind->virtualFields = ['name' => 'concat(Carmaster.plateno ," ", Shippermaster.shippername, "/", Productdetailmaster.pdname)'];
		$truckkinds = $this->Truckkind->find('list', [
			'fields' => ['Truckkind.id', 'name'],
			'conditions' => ['Truckkind.productdetailmasters_id' => $pid],
			'recursive' => 0,
		]);
		$truckkind2s = $this->Truckkind->find('list', [
			'fields' => ['Truckkind.id', 'Truckkind.carmasters_id']
		]);
		$this->set(compact('shippermasters','productdetailmasters','loadingmasters','deliverymasters','truckkinds','truckkind2s'));

		if ($this->request->is('post')) {
			$this->Ordertran->create();
			$this->request->data['Ordertran']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
			$this->request->data['Ordertran']['carmasters_id'] = $truckkind2s[$this->request->data['Ordertran']['truckkinds_id']];
			$this->request->data['Ordertran']['loadingdate'] = $this->request->data['Ordertran']['deliverydate'];
			$this->request->data['Ordertran']['loadingtime'] = $this->request->data['Ordertran']['deliverytime'];
			//debug($this->request->data);
			//$this->request->data['Ordertran']['carmasters_id'] = $this->params['named']['cid'];
			if ($this->Ordertran->save($this->request->data)) {
			    /* 一旦processtrans保存は保留
				$this->Processtran->create();
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
				*/
				$this->Flash->success('保存しました');
				return $this->redirect(['controller' => 'Co000s','action' => 'index', $sid, $pid]);
			} else {
				//$this->Flash->error('失敗しました');
			}
		}
	}


    public function edit($id = null,$flg = null) {
    	$post = $this->Ordertran->findById($id);
    	if (!$post) {
    		throw new NotFoundException(__('Invalid post'));
    	}
    	$date = new DateTime($post['Ordertran']['deliverydate'].$post['Ordertran']['deliverytime']);
		$tyy = $date->format('Y');
		$tmm = $date->format('m');
		$tdd = $date->format('d');
    	$sid = $post['Ordertran']['shippermasters_id'];
    	$pid = $post['Ordertran']['productdetailmasters_id'];
    	//debug($post);

    	$shippermasters = $this->Shippermaster->find('list', [
    		'fields' => ['Shippermaster.id','Shippermaster.shippername'],
    	    'conditions'=>['Shippermaster.departmentmasters_id'=>4]
    	]);
    	$productdetailmasters = $this->Productdetailmaster->find('list', [
    		'fields' => ['Productdetailmaster.id','Productdetailmaster.pdname'],
    		'conditions' => ['Productdetailmaster.shippermasters_id' => $post['Ordertran']['shippermasters_id']]
    	]);
    	$loadingmasters = $this->Loadingmaster->find('list',[
    		'fields' => ['Loadingmaster.id','Loadingmaster.loadingname'],
    	    'conditions'=>['Loadingmaster.departmentmasters_id'=>4],
    		'order' => ['Loadingmaster.loadingnamekana' => 'asc']
    	]);
    	$deliverymasters = $this->Deliverymaster->find('list',[
    		'fields' => ['Deliverymaster.id','Deliverymaster.deliveryname'],
    	    'conditions'=>['Deliverymaster.departmentmasters_id'=>4],
    		'order' => ['Deliverymaster.deliverynamekana' => 'asc']
    	]);
    	$truckkind2s = $this->Truckkind->find('list', [
    		'fields' => ['Truckkind.id', 'Truckkind.carmasters_id']
    	]);
    	$this->set(compact('shippermasters','productdetailmasters','loadingmasters','deliverymasters','truckkind2s'));

    	if ($this->request->is(array('post', 'put'))) {
    		$this->Ordertran->id = $id;
    		$this->request->data['Ordertran']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
    		$this->request->data['Ordertran']['carmasters_id'] = $truckkind2s[$this->request->data['Ordertran']['truckkinds_id']];
    		$this->request->data['Ordertran']['loadingdate'] = $this->request->data['Ordertran']['deliverydate'];
    		$this->request->data['Ordertran']['loadingtime'] = $this->request->data['Ordertran']['deliverytime'];
    		if ($this->Ordertran->save($this->request->data)) {
    			$this->Flash->success('保存しました。');
				if ($flg == 1) {
					return $this->redirect(['controller' => 'Co001s', 'action' => 'index', $tyy, $tmm, $tdd, $sid, $pid]);
				} else {
    				return $this->redirect(['controller' => 'Co000s', 'action' => 'index', $sid, $pid]);
				}
    		}
    		//$this->Flash->error('失敗しました。');
    	}

    	if (!$this->request->data) {
    		$this->request->data = $post;
    	}

    	$this->Truckkind->virtualFields = ['name' => 'concat(Carmaster.plateno ," " ,Productdetailmaster.pdname, "/", Shippermaster.shippername)'];
    	$truckkinds = $this->Truckkind->find('list', [
    		'fields' => ['Truckkind.id', 'name'],
    		'conditions' => ['Truckkind.productdetailmasters_id' => $post['Ordertran']['productdetailmasters_id']],
    		'recursive' => 0
    	]);
    	$this->set(compact('truckkinds'));
    }

    public function edit2($id = null, $oid = null) {
    	if (!$this->Processtran->exists($id)) {
    		throw new NotFoundException(__('存在しません'));
    	}
    	if ($this->request->is(array('post', 'put'))) {
    		$this->Processtran->id = $id;
    		$this->request->data['Processtran']['ordertrans_id'] = $oid;
    		if ($this->Processtran->save($this->request->data)) {
    			$this->Flash->success(__('変更しました'));
    			return $this->redirect(['action' => 'add2', $oid]);
    		} else {
    			//$this->Flash->error(__('変更できませんでした'));
    		}
    	} else {
    		$options = ['conditions' => ['Processtran.' . $this->Processtran->primaryKey => $id]];
    		$this->request->data = $this->Processtran->find('first', $options);
    	}

    	$ordertrans = $this->Ordertran->findById($oid);
    	$users = $this->User->find('list', [
    		/*
    		 'condtitions' => [
    			'AND' => [
    				['User.rolemasters_id >=' => '20'],
    				['User.rolemasters_id <=' => '29']
    			]
    		 ],
    	*/
    		'fields' => ['User.id','User.usernameK'],
    	]);
    	$workdetailmasters = $this->Workdetailmaster->find('list', [
    		'fields' => ['Workdetailmaster.id','Workdetailmaster.workname'],
    	]);

    	$this->set(compact('ordertrans', 'users', 'workdetailmasters'));

    	/*
    	if (!$id) {
    		throw new NotFoundException(__('Invalid post'));
    	}

    	$post = $this->Processtran->findById($id);
    	if (!$post) {
    		throw new NotFoundException(__('Invalid post'));
    	}

    	if ($this->request->is(array('post', 'put'))) {
    		$this->Processtran->id = $id;
    		if ($this->Processtran->save($this->request->data)) {
    			$this->Flash->success('保存しました。');
    			return $this->redirect(['controller' => 'Oi050s', 'action' => 'index']);
    		} else {
    			$this->Flash->error('失敗しました。');
    		}
    	}

    	if (!$this->request->data) {
    		$this->request->data = $post;
    	}

    	$ordertrans = $this->Ordertran->find('list', [
    		'fields' => ['Ordertran.id','Ordertran.productnote'],
    	]);
    	$users = $this->User->find('list', [
    		'fields' => ['User.id','User.usernameK'],
    	]);
    	$workdetailmasters = $this->Workdetailmaster->find('list', [
    		'fields' => ['Workdetailmaster.id','Workdetailmaster.workname'],
    	]);

    	$this->set(compact('ordertrans', 'users', 'workdetailmasters'));
    	*/
    }

    public function delete($id = null) {
    	$this->Ordertran->id = $id;
    	if ($this->request->is('get')){
    		throw new MethodNotAllowedException();
    	}else{
    		$this->request->data['Ordertran']['delflg'] = 1;
    		$this->Ordertran->id = $id;
    		if ($this->Ordertran->save($this->request->data,false,['delflg'])){
    			$processtrans = $this->Processtran->find('all',[
    				'conditions' => [
    					'Processtran.ordertrans_id' => $id
    				],
    				'recursive' => 0,
    			]);
    			if ($processtrans) {
	    			foreach ($processtrans as $processtran) {
	    				$this->request->data['Processtran']['id'] = $processtran['Processtran']['id'];
	    				$this->request->data['Processtran']['delflg'] = 1;
	    				$this->Processtran->save($this->request->data);
	    			}
    			}
    			$this->Flash->success(__('削除しました'));
    			return $this->redirect(['controller' => 'Co000s','action' => 'index']);
    		}else{
    			$this->Flash->error(__('失敗しました'));
    		}
    	}
    	//return $this->redirect(['controller' => 'Oi000s','action' => 'index']);
    }

    public function ajax_truckkind() {
    	$this->layout = false;
    	//ajax送信でない場合はアクセスしないようにする
    	if (!$this->request->is('ajax')) {
    		$this->redirect(['controller' => 'Co000s','action' => 'index']);
    	}

    	$this->Truckkind->virtualFields = ['name' => 'concat(Carmaster.plateno ," " ,Productdetailmaster.pdname, "/", Shippermaster.shippername)'];
    	/*
    	$this->set('truckkinds',
    		$this->Truckkind->find('list',[
    			//'conditions' => ['Truckkind.productdetailmasters_id' => $this->params['url']['data']['Ordertran']['productdetailmasters_id']],
    			'conditions' => [
    				'OR' => [
    					['Truckkind.productdetailmasters_id' => $this->params['url']['data']['Ordertran']['productdetailmasters_id']],
    					['Truckkind.shippermasters_id' => $this->params['url']['data']['Ordertran']['shippermasters_id']],
    				]
    			],
    			'fields' => [
    				'Truckkind.id',
    				'name'
    			],
    			'recursive' => 0,
    		])
    	);
    	*/
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
    		$this->redirect(['controller' => 'Co000s','action' => 'index']);
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
