<?php
App::uses('AppController', 'Controller');
/**
 * Oi030 Controller
 */
class Oi030sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = ['Ordertran','User','Kaisya','Shippermaster','Productdetailmaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran','Workdetailmaster','Kintaitran','Branchmaster'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 1;
		$this->page = 'Oi030s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 20; //事務職（石油）
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
	    $date = new DateTime();
	    $did = $this->did;
	    $page = $this->page;

	    $branchmasters_id = $this->Auth->user('branchmasters_id');
	    $branchmasters = $this->SelectData->branch_select_conditions($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);
	    $this->set(compact('branchmasters'));

	    if ($this->request->is('post')) {
	        if (isset($this->request->data['selectym1'])) {
	            $date = new DateTime($this->request->data('selectym1'));
	            $branchmasters_id = $this->request->data('branchmasters_id');
	            $backdatas = [
	                'date' => [
	                    'year' => $date->format('Y'),
	                    'month' => $date->format('m'),
	                    'day' => $date->format('d')
	                ],
	                'bid' => $branchmasters_id,
	                'Page' => $page
	            ];
	            $this->Session->write('BackData1', $backdatas);
	        }else{
	            if (isset($this->request->data['Processtran'])) {
	                $wdate = $this->Session->read('BackData1.date');
	                if($this->request->data['Processtran']['xxkey']==1){
	                    //1レコード目を変更したらそれ以降は同じドライバをセット
	                    //ordertranidでprocesstranを全件読み、ドライバをすべて更新する
	                    if($this->request->data['Processtran']['pdriver1id']){
	                        $driver1id = $this->request->data['Processtran']['pdriver1id'];
	                    }else{
	                        $driver1id = "''";
	                    }
	                    if($this->request->data['Processtran']['pdriver2id']){
	                        $driver2id = $this->request->data['Processtran']['pdriver2id'];
	                    }else{
	                        $driver2id = "''";
	                    }
	                    $result = $this->Processtran->updateAll([
	                        'Processtran.pdriver1id'=>$driver1id,
	                        'Processtran.pdriver2id'=>$driver2id
	                    ],
	                        ['Processtran.ordertrans_id'=>$this->request->data['Processtran']['ordertranid']]
	                        );
	                    if($result){
	                        $this->Flash->success(('乗務員を変更しました。2'));
	                    }else{
	                        $this->Flash->error(('失敗しました。2'));
	                    }
	                }else{
	                    if ($this->Processtran->save($this->request->data['Processtran'])) {
	                        $this->Flash->success(('乗務員を変更しました。1'));
	                    } else {
	                        $this->Flash->error(('乗務員の変更に失敗しました。'));
	                    }
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
	    $driverlist = $this->User->SelectUserDriversListoil($did,$branchmasters_id);
	    $multidriverlist = $this->User->SelectUserMultiDriversList();
	    $userlist = $driverlist + $multidriverlist;
	    /* 有給の運転手を抽出 */
	    $driversrest = $this->Kintaitran->HolidayDriver($paramsDate,$paramsDate);
	    $driversrest2 = $this->Kintaitran->HolidayDriverList($paramsDate,$paramsDate);
	    $vacancyusers = array_diff($userlist,$driversrest2);

	    /* 空きドライバ */
	    $orderdriver1 = $this->Processtran->ProcesstranDriver1($paramsDate,$branchmasters_id);
	    $orderdriver2 = $this->Processtran->ProcesstranDriver2($paramsDate,$branchmasters_id);
	    $vacancyusers2 = array_diff($vacancyusers, $orderdriver1, $orderdriver2);

	    $this->set(compact('ordertrans','carmasters','driversrest','userlist','vacancyusers2'));
	}

	public function confirm() {
	    $nowdate = new DateTime();
	    if ($this->params['named']['bid'] != 0) {
	        $bid = $this->params['named']['bid'];
	    } else {
	        $this->Flash->error(('営業所を選択してください。'));
	        exit;
	    }
	    $did = $this->did;
	    $paramsDate = new DateTime($this->params['named']['tyy'].'-'.$this->params['named']['tmm'].'-'.$this->params['named']['tdd']);
	    $week = ['日', '月', '火', '水', '木', '金', '土'];
	    if($this->request->is('post')){
	        // ordertranをよみ、該当のデータを取得し、確定処理する
	        $updatedata = [
	            'Ordertran.confirmflg'=>1,
	            'Ordertran.confirmdate'=>"'".$nowdate->format('Y-m-d H:i')."'"
	        ];
	        $ordertranconditions = [
	            'Ordertran.loadingdate' =>$paramsDate->format('Y-m-d'),
	            'Ordertran.delflg' => 0,
	            'Ordertran.departmentmasters_id'=>$did,
	            'Ordertran.branchmasters_id'=>$bid
	        ];
	        $updateresult = $this->Ordertran->updateAll($updatedata,$ordertranconditions);
	        if(!$updateresult){
	            $this->Flash->error(('確定失敗です'));
	            exit;
	        }

	        $ordertrans = $this->Ordertran->find('all',[
	            'fields'=>[
	                'Ordertran.id',
	            ],
	            'conditions' => [
	                'OR'=>[
	                    'Ordertran.loadingdate' => $paramsDate->format('Y-m-d'),
	                    'Ordertran.deliverydate' => $paramsDate->format('Y-m-d'),
	                ],
	                'Ordertran.delflg' => 0,
	                'Ordertran.departmentmasters_id'=>$did,
	                'Ordertran.branchmasters_id'=>$bid
	            ],
	            'contain'=>[
	                'Processtran'=>[
	                    'pdriver1id',
	                    'pdriver2id'
	                ],
	            ],
	            'recursive' => 0
	        ]);
	        $driver = [];
	        foreach($ordertrans as $ordertran){
	            foreach($ordertran['Processtran'] as $processtran){
	                if(in_array($processtran['pdriver1id'],$driver)){
	                    // 存在する場合
	                }else{
	                    //存在しない場合（運転手）
	                    $driver[] = $processtran['pdriver1id'];
	                }
	                if(in_array($processtran['pdriver2id'],$driver)){
	                    // 存在する場合
	                }else{
	                    //存在しない場合（助手）
	                    $driver[] = $processtran['pdriver2id'];
	                }
	            }
	        }
	        //該当者にメールを送信する。
	        $usermax = count($driver);
	        $bcclist=[];
	        for($k=0;$k<$usermax;$k++){
	            if(!$driver[$k]==0){
	                $users = $this->User->find('first',[
	                    'fields'=>[
	                        'useremail'
	                    ],
	                    'conditions'=>[
	                        'User.id'=>$driver[$k]
	                    ]
	                ]);
	                $bcclist[] = $users['User']['useremail'];
	            }
	        }
	        if($bcclist){
	            $text1 = $this->request->data['Mail']['message'];
	            $xemail = new CakeEmail('xserver');
	            $xemail->from(['support@asahitrans.com' => '朝日運輸サポート']);
	            $xemail->bcc($bcclist);
	            $xemail->emailPattern('/^.+$/');
	            $var1 = $paramsDate->format('Y年m月d日').'('.$week[$paramsDate->format('w')].')';
	            $xemail->subject('【朝日運輸】配車の予約が確定（変更）しました。('.$var1.')');
	            $xemail->emailFormat('text');
	            $xemail->viewVars(compact('var1','text1'));
	            $xemail->template('HostOrdertrans');
	            $xemail->send();
	        }
	        $this->Flash->success(('確定し、メールを送信しました。'));
	        return $this->redirect(['controller' => 'Oi030s', 'action' => 'index']);
	    }

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

	    $branchmasters_id = $this->params['pass']['2'];
	    $branchname = $this->Branchmaster->findById($branchmasters_id);
	    $this->set(compact('branchname','did'));

	    $paramsDate = $date->format('Y-m-d');
	    $week = ['日','月','火','水','木','金','土'];
	    $this->set(compact('paramsDate','week','branchmasters_id'));

	    $ordertrans = $this->SelectData->data_select_conditions030s($paramsDate,$did,$branchmasters_id);

	    $this->set(compact('ordertrans','carmasters'));
	}
}
