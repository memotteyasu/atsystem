<?php
App::uses('AppController', 'Controller');
/**
 * Co120 Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Co120sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = ['User','Kaisya','Ordertran','Shippermaster','Productdetailmaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran','Workdetailmaster','Invoice','Invoicedetail',];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 4;
		$this->page = 'Co120s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 23; //事務職（コンテナ）
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
		// $did = 4;
		// $page = 'Co120s';
		$did = $this->did;
		$page = $this->page;

		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$carmasters = $this->Carmaster->CarnameList($did);
		$branchmasters_id = $this->branchmasters_id;
		$branchmasters = $this->SelectData->branch_select_conditions($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);
		$this->set(compact('shippermasters','carmasters','branchmasters'));

		$date = new DateTime();
		$date->modify('first day of this months');
		$date2 = new DateTime();
		$date2->modify('last day of this months');
		$shippermasters_id = null;
		$productdetailmasters_id = null;
		$carmasters_id = null;

		if ($this->request->is('post')) {
			list($date,$date2) = $this->SelectData->date_select_month($this->data['selectym1']);
			$shippermasters_id = $this->data['shippermasters_id'];
//			$productdetailmasters_id = $this->data['productdetailmasters_id'];
//			$carmasters_id = $this->data['carmasters_id'];
			$branchmasters_id = $this->request->data('branchmasters_id');
			$backdatas = ['date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')], 'date2' => ['year' => $date2->format('Y'), 'month' => $date2->format('m'), 'day' => $date2->format('d')], 'sid' => $shippermasters_id, 'pid' => $productdetailmasters_id, 'cid' => $carmasters_id, 'bid' => $branchmasters_id, 'Page' => $page];
			$this->Session->write('BackData1', $backdatas);
		} elseif ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				$date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				$date2 = $this->SelectData->date_select($this->Session->read('BackData2.date2'));
				$shippermasters_id = $this->Session->read('BackData2.sid');
				//$productdetailmasters_id = $this->Session->read('BackData2.pid');
				$carmasters_id = $this->Session->read('BackData2.cid');
				$branchmasters_id = $this->Session->read('BackData2.bid');
			}
			$this->Session->delete('BackData2');
		} else {
			$this->Session->delete('BackData1');
		}

		$paramsDate = $date->format('Y-m-d');
		$paramsDate2 = $date2->format('Y-m-d');
		$this->set(compact('paramsDate','paramsDate2','shippermasters_id','branchmasters_id'));
		$invoices = $this->SelectData->data_select_conditions120s2($paramsDate,$paramsDate2,$did,$shippermasters_id,$productdetailmasters_id,$carmasters_id,$branchmasters_id);
		$this->set(compact('invoices'));
	}

	public function pdf($paramsDate,$paramsDate2,$sid,$pid,$cid,$bid) {
		// $did = 4;
		$did = $this->did;
		$date = new DateTime();
		$printdate = $date->format('Y-m-d H:i');
		$ordertrans = $this->SelectData->data_select_conditions120spdf($paramsDate,$paramsDate2,$did,$sid,$pid,$cid,$bid);
		$kaisyas = $this->Kaisya->SelectKaisyaName($this->Auth->user('kaisyas_id'));
		$shippername = $this->Shippermaster->SelectShipperName($sid);
		$this->set(compact('ordertrans','kaisyas','shippername','printdate'));

		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';
	}
}
