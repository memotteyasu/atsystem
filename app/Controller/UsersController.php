<?php

use phpDocumentor\Reflection\Types\Integer;

App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class UsersController extends AppController {
	public $uses = array('User','Kaisya','Rolemaster','Branchmaster','Employeepoint','Departmentmaster','Calendarusermaster','Evaluation');
	public $helpers = array('Html','Form','Session','Js' => ['Jquery']);
	public $components = array('Search.Prg','RequestHandler','Cookie');
	public $presetVars = array();
/**
 * Components
 *
 * @var array
 */
	//public $components = array('Paginator', 'Flash', 'Session');

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('login','logout', 'add', 'ajax_department', 'ajax_branch');
		// 認証コンポーネントをViewで利用可能にしておく
		$this->Auth->loginError = 'ユーザ名もしくはパスワードに誤りがあります';
		$this->Auth->authError = 'IDを入力しログインしてください';
		// $this->set('auth',$this->Auth);
		$this->page = 'Users';

		// 検索対象のフィールド設定代入
		$this->presetVars = $this->User->presetVars;
	}

	public function isAuthorized($user){
		if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '90'){
			$this->set('user',$user);

			return true;
		}
		return false;
	}

/**
 * login method
 *
 * @return void
 */
	public function login() {
		//セッション、クッキーをすべて削除
		$_SESSION = array();
		$this->Cookie->destroy();
		
		if ($this->request->is('post')) {
		    if($this->Auth->login()){
				$user = $this->Auth->user();
				
				if($this->request->data('User.kaisyakey') != $user['Kaisya']['kaisyakey']){
					$this->Flash->error(__('何かが違います'));
					$this->logout();
				}
				/* 管理者 */
				if(($user['Rolemaster']['id']>=10)and($user['Rolemaster']['id']<=19))
				{
					return $this->redirect('/HostTops/index');
					// break;
				}
				/* 事務職 */
				if(($user['Rolemaster']['id']>=20)and($user['Rolemaster']['id']<=28))
				{
					return $this->redirect('/ClerkTops/index');
					// break;
				}
				/* 運転手 */
				if(($user['Rolemaster']['id']>=30)and($user['Rolemaster']['id']<=39))
				{
					return $this->redirect('/DrTops/index');
					// break;
				}
				/* 協力会社 */
				if($user['Rolemaster']['id']==29)
				{
				    return $this->redirect('/PartnerTops/index');
				    // break;
				}
				/* 受付 */
				if($user['Rolemaster']['id']>=90)
				{
					// クッキーに書き込み
					$cookie = $this->request->data;
					$this->Cookie->write('Auth',$cookie,true,'+1 years');
					return $this->redirect('/ReceTops/index');
					// break;
				}

			}else{
				$this->Flash->error(__('会社ID,ユーザーID、またはパスワードが違います'));
				$this->logout();
				//$this->Session->setFlash('メールアドレスかパスワードが間違っています');
			}
		}
	}

/**
 * logout method
 *
 * @return void
 */
	public function logout(){
		return $this->redirect($this->Auth->logout()); // ログアウト処理
	}

