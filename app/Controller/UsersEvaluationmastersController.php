<?php
App::uses('AppController', 'Controller');
class UsersEvaluationmastersController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('User','UsersEvaluationmaster','Evaluationmaster');

    public function beforeFilter(){
        parent::beforeFilter();
        $this->page = 'UsersEvaluationmaster';
    }

    public function isAuthorized($user){
        if (isset($user['rolemasters_id'])) {
            switch($user['rolemasters_id']){
                case 10: //管理者
                case 11;
                case 12;
                case 24; //事務職（全て）
                $this->set('user', $user);
                return true;
                break;
            }
        }
        return false;
        break;
    }

    /**
 * index method
 *
 * @return void
 */
	public function index($id = null) {
	    if($id){
	        $selectuser = $this->User->findById($id);
	        $this->set('selectuser',$selectuser);
	    }
	    $usersevaluationmasters = $this->UsersEvaluationmaster->find('all',[
	        'conditions'=>[
	            'UsersEvaluationmaster.users_id'=>$id,
	        ],
	        'order' =>[
	            'UsersEvaluationmaster.startdate'=>'desc'
	        ]
	    ]);

	    $this->set('usersevaluationmasters', $usersevaluationmasters);
	    $this->set('selectuserid',$id);
	}

	public function add($id = null) {
	    if($id){
	        $selectuser = $this->User->findById($id);
	        $this->set('selectuser',$selectuser);
	    }
	    if ($this->request->is('post')) {
			$this->UsersEvaluationmaster->create();
			$this->request->data['UsersEvaluationmaster']['users_id'] = $id;
			if ($this->UsersEvaluationmaster->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index',$id));
			} else {

			}
		}
		$evaluationmasters = $this->Evaluationmaster->find('list',[
		    'fields'=>[
		        'id','evarank'
		    ],
		    'order'=>[
		        'evarank'=>'asc'
		    ]
		]);
		$this->set(compact('evaluationmasters'));
	}

	public function edit($id = null) {
	    if($this->params['pass'][1]){
	        $selectuser = $this->User->findById($this->params['pass'][1]);
	        $this->set('selectuser',$selectuser);
	    }
	    if ($id == null) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->UsersEvaluationmaster->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
		    $this->UsersEvaluationmaster->id = $id;
		    if ($this->UsersEvaluationmaster->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(array('action' => 'index',$this->params['pass'][1]));
			}
			//$this->Flash->error('失敗しました。');
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
		$evaluationmasters = $this->Evaluationmaster->find('list',[
		    'fields'=>[
		        'id','evarank'
		    ],
		    'order'=>[
		        'evarank'=>'asc'
		    ]
		]);
		$this->set(compact('evaluationmasters'));
	}


}
