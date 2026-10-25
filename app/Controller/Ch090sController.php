<?php
App::uses('AppController', 'Controller');
/**
 * Ch090 Controller
 *
 */
class Ch090sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('User','Kaisya','Ordertran','Shippermaster','Productdetailmaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran','Workdetailmaster','Kintaitran','Branchmaster');
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 2;
		$this->page = 'Ch090s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 23; //事務職（化学品）
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
		// $did = 2;
		// $page = 'Ch090s';
		$did = $this->did;
		$page = $this->page;

		$date = new DateTime();
		$branchmasters_id = null;

		$branchmasters = $this->Branchmaster->BranchList($this->kaisyas_id);
		$this->set(compact('branchmasters'));

		if ($this->request->is('post')) {
			if (isset($this->request->data['selectym1'])) {
				$date = $this->SelectData->date_select($this->data['selectym1']);
				$branchmasters_id = $this->data['branchmasters_id'];
				$backdatas = ['date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')], 'bid' => $branchmasters_id, 'Page' => $page];
				$this->Session->write('BackData1', $backdatas);
			} else {
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
							$this->request->data['Processtran'][$i]['users_id'] = $d1id;
							$i = $i + 1;
						}
					}
					if ($this->Ordertran->saveAll($this->request->data)) {
						$this->Flash->success(('運転手を変更しました。'));
					}else{
						$this->Flash->error(('運転手の変更に失敗しました。'));
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
			$this->Session->delete('BackData1');
		}

		$paramsDate = $date->format('Y-m-d');
		$week = ['日','月','火','水','木','金','土'];
		$this->set(compact('paramsDate','week','branchmasters_id'));

		$data = $this->SelectData->data_select_conditions090s($paramsDate,$did,$branchmasters_id);
		$ordertrans = $data['ordertrans'];

		/* ドライバリスト */
		$userlist = $this->User->SelectUserDriversList($did,$branchmasters_id);

		/* 有給の運転手を抽出 */
		$driversrest = $this->Kintaitran->HolidayDriver($paramsDate,$paramsDate);
		$driversrest2 = $this->Kintaitran->HolidayDriverList($paramsDate,$paramsDate);
		$vacancyusers = array_diff($userlist,$driversrest2);

		/* 空きドライバ */
		$orderdriver1 = $this->Ordertran->OrderDriver1($paramsDate,$branchmasters_id);
		$orderdriver2 = $this->Ordertran->OrderDriver2($paramsDate,$branchmasters_id);
		$vacancyusers2 = array_diff($vacancyusers, $orderdriver1, $orderdriver2);

		$this->set(compact('ordertrans','driversrest','userlist','vacancyusers2'));
	}
}
