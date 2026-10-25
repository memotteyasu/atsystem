<?php
App::uses('AppController', 'Controller');
/**
 * HostKaisyas Controller
 *
 */
class HostKaisyasController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Kaisya','Departmentmaster','Branchmaster');
    public $components = array('Search.Prg');
    public $presetVars = array();

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
/**
 * index method
 *
 * @return void
 */
    public function index() {
        // 検索条件設定
        $this->Prg->commonProcess();
        // 検索条件取得
        $conditions = $this->Kaisya->parseCriteria($this->passedArgs);

        $this->paginate = [
            'conditions' => ['AND' => $conditions, ['Kaisya.delflg' => 0]],
            'limit' => 10,
            'order' => [
                'Kaisya.id' => 'asc'
            ],
            'recursive' => 0,
        ];
        $this->set('kaisyas', $this->paginate('Kaisya'));
    }


	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Kaisya->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Kaisya->id = $id;
			if ($this->Kaisya->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(array('action' => 'edit',$id));
			}
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}

		$departmentmasters = $this->Departmentmaster->find('list',[
		    'fields' => ['Departmentmaster.id','Departmentmaster.departmentname']
		]);

		$this->set(compact('departmentmasters'));
	}
	public function add() {
	    if ($this->request->is(array('post', 'put'))) {
	        if ($this->Kaisya->save($this->request->data)) {
	            $this->Flash->success('保存しました。');
	            return $this->redirect(array('action' => 'index'));
	        }
	    }

	    $departmentmasters = $this->Departmentmaster->find('list',[
	        'fields' => ['Departmentmaster.id','Departmentmaster.departmentname']
	    ]);

	    $this->set(compact('departmentmasters'));
	}

}
