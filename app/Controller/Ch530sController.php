<?php
App::uses('AppController', 'Controller');
/**
 * Ch530 Controller
 *
 */
class Ch530sController extends AppController {
	public $layout = 'layout_partner';
    public $helpers = array('Html','Form');
    public $uses = ['Ordertran','User','Kaisya','Shippermaster','Productdetailmaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran','Workdetailmaster','Kintaitran','Branchmaster'];
    public $components = ['SelectData'];

    public function beforeFilter(){
        parent::beforeFilter();
        $this->did = 2;
        $this->page = 'Ch530s';
    }

    public function isAuthorized($user){
        if (isset($user['rolemasters_id'])) {
            switch($user['rolemasters_id']){
                case 10: //管理者
                case 12;
                case 29; //事務職（協力会社）
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

        $branchmasters_id = $this->branchmasters_id;

		if ($this->request->is('post')) {
			if (isset($this->request->data['selectym1'])) {
			    $date = new DateTime($this->request->data('selectym1'));
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
			}
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
        //基本はヒナセの車両を表示する？
        //その後に、ヒナセ以外の車両で運転手がヒナセが着いているものをselect
        /* ヒナセの車両だけを抽出
        $ids = array_values(
            $this->Carmaster->find('list', [
                'fields' => ['Carmaster.id'],
                'conditions'=>[
                    'Carmaster.branchmasters_id' => $branchmasters_id
                ],
                'recursive' => -1
            ])
        ); 
        */       
        //ヒナセのドライバが選択されているオーダーを抽出
        $ids = array_values(
            $this->User->find('list', [
                'fields' => ['User.id'],
                'conditions'=>[
                    'User.branchmasters_id' => $branchmasters_id
                ],
                'recursive' => -1
            ])
        );        

        $processtrans = $this->Processtran->find('all',[
            'conditions'=>[
                'Processtran.processstartdate' => $paramsDate,
                'OR'=>[
                    'Processtran.pdriver1id'=>$ids,
                    'Processtran.pdriver2id'=>$ids  
                ]
            ],
            'contain' => [
                'Ordertran',
                'PDriver1',
                'PDriver2',
                'Workdetailmaster',
                'Productcontainer',
                'Workplace',
            ],
            'order' => [
                'Ordertran.shippermasters_id',
                'Ordertran.carmasters_id'
            ],
            'recursive'=>1
        ]);
        //グループに属しているドライバの権限を持った部署指定した全員（会社関係なく）
		$userlist = $this->User->SelectUserDriversListpartner($this->branchmasters_id);

		$this->set(compact('processtrans','carmasters','userlist'));
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
                    'Ordertran.loadingdate' => $paramsDate->format('Y-m-d'),
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
            $bcclist='';
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
                    $bcclist .= $users['User']['useremail'].',';
                }
            }
            if($bcclist){
                $text1 = $this->request->data['Mail']['message'];
                $email = new CakeEmail('xserver');
                $email->from(['support@asahitrans.com' => '朝日運輸サポート']);
                $email->bcc($bcclist);
                $var1 = $paramsDate->format('Y年m月d日').'('.$week[$paramsDate->format('w')].')';
                $email->subject('【朝日運輸】配車の予約が確定しました。('.$var1.')');
                $email->emailFormat('text');
                $email->viewVars(compact('var1','text1'));
                $email->template('HostOrdertrans');
                $email->send();
            }
            $this->Flash->success(('確定し、メールを送信しました。'));
            return $this->redirect(['controller' => 'Ch530s', 'action' => 'index']);
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

        $branchmasters_id = $this->branchmasters_id;

        $paramsDate = $date->format('Y-m-d');
        $week = ['日','月','火','水','木','金','土'];
        $this->set(compact('paramsDate','week','branchgroups_id'));

        $ordertrans = $this->SelectData->data_select_conditionsCh030s($paramsDate,$did,$branchmasters_id,$this->kaisyas_id);

        $this->set(compact('ordertrans','carmasters'));
    }

    public function pdftehaipro() {
        // Content-Type
        $selectdate = $this->params['pass']['0'];
        $this->RequestHandler->respondAs('application/pdf');

        // レイアウトを使用しない
        $this->layout = '';

        $date = new DateTime($selectdate);
        $did = $this->params['pass']['1'];
        $page = $this->page;

        $branchgroups_id = $this->params['pass']['2'];
        if(!isset($branchgroups_id)){
            $branchgroups_id = $this->branchgroups_id;
        }

        $paramsDate = $date->format('Y-m-d');
        $week = ['日','月','火','水','木','金','土'];
        $this->set(compact('paramsDate','week','branchgroups_id'));
        $ids = [];
        //従業員を検索（協力会社の）
        $ids = $this->User->find('list', [
            'fields' => ['User.id'],
            'conditions' => ['User.branchmasters_id' => $this->branchmasters_id]
        ]);
        $processtrans = $this->Processtran->find('all',[
            'conditions'=>[
                'Processtran.processstartdate >=' => $paramsDate,
                'Processtran.pdriver1id'=>$ids,
                'Processtran.delflg'=>0,
            ],
            'order'=>[
                'Ordertran.departmentmasters_id' => 'asc',
                'Processtran.pdriver1id' =>'asc',
                'Ordertran.loadingdate' => 'asc',
                'Ordertran.loadingtime' => 'asc',
                'Processtran.processstartdate' => 'asc',
                'Processtran.processstarttime' => 'asc',
                'Processtran.displayno' => 'asc',
                'Ordertran.carmasters_id' => 'asc',
                'Ordertran.id' => 'asc',
            ],
            'recrusive'=>0
        ]);

        $this->set(compact('processtrans'));
    }

    public function ajax_chg(){
        $this->autoRender = false;
        $this->layout = 'ajax';
        $driverid = isset($this->request->data['pdriverid']) ? $this->request->data['pdriverid'] : null;
        $oid = isset($this->request->data['oid']) ? $this->request->data['oid'] : null;
        $prid = isset($this->request->data['prid']) ? $this->request->data['prid'] : null;
        $xxkey = isset($this->request->data['xxkey']) ? $this->request->data['xxkey'] : null;
        if (empty($driverid)) {
            echo json_encode(['status' => 'error', 'message' => 'driveridが指定されていません']);
            return;
        }
        $returntext = 'null';
        //20251110 積卸しは同じ人をセットしない。個別に選択する（穴井
        //1レコード目を変更したらそれ以降は同じドライバをセット
        $data = [
            'Processtran'=>[
                'id'=>$prid,
                'pdriver1id'=>$driverid
            ]
        ];
        if ($this->Processtran->save($data)) {
            $returntext = 'ok';
        } else {
            $returntext = 'ng';
        }
        echo json_encode(['status' => $returntext]);

    }
    public function ajax_chg2(){
        $this->autoRender = false;
        $this->layout = 'ajax';
        $driverid = isset($this->request->data['pdriverid']) ? $this->request->data['pdriverid'] : null;
        $oid = isset($this->request->data['oid']) ? $this->request->data['oid'] : null;
        $prid = isset($this->request->data['prid']) ? $this->request->data['prid'] : null;
        $xxkey = isset($this->request->data['xxkey']) ? $this->request->data['xxkey'] : null;
        if (empty($driverid)) {
            echo json_encode(['status' => 'error', 'message' => 'driveridが指定されていません']);
            return;
        }
        $returntext = 'null';
        $data = [
            'Processtran'=>[
                'id'=>$prid,
                'pdriver2id'=>$driverid
            ]
        ];
        if ($this->Processtran->save($data)) {
            $returntext = 'ok';
        } else {
            $returntext = 'ng';
        }
        echo json_encode(['status' => $returntext]);

    }
    
}
