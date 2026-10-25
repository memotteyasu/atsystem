<?php
App::uses('AppController', 'Controller');
/**
 * Oi051 Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Oi051sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('User','Kaisya','Ordertran','Shippermaster','Productdetailmaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran','Workdetailmaster');
	public $components = ['Timediff'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 1;
		$this->page = 'Oi051s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 20; //事務職（石油）
				case 24; //事務職（全て）
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
	public function index($id = null, $lid = null, $dlid =null) {
		// $did = 1;
		$did = $this->did;

		$orderlists = $this->Processtran->SelectProcesstran051s2($id,$lid,$dlid);
		$processtrans = $this->Processtran->SelectProcesstran051s($id,$lid,$dlid);
		$this->set(compact('orderlists','processtrans'));

		$shippermasters = $this->Shippermaster->SelectShipperList($did);
		$deliverymasters = $this->Deliverymaster->SelectDeliveryList($did);
		$productdetailmasters = $this->Productdetailmaster->pdnameList($did);
		$loadingmasters = $this->Loadingmaster->SelectLoadingList($did);
		$this->set(compact('deliverymasters','shippermasters','productdetailmasters','loadingmasters'));
	}

	public function copy($id = null, $oid = null) {
		$ordertrans = $this->Ordertran->SelectOrdertran051s($oid);
		$processtrans = $this->Processtran->SelectProcesstran051s3($id);

		$newstartdate = $ordertrans['Ordertran']['deliverydate'];
		$newstarttime = $ordertrans['Ordertran']['deliverytime'];
		$newstartdatetime = $newstartdate.' '.$newstarttime;

		/* 1.卸作業のキー（2）をもつ配列データを抽出　（卸が必ずあるわけではない） */
		foreach ($processtrans as $keycheck){
			if($keycheck['Processtran']['workdetailmasters_id'] == 2){
				/* 卸の日時を待避 */
				$keystartdate = $keycheck['Processtran']['processstartdate'];
				$keystarttime = $keycheck['Processtran']['processstarttime'];
				$keystartdatetime = $keystartdate.' '.$keystarttime;
				$keyenddate = $keycheck['Processtran']['processstartdate'];
				$keyendtime = $keycheck['Processtran']['processstarttime'];
				$keyenddatetime = $keyenddate.' '.$keyendtime;
			}
		}
		$this->request->data = $processtrans;
		$i = 0;
		foreach($this->request->data as $workarea){
			/* 1レコードずつprocessstartdate,timeとの時間の差を計算 */
			$startdate = $workarea['Processtran']['processstartdate'];
			$starttime = $workarea['Processtran']['processstarttime'];
			$startdatetime = $startdate.' '.$starttime;
			$diffdatetime1 = $this->Timediff->time_diff($keystartdatetime,$startdatetime);
			$currectdatetime1 = $this->Timediff->time_diff_add($newstartdatetime,$diffdatetime1);

			$enddate = $workarea['Processtran']['processenddate'];
			$endtime = $workarea['Processtran']['processendtime'];
			$enddatetime = $enddate.' '.$endtime;
			$diffdatetime2 = $this->Timediff->time_diff($keyenddatetime,$enddatetime);
			$currectdatetime2 = $this->Timediff->time_diff_add($newstartdatetime,$diffdatetime2);

			$this->request->data[$i]['Processtran']['id'] = null;
			$this->request->data[$i]['Processtran']['ordertrans_id'] = $oid;
			$this->request->data[$i]['Processtran']['processstartdate'] = date('Y-m-d',$currectdatetime1);
			$this->request->data[$i]['Processtran']['processstarttime'] = date('H:i:s',$currectdatetime1);
			$this->request->data[$i]['Processtran']['processenddate'] = date('Y-m-d',$currectdatetime2);
			$this->request->data[$i]['Processtran']['processendtime'] = date('H:i:s',$currectdatetime2);
			$this->request->data[$i]['Processtran']['created'] = date('Y-m-d H:i:s');
			$this->request->data[$i]['Processtran']['modified'] = null;
			$this->request->data[$i]['Processtran']['users_id'] = null;
			$i = $i + 1;
		}
		//debug($this->request->data);
		if ($this->Processtran->saveAll($this->request->data)) {
			$this->Flash->success(__('工程を複製しました。'));
			return $this->redirect(['controller' => 'Oi050s','action' => 'add', $oid]);
		} else {
			$this->Flash->error(__('複製に失敗しました。'));
		}
	}
}
