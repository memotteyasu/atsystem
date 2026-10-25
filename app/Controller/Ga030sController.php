<?php
App::uses('AppController', 'Controller');
/**
 * Ga030 Controller
 */
class Ga030sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = ['User','Kaisya','Ordertran','Shippermaster','Productdetailmaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran','Workdetailmaster','Kintaitran','Branchmaster'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 7;
		$this->page = 'Ga030s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 24; //事務職（全て）
				case 22; //事務職（ガス）
				case 27; //事務職（石油・ガス）
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
		// $did = 3;
		$did = $this->did;
		$date = new DateTime();
		// $page = 'Ga030s';
		$page = $this->page;

		$branchmasters_id = $this->Auth->user('branchmasters_id');
		$branchmasters = $this->SelectData->branch_select_conditions($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);
		$this->set(compact('branchmasters'));

		if ($this->request->is('post')) {
			if (isset($this->request->data['selectym1'])) {
				$date = $this->SelectData->date_select($this->data['selectym1']);
				$branchmasters_id = $this->data['branchmasters_id'];
				$backdatas = ['date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')], 'bid' => $branchmasters_id, 'Page' => $page];
				$this->Session->write('BackData1', $backdatas);
			}else{
				if (isset($this->request->data['Ordertran'])) {
					$id = $this->data['Ordertran']['id'];
					$d1id = $this->data['Ordertran']['driver1id'];
					$d2id = $this->data['Ordertran']['driver2id'];
					$this->Ordertran->id = $id;
					$this->request->data['Ordertran']['driver1id'] = $d1id;
					$this->request->data['Ordertran']['driver2id'] = $d2id;
					$post = $this->Ordertran->findById($id);
					if (isset($post['Processtran'])) {
						$i = 0;
						foreach ($post['Processtran'] as $processtran) {
							$this->request->data['Processtran'][$i]['id'] = $processtran['id'];
							$this->request->data['Processtran'][$i]['pdriver1id'] = $d1id;
							$this->request->data['Processtran'][$i]['pdriver2id'] = $d2id;
							$i = $i + 1;
						}
					}
					if ($this->Ordertran->saveAll($this->request->data)) {
						$this->Flash->success(('乗務員を変更しました。'));
					}else{
						$this->Flash->error(('乗務員の変更に失敗しました。'));
					}
					if ($this->Session->check('BackData1')) {
						if ($this->Session->read('BackData1.Page') == $page) {
							$backdata2 = $this->Session->read('BackData1');
							$this->Session->write('BackData2', $backdata2);
						}
					}
					return $this->redirect(['action' => 'index']);
				}
			}
		} elseif ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				$date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				$branchmasters_id = $this->Session->read('BackData2.bid');
			}
			$this->Session->delete('BackData2');
		} else {
			if($this->Session->read('BackData1.date')){
				$date = $this->SelectData->date_select($this->Session->read('BackData1.date'));
				$branchmasters_id = $this->Session->read('BackData1.bid');
			}
			$this->Session->delete('BackData1');
		}

		$paramsDate = $date->format('Y-m-d');
		$week = ['日','月','火','水','木','金','土'];
		$this->set(compact('paramsDate','week','branchmasters_id'));

		$ordertrans = $this->SelectData->data_select_conditions030s($paramsDate,$did,$branchmasters_id);
		/* ドライバリスト */
		$driverlist = $this->User->SelectUserDriversListgas($did,$branchmasters_id);
		$multidriverlist = $this->User->SelectUserMultiDriversList();
		$userlist = $driverlist + $multidriverlist;
		/* 有給の運転手を抽出 */
		$driversrest = $this->Kintaitran->HolidayDriver($paramsDate,$paramsDate);
		$driversrest2 = $this->Kintaitran->HolidayDriverList($paramsDate,$paramsDate);
		$vacancyusers = array_diff($userlist,$driversrest2);

		/* 空きドライバ */
		$orderdriver1 = $this->Ordertran->OrderDriver1($paramsDate,$did);
		$orderdriver2 = $this->Ordertran->OrderDriver2($paramsDate,$did);
		$vacancyusers2 = array_diff($vacancyusers, $orderdriver1, $orderdriver2);

		$this->set(compact('ordertrans','carmasters','driversrest','userlist','vacancyusers2'));
	}
}
