<?php
App::uses('AppController', 'Controller');
/**
 * Co052 Controller
 *
 * @copy Oi050
 */
class Co052sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('User','Kaisya','Ordertran','Shippermaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran','Workdetailmaster','Workplace','Departmentmaster','Branchmaster','Branchgroup','OrdertranPricemaster');
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 4;
		$this->page = 'Co052s';
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
		$branchmasters = $this->Branchmaster->SelectBranchList($this->kaisyas_id);
		//$branchgroups = $this->Branchgroup->BranchgroupList();
		$this->set('branchmasters',$branchmasters);

		$date = new DateTime();
		if ($this->request->is('post')) {
		    $date = new DateTime($this->request->data('selectym1'));
			$branchmasters_id = $this->request->data('branchmasters_id');
		}else{
		    if(!isset($branchmasters_id)){
		        $branchmasters_id = $this->branchmasters_id;
		    }
		}
		$this->set('branchmasters_id',$branchmasters_id);
		$paramsDate = $date->format('Y-m-d');
		$this->set('paramsDate',$paramsDate);
		//$branchmastersにbranchgroupに紐づくIDを配列で渡す
		$check1flg = 1;
		$this->Set('ordertrans', $this->Ordertran->SelectOrdertranco052s($paramsDate,$did,$branchmasters_id,$check1flg));
		$this->set(compact('branchmasters_id'));

		// $page = 'Co052s';
		$backdata = ['Page' => $page];
		$this->Session->write('BackData1', $backdata);
		$this->Session->delete('BackData2');
	}

	public function add($id = null) {
		// $did = 4;
		$did = $this->did;
		$ordertrans = $this->Ordertran->SelectOrdertran050s2($id);
		// 入力者抽出
		$modifieduserid = $ordertrans['Ordertran']['modified_user'];
		$modified_username = $this->User->findById($modifieduserid);
		$this->set('modifiedusername',$modified_username['User']['usernameK']);

		$check1userid = $ordertrans['Ordertran']['check1_user'];
		if($check1userid){
    		$check1_username = $this->User->findById($check1userid);
    		$this->set('check1username',$check1_username['User']['usernameK']);
		}else{
		    $check1_username = null;
		    $this->set('check1username',$check1_username);
		}


		$pdriver1 = $this->User->SelectUserDriversList($did,$ordertrans['Carmaster']['branchmasters_id']);
		$pdriver2 = $pdriver1;
		$workdetailmasters = $this->Workdetailmaster->SelectWorkdetailList($did);

		$workplaces = $this->Workplace->SelectWorkplaceList2($did);

		$processtrans = $this->Processtran->SelectProcesstran050containers($id);


		$this->set(compact('ordertrans','processtrans', 'pdriver1','pdriver2', 'workdetailmasters','loadingmasters','deliverymasters','workplaces'));

		if ($this->request->is('post')) {
			$this->Processtran->create();
			if ($this->request->data['Processtran']['pdriver1id'] == 0) {
				$this->request->data['Processtran']['pdriver1id'] = null;
			}
			if ($this->request->data['Processtran']['pdriver2id'] == 0) {
				$this->request->data['Processtran']['pdriver2id'] = null;
			}
			$this->request->data['Processtran']['processenddate'] = $this->request->data['Processtran']['processstartdate'];
			$this->request->data['Processtran']['processendtime'] = $this->request->data['Processtran']['processstarttime'];
			$this->request->data['Processtran']['ordertrans_id'] = $id;
			if ($this->Processtran->save($this->request->data)) {
				$this->Flash->success('工程を登録しました。');
				//ordertranの終了場所を新しいデータで更新
				//processtrnaを全件読み込み、最初と最後のデータでworkplaces_id1とworkplaces_id2を更新する
				$processtrans = array();
				$processtrans = $this->Processtran->SelectProcesstran050containers($id);
				$cnt=0;
				foreach($processtrans as $processtran){
					if($cnt==0){
						//１件目
						$first_date = $processtran['Processtran']['processstartdate'];
						$first_time = $processtran['Processtran']['processstarttime'];
						$first_place = $processtran['Processtran']['workplaces_id'];
						$first_driver1 = $processtran['Processtran']['pdriver1id'];
						$first_driver2 = $processtran['Processtran']['pdriver2id'];
					}else{
					    // 以降のデータをセット
					    $last_date = $processtran['Processtran']['processstartdate'];
					    $last_time = $processtran['Processtran']['processstarttime'];
					    $last_place = $processtran['Processtran']['workplaces_id'];
					    $last_driver1 = $processtran['Processtran']['pdriver1id'];
					    $last_driver2 = $processtran['Processtran']['pdriver2id'];
					}
					$cnt++;
				}
				$this->Ordertran->create();
				$data = [
					'Ordertran'=>[
						'id'=>$id,
						'loadingdate' => $first_date,
						'loadingtime' => $first_time,
						'deliverydate' => $last_date,
						'deliverytime' => $last_time,
						'driver1id' => $first_driver1,
						'driver2id' => $first_driver2,
						'workplaces_id1' => $first_place,
						'workplaces_id2' => $last_place,
					    'check1flg' => 0,
					    'check1_user' => 0,
					]
				];
				$this->Ordertran->save($data);
				return $this->redirect(['controller' => 'Co052s','action' => 'add',$id]);
			} else {
				$this->Flash->error('工程の登録に失敗しました。');
			}
		}
		/*
		* 現在登録されているordertrans_pricematerを読み込み、表示する
		*/
		$ordertranpricemasters = $this->OrdertranPricemaster->find('all',[
			'conditions'=>[
				'ordertrans_id'=>$id,
			]
		]);
		$this->set('ordertranpricemasters',$ordertranpricemasters);

	}

	public function edit($id = null, $oid = null) {
		$post = $this->Processtran->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		// $did = 4;
		$did = $this->did;
		$users = $this->User->SelectUserDriversList($did,$post['Ordertran']['branchmasters_id']);
		$workdetailmasters = $this->Workdetailmaster->SelectWorkdetailList($did);

// 		$loadingmasters = $this->Loadingmaster->SelectLoadingList($did);
// 		$deliverymasters = $this->Deliverymaster->SelectDeliveryList($did);

		$workplaces = $this->Workplace->SelectWorkplaceList2($did);


		$this->set(compact('users', 'workdetailmasters','loadingmasters','deliverymasters','workplaces'));

		if ($this->request->is(['post', 'put'])) {
			$this->Processtran->id = $id;
			$this->request->data['Processtran']['ordertrans_id'] = $oid;
			if ($this->Processtran->save($this->request->data)) {
				$this->Flash->success(__('工程を編集しました。'));
				//ordertranの終了場所を新しいデータで更新
				//processtrnaを全件読み込み、最初と最後のデータでworkplaces_id1とworkplaces_id2を更新する
				$processtrans = array();
				$processtrans = $this->Processtran->SelectProcesstran050containers($oid);
				$cnt=0;
				foreach($processtrans as $processtran){
				    if($cnt==0){
				        //１件目
				        $first_date = $processtran['Processtran']['processstartdate'];
				        $first_time = $processtran['Processtran']['processstarttime'];
				        $first_place = $processtran['Processtran']['workplaces_id'];
				        $first_driver1 = $processtran['Processtran']['pdriver1id'];
				        $first_driver2 = $processtran['Processtran']['pdriver2id'];
				    }else{
				        // 以降のデータをセット
				        $last_date = $processtran['Processtran']['processstartdate'];
				        $last_time = $processtran['Processtran']['processstarttime'];
				        $last_place = $processtran['Processtran']['workplaces_id'];
				        $last_driver1 = $processtran['Processtran']['pdriver1id'];
				        $last_driver2 = $processtran['Processtran']['pdriver2id'];
				    }
				    $cnt++;
				}
				$this->Ordertran->create();
				$data = [
				    'Ordertran'=>[
				        'id'=>$oid,
				        'loadingdate' => $first_date,
				        'loadingtime' => $first_time,
				        'deliverydate' => $last_date,
				        'deliverytime' => $last_time,
				        'driver1id' => $first_driver1,
				        'driver2id' => $first_driver2,
				        'workplaces_id1' => $first_place,
				        'workplaces_id2' => $last_place,
				        'check1flg' => 0,
				        'check1_user' => 0,
				    ]
				];
				$this->Ordertran->save($data);
				return $this->redirect(['action' => 'add', 'controller' => 'Co052s', $oid]);
			} else {
				$this->Flash->error(__('工程の編集に失敗しました。'));
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function delete($id = null, $oid = null) {
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			if ($this->Processtran->delete($id)) {
			    //ordertranの終了場所を新しいデータで更新
			    //processtrnaを全件読み込み、最初と最後のデータでworkplaces_id1とworkplaces_id2を更新する
			    $processtrans = array();
			    $processtrans = $this->Processtran->SelectProcesstran050containers($oid);
			    $cnt=0;
			    foreach($processtrans as $processtran){
			        if($cnt==0){
			            //１件目
			            $first_date = $processtran['Processtran']['processstartdate'];
			            $first_time = $processtran['Processtran']['processstarttime'];
			            $first_place = $processtran['Processtran']['workplaces_id'];
			            $first_driver1 = $processtran['Processtran']['pdriver1id'];
			            $first_driver2 = $processtran['Processtran']['pdriver2id'];
			        }else{
			            // 以降のデータをセット
			            $last_date = $processtran['Processtran']['processstartdate'];
			            $last_time = $processtran['Processtran']['processstarttime'];
			            $last_place = $processtran['Processtran']['workplaces_id'];
			            $last_driver1 = $processtran['Processtran']['pdriver1id'];
			            $last_driver2 = $processtran['Processtran']['pdriver2id'];
			        }
			        $cnt++;
			    }
			    $this->Ordertran->create();
			    $data = [
			        'Ordertran'=>[
			            'id'=>$oid,
			            'loadingdate' => $first_date,
			            'loadingtime' => $first_time,
			            'deliverydate' => $last_date,
			            'deliverytime' => $last_time,
			            'driver1id' => $first_driver1,
			            'driver2id' => $first_driver2,
			            'workplaces_id1' => $first_place,
			            'workplaces_id2' => $last_place,
			            'check1flg' => 0,
			            'check1_user' => 0,
			        ]
			    ];
			    $this->Ordertran->save($data);
			    $this->Flash->success(__('削除しました'));
			} else {
				$this->Flash->error(__('失敗しました'));
			}
		}
		return $this->redirect(['controller' => 'Co052s','action' => 'add',$oid]);
	}

	public function approve1($id=null){
	    if(!$id){
	        //エラーとして、画面に戻る
	        return $this->redirect(['controller' => 'Co052s','action' => 'add',$id]);
	    }else{
	        $this->Ordertran->id = $id;
	        $this->request->data['Ordertran']['check1flg']=1;
	        $this->request->data['Ordertran']['check1_user']=Configure::read('logged_user_id');
	        $this->Ordertran->save($this->request->data,false,['check1flg','check1_user']);
	        return $this->redirect(['controller' => 'Co052s','action' => 'add',$id]);
	    }
	}

	public function pdf() {
		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';

		$paramsDate = new DateTime($this->params['named']['tyy'].'-'.$this->params['named']['tmm'].'-'.$this->params['named']['tdd']);
		$this->set('paramsDate',$paramsDate);
		$did = $this->did;

		$departmentmasters = $this->Departmentmaster->SelectDepartmentList();
		$department = $departmentmasters[$did];
		$this->set('department',$department);

		$this->set('ordertrans', $this->Ordertran->SelectOrdertran050containerspdf($paramsDate->format('Y-m-d'),$did,$this->params['named']['bid']));

		$this->set('workdetailmasters', $this->Workdetailmaster->find('list', [
			'fields' => ['id', 'workname']
		]));

		$this->set('branchmasters', $this->Branchmaster->find('first',[
			'fields' => ['id','branchname'],
			'conditions' => ['Branchmaster.id' => $this->params['named']['bid']]
		]));
	}
	
    public function pdftehai() {
        // Content-Type
        $selectdate = $this->params['pass']['0'];
        $this->RequestHandler->respondAs('application/pdf');

        // レイアウトを使用しない
        $this->layout = '';

        $date = new DateTime($selectdate);
        $did = $this->params['pass']['1'];
        $page = $this->page;
		$check1flg=0;

		$branchmasters_id = $this->params['pass']['2'];

        $paramsDate = $date->format('Y-m-d');
        $week = ['日','月','火','水','木','金','土'];
        $this->set(compact('paramsDate','week','branchmasters_id','did'));

        $ordertrans = $this->Ordertran->SelectOrdertranco052s($paramsDate,$did,$branchmasters,$check1flg);

        $this->set(compact('ordertrans','carmasters'));
    }

}
