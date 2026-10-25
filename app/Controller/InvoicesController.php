<?php
App::uses('AppController', 'Controller');
/**
 * HostTops Controller
 *
 * @property HostTop $HostTops
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class InvoicesController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = ['Shippermaster','Productdetailmaster','Truckkind','Ordertran'];

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
    	break;
    }
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->set('shippermasters', $this->Shippermaster->find('list',[
			'fields' => ['id','shippershortname'],
		]));
		//$this->set(compact('productdetailmasters','shippermasters'));

		if ($this->request->is('post')) {
			$tyy = $this->data['selectym']['year'];
			$tmm = $this->data['selectym']['month'];
			$shippermasters_id = $this->data['shippermasters_id'];
			//$productdetailmasters_id = $this->data['productdetailmasters_id'];
			$truckkinds_id = $this->data['truckkinds_id'];
		}else{
			$tyy = date('Y');
			$tmm = date('m');
			$shippermasters_id = null;
			//$productdetailmasters_id = null;
			$truckkinds_id = null;
		}

		$paramsDate = new DateTime($tyy.'-'.$tmm);

		$this->set(compact('paramsDate'));

		$this->Truckkind->virtualFields = ['name' => 'concat(Carmaster.plateno ,"（" ,Productdetailmaster.pdname, "）")'];

		if ($shippermasters_id == null && $truckkinds_id == null) {
			/*
			$this->set('productdetailmasters', $this->Productdetailmaster->find('list',[
				'fields' => ['id','pdname']
			]));
			*/
			$this->set('truckkinds', $this->Truckkind->find('list', [
				'fields' => ['Truckkind.id', 'name'],
				'recursive' => 0,
				'order' => ['Truckkind.carmasters_id' => 'asc']
			]));
		} elseif ($shippermasters_id == null) {
			/*
			$this->set('productdetailmasters', $this->Productdetailmaster->find('list',[
				'fields' => ['id','pdname']
			]));
			*/
			$this->set('truckkinds', $this->Truckkind->find('list', [
				'fields' => ['Truckkind.id', 'name'],
				'recursive' => 0,
				'order' => ['Truckkind.carmasters_id' => 'asc']
			]));
		} elseif ($truckkinds_id == null) {
			/*
			$this->set('productdetailmasters', $this->Productdetailmaster->find('list', [
				'fields' => ['id', 'pdname'],
				'conditions' => [
					'Productdetailmaster.shippermasters_id' => $shippermasters_id
				]
			]));
			*/
			$this->set('truckkinds', $this->Truckkind->find('list', [
				'fields' => ['Truckkind.id', 'name'],
				'conditions' => ['Truckkind.shippermasters_id' => $shippermasters_id],
				'recursive' => 0,
				'order' => ['Truckkind.carmasters_id' => 'asc']
			]));
		} else {
			/*
			$this->set('productdetailmasters', $this->Productdetailmaster->find('list', [
				'fields' => ['id', 'pdname'],
				'conditions' => [
					'Productdetailmaster.shippermasters_id' => $shippermasters_id
				]
			]));
			*/
			$this->set('truckkinds', $this->Truckkind->find('list', [
				'fields' => ['Truckkind.id', 'name'],
				'conditions' => ['Truckkind.shippermasters_id' => $shippermasters_id],
				'recursive' => 0,
				'order' => ['Truckkind.carmasters_id' => 'asc']
			]));
		}

		/*
		if ($this->request->is(array('post', 'put'))) {
			$date = $this->request->data['Invoice']['date'];
			$sid = $this->request->data['Invoice']['shippermasters_id'];
			$pid = $this->request->data['Invoice']['productdetailmasters_id'];
			return $this->redirect(['controller' => 'Invoices', 'action' => 'pdf', $sid, $pid]);
			//debug($this->request->data);
		}
		*/

		//$this->set('shippermasters',$this->Shippermaster->find('all'));
	}

	function pdf($y = null, $m = null, $sid = null, $tid = null){
		$firstday = new DateTime('first day of '.$y.'-'.$m);
		$lastday = new DateTime('last day of '.$y.'-'.$m);
		$this->set(compact('firstday','lastday'));

		$shippermasters = $this->Shippermaster->find('first',[
			'fields' => ['shippername'],
			'conditions' => [
				'Shippermaster.id' => $sid,
			],
			'recursive' => -1,
		]);
		/*
		$productdetailmasters = $this->Productdetailmaster->find('first',[
			'fields' => ['id','pdname'],
			'conditions' => [
				'Productdetailmaster.id' => $pid,
			]
		]);
		*/
		$this->Truckkind->virtualFields = ['name' => 'concat(Carmaster.plateno ,"（" ,Productdetailmaster.pdname, "）")'];
		$this->set('truckkinds', $this->Truckkind->find('first', [
			'fields' => ['Truckkind.id', 'name'],
			'conditions' => ['Truckkind.id' => $tid],
			'recursive' => 0,
			'order' => ['Truckkind.carmasters_id' => 'asc']
		]));

		$ordertrans = $this->Ordertran->find('all', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.price',
				'Ordertran.amount',
				'Productdetailmaster.pdname'
			],
			'conditions' => [
				'AND' => [
					'Ordertran.shippermasters_id' => $sid,
					'Ordertran.loadingdate >= ' => $firstday->format('Y-m-d'),
					'Ordertran.loadingdate <= ' => $lastday->format('Y-m-d'),
				]
			],
			'recursive' => 0,
		]);
		$this->set(compact(/*'productdetailmasters',*/'shippermasters','ordertrans'));
		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';
	}

}