/**
 * index method
 *
 * @return void
 */

	public function index() {
		$user = $this->Auth->user();
		if(($user['Rolemaster']['id']>=10)and($user['Rolemaster']['id']<=19))
		{
			$this->layout = 'layout_host';
		}
		if(($user['Rolemaster']['id']>=20)and($user['Rolemaster']['id']<=28))
		{
			$this->layout = 'layout_clerk';
		}

		switch($user['Rolemaster']['id']){
		    case 10:
		        $kaisyaconfition = "";
		        //$kaisyaconfition = ['User.kaisyas_id' => $this->Auth->user('kaisyas_id')];
		        break;
		    default:
		        $kaisyaconfition = ['User.kaisyas_id' => $this->Auth->user('kaisyas_id')];
		        break;
		}
		// 検索条件設定
		$this->Prg->commonProcess();
		// 検索条件取得
		// セッション
		$conditions=[];
		if ($this->request->data) {
		    $conditions = $this->User->parseCriteria($this->passedArgs);
			$this->Session->write('BackData1.conditions',$conditions);
		}else{
		    if($this->Session->check('BackData1.page')){
		        $backdata1 = $this->Session->read('BackData1');
		        if($backdata1['page']=='Users'){
		            $conditions = $backdata1['conditions'];
		        }else{
		            $this->Session->delete('BackData1');
		            $backdata1 = [
		                'page'=>$this->page,
		                'conditions'=>[
		                    'User.branchmasters_id'=>1,
		                    'User.departmentmasters_id'=>1,
		                    'User.zaisekiflg'=>0
		                ]
		            ];
		            $this->Session->write('BackData1',$backdata1);
		            $conditions = $backdata1['conditions'];
		        }
		    }else{
		        $backdata1 = [
		            'page'=>$this->page,
		            'conditions'=>[
		                'User.branchmasters_id'=>1,
		                'User.departmentmasters_id'=>1,
		                'User.zaisekiflg'=>0
		            ]
		        ];
		        $this->Session->write('BackData1',$backdata1);
		        $conditions = $backdata1['conditions'];
		    }
		}
		$this->set('conditions',$conditions);

		$this->paginate = [
			'fields' => [
				'User.id',
				'User.usernameK',
				'User.username',
				'Rolemaster.rolename',
				'Kaisya.companyname',
				'User.useremail',
				'User.zaisekiflg',
				'User.entrydate',
			    'User.enddate',
			    'User.syain_cd',
			    'Branchmaster.branchname',
				'User.roudouflg',
				'Branchmaster.id'
			],
			'conditions' => [
				'AND' => $conditions,
				// 'User.zaisekiflg' => 0,
//				'User.kaisyas_id' => $this->Auth->user('kaisyas_id')
                $kaisyaconfition
			],
			'limit' => 10,
			'order' => [
				'User.syain_cd' => 'asc'
			],
			'recursive' => 0,
		];
		$this->set('users', $this->paginate('User'));

		$branchmasters = $this->Branchmaster->SelectBranchList($this->kaisyas_id);
		$departmentmasters = $this->Departmentmaster->SelectDepartmentList();
		$this->set(compact('branchmasters','departmentmasters'));

	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$users_id = $this->User->getLastInsertID();
				$this->Employeepoint->create();
				if ($this->Employeepoint->save(['users_id'=>$users_id, 'pointzan'=>3000])) {
					$this->Flash->success(__('保存しました'));
					$this->logout();
					return $this->redirect(['controller' => 'users', 'action' => 'login']);
				} else {
					$this->Flash->error(__('入力が間違っています。再度入力してください'));
				}
			}
		}

		$rolemasters = $this->Rolemaster->find('list', [
			'fields' => ['Rolemaster.id','Rolemaster.rolename'],
		]);
		$kaisyas = $this->Kaisya->find('list', [
			'fields' => ['Kaisya.id','Kaisya.companyname'],
		]);
