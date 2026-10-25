<?php
App::uses('AppController', 'Controller');
/**
 * Ga091 Controller
 *
 */
class Ga091sController extends AppController {
	public $layout = 'layout_host';
	public $helpers = ['Html','Form'];
	public $uses = ['User','Kaisya','Ordertran','Branchmaster'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 7;
		$this->page = 'Ga091s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10: //管理者
				case 12;
				case 22; //事務職（化学品）
				case 24; //事務職（全て）
				case 27; //事務職（石油・ガス）
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
		// $did = 3;
		// $page = 'Ga091s';
		$did = $this->did;
		$page = $this->page;

		$date = new DateTime();

		$branchmasters_id = $this->branchmasters_id;
		$branchmasters = $this->SelectData->branch_select_conditions($this->rolemasters_id,$branchmasters_id,$this->kaisyas_id);
		$this->set(compact('branchmasters'));

		if ($this->request->is('post')) {
			$date = $this->SelectData->date_select($this->data['selectym1']);
			$branchmasters_id = $this->data['branchmasters_id'];
			$backdatas = ['date' => ['year' => $date->format('Y'), 'month' => $date->format('m'), 'day' => $date->format('d')], 'bid' => $branchmasters_id, 'Page' => $page];
			$this->Session->write('BackData1', $backdatas);
		} elseif ($this->Session->check('BackData2')) {
			if ($this->Session->read('BackData2.Page') == $page) {
				$date = $this->SelectData->date_select($this->Session->read('BackData2.date'));
				$branchmasters_id = $this->Session->read('BackData2.bid');
			}
			$this->Session->delete('BackData2');
		} else {
			$this->Session->delete('BackData1');
		}

		$paramsDate = $date->format('Y-m-d');
		$week = ['日','月','火','水','木','金','土'];
		$this->set(compact('paramsDate','week','branchmasters_id'));

// オーダーの開始と終了の期間でセレクトするようにする
		$ordertrans = $this->SelectData->data_select_conditions091s($paramsDate,$did,$branchmasters_id);

		/* ドライバリスト */
		$userlist = $this->User->SelectUserDriversList($did);

		$this->set(compact('ordertrans','userlist'));
	}

	public function sendmail($id,$tyy,$tmm,$tdd,$oid) {
		$backdata2 = $this->Session->read('BackData1');
		$this->Session->write('BackData2', $backdata2);

		// $did = 3;
		$did = $this->did;
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

		$post = $this->Ordertran->find('first', [
			'fields' => [
				'Ordertran.id',
				'Ordertran.driver1id',
				'Ordertran.driver2id',
				'Ordertran.dr1mailflg',
				'Ordertran.dr2mailflg'
			],
			'conditions' => [
				'Ordertran.id' => $oid,
			],
			'recursive' => -1,
		]);

		$this->Ordertran->id = $oid;
		if ($post['Ordertran']['driver1id'] == $id) {
			$this->request->data['Ordertran']['dr1mailflg'] = 1;
		} elseif ($post['Ordertran']['driver2id'] == $id) {
			$this->request->data['Ordertran']['dr2mailflg'] = 1;
		}
		$this->Ordertran->save($this->request->data);

		$email = new CakeEmail('wadax');
		$email->from(['support@asahitrans.com' => '朝日運輸サポート']);
		$email->bcc($emailadd);
		$var1 = $paramsDate->format('Y年m月d日').'('.$week[$paramsDate->format('w')].')';
		$email->subject('【朝日運輸】配車の予約が確定しました。('.$var1.')');
		$email->emailFormat('text');
		$email->viewVars(compact('var1'));
		$email->template('HostOrdertrans2');
		$email->send();

		$this->Flash->success('メールを送信しました。');
		return $this->redirect(['controller' => 'Co091s', 'action' => 'index']);
	}

	public function confirm() {
		if ($this->params['named']['bid'] != 0) {
			$branchconditions = ['Ordertran.branchmasters_id' => $this->params['named']['bid']];
			$bc = ['branchmasters_id' => $this->params['named']['bid']];
		} else {
			$branchconditions = [];
			$bc = [];
		}
		// $did = 3;
		$did = $this->did;
		$paramsDate = new DateTime($this->params['named']['tyy'].'-'.$this->params['named']['tmm'].'-'.$this->params['named']['tdd']);
		$week = ['日', '月', '火', '水', '木', '金', '土'];

		if($this->request->is('post')){
			$ordertrans = $this->Ordertran->SelectMailOrdertran($paramsDate,$did,$branchconditions);
			$cclist = null;
			if ($ordertrans) {
				foreach ($ordertrans as $cc) {
					if ($cc['Driver1']['useremail']) {
						$cclist[] = $cc['Driver1']['useremail'];
					}
					if ($cc['Driver2']['useremail']) {
						$cclist[] = $cc['Driver2']['useremail'];
					}
				}

				if (isset($cclist)) {
					//メールアドレスが登録されていたときのみ確定処理を行う
					$this->Ordertran->updateAll([
						'confirmflg' => 1,
						'confirmdate' => "'".date('Y-m-d H:i:s')."'"
					], [
						'deliverydate' => $paramsDate->format('Y-m-d'),
						'departmentmasters_id' => $did,
						$bc,
						'driver1id !=' => null,
						'confirmflg' => 0,
						//'driver2id !=' => null,
						'delflg !=' => 1,
					]);

					// アドレスが重複している場合、統合する
					$cclist = array_unique($cclist);

					$text1 = $this->request->data['Mail']['message'];
					$email = new CakeEmail('wadax');
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
					$this->Flash->error('乗務員にメールアドレスが存在していません。');
				}
				// return $this->redirect(['controller' => 'Co090s', 'action' => 'index']);
				return $this->redirect(['controller' => 'Ga030s', 'action' => 'index']);
			} else {
				$this->Flash->error('乗務員を登録して下さい。');
			}
			// return $this->redirect(['controller' => 'Co090s', 'action' => 'index']);
			return $this->redirect(['controller' => 'Ga030s', 'action' => 'index']);
		}
	}
}
