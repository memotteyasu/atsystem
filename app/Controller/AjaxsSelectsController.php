<?php
class AjaxsSelectsController extends AppController {
	public $uses = ['Truckkind','Carmaster','Productdetailmaster','Shippermaster','Productcontainer','Loadingmaster','Deliverymaster','Tantomaster'];
	public $helpers = ['Html','Form'];

	public function beforeFilter(){
		parent::beforeFilter();
		//$this->set('auth',$this->Auth);
	}

	public function isAuthorized($user){
		if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '29'){
			$this->set('user',$user);
			return true;
		}
		return false;
	}

	public function ajax_productdetail($model) {
		//ajax送信でない場合はアクセスしないようにする
		if (!$this->request->is('ajax')) {
			$this->redirect(['controller' => 'HostTops', 'action' => 'index']);
		}
		$this->set('productdetailmasters', $this->Productdetailmaster->pdnameList2($this->params['url']['data'][$model]['shippermasters_id']));
	}
	//ガス用
	public function ajax_productdetail_7() {
		//ajax送信でない場合はアクセスしないようにする
		if (!$this->request->is('ajax')) {
			$this->redirect(['controller' => 'HostTops', 'action' => 'index']);
		}
		$this->set('productdetailmasters', $this->Productdetailmaster->pdnameList2($this->params['url']['data'][$model]['shippermasters_id']));
	}

	public function ajax_truckkind($model) {
		$this->layout = false;
		//ajax送信でない場合はアクセスしないようにする
		if (!$this->request->is('ajax')) {
			$this->redirect(['controller' => 'HostTops', 'action' => 'index']);
		}
		//$this->set('truckkinds', $this->Truckkind->SelectTruckkindList($this->params['url']['data']['Ordertran']['productdetailmasters_id']));
		$this->set('truckkinds', $this->Truckkind->SelectTruckkindList($this->params['url']['data'][$model]['productdetailmasters_id']));
	}

	public function ajax_truckkind2($model,$cid) {
		$this->layout = false;
		//ajax送信でない場合はアクセスしないようにする
		if (!$this->request->is('ajax')) {
			$this->redirect(['controller' => 'HostTops', 'action' => 'index']);
		}
		$this->Truckkind->virtualFields = ['name' => 'concat(Carmaster.plateno ,"/", Productdetailmaster.pdname)'];
		$truckkinds = $this->Truckkind->find('list', [
			'order' => [
				'Carmaster.plateno' => 'asc'
			],
			'fields' => ['Truckkind.id', 'name'],
			'conditions' => [
				'Truckkind.productdetailmasters_id' => $this->params['url']['data'][$model]['productdetailmasters_id'],
				'Truckkind.carmasters_id' => $cid,
				'Truckkind.delflg' => 0
			],
			'recursive' => 0,
		]);
		$pid = $this->params['url']['data'][$model]['productdetailmasters_id'];
		if (!$truckkinds && !$pid) {
			$truckkinds = ['1' => '商品を選択してください'];
		} elseif (!$truckkinds) {
			$truckkinds = ['1' => '車両が存在しません'];
		}
		$this->set('truckkinds', $truckkinds);
	}

	public function ajax_shipper($model) {
		$this->layout = false;
		//ajax送信でない場合はアクセスしないようにする
		if (!$this->request->is('ajax')) {
			$this->redirect(['controller' => 'HostTops','action' => 'index']);
		}
		$this->set('shippermasters', $this->Shippermaster->SelectShipperList($this->params['url']['data'][$model]['departmentmasters_id']));
	}

	public function ajax_tanto($model) {
	    $this->layout = false;
	    //ajax送信でない場合はアクセスしないようにする
	    if (!$this->request->is('ajax')) {
	        $this->redirect(['controller' => 'HostTops','action' => 'index']);
	    }
	    $this->set('tantomasters', $this->Tantomaster->SelectTantoList($this->params['url']['data'][$model]['shippermasters_id']));
	}

	public function ajax_productcontainer($model) {
		$this->layout = false;
		//ajax送信でない場合はアクセスしないようにする
		if (!$this->request->is('ajax')) {
			$this->redirect(['controller' => 'HostTops','action' => 'index']);
		}
		$this->set('productcontainers', $this->Productcontainer->SelectProductcontainerList($this->params['url']['data'][$model]['productdetailmasters_id']));
	}

	public function ajax_loading($model) {
		$this->layout = false;
		//ajax送信でない場合はアクセスしないようにする
		if (!$this->request->is('ajax')) {
			$this->redirect(['controller' => 'HostTops','action' => 'index']);
		}
		$this->set('loadingmasters', $this->Loadingmaster->SelectLoadingList($this->params['url']['data'][$model]['departmentmasters_id']));
	}

	public function ajax_delivery($model) {
		$this->layout = false;
		//ajax送信でない場合はアクセスしないようにする
		if (!$this->request->is('ajax')) {
			$this->redirect(['controller' => 'HostTops','action' => 'index']);
		}
		$this->set('deliverymasters', $this->Deliverymaster->SelectDeliveryList($this->params['url']['data'][$model]['departmentmasters_id']));
	}

}
