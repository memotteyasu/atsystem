<?php
App::uses('AppController', 'Controller');
/**
 * ReceTops Controller
 *
 */
class ReceTopsController extends AppController {
	public $layout = 'layout_reception';
    public $helpers = array('Html','Form');
    public $uses = array('Receptionmessage');

    public function beforeFilter(){
        parent::beforeFilter();
    }

    public function isAuthorized($user){
    	if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '90'){
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
        $date = new DateTime();

        $receptionmessages = $this->Receptionmessage->find('first',[
            'fields'=>['id','receptiondate',
                'receptioncomment','personalname',
                'receptioncomment2','personalname2',
                'receptioncomment3','personalname3'],
            'conditions'=>[
                'AND'=>[
                    'Receptionmessage.delflg'=>0,
                    'Receptionmessage.kaisyas_id'=>$this->Auth->user('kaisyas_id'),
                    'Receptionmessage.receptiondate >='=>$date->format('Y-m-d 00:00:00'),
                    'Receptionmessage.receptiondate <='=>$date->format('Y-m-d 23:59:59'),
                ]
            ],
            'order'=>[
                'Receptionmessage.receptiondate'=>'asc'
            ]
        ]);

        $this->set('receptionmessages',$receptionmessages);

        $this->set('date',$date);
    }

    public function index2() {
        $date = new DateTime();

        $receptionmessages = $this->Receptionmessage->find('all',[
            'fields'=>['id','receptioncomment','receptiondate'],
            'conditions'=>[
                'AND'=>[
                    'Receptionmessage.delflg'=>0,
                    'Receptionmessage.kaisyas_id'=>$this->Auth->user('kaisyas_id'),
                    'Receptionmessage.receptiondate >='=>$date->format('Y-m-d 00:00:00'),
                    'Receptionmessage.receptiondate <='=>$date->format('Y-m-d 23:59:59'),
                ]
            ],
            'order'=>[
                'Receptionmessage.receptiondate'=>'asc'
            ]
        ]);

        $this->set('receptionmessages',$receptionmessages);
    }


}
