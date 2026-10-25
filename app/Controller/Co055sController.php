<?php
App::uses('AppController', 'Controller');
/**
 * Co050 Controller
 *
 * @copy Co050
 */
class Co055sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('User','Kaisya','Ordertran','Shippermaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran','Workdetailmaster','Workplace','Departmentmaster','Branchmaster','Branchgroup','OrdertranPricemaster');
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 4;
		$this->page = 'Co050s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 23; //事務職（コンテナ）
				case 24; //事務職（全て）
				case 25; //事務職（化学品コンテナ）
				case 28; //事務職（化学品コンテナ）
    				$this->set('user', $user);
    				return true;
    				break;
			}
		}
		return false;
	}
	/**
 * index method
 *
 * @return void
 */
	public function index() {
		$did = $this->did;
		$page = $this->page;
		$branchmasters_id = $this->branchmasters_id;
		$branchmasters = $this->SelectData->branch_select_conditions($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);
		$branchgroups = $this->Branchgroup->BranchgroupList();
		$this->set('branchgroups',$branchgroups);

		$date = new DateTime();
		if ($this->request->is('post')) {
		    $date = new DateTime($this->request->data('selectym1'));
		    //$date = $this->SelectData->date_select($this->request->data('selectym1'));
			$branchgroups_id = $this->request->data('branchgroups_id');
		}else{
		    if(!isset($branchgroups_id)){
		        $branchgroups_id = $this->branchgroups_id;
		    }
		}
		$this->set('branchgroups_id',$branchgroups_id);
		$paramsDate = $date->format('Y-m-d');
		$this->set('paramsDate',$paramsDate);
//$branchmastersにbranchgroupに紐づくIDを配列で渡す
		$branchmasters = $this->Branchmaster->find('list',[
			'fields' => ['id'],
			'conditions' => [
				'kaisyas_id' => $this->kaisyas_id,
				'branchgroups_id' => $branchgroups_id,
			],
			'order' => ['id' => 'asc']
		]);
		$this->Set('ordertrans', $this->Ordertran->SelectOrdertranco055s($paramsDate,$did,$branchmasters));
		$this->set(compact('branchmasters_id'));

		// $page = 'Co050s';
		$backdata = ['Page' => $page];
		$this->Session->write('BackData1', $backdata);
		$this->Session->delete('BackData2');
	}


}
