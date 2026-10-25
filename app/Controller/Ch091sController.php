<?php
App::uses('AppController', 'Controller');
/**
 * Ch091 Controller
 *
 */
class Ch091sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = ['Html','Form'];
	public $uses = ['User','Kaisya','Ordertran','Branchmaster','Branchgroup'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 2;
		$this->page = 'Ch091s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 21; //事務職（化学品）
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
		// $page = 'Ch091s';
		$did = $this->did;
		$page = $this->page;

		$date = new DateTime();

	    $branchmasters_id = $this->Auth->user('branchmasters_id');
		$branchgroups = $this->Branchgroup->BranchgroupList();
		$this->set('branchgroups',$branchgroups);
        if(!isset($branchgroups_id)){
            $branchgroups_id = $this->branchgroups_id;
        }

		if ($this->request->is('post')) {
			if (isset($this->request->data['selectym1'])) {
			    $date = new DateTime($this->request->data('selectym1'));
    			$branchgroups_id = $this->request->data('branchgroups_id');
			    $backdatas = [
			        'date' => [
			            'year' => $date->format('Y'),
			            'month' => $date->format('m'),
			            'day' => $date->format('d')
			        ],
			        'bgid' => $branchgroups_id,
			        'Page' => $page
			    ];
			    $this->Session->write('BackData1', $backdatas);
			}
		} elseif ($this->Session->check('BackData2')) {
		    if ($this->Session->read('BackData2.Page') == $page) {
		        $date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
		        $branchgroups_id = $this->Session->read('BackData2.bgid');
		    }
		    $this->Session->delete('BackData2');
		} else {
		    if($this->Session->read('BackData1.date')){
		        $date = $this->SelectData->date_select($this->Session->read('BackData1.date'));
		        $branchgroups_id = $this->Session->read('BackData1.bgid');
		    }else{
                if(!isset($branchgroups_id)){
                    $branchgroups_id = $this->branchgroups_id;
                }
            }
		    $this->Session->delete('BackData1');
		}

		$paramsDate = $date->format('Y-m-d');
		$week = ['日','月','火','水','木','金','土'];
		$this->set(compact('paramsDate','week','branchmasters_id'));
		$ordertrans = $this->SelectData->data_select_conditions091s($paramsDate,$did,$branchgroups_id);

		/* ドライバリスト */
		$userlist = $this->User->SelectUserDriversList($did);
		$this->set('branchgroups_id',$branchgroups_id);

		$this->set(compact('ordertrans','userlist'));
	}

	public function sendmail($id,$tyy,$tmm,$tdd) {
		$paramsDate = new DateTime($tyy.'-'.$tmm.'-'.$tdd);
		$week = ['日', '月', '火', '水', '木', '金', '土'];

		$user = $this->User->find('first', [
			'fields' => ['User.usernameK', 'User.useremail'],
			'conditions' => [
				'User.id' => $id
			],
			'recursive' => 0
		]);

		$emailadd = $user['User']['useremail'];

		$email = new CakeEmail('xserver');
		$email->from(['support@asahitrans.com' => '朝日運輸サポート']);
		$email->bcc($emailadd);
		$var1 = $paramsDate->format('Y年m月d日').'('.$week[$paramsDate->format('w')].')';
		$email->subject('【朝日運輸】配車の予約が確定しました。('.$var1.')');
		$email->emailFormat('text');
		$email->viewVars(compact('var1'));
		$email->template('HostOrdertrans2');
		$email->send();

		//debug($this->request);
		$this->Flash->success('メールを送信しました。');
		return $this->redirect(['controller' => 'Ch091s', 'action' => 'index']);
	}

	public function confirm() {
		if (isset($this->params['named']['bid'])) {
			$branchconditions = ['Ordertran.branchmasters_id' => $this->params['named']['bid']];
			$bc = ['branchmasters_id' => $this->params['named']['bid']];
		} else {
			$branchconditions = [];
			$bc = [];
		}
		// $did = 2;
		$did = $this->did;
		$paramsDate = new DateTime($this->params['named']['tyy'].'-'.$this->params['named']['tmm'].'-'.$this->params['named']['tdd']);
		$week = ['日', '月', '火', '水', '木', '金', '土'];

		if($this->request->is('post')){
			$ordertrans = $this->Ordertran->SelectMailOrdertran($paramsDate,$did,$branchconditions);
			$cclist = null;
			if ($ordertrans) {
				$this->Ordertran->updateAll([
					'confirmflg' => 1,
					'confirmdate' => "'".date('Y-m-d H:i:s')."'"
				], [
					'deliverydate' => $paramsDate->format('Y-m-d'),
					'departmentmasters_id' => 2,
					$bc,
					'driver1id !=' => null,
					// 'driver2id !=' => null,
					'delflg !=' => 1,
				]);

				foreach ($ordertrans as $cc) {
					if ($cc['Driver1']['useremail']) {
						$cclist[] = $cc['Driver1']['useremail'];
					}
					if ($cc['Driver2']['useremail']) {
						$cclist[] = $cc['Driver2']['useremail'];
					}
				}
				if (isset($cclist)) {
					// アドレスが重複している場合、統合する
					$cclist = array_unique($cclist);

					$text1 = $this->request->data['Mail']['message'];
					$email = new CakeEmail('xserver');
					$email->from(['support@asahitrans.com' => '朝日運輸サポート']);
					$email->bcc($cclist);
					$var1 = $paramsDate->format('Y年m月d日').'('.$week[$paramsDate->format('w')].')';
					$email->subject('【朝日運輸】配車の予約が確定しました。('.$var1.')');
					$email->emailFormat('text');
					$email->viewVars(compact('var1','text1'));
					$email->template('HostOrdertrans');
					$email->send();
					$this->Flash->success('メールを送信しました。');
				} else {
					$this->Flash->error('運転手・助手にメールアドレスが存在していません。');
				}
				return $this->redirect(['controller' => 'Ch090s', 'action' => 'index']);

			} else {
				$this->Flash->error('運転手・助手を登録して下さい。');
			}
			return $this->redirect(['controller' => 'Ch090s', 'action' => 'index']);
		}
	}
}
