<?php
class AjaxsController extends AppController {
	public $name = 'Ajaxs';
	public $uses = array( 'Reservetran');
	public $helpers = array( 'Js');
	public $components = array( 'RequestHandler');

	public function beforeFilter(){
		parent::beforeFilter();
		//$this->layout = 'user';
		// 認証コンポーネントをViewで利用可能にしておく
		//$this->set('auth',$this->Auth);
	}

	//	public $uses = null;

	public function isAuthorized($user) {
		if (isset($user['Kaisyas']['rolemasters_id']) && $user['Kaisyas']['rolemasters_id'] === '1') {
			$this->set('user',$user);
			//adminは許可
			return true;
		}
		// デフォルトは拒否
		return false;
	}


}
?>
