<?php
App::uses('AppController', 'Controller');

class Dr060sController extends AppController {
	public $layout = 'layout_drive';
	public $helpers = ['Html','Form','Format'];
	public $uses = ['Workplace'];
	public $components = ['SelectData'];

	public function beforeFilter(){
		parent::beforeFilter();
	}

	public function isAuthorized($user){
		if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '30' && $user['rolemasters_id'] <= '39'){
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
	public function index($id = null) {
        $workplaces = $this->Workplace->find('all',[
            'fields'=>[
                'workplaceshortname',
                'placetel',
                'placeadd',
                'googlemapurl',
                'mapcomment'
            ],
            'conditions'=>[
                'Workplace.id'=>$id
            ],
            'contain'=>[
                'Workplaceimage'
            ]
        ]);
        $this->set('workplaces',$workplaces);
	}

}
