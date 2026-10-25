<?php
App::uses('AppController', 'Controller');
/**
 * DriveTops Controller
 *
 * @property DriveTop $DriveTops
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class DriveTopsController extends AppController {
	public $layout = 'layout_drive';
    public $helpers = array('Html','Form');

    public function beforeFilter(){
    	parent::beforeFilter();
    	$this->set('auth',$this->Auth);
    }
    
    public function isAuthorized($user){
    	if(isset($user['rolemasters_id']) && $user['rolemasters_id'] === '5'){
    		$this->set('user',$user);
    		return true;
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
		$user = $this->Auth->user();
	}


}
