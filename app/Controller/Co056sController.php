<?php
App::uses('AppController', 'Controller');
/**
 * Co056 Controller
 *
 */
class Co056sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('User','Kaisya','Ordertran','Shippermaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran','Workdetailmaster','Workplace','Departmentmaster','Branchmaster','Branchgroup','OrdertranPricemaster','Ordercanceltran','Orderexpensestran');
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 4;
		$this->page = 'Co056s';
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
	    if($this->Session->read('Backdata1.page')!=$this->page){
	       $check1flg = 0;
	    }
		$did = $this->did;
		$page = $this->page;
		$branchmasters_id = $this->branchmasters_id;
		$branchmasters = $this->SelectData->branch_select_conditions($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);
		$branchgroups = $this->Branchgroup->BranchgroupList();
		$this->set('branchgroups',$branchgroups);

		$date = new DateTime();
		if ($this->request->is('post')) {
		    $date = new DateTime($this->request->data('selectym1'));
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
		$this->Set('ordertrans', $this->Ordertran->SelectOrdertranco056s($paramsDate,$did,$branchmasters));


		$this->set(compact('branchmasters_id'));

		$backdata = ['Page' => $page];
		$this->Session->write('BackData1', $backdata);
		$this->Session->delete('BackData2');
	}



	public function delete($id = null, $oid = null) {
	    if ($this->request->is('get')){
	        throw new MethodNotAllowedException();
	    }else{
			//削除は1を設定
	        $this->request->data['Ordertran']['delflg'] = 1;
	        $this->Ordertran->id = $id;
	        $this->Ordertran->save([
				'id' => $id,
				'delflg' => 1
			]);
			$processtrans = $this->Processtran->find('all',[
				'conditions' => [
					'Processtran.ordertrans_id' => $id
				],
				'recursive' => 0,
			]);
			if ($processtrans) {
				foreach ($processtrans as $processtran) {
					$this->request->data['Processtran']['id'] = $processtran['Processtran']['id'];
					$this->request->data['Processtran']['delflg'] = 1;
					$this->Processtran->save($this->request->data);
				}
			}
			$this->Flash->success(__('削除しました'));
	    }
		return $this->redirect(['controller' => 'Co056s','action' => 'index']);
	}

	public function cancelentry($id = null){
		//20251218 priceを更新するように追加
		if ($this->request->is('post')) {
			//idのordertranのbillconfirmdate、cancelper,cancelpriceを更新
	        $this->Ordertran->id = $id;
	        $this->Ordertran->save([
				'id' => $id,
				'billconfirmdate' => $this->request->data('Ordertran.billconfirmdate'),
				'detailbilldate' => $this->request->data('Ordertran.detailbilldate'),
				'cancelper' => $this->request->data('Ordertran.cancelper'),
				'cancelprice' => $this->request->data('Ordertran.cancelprice'),
				'price' => $this->request->data('Ordertran.cancelprice'),
			]);
			$this->Flash->success('キャンセル金額を保存しました');
		}
		$orderpricemasters = $this->OrdertranPricemaster->SelectOrdertranPrice080s($id);
		$this->set('orderpricemasters',$orderpricemasters);
		// 諸費データを読み込み
		$orderexpensestrans = $this->Orderexpensestran->find('all',[
			'conditions'=>[
				'ordertrans_id' => $id
			],
			'recursive'=>-1
		]);
		$this->set('orderepensestrans',$orderexpensestrans);

		//キャンセル確認画面用
		$ordertran = $this->Ordertran->find('first',[
			'conditions'=>[
				'Ordertran.id'=>$id
			],
			'contain'=>[
				'Processtran'=>[
					'order'=>[
						'id',
						'processstartdate',
						'processstarttime',
					],
					'fields'=>[
						'Processtran.id',
						'Processtran.workplaces_id',
						'Processtran.workddetailcomment',
						'Processtran.ordertrans_id',
						'Processtran.processstartdate',
						'Processtran.processstarttime',
						'Processtran.pdriver1id',
						'Processtran.pdriver2id',
						'Processtran.workdetailmasters_id',
						'Processtran.workplaces_id',
						'Processtran.productdetailmastername',
						'Processtran.container_no',
						'Processtran.ref_no',
						'Processtran.timenote',
						'Processtran.productcontainers_id'
					],
					'conditions'=>[
						'Processtran.delflg'=>3
					]
				],
				'Shippermaster',
				'Carmaster'=>[
					'fields'=>[
						'Carmaster.plateno',
						'Carmaster.carno',
						'Carmaster.branchmasters_id'
					],
				],
				'Workplace1',
				'Workplace2',
				'Processtran',
				'Tantomaster',
			],
		]);
		$this->set(compact('ordertran'));
		$lastdate = New DateTime($ordertran['Ordertran']['loadingdate']);
		$lastday = $lastdate->modify('last day of')->format('Y-m-d');
		$this->set('lastday',$lastday);

	}

}
