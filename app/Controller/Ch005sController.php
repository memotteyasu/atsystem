<?php
App::uses('AppController', 'Controller');
/**
 * Ch005 Controller
 *
 */
class Ch005sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = ['Pricemaster','Ordertran','Processtran','OrdertransPricemaster','Truckkind','Departmentmaster'];
	public $components = array('Search.Prg');
	public $presetVars = array();

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 2;
		$this->page = 'Ch005s';
		// 検索対象のフィールド設定代入
		$this->presetVars = $this->Pricemaster->presetVars;
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 23; //事務職（コンテナ）
				case 24; //事務職（全て）
				case 25; //事務職（化学品コンテナ）
				case 28; //事務職（運転手兼）
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
		if($this->Session->read('Ch005s')){
	        $sid = $this->Session->read('Ch005s.sid');
	        $pid = $this->Session->read('Ch005s.pid');
	        $id = $this->Session->read('Ch005s.id');
	        $cid = $this->Session->read('Ch005s.cid');
	        $page = $this->Session->read('Ch005s.page');
	        $haidate = $this->Session->read('Ch005s.haidate');
	    }else{
	        $this->Session->write('Ch005s.sid',$this->params['named']['sid']);
	        $this->Session->write('Ch005s.pid',$this->params['named']['pid']);
	        $this->Session->write('Ch005s.cid',$this->params['named']['cid']);
	        $this->Session->write('Ch005s.page',$this->params['named']['page']);
	        $this->Session->write('Ch005s.haidate',$this->params['named']['haidate']);
	        $sid = $this->params['named']['sid'];
	        $pid = $this->params['named']['pid'];
	        $cid = $this->params['named']['cid'];
	        $page = $this->params['named']['page'];
	        $haidate = $this->params['named']['haidate'];
	    }
	// 検索条件設定
	    $this->Prg->commonProcess();
	    // 検索条件取得
	    $conditions = $this->Pricemaster->parseCriteria($this->passedArgs);
		debug($conditions);
		debug('sid='.$sid);
		debug('pid='.$pid);
	    $this->paginate = [
	        'conditions' => [
	            'AND' => $conditions,
			    [
    	            'Pricemaster.delflg' => 0,
    	            'Pricemaster.keihiflg'=>0,
    	            'Pricemaster.kaisyas_id'=>$this->kaisyas_id,
					'Pricemaster.branchmasters_id'=>$this->branchmasters_id,
					'Pricemaster.departmentmasters_id'=>$this->did,
					'Pricemaster.shippermasters_id'=>$sid,
					'Pricemaster.productdetailmasters_id'=>$pid
			    ]
	        ],
	        'limit' => 10,
	        'order' => [
	            'Pricemaster.pricename' => 'asc',
	        ],
	        'recursive' => 0,
	    ];
	    $this->set('pricemasters', $this->paginate('Pricemaster'));

	    $departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
	    $this->set(compact('departmentmasters','sid','pid','id','cid','page','haidate'));
	}

	public function copy($id = null) {
	    //pricemasterの内容から、データをコピーしてordertran-processtranを作成する
        $pricemasters = $this->Pricemaster->find('first',[
            'conditions'=>[
                'Pricemaster.id'=>$id
            ],
            'recursive'=>0
        ]);
/*
		$truckkinds = $this->Truckkind->find('first',[
            'conditions'=>[
                'Truckkind.id' =>$this->params['named']['id']
            ],
            'recursive'=>0
        ]);
*/
        $ordertranpricemasters = $this->OrdertransPricemaster->findByPricemastersId($id);
        $oid = $ordertranpricemasters['OrdertransPricemaster']['ordertrans_id'];
        $ordertrans = $this->Ordertran->find('first',[
            'conditions'=>[
                'Ordertran.id' => $oid
			],
		    'contain'=>[
		        'Processtran'=>[
		            'order'=>[
		                'id',
		            ],
				]
			]
        ]);
        //ordertran作成
        $this->Ordertran->create();
        $this->request->data['Ordertran']['loadingdate'] = $this->params['named']['haidate'];
        $this->request->data['Ordertran']['loadingtime'] = '00:00';
        $this->request->data['Ordertran']['deliverydate'] = $this->params['named']['haidate'];
        $this->request->data['Ordertran']['deliverytime'] = '00:00';
        $this->request->data['Ordertran']['kaisyas_id'] = $this->kaisyas_id;
//        $this->request->data['Ordertran']['carmasters_id'] = $truckkinds['Truckkind']['carmasters_id'];
        $this->request->data['Ordertran']['carmasters_id'] = $this->params['named']['cid'];
//		$this->request->data['Ordertran']['productdetailmasters_id'] = $this->params['named']['pid'];
		$this->request->data['Ordertran']['productdetailmasters_id'] = $ordertrans['Ordertran']['productdetailmasters_id'];
//        $this->request->data['Ordertran']['shippermasters_id'] = $this->params['named']['sid'];
        $this->request->data['Ordertran']['shippermasters_id'] = $ordertrans['Ordertran']['shippermasters_id'];
//       $this->request->data['Ordertran']['truckkinds_id'] = $this->params['named']['id'];
        $this->request->data['Ordertran']['kaisen'] = 1;
        $this->request->data['Ordertran']['departmentmasters_id'] = $this->did;
        $this->request->data['Ordertran']['branchmasters_id'] = $pricemasters['Pricemaster']['branchmasters_id'];
        $this->request->data['Ordertran']['color'] = sprintf("#%06x",rand(0x000000, 0xFFFFFF));
        $this->request->data['Ordertran']['workplaces_id1'] = $ordertrans['Ordertran']['workplaces_id1'];
        $this->request->data['Ordertran']['workplaces_id2'] = $ordertrans['Ordertran']['workplaces_id2'];
        $this->request->data['Ordertran']['taxflg'] = $ordertrans['Ordertran']['taxflg'];
		$thsi->request->data['Ordertran']['delflg'] = 0;
        $this->Ordertran->save($this->request->data);
        $last_id = $this->Ordertran->getLastInsertID();
        //processtran作成
        foreach($ordertrans['Processtran'] as $processtran){
            $this->Processtran->create();
            $this->request->data['Processtran']['ordertrans_id'] = $last_id;
            $this->request->data['Processtran']['processstartdate'] = $this->params['named']['haidate'];
            $this->request->data['Processtran']['processstarttime'] = $processtran['processstarttime'];
            $this->request->data['Processtran']['workdetailmasters_id'] = $processtran['workdetailmasters_id'];
            $this->request->data['Processtran']['workplaces_id'] = $processtran['workplaces_id'];
            $this->request->data['Processtran']['productcontainers_id'] = $processtran['productcontainers_id'];
            $this->Processtran->save($this->request->data);
        }
        $this->Flash->success('オーダーをコピーしました。');
        return $this->redirect(['controller'=>'Ch003s','action' => 'index']);
	}


}
