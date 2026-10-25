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
	public function index() {
		$date = new DateTime();

		if ($this->request->is('post')) {
			list($date,$date2) = $this->SelectData->date_select_month($this->request->data('selectym1'));
		}

		$select_month = $date->format('Y-m');

		$firstday = new DateTime('first day of ' . $select_month);
		$lastday = new DateTime('last day of ' . $select_month);

		$paramsDate = $date->format('Y-m-d');
		$this->set(compact('paramsDate'));

		$kintaitrans = $this->Kintaitran->find('all', [
			'fields' => [
				// 'Kintaitran.kintaidate'
			],
			'conditions' => [
				'Kintaitran.users_id' => $this->Auth->user('id'),
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
