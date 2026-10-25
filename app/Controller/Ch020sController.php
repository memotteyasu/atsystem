<?php
App::uses('AppController', 'Controller');
/**
 * Ch020 Controller
 *
 * @copy Oi001s
 */
class Ch020sController extends AppController {
	public $layout = 'layout_host';
	public $uses = ['User','Kaisya','Ordertran','Carmaster','Shippermaster','Truckkind','Productdetailmaster','Deliverymaster','Kintaitran','Branchmaster','Workdetailmaster'];
    public $helpers = array('Html','Form');
    public $components = ['SelectData'];

/**
 * Components
 *
 * @var array
 */
    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);
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
		$did = 2;
		$date = new DateTime();
		$page = 'Ch020s';

		$branchmasters_id = null;

		$branchmasters = $this->Branchmaster->BranchList($this->Auth->user('kaisyas_id'));
		$deliverymasters = $this->Deliverymaster->SelectDeliveryList($did);
		$this->set(compact('branchmasters','deliverymasters'));

		if ($this->request->is('post')) {
			if (isset($this->request->data['selectym1'])) {
				$date = $this->SelectData->date_select($this->data['selectym1']);
				$branchmasters_id = $this->data['branchmasters_id'];
				$backdatas = ['date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')],'bid' => $branchmasters_id, 'Page' => $page];
				$this->Session->write('BackData1', $backdatas);
			}
			if (isset($this->request->data['No1'])) {
				$this->request->data['Ordertran']['id'] = $this->request->data['No1']['id'];
				$this->request->data['Ordertran']['container_no'] = $this->request->data['No1']['container_no'];
				if ($this->Ordertran->save($this->request->data['Ordertran'])) {
					$this->Flash->success(('Noを入力しました。'));
				}else{
					$this->Flash->error(('失敗しました。'));
				}
				if ($this->Session->check('BackData1')) {
					if ($this->Session->read('BackData1.Page') == $page) {
						$backdata2 = $this->Session->read('BackData1');
						$this->Session->write('BackData2', $backdata2);
					}
				}
				return $this->redirect(['action' => 'index']);
			}
			if (isset($this->request->data['No2'])) {
				$this->request->data['Ordertran']['id'] = $this->request->data['No2']['id'];
				$this->request->data['Ordertran']['ref_no'] = $this->request->data['No2']['ref_no'];
				if ($this->Ordertran->save($this->request->data['Ordertran'])) {
					$this->Flash->success(('Noを入力しました。'));
				}else{
					$this->Flash->error(('失敗しました。'));
				}
				if ($this->Session->check('BackData1')) {
					if ($this->Session->read('BackData1.Page') == $page) {
						$backdata2 = $this->Session->read('BackData1');
						$this->Session->write('BackData2', $backdata2);
					}
				}
				return $this->redirect(['action' => 'index']);
			}
		} elseif ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				$date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				$branchmasters_id = $this->Session->read('BackData2.bid');
			}
			$this->Session->delete('BackData2');
		}

		$paramsDate = $date->format(('Y-m-d'));
		$week = ['日','月','火','水','木','金','土'];
		$this->set(compact('paramsDate','week','branchmasters_id'));

		$truckkinds = $this->SelectData->data_select_conditions020s($paramsDate,$branchmasters_id,$did);
		$carmasters = $this->Carmaster->NotRunCar($paramsDate,$paramsDate);
		$drivers = $this->Kintaitran->HolidayDriver($paramsDate,$paramsDate);

		$this->set(compact('truckkinds','carmasters','drivers'));
	}

	public function pdf($tyy,$tmm,$tdd,$bid) {
		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';

		$paramsDate = $tyy.'-'.$tmm.'-'.$tdd;
		$this->set('paramsDate',$paramsDate);
		$did = 2;

		if ($bid == 0) {
			$branchconditions = [];
		} else {
			$branchconditions = ['Ordertran.branchmasters_id' => $bid];
		}

		$this->set('ordertrans', $this->Ordertran->find('all', [
// 			'fields' => [
// 				'Ordertran.id',
// 				'Ordertran.truckkinds_id',
// 				'Ordertran.deliverydate',
// 				'Ordertran.deliverytime',
// 				'Ordertran.kaisen',
// 				'Ordertran.container_no',
// 				'Ordertran.ref_no',
// 				'Deliverymaster.deliveryname',
// 				'Deliverymaster.deliveryshortname',
// 				'Productdetailmaster.pdname',
// 			],
			'order' => [
				'Ordertran.carmasters_id' => 'asc',
				'Ordertran.deliverydate' => 'asc',
				'Ordertran.deliverytime' => 'asc',
				'Ordertran.kaisen' => 'asc',
			],
			'conditions' => [
				'AND' => [
					'Ordertran.deliverydate' => $paramsDate,
					'Ordertran.departmentmasters_id' => $did,
					$branchconditions
				]
			]
		]));

		$this->set('orderlists', $this->Ordertran->find('all', [
			'fields' => [
				'Ordertran.carmasters_id',
				'Ordertran.truckkinds_id',
				'Carmaster.plateno',
				'Shippermaster.id',
				'Productdetailmaster.id',
				'Productdetailmaster.pdname',
				'Truckkind.id',
			],
			'order' => [
				'Ordertran.carmasters_id' => 'asc',
				'Ordertran.deliverydate' => 'asc',
				'Ordertran.deliverytime' => 'asc',
				'Ordertran.kaisen' => 'asc',
			],
			'conditions' => [
				'AND' => [
					'Ordertran.deliverydate' => $paramsDate,
					'Ordertran.departmentmasters_id' => $did,
					$branchconditions
				]
			],
			'group' => 'Ordertran.truckkinds_id'
		]));

		$this->set('workdetailmasters', $this->Workdetailmaster->find('list', [
			'fields' => ['id', 'workname']
		]));

		$this->set('branchmasters', $this->Branchmaster->find('first',[
			'fields' => ['id','branchname'],
			'conditions' => ['Branchmaster.id' => $bid]
		]));
	}
}
