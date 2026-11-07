<?php
App::uses('AppController', 'Controller');

class Dr050sController extends AppController {
    public $layout = 'layout_drive';
	public $helpers = ['Html','Form','Format'];
	public $uses = ['User', 'Kintaitran'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
	}

	public function isAuthorized($user){
		if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '39'){
			$this->set('user',$user);
			return true;
		}
		return false;
		//break;
	}
/**
 * index method
 *
 * @return void
 */
	public function index($selectym1 = null, $users_id = null) {
		$date = new DateTime();

		if ($this->request->is('post')) {
			list($date,$date2) = $this->SelectData->date_select_month($this->request->data('selectym1'));
		} elseif ($selectym1) {
			try {
				$date = new DateTime($selectym1);
			} catch (Exception $e) {
				throw new NotFoundException(__('Invalid month'));
			}
		}

		// Host050sからは、対象社員IDを指定して個人別の勤怠を表示する。
		// 運転手は従来どおり自分自身の勤怠だけを参照できる。
		$target_users_id = $this->Auth->user('id');
		$targetUserName = null;
		if ($users_id !== null) {
			if ($this->Auth->user('rolemasters_id') >= 30 || !ctype_digit((string)$users_id)) {
				throw new NotFoundException(__('Invalid user'));
			}

			$targetUser = $this->User->find('first', [
				'fields' => ['User.id', 'User.usernameK'],
				'conditions' => [
					'User.id' => $users_id,
					'User.kaisyas_id' => $this->kaisyas_id,
				],
				'recursive' => -1,
			]);
			if (!$targetUser) {
				throw new NotFoundException(__('Invalid user'));
			}
			$target_users_id = $targetUser['User']['id'];
			$targetUserName = $targetUser['User']['usernameK'];
		}

		$select_month = $date->format('Y-m');

		$firstday = new DateTime('first day of ' . $select_month);
		$lastday = new DateTime('last day of ' . $select_month);

		$paramsDate = $date->format('Y-m-d');
		$this->set(compact('paramsDate', 'targetUserName'));

		$kintaitrans = $this->Kintaitran->find('all', [
			'fields' => [
				// 'Kintaitran.kintaidate'
			],
			'conditions' => [
				'Kintaitran.users_id' => $target_users_id,
				'Kintaitran.kintaidate >=' => $firstday->format('Y-m-d'),
				'Kintaitran.kintaidate <=' => $lastday->format('Y-m-d'),
			],
			'order' => [
				'Kintaitran.kintaidate'=>'asc'
			],
			'recursive' => 0
		]);

		$this->set(compact('kintaitrans'));
	}
	public function detail($selectym1){
	    $selectdate = new DateTime($selectym1);
	    $tyy1 = $selectdate->format('Y');
	    $tmm1 = $selectdate->format('m');
	    $date = new DateTime($tyy1.'-'.$tmm1);
	    $date->modify('first day of this months');
	    $date2 = new DateTime($tyy1.'-'.$tmm1);
	    $date2->modify('last day of this months');

	    $select_month = $date->format('Y-m');

//	    $firstday = new DateTime('first day of ' . $select_month);
//	    $lastday = new DateTime('last day of ' . $select_month);

//	    $paramsDate = $date->format('Y-m-d');
        $paramsDate = $selectym1;
	    $this->set(compact('paramsDate'));

	    $kintaitrans = $this->Kintaitran->find('all', [
	        'fields' => [
	            // 'Kintaitran.kintaidate'
	        ],
	        'conditions' => [
	            'Kintaitran.users_id' => $this->Auth->user('id'),
	            'Kintaitran.kintaidate >=' => $date->format('Y-m-d'),
	            'Kintaitran.kintaidate <=' => $date2->format('Y-m-d'),
	        ],
	        'order' => [
	            'Kintaitran.kintaidate'=>'asc'
	        ],
	        'recursive' => 0
	    ]);

	    $this->set(compact('kintaitrans'));

	}

}
