<?php
App::uses('AppController', 'Controller');
/**
 * Co110 Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Co110sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = ['User','Kaisya','Ordertran','Shippermaster','Productcontainer','Carmaster','Truckkind','Processtran','Workdetailmaster','Invoice','Invoicedetail',];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 4;
		$this->page = 'Co110s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 23; //事務職（コンテナ）
				case 24; //事務職（全て）
				case 25; //事務職（化学品コンテナ）
				case 28; //事務職兼運転手
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
		// $page = 'Co110s';
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
			// $productdetailmasters_id = $this->data['productdetailmasters_id'];
			$carmasters_id = $this->data['carmasters_id'];
			$branchmasters_id = $this->request->data('branchmasters_id');
			$backdatas = ['date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')], 'date2' => ['year' => $date2->format('Y'), 'month' => $date2->format('m'), 'day' => $date2->format('d')], 'sid' => $shippermasters_id, 'pid' => $productdetailmasters_id, 'cid' => $carmasters_id, 'bid' => $branchmasters_id, 'Page' => $page];
			$this->Session->write('BackData1', $backdatas);
		} elseif ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				$date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				$date2 = $this->SelectData->date_select($this->Session->read('BackData2.date2'));
				$shippermasters_id = $this->Session->read('BackData2.sid');
				$productdetailmasters_id = $this->Session->read('BackData2.pid');
				$carmasters_id = $this->Session->read('BackData2.cid');
				$branchmasters_id = $this->Session->read('BackData2.bid');
			}
			$this->Session->delete('BackData2');
		} else {
			$this->Session->delete('BackData1');
		}

		$paramsDate = $date->format('Y-m-d');
		$paramsDate2 = $date2->format('Y-m-d');
		$this->set(compact('paramsDate','paramsDate2','shippermasters_id','productdetailmasters_id','carmasters_id','branchmasters_id'));

		$selectData = $this->SelectData->data_select_conditions110s($paramsDate,$paramsDate2,$did,$shippermasters_id,$productdetailmasters_id,$carmasters_id,$branchmasters_id);
		$ordertrans = $selectData['ordertrans'];
		$productdetailmasters = $selectData['pdlist'];
		$this->set(compact('ordertrans','productdetailmasters'));
	}

	public function add($date = null, $date2 = null, $sid = null, $pid = null, $cid = null, $bid = null) {
		// $did = 4;
		// $page = 'Co110s';
		$did = $this->did;
		$page = $this->page;
		$shippermasters_id = $sid;
		$productdetailmasters_id = $pid;
		$branchmasters_id = $bid;

		if ($cid) {
			$carmasters_id = $cid;
		} else {
			$this->Flash->error(__('車両を選んでください。'));
			return $this->redirect(['action' => 'index']);
		}
		$carplateno = $this->Carmaster->find('first',[
			'fields'=>['Carmaster.plateno'],
			'conditions'=>['Carmaster.id'=>$cid],
			'recursive'=>0
		]);

		$this->set('carplateno',$carplateno);

		$backdata2 = $this->Session->read('BackData1');
		$this->Session->write('BackData2', $backdata2);

		$paramsDate = new DateTime($date);
		$paramsDate2 = new DateTime($date2);
		$this->set(compact('paramsDate','paramsDate2'));

		$selectData = $this->SelectData->data_select_conditions110s2($paramsDate,$paramsDate2,$did,$shippermasters_id,$productdetailmasters_id,$carmasters_id,$branchmasters_id);
		$ordertrans = $selectData['ordertrans'];
		$orderlist = $selectData['orderlist'];
		$this->set(compact('ordertrans','orderlist'));

		if ($this->request->is('post')) {
			$i = 0;

			foreach ($this->request->data['Details'] as $details) {
				if ($details['itemname']) {
					$this->request->data['Invoicedetail'][$i]['detaildate'] = $details['detaildate'];
					$this->request->data['Invoicedetail'][$i]['ordertrans_id'] = $details['ordertranid'];
					$this->request->data['Invoicedetail'][$i]['itemname'] = $details['itemname'];
					$this->request->data['Invoicedetail'][$i]['containerno'] = $details['containerno'];
					$this->request->data['Invoicedetail'][$i]['refno'] = $details['refno'];
					$this->request->data['Invoicedetail'][$i]['productdetailname'] = $details['productdetailname'];
					$this->request->data['Invoicedetail'][$i]['quantity'] = 1;
					$this->request->data['Invoicedetail'][$i]['unit'] = '式';
					$this->request->data['Invoicedetail'][$i]['price'] = $details['payment'];
					$this->request->data['Invoicedetail'][$i]['payment'] = $details['payment'];
				}
				$i = $i + 1;
			}

			$j = $i;
			foreach ($this->request->data['Details2'] as $details2) {
				if ($details2['itemname']) {
					$this->request->data['Invoicedetail'][$j]['itemname'] = $details2['itemname'];
					$this->request->data['Invoicedetail'][$j]['quantity'] = $details2['quantity'];
					$this->request->data['Invoicedetail'][$j]['unit'] = $details2['unit'];
					$this->request->data['Invoicedetail'][$j]['price'] = $details2['price'];
					$this->request->data['Invoicedetail'][$j]['payment'] = $details2['payment'];
					$this->request->data['Invoicedetail'][$j]['tax_flg'] = 1;
				}
				$j = $j + 1;
			}
			$this->request->data['Invoice']['departmentmasters_id'] = $did;
			$this->request->data['Invoice']['pagetype'] = $page;
			$this->request->data['Invoice']['mirror_flg'] = 1;
			$this->Invoice->create();
			if ($this->Invoice->saveAll($this->request->data)) {
				// 対象となったordertranのinvoice_flgを１にする。
				$i=0;
				foreach ($this->request->data['Details'] as $details) {
					if ($details['itemname']) {
						$ordertranid = $details['ordertranid'];
						// ordertranを更新
						$this->Ordertran->id = $ordertranid;
						$this->Ordertran->saveField('invoice_flg',1);
					}
					$i = $i + 1;
				}

				$this->Flash->success(__('請求書を作成しました。'));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error(__('請求書作成に失敗しました。'));
			}
		}
	}
}
