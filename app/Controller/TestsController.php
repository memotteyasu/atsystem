<?php
App::uses('AppController', 'Controller');
/**
 * Test
 *
 * @property Gasusetran $Gasusetran
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
 */
class TestsController extends AppController {

/**
 * Components
 *
 * @var array
 */
    public $layout = 'layout_host';
    public $helpers = array('Html','Form','Session','Js' => ['Jquery']);
    public $uses = array('Branchgroup','Branchmaster','User','Kaisya');
    public $components = array('Timediff','Search.Prg','RequestHandler');

    public function beforeFilter(){
        parent::beforeFilter();

    }

    public function isAuthorized($user){
        if (isset($user['rolemasters_id'])) {
            switch($user['rolemasters_id']){
                case 10: //管理者
                $this->set('user', $user);
                return true;
                break;
            }
        }
        return false;
    }


/**
 * index method
 *
 * @return void
 */

	public function index() {
		//配列で渡す
		$branchmasters = $this->Branchmaster->find('list',[
			'fields'=>[
				'id','branchname'
			],
			'conditions'=>[
				'branchgroups_id'=>$this->branchgroups_id
			]
		]);
		$branchids = array_keys($branchmasters);
		$driverlist = $this->User->SelectUserDriversList2($branchids);
		debug($driverlist);
		exit;
	}
}
