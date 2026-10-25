<?php
 class PdfsController extends AppController {
  public $components = array('RequestHandler');
  public $helpers = array('Html','Form');
  public $uses = ['User','Kintaitran','Calendarusermaster'];

  public function beforeFilter(){
  	parent::beforeFilter();
  	$this->set('auth',$this->Auth);
  }


  public function isAuthorized($user){
  	if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '19'){
  		$this->set('user',$user);
  		return true;
  	}
  	return false;
  	break;
  }

  public function pdfTest() {
   // Content-Type
   $this->RequestHandler->respondAs('application/pdf');

   // レイアウトを使用しない
   $this->layout = '';
  }

  public function pdfTest2() {
  	// Content-Type
  	$this->RequestHandler->respondAs('application/pdf');

  	// レイアウトを使用しない
  	$this->layout = '';
  }

  public function pdfTest3() {
  	// Content-Type
  	$this->RequestHandler->respondAs('application/pdf');

  	// レイアウトを使用しない
  	$this->layout = '';
  }

	public function pdfTest4() {
		// Content-Type
		$this->RequestHandler->respondAs('application/pdf');

		// レイアウトを使用しない
		$this->layout = '';

		$test = '2020-04-01';

		$date = new DateTime($test);
		$firstday = $date->modify('first day of this months');
		$date2 = new DateTime($test);
		$lastday = $date2->modify('last day of this months');
		$this->set(compact('firstday','lastday'));

		$this->User->Behaviors->load('EagerLoader.EagerLoader');

		$users = $this->User->find('all', [
			'order' => [
				'User.id' => 'asc',
			],
			'fields' => [
				'User.id',
				'User.usernameK',
			],
			'conditions' => [
				'User.zaisekiflg' => 0,
			],
			'contain' => [
				'Kintaitran' => [
					'conditions' => [
						'Kintaitran.kintaidate >= ' => $firstday->format('Y-m-d'),
						'Kintaitran.kintaidate <= ' => $lastday->format('Y-m-d'),
					],
					'Flgmaster' => [
						'fields' => [
							'id',
							'flgname'
						]
					]
				],
				'Calendarusermaster' => [
					'conditions' => [
						'Calendarusermaster.month >=' => $firstday->format('Y-m-d'),
						'Calendarusermaster.month <=' => $lastday->format('Y-m-d'),
					]
				]
			],
			// 'recursive' => 2
		]);
		// debug($users);

		$kintaitrans = $this->Kintaitran->find('all', [
			'conditions' => [
				'Kintaitran.delflg' => 0,
			]
		]);

		$this->set(compact('users','kintaitrans'));
	}

  public function pdfTest5() {
  	// Content-Type
  	$this->RequestHandler->respondAs('application/pdf');

  	// レイアウトを使用しない
  	$this->layout = '';
  }

  public function pdfTest6() {
	  // Content-Type
	  $this->RequestHandler->respondAs('application/pdf');

	  // レイアウトを使用しない
	  $this->layout = '';
  }
  public function pdfTest7() {
	  // Content-Type
	  $this->RequestHandler->respondAs('application/pdf');

	  // レイアウトを使用しない
	  $this->layout = '';
  }
  public function pdfTest8() {
	// Content-Type
	$this->RequestHandler->respondAs('application/pdf');

	// レイアウトを使用しない
	$this->layout = '';
}
 }
