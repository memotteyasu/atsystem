<?php
App::uses('AppController', 'Controller');
/**
 * Oi001 Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Oi001sController extends AppController {
	public $layout = 'layout_host';
	public $uses = array('User','Kaisya','Ordertran','Carmaster','Shippermaster','Truckkind','Productdetailmaster','Deliverymaster');
    public $helpers = array('Html','Form');
/**
 * Components
 *
 * @var array
 */
    public function beforeFilter(){
    	parent::beforeFilter();
    	$this->set('auth',$this->Auth);
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
		$shippermasters = $this->Shippermaster->find('list',[
			'fields' => ['id','shippershortname'],
		]);
		$deliverymasters = $this->Deliverymaster->find('list',[
			'fields' => ['id','deliveryname']
		]);
		$this->set(compact('shippermasters','deliverymasters'));

		if ($this->request->is('post')) {
			$tyy1 = $this->data['selectym1']['year'];
			$tmm1 = $this->data['selectym1']['month'];
			$tdd1 = $this->data['selectym1']['day'];
			$shippermasters_id = $this->data['shippermasters_id'];
			$productdetailmasters_id = $this->data['productdetailmasters_id'];
		}else{
			//$tyy1 = date('Y');
			//$tmm1 = date('m');
			//$tdd1 = date('d');
			if (!$this->params['pass']) {
				$tyy1 = date('Y');
				$tmm1 = date('m');
				$tdd1 = date('d');
				$shippermasters_id = null;
				$productdetailmasters_id = null;
			} else {
				$tyy1 = $this->params['pass'][0];
				$tmm1 = $this->params['pass'][1];
				$tdd1 = $this->params['pass'][2];
				$shippermasters_id = $this->params['pass'][3];
				$productdetailmasters_id = $this->params['pass'][4];
			}
			//$shippermasters_id = null;
			//$productdetailmasters_id = null;
		}

		$paramsDate = $tyy1.'-'.$tmm1.'-'.$tdd1;
		$week = ['日','月','火','水','木','金','土'];

		if ($shippermasters_id == null && $productdetailmasters_id == null) {
			/*
			$this->set('truckkinds', $this->Truckkind->find('all', [
				'order' => [
					'Truckkind.carmasters_id'
				],
				'fields' => [
					'Truckkind.id',
					'Truckkind.carmasters_id',
					'Truckkind.shippermasters_id',
					'Truckkind.productdetailmasters_id',
					'Carmaster.plateno',
					'Productdetailmaster.pdname',
				],
				'contain' => [
					'Carmaster',
					'Shippermaster',
					'Productdetailmaster',
					'Ordertran' => [
						'conditions' => [
							'Ordertran.loadingdate' => $paramsDate
						]
					]
				]
			]));
			*/
			$this->set('truckkinds', []);
			$this->set('productdetailmasters', $this->Productdetailmaster->find('list',[
				'fields' => ['id','pdname']
			]));
		} elseif ($shippermasters_id == null) {
			$this->set('truckkinds', $this->Truckkind->SelectTruckkindProductdetail2($paramsDate,$productdetailmasters_id));
			$this->set('productdetailmasters', $this->Productdetailmaster->find('list',[
				'fields' => ['id','pdname']
			]));
		} elseif ($productdetailmasters_id == null) {
			$this->set('truckkinds', $this->Truckkind->SelectTruckkindShipper2($paramsDate,$shippermasters_id));
			$this->set('productdetailmasters', $this->Productdetailmaster->find('list', [
				'fields' => ['id', 'pdname'],
				'conditions' => [
					'Productdetailmaster.shippermasters_id' => $shippermasters_id
				]
			]));
		} else {
			$this->set('truckkinds', $this->Truckkind->SelectTruckkindAll2($paramsDate,$shippermasters_id,$productdetailmasters_id));
			$this->set('productdetailmasters', $this->Productdetailmaster->find('list', [
				'fields' => ['id', 'pdname'],
				'conditions' => [
					'Productdetailmaster.shippermasters_id' => $shippermasters_id
				]
			]));
		}

		$this->set(compact('paramsDate','week'));

	}

}
