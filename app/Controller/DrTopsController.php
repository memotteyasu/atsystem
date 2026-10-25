<?php
App::uses('AppController', 'Controller');
/**
 * DrTops Controller
 *
 * @property DrTop $DrTops
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class DrTopsController extends AppController {
	public $layout = 'layout_drive';
    public $helpers = array('Html','Form');
    public $uses = array('Personalmessage','Gyoumumessage','Ordertran','Processtran');

    public function beforeFilter(){
        parent::beforeFilter();
        $this->response->disableCache();
        //$this->set('auth',$this->Auth);
    }

    public function isAuthorized($user){
    	if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '30' && $user['rolemasters_id'] <='39'){
    		$this->set('user',$user);
    		return true;
    	}
		if (
			$this->request->params['action'] === 'pdf23' &&
			isset($user['rolemasters_id']) &&
			$user['rolemasters_id'] >= '20' && $user['rolemasters_id'] <= '29'
		) {
			$this->set('user', $user);
			return true;
		}
    	return false;
    	break;
    }

	/*
    public function isAuthorized($user){
        if(($user['Rolemasters']['id'] >= '30')&&($user['Rolemasters']['id'] <= '39')){
            return true;
        }else{
            	return false;
            	break;
        }
    }
    */

/**
 * index method
 *
 * @return void
 */
	public function index() {
	    $date = new DateTime('now');
		$date->modify('-1 day');

		$personalmessages = $this->Personalmessage->find('all',[
			'fields'=>['id','personalcomment','created'],
			'conditions'=>[
				'AND'=>[
					'Personalmessage.delflg'=>0,
					'Personalmessage.users_id'=>$this->Auth->user('id')
				]
			],
			'order'=>[
				'Personalmessage.created'=>'desc'
			]
		]);

		$gyoumumessages = $this->Gyoumumessage->find('all',[
			'fields'=>['id','gyoumucomment','created'],
		    'conditions'=>[
		        'AND'=>[
		            'Gyoumumessage.delflg'=>0,
		            'Gyoumumessage.kaisyas_id'=>$this->Auth->user('kaisyas_id'),
//		            'Gyoumumessage.created >='=>$date->format('Y-m-d')
		        ]
		    ],
			'order'=>[
				'Gyoumumessage.created'=>'desc'
			]
		]);

		$this->set(compact('personalmessages','gyoumumessages'));
		
		//processtranから日付・自分が運転手のデータを取得
		$processtrans = $this->Processtran->find('all',[
		    'fields'=>[
		        'Processtran.ordertrans_id',
		        "MIN(CONCAT(Processtran.processstartdate, ' ', Processtran.processstarttime)) AS first_process_start",
		    ],
		    'conditions'=>[
		        'Processtran.pdriver1id'=>$this->Auth->user('id'),
		        'Processtran.processstartdate >='=>$date->format('Y-m-d'),
				'Processtran.scheduledate !='=>null 
		    ],
		    'group'=>[
		        'Processtran.ordertrans_id'
		    ],
		    'order'=>[
		        'first_process_start'
		    ]
		]);
		$ordertrans=[];
		foreach($processtrans as $processtran){

		    $ordertrans[] = $this->Ordertran->findById($processtran['Processtran']['ordertrans_id']);

		}
		$datacount = count($ordertrans);
		$flg0=0;
		$flg1=0;
		for($i=0;$i<$datacount;$i++){
            $confirmflg = $ordertrans[$i]['Ordertran']['confirmflg'];
            if($confirmflg==0){
                $flg0++;
            }else{
                $flg1++;
            }
		}
		if($datacount == $flg1){
		    //すべて確定されている場合
		    $this->set('confirmchk','OK');
		}else{
		    $this->set('confirmchk','NG');
		}
		$this->set('ordertrans',$ordertrans);
		$this->set('wdate',$date->format('Y-m-d'));
		/*
		$dr1confirm1 = $this->Processtran->find('all',[
		    'fields'=>[
		        'Processtran.id',
		        'PDriver1.usernameK',
		        'PDriver2.usernameK',
		        'Processtran.processstartdate',
		        'Processtran.processstarttime',
		        'Processtran.workddetailcomment',
		        'Workdetailmaster.workname',
		        'Processtran.container_no',
		        'Processtran.ref_no',
		        'Processtran.ordertrans_id',
		        'Workplace.workplaceshortname',
		        'Workplace.googlemapurl',
		        'Workplace.mapcomment',
		        'Ordertran.carmasters_id',
		        'Ordertran.workplaces_id1',
		        'Ordertran.workplaces_id2',
		        'Ordertran.correctdate',
		        'Productcontainer.pdconname',
		    ],
		    'conditions'=>[
		        'Processtran.pdriver1id'=>$this->Auth->user('id'),
		        'Processtran.processstartdate >='=>$date->format('Y-m-d'),
		        'Ordertran.confirmflg'=>1
		    ],
		    'order'=>[
		        'Processtran.processstartdate',
		        'Processtran.processstarttime',
		        'Processtran.id'
		    ],
		    'recursive'=>1
		]);
		debug($dr1confirm1);
		*/
	}

	public function addpersonalmessage(){
		if($this->request->is('POST')){
			$this->Personalmessage->create();
			$this->request->data['Personalmessage']['users_id'] = $this->Auth->user('id');
			if ($this->Personalmessage->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error('メモを入力してください。');
				return $this->redirect(['action' => 'index']);
			}
			//return true;
		}
	}

	public function delete($id = null) {
	    $this->Personalmessage->id = $id;
	    if(!$this->Personalmessage->exists()){
	        throw new NotfoundException(__('データが存在しません'));
	    }
	    $this->request->allowMethod('post','delete');
	    if($this->Personalmessage->delete()){
	        $this->Flash->success('削除しました');
	    }else{
	        $this->Flash->error('削除できません！');
	    }
	    return $this->redirect(array('action'=>'index'));
	}

	public function pdf($date) {

	    $week = ['日','月','火','水','木','金','土'];

	    $newdate = new DateTime($date);

	    $this->set('date',$newdate->format('Y-m-d'));
	    $this->set('week',$week);
//PDF出力時にconfirmdateを更新
	    $this->Processtran->updateAll([
	        'confirmdate' => "'".$newdate->format('Y-m-d H:i:s')."'"
	    ], [
	        'OR' => [
	            'Processtran.pdriver1id'=>$this->Auth->user('id'),
	            'Processtran.pdriver2id'=>$this->Auth->user('id'),
	        ],
	        'Processtran.processstartdate'=>$newdate->format('Y-m-d'),
	        'Ordertran.confirmflg'=>1
	    ]);


	    $drconfirm = $this->Processtran->find('all',[
	        'fields' => [
	            'Processtran.id',
	            'PDriver1.usernameK',
	            'PDriver2.usernameK',
	            'Processtran.processstartdate',
	            'Processtran.processstarttime',
	            'Processtran.workddetailcomment',
	            'Workdetailmaster.workname',
	            'Processtran.container_no',
	            'Processtran.ordertrans_id',
	            'Workplace.workplacename',
	            'Workplace.workplaceshortname',
	            'Workplace.googlemapurl',
	            'Workplace.mapcomment',
	            'Productcontainer.pdconname',
	        ],
	        'conditions' => [
	            'OR' => [
	                'Processtran.pdriver1id'=>$this->Auth->user('id'),
	                'Processtran.pdriver2id'=>$this->Auth->user('id'),
	            ],
	            'Processtran.processstartdate'=>$newdate->format('Y-m-d'),
	            'Ordertran.confirmflg'=>1
	        ],
	        'contain' => [
	            'PDriver1',
	            'PDriver2',
	            'Workdetailmaster',
	            'Workplace',
	            'Productcontainer',
	            'Ordertran' => [
	                'fields' => [
	                    'id',
	                    'amount',
	                    'carmasters_id',
	                    'shippermasters_id',
	                    'productdetailmasters_id',
	                ],
	                'Carmaster' => [
	                    'fields' => [
	                        'id',
	                        'plateno',
	                        'innerno'
	                    ]
	                ],
	                'Shippermaster' => [
	                    'fields' => [
	                        'id',
	                        'shippername'
	                    ]
	                ],
	                'Productdetailmaster' => [
	                    'fields' => [
	                        'id',
	                        'pdname'
	                    ]
	                ]
	            ],
	        ],
	        'order' => [
	            'Processtran.processstartdate',
	            'Processtran.processstarttime',
	            'Processtran.id'
	        ],
	        'recursive' => 0
	    ]);
	    $this->set('drconfirm',$drconfirm);
	    if($drconfirm){
    	    // Content-Type
    	    $this->RequestHandler->respondAs('application/pdf');
    	    // レイアウトを使用しない
    	    $this->layout = '';
	    }

	}

	public function pdf2($date) {

	    $week = ['日','月','火','水','木','金','土'];

	    $newdate = new DateTime($date);

	    $this->set('date',$newdate->format('Y-m-d'));
	    $this->set('week',$week);
	    //PDF出力時にconfirmdateを更新

        // Content-Type
        $this->RequestHandler->respondAs('application/pdf');
        // レイアウトを使用しない
        $this->layout = '';

	}
	public function pdf22($date) {

	    $week = ['日','月','火','水','木','金','土'];

	    $newdate = new DateTime($date);

	    $this->set('date',$newdate->format('Y-m-d'));
	    $this->set('week',$week);
	    //PDF出力時にconfirmdateを更新

	    // Content-Type
	    $this->RequestHandler->respondAs('application/pdf');
	    // レイアウトを使用しない
	    $this->layout = '';

	}
	public function pdf23($date) {

	    $week = ['日','月','火','水','木','金','土'];

	    $newdate = new DateTime($date);

	    $this->set('date',$newdate->format('Y-m-d'));
	    $this->set('week',$week);
	    //PDF出力時にconfirmdateを更新

	    // Content-Type
	    $this->RequestHandler->respondAs('application/pdf');
	    // レイアウトを使用しない
	    $this->layout = '';

	}
}