//		$branchmasters = $this->Branchmaster->SelectBranchList($this->kaisyas_id);
		$branchmasters = $this->Branchmaster->find('list',[
		    'fields'=>[
		        'id','branchname'
		    ],
		    'order'=>[
		        'id'=>'asc'
		    ]
		]);
		$departmentmasters = $this->Departmentmaster->SelectDepartmentList();
		$this->set(compact('rolemasters','kaisyas','branchmasters','departmentmasters'));

	}

	public function add2() {
		$this->layout = 'layout_host';
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$users_id = $this->User->getLastInsertID();
				$this->Employeepoint->create();
				if ($this->Employeepoint->save(['users_id'=>$users_id, 'pointzan'=>3000])) {
					$this->Flash->success(__('保存しました'));
					return $this->redirect(['controller'=>'users', 'action'=>'index']);
				} else {
					$this->Flash->error(__('入力が間違っています。再度入力してください'));
				}
				//return $this->redirect(['controller' => 'users', 'action' => 'index']);
			}
		}

		$rolemasters = $this->Rolemaster->find('list', [
			'fields' => ['Rolemaster.id','Rolemaster.rolename'],
		]);
		$kaisyas = $this->Kaisya->find('list', [
			'fields' => ['Kaisya.id','Kaisya.companyname'],
		]);
		$branchmasters = $this->Branchmaster->SelectBranchList($this->kaisyas_id);
		$departmentmasters = $this->Departmentmaster->SelectDepartmentList();
		$this->set(compact('rolemasters','kaisyas','branchmasters','departmentmasters'));

	}

	/*
	public function ajax_department() {
		$this->layout = false;
		//ajax送信でない場合はアクセスしないようにする
		if (!$this->request->is('ajax')) {
			//$this->redirect(['action'=>'index']);
		}

		$this->set('departmentmasters',
			$this->Departmentmaster->find('list',[
				//'conditions' => ['Departmentmaster.branchmasters_id' => $this->params['url']['data']['User']['branchmasters_id']],
				'conditions' => [
					'AND' => [
						['Departmentmaster.branchmasters_id' => $this->params['url']['data']['User']['branchmasters_id']],
						['Departmentmaster.kaisyas_id' => $this->Auth->user('kaisyas_id')]
					]
				],
				'fields' => [
					'Departmentmaster.id',
					'Departmentmaster.departmentname'
				]
			])
		);
	}

	public function ajax_branch() {
		$this->layout = false;
		//ajax送信でない場合はアクセスしないようにする
		if (!$this->request->is('ajax')) {
			//$this->redirect(['action'=>'index']);
		}

		$this->set('branchmasters',
			$this->Branchmaster->find('list',[
				'conditions' => ['Branchmaster.kaisyas_id' => $this->params['url']['data']['User']['kaisyas_id']],
				'fields' => [
					'Branchmaster.id',
					'Branchmaster.branchname'
				]
			])
		);
	}
	*/

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->layout = 'layout_host';

		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->User->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		// 権限及び所属営業所による制御
		switch($this->rolemasters_id){
			case 10: //管理者
			case 24: //事務職（全て）
				break;
			default:
				// データとログインユーザーの所属営業所が一致しないとき、不正なアクセスとみなしてindexへとリダイレクトする
				/* 一旦コメントアウト(2020-06-25)
				if ($this->branchmasters_id == $post['Branchmaster']['id']) {
					break;
				} else {
					return $this->redirect(['action' => 'index']);
				}
				*/
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->User->id = $id;
			if ($this->rolemasters_id == 10) {
				if ($this->request->data['User']['password_confirm']) {
					$this->request->data['User']['password'] = $this->request->data['User']['password_confirm'];
				}
			}
			if ($this->User->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(array('action' => 'index'));
			}
			//$this->Flash->error('失敗しました。');
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}

		$rolemasters = $this->Rolemaster->find('list', [
			'fields' => ['Rolemaster.id','Rolemaster.rolename'],
		]);
		$kaisyas = $this->Kaisya->find('list', [
			'fields' => ['Kaisya.id','Kaisya.companyname'],
		]);
		$branchmasters = $this->Branchmaster->SelectBranchList($this->kaisyas_id);
		$departmentmasters = $this->Departmentmaster->SelectDepartmentList();
		$this->set(compact('rolemasters','kaisyas','branchmasters','departmentmasters'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		if ($this->User->delete($id)) {
			$this->Flash->success(__('削除しました'));
		} else {
			$this->Flash->error(__('失敗しました'));
		}
		return $this->redirect(['action' => 'index']);
	}

	public function calendaruser($id = null){
		$this->layout = 'layout_host';

		if ($this->request->is('post')) {
			if ($this->request->data('selectym1')) {
				$tyy1 = $this->request->data['selectym1']['year'];
				$tmm1 = $this->request->data['selectym1']['month'];
			} else {
				$tyy1 = date('Y');
				$tmm1 = date('m');
			}
		} else {
			if ($this->Session->read('paramsDate')) {
				$sessiondate = new DateTime($this->Session->read('paramsDate'));
				$tyy1 = $sessiondate->format('Y');
				$tmm1 = $sessiondate->format('m');
				$this->Session->delete('paramsDate');
			} else {
				$tyy1 = date('Y');
				$tmm1 = date('m');
			}
		}
		$paramsDate = $tyy1.'-'.$tmm1.'-01';

		$array = [
			date("Y/m/d", mktime(0, 0, 0, $tmm1+0, 1, $tyy1)),
			date("Y/m/d", mktime(0, 0, 0, $tmm1+1, 0, $tyy1))
		];

		/*
		 * カレンダーマスタ読み込み
		 */
		$calendarusermasters = $this->Calendarusermaster->find('first', [
			'conditions' => [
				'Calendarusermaster.users_id' => $id,
				'Calendarusermaster.month >=' => $array[0],
				'Calendarusermaster.month <=' => $array[1],
			],
			'recursive' => -1,
		]);
		// debug($calendarusermasters);

		$tsta = $array[0];
		$tend = $array[1];

		while (strcmp(date('N', strtotime($tsta)), "7")) {
			$tsta = date("Y/m/d", strtotime($tsta." -1 day"));
		}
		while (strcmp(date('N', strtotime($tend)), "6")) {
			$tend = date("Y/m/d", strtotime($tend." +1 day"));
		}

		$calendar = array();
		$week = array();
		$header = array();
		$temp = $tsta;
		$weekja = [
			'日', //0
			'月', //1
			'火', //2
			'水', //3
			'木', //4
			'金', //5
			'土', //6
		];
		while (1 == 1) {
			if (count($week) == 7) {
				array_push($calendar, $week);
				if (count($header) != 7) {
					foreach ($week as $key=>$value) {
						array_push($header, $weekja[date("w", strtotime($value))]);
					}
				}
				$week = array();
			}
			array_push($week, $temp);
			if (strtotime($tend) == strtotime($temp)) {
				array_push($calendar, $week);
				break;
			}
			$temp = date("Y/m/d", strtotime($temp." +1 day"));
		};

		$url = Router::url(['controller' => 'Users', 'action' => 'calendaruseradd']);

		if ($calendarusermasters) {
			$baseday = $calendarusermasters['Calendarusermaster']['baseday'];

			foreach ($calendar as $key=>$value) {
				foreach ($value as $key2=>$value2) {
					$conditionDate = new DateTime($value2);
					$j = $conditionDate->format('d');

					$restflg = intval($calendarusermasters['Calendarusermaster']['day'.$j]);

					$selected0 = $selected1 = $selected2 = null;
					if ($restflg == 0) {
						$selected0 = ' selected';
					} elseif ($restflg == 1) {
						$selected1 = ' selected';
					} elseif ($restflg == 2) {
						$selected2 = ' selected';
					}

					$calendar[$key][$key2]=intval(date("m", strtotime($value2)))!=intval($tmm1)?"&nbsp;":date("d", strtotime($value2)).
					'<form action="'.$url.'" method="post" onchange="submit(this.form)">'.
					'<input type="hidden" name="id" value="'.$calendarusermasters['Calendarusermaster']['id'].'">'.
					'<input type="hidden" name="users_id" value="'.$id.'">'.
					'<input type="hidden" name="restdate" value="'.$conditionDate->format('Y-m-d').'">'.
					'<select name="day'.$j.'" data-rel="chosen"><option value=0'.$selected0.'>通常</option><option value=1'.$selected1.'>法定外休日</option><option value=2'.$selected2.'>法定休日</option></select>'.
					'</form>';
				}
			}
			$flg = true;
		} else {
			$this->Flash->error(__('個人のカレンダーがまだ作成されていません。'));

			$baseday = null;
			foreach ($calendar as $key=>$value) {
				foreach ($value as $key2=>$value2) {
					$calendar[$key][$key2]=intval(date("m", strtotime($value2)))!=intval($tmm1)?"&nbsp;":date("d", strtotime($value2));
				}
			}
			$flg = false;
		}

		$this->set(compact('calendar','header','paramsDate','baseday','flg'));
	}

	public function calendaruseradd($id = null){
		if ($this->request->is('post')) {
			$date = new DateTime($this->request->data('restdate'));
			$i = $date->format('d');
			$users_id = $this->request->data('users_id');
			$this->request->data['Calendarusermaster']['id'] = $this->request->data('id');
			$this->request->data['Calendarusermaster']['day'.$i] = $this->request->data('day'.$i);

			$calendarusermaster = $this->Calendarusermaster->find('first', [
				'fields' => [
					'Calendarusermaster.day'.$i,
					'Calendarusermaster.baseday',
				],
				'conditions' => [
					'Calendarusermaster.id' => $this->request->data('id')
				],
				'recursive' => -1,
			]);

			$restflg_before = $calendarusermaster['Calendarusermaster']['day'.$i];
			$restflg_after = $this->request->data('Calendarusermaster.day'.$i);
			$baseday = $calendarusermaster['Calendarusermaster']['baseday'];

			if ($restflg_before != $restflg_after) {
				switch ($restflg_before) {
					case 0:
						if ($restflg_after == 1 || $restflg_after == 2) {
							$baseday = intval($baseday - 1);
						}
						break;
					case 1:
					case 2:
						if ($restflg_after == 0) {
							$baseday = intval($baseday + 1);
						}
						break;
				}
			}
			$this->request->data['Calendarusermaster']['baseday'] = $baseday;

			if ($this->Calendarusermaster->save($this->request->data['Calendarusermaster'])) {
				$this->Session->write('paramsDate',$this->request->data('restdate'));
				$this->Flash->success('変更を保存しました。');
			} else {
				$this->Flash->error('変更の保存に失敗しました。');
			}
			return $this->redirect(['controller' => 'Users', 'action' => 'calendaruser', $users_id]);
		}
	}

	public function password_confirm() {
		$user = $this->Auth->user();
		if($user['Rolemaster']['id'] >= 10 && $user['Rolemaster']['id'] <= 19) {
			$this->layout = 'layout_host';
		}
		if($user['Rolemaster']['id'] >= 20 && $user['Rolemaster']['id'] <= 28) {
			$this->layout = 'layout_clerk';
		}
		if($user['Rolemaster']['id'] == 29) {
			$this->layout = 'layout_partner';
		}
		if ($user['Rolemaster']['id'] >= 30 && $user['Rolemaster']['id'] <= 39) {
			$this->layout = 'layout_drive';
		}

		$id = $user['id'];
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->User->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->User->id = $id;
			if ($this->request->data['User']['password_confirm']) {
				$this->request->data['User']['password'] = $this->request->data['User']['password_confirm'];
			}
			if ($this->User->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(['action' => 'password_confirm']);
			}
			//$this->Flash->error('失敗しました。');
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}
}
