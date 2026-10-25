<?php
App::uses('AppController', 'Controller');
/**
 * Flgmasters Controller
 *
 * @property Flgmaster $Flgmaster
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
 */
class FlgmastersController extends AppController {

/**
 * Components
 *
 * @var array
 */
    public $uses = ['Flgmaster'];
    public $layout = 'layout_host';
    public $helpers = array('Html','Form');

    public function beforeFilter(){
        parent::beforeFilter();
        //$this->set('auth',$this->Auth);
    }

    public function isAuthorized($user){
        if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '19'){
            $this->set('user',$user);
            return true;
        }
        return false;
        break;
    }

	public function index() {
	    $flgmasters = $this->Flgmaster->find('all',array(
	        'order'=>array('id')
	    ));
        $this->set('flgmasters',$flgmasters);
	}

}
