<?php
App::uses('Ch201sController', 'Controller');

class Ga201sController extends Ch201sController {

	public function beforeFilter(){
		parent::beforeFilter();
		$this->did = 7;
		$this->page = 'Ga201s';
		$this->viewPath = 'Ch201s';
	}

	public function isAuthorized($user){
		if (isset($user['rolemasters_id'])) {
			switch($user['rolemasters_id']){
				case 10:
				case 12;
				case 22;
				case 24;
					$this->set('user', $user);
					return true;
					break;
			}
		}
		return false;
	}
}
