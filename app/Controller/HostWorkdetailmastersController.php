<?php
App::uses('AppController', 'Controller');
/**
 * HostTops Controller
 *
 * @property HostTop $HostTops
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class HostWorkdetailmastersController extends AppController {
	public $layout = 'layout_host';
	public $helpers = array('Html','Form');
	public $uses = array('Workdetailmaster','Departmentmaster');
	public $components = array('Search.Prg');
	public $presetVars = array();

    public function beforeFilter(){
        parent::beforeFilter();
        //$this->set('auth',$this->Auth);
        // 検索対象のフィールド設定代入
        $this->presetVars = $this->Workdetailmaster->presetVars;
        //Configure::write('logged_user_id', $this->Auth->user('id'));
    }


    public function isAuthorized($user){
        if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '29'){
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
	    $conditions = $this->Workdetailmaster->parseCriteria($this->passedArgs);
	    $this->paginate = [
	    	'fields' => [
	    		'id',
	    		'workname',
	    		'displayno'
	    	],
	        'conditions' => [
	            'AND' => $conditions,
	            [
	                'Workdetailmaster.delflg' => 0,
                    'Workdetailmaster.kaisyas_id'=>$this->kaisyas_id
	            ]
	        ],
	        'limit' => 10,
	        'order' => [
	            'Workdetailmaster.displayno' => 'asc'
	        ],
	    	'recursive' => 0
	    ];
	    $this->set('workdetailmasters', $this->paginate('Workdetailmaster'));
	    $departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
	    $this->set(compact('departmentmasters'));

/*
	    $workdetailmasters = $this->Workdetailmaster->find('all', [
	        'fields' => ['id','workname'],
	        'conditions' => [
	            'Workdetailmaster.delflg'=>0
	        ],
	    ]
	        );
	    $this->set(compact('workdetailmasters'));
*/
	}

	public function add() {
	    $this->edit();
	    $this->render('edit');
/*
	    if ($this->request->is('post')) {
			$this->Workdetailmaster->create();
			if ($this->Workdetailmaster->save($this->request->data)) {
				$this->Flash->success('保存しました');
				return $this->redirect(array('action' => 'index'));
			} else {
				//$this->Flash->error('失敗しました');
			}
		}
*/
	}

	public function edit($id = null) {
	    if($id){
            $post = $this->Workdetailmaster->findById($id);
	    }else{
	        $post = null;
	    }
		if ($this->request->is(['patch', 'post', 'put'])) {
			$this->Workdetailmaster->id = $id;
			$this->request->data['Workdetailmaster']['kaisyas_id'] = $this->kaisyas_id;
			if ($this->Workdetailmaster->save($this->request->data)) {
				$this->Flash->success('保存しました。');
				return $this->redirect(array('action' => 'index'));
			}
			//$this->Flash->error('失敗しました。');
		}else{
		    $this->request->data = $post;
		}

		$departmentmasters = $this->Departmentmaster->DepartmentList($this->Auth->user('kaisyas_id'));
		$this->set(compact('departmentmasters'));
	}

	public function delete($id = null) {
	    $this->Workdetailmaster->id = $id;

		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}else{
		    $this->request->data['Workdetailmaster']['delflg'] = 1;
		    if($this->Workdetailmaster->save($this->request->data,false)){
		        $this->Flash->success(__('削除しました'));
		    }else{
		        $this->Flash->error(__('削除できません'));
		    }
		}
		return $this->redirect(['action' => 'index']);

	}

}
