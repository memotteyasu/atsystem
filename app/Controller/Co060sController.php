<?php
App::uses('AppController', 'Controller');
/**
 * Co060 Controller
 */
class Co060sController extends AppController {
	public $layout = 'layout_host';
    public $helpers = ['Html','Form'];
    public $uses = ['User','Kaisya','Departmentmaster','Ordertran','Shippermaster','Productdetailmaster','Productcontainer','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Pricemaster','OrdertranPricemaster'];
    public $components = ['SelectData'];

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
    	$did = 4;
		$date = new DateTime();
		if ($this->request->is('post')) {
			$date = $this->SelectData->date_select($this->data['selectym1']);
		}
		$paramsDate = $date->format('Y-m-d');

		$this->Set('ordertrans', $this->Ordertran->SelectOrdertran050s($paramsDate,$did));

		$page = 'Co060s';
		$backdata = ['Page' => $page];
		$this->Session->write('BackData1', $backdata);
		$this->Session->delete('BackData2');
    }

	public function add($id = null) {
		$did = 4;

		$ordertrans = $this->Ordertran->SelectOrdertran050s2($id);
		$pricemasters = $this->OrdertranPricemaster->find('all', [
			'fields' => [
				'id', 'ordertrans_id', 'pricemasters_id'
			],
			'conditions' => [
				'OrdertranPricemaster.ordertrans_id' => $id,
				'OrdertranPricemaster.delflg' => 0
			],
			'contain' => [
				'Pricemaster' => [
					'fields' => [
						'id',
						'pricename',
						'price',
						'departmentmasters_id',
						'shippermasters_id',
						'loadingmasters_id',
						'deliverymasters_id',
						'productdetailmasters_id',
						'productcontainers_id'
					],
					'order' => [
						'pricename' => 'asc'
					]
				]
			],
			'recursive' => 0
		]);
		$pricemasters2 = $this->Pricemaster->SelectPriceList();

		$this->set(compact('ordertrans','pricemasters','pricemasters2'));

		if ($this->request->is('post')) {
			$this->OrdertranPricemaster->create();
			$this->request->data['OrdertranPricemaster']['ordertrans_id'] = $id;
			if ($this->OrdertranPricemaster->save($this->request->data)) {
				$this->Flash->success('価格内容を登録しました。');
				return $this->redirect(['controller' => 'Co060s','action' => 'add', $id]);
			} else {
				$this->Flash->error('価格内容の登録に失敗しました。');
			}
		}

		$shippermasters = $this->Shippermaster->ShipperList($this->kaisyas_id);
		$departmentmasters = $this->Departmentmaster->DepartmentList($this->kaisyas_id);
		$loadingmasters = $this->Loadingmaster->SelectLoadingList2();
		$deliverymasters = $this->Deliverymaster->SelectDeliveryList2();
		$productdetailmasters = $this->Productdetailmaster->SelectList();
		$productcontainers = $this->Productcontainer->SelectProductcontainerList2(111);
		$this->set(compact('shippermasters','departmentmasters','loadingmasters','deliverymasters','productdetailmasters','productcontainers'));
    }

	public function edit($id = null, $oid = null) {
		$post = $this->OrdertranPricemaster->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		$did = 4;

		$pricemasters = $this->Pricemaster->SelectPriceList();
		$this->set(compact('pricemasters'));

		if ($this->request->is(['post', 'put'])) {
			$this->OrdertranPricemaster->id = $id;
			$this->request->data['OrdertranPricemaster']['ordertrans_id'] = $oid;
			if ($this->OrdertranPricemaster->save($this->request->data)) {
				$this->Flash->success(__('工程を編集しました。'));
				return $this->redirect(['action' => 'add', 'controller' => 'Co060s', $oid]);
			} else {
				$this->Flash->error(__('工程の編集に失敗しました。'));
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
    }

    public function delete($id = null, $oid = null) {
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			if ($this->Processtran->delete($id)) {
				$this->Flash->success(__('削除しました'));
			} else {
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(['controller' => 'Co060s','action' => 'add',$oid]);
	}

}
