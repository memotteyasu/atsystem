<?php
App::uses('AppController', 'Controller');
/**
 * HostProductcontainer Controller
 *
 */
class HostProductcontainersController extends AppController {
    public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Productcontainer','Productdetailmaster');
    public $components = array('Search.Prg');
    public $presetVars = array();

    public function beforeFilter(){
        parent::beforeFilter();
       // $this->set('auth',$this->Auth);
        // 検索対象のフィールド設定代入
        $this->presetVars = $this->Productcontainer->presetVars;
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
	    $pid = $this->params->named['pid'];
	    $this->Productcontainer->recursive = 0;
	    // 検索条件設定
	    $this->Prg->commonProcess();
	    // 検索条件取得
		$conditions = $this->Productcontainer->parseCriteria($this->passedArgs);

	    $this->paginate = [
	    	'fields' => [
	    		'id',
	    		'pdconcode',
	    		'pdconname'
	    	],
	        'conditions' => [
	            'AND' => $conditions,
	            [
	                'Productcontainer.delflg' => 0,
	                'Productcontainer.productdetailmasters_id' => $pid
	            ]
	        ],
	        'limit' => 10,
	        'order' => [
	            'Productcontainer.pdconname' => 'asc',
	            'Productcontainer.id' => 'asc'
	        ],
	    	'recursive' => 0,
	    ];
	    $this->set('productcontainers', $this->paginate('Productcontainer'));
//        $did=4; // コンテナ専用
	    $productdetailmasters = $this->Productdetailmaster->SelectList($this->did);
	    $this->set(compact('productdetailmasters','pid'));

	}
/**
 * add method
 *
 * @return void
 */
	public function add() {
	    $pid = $this->params->named['pid'];
	    if ($this->request->is(array('post', 'put'))) {
	        if ($this->Productcontainer->save($this->request->data)) {
	            $this->Flash->success(__('保存しました'));
                return $this->redirect(['action' => 'index','pid'=>$this->params->named['pid']]);
	        } else {
	            $this->Flash->error(__('保存できません'));
	        }
	    } else {
/*
	        if($pid){
	            $options = array('conditions' => array('Productcontainer.' . $this->Productcontainer->primaryKey => $pid));
	            $this->request->data = $this->Productcontainer->find('first', $options);
	        }
*/
//	        $productdetailmasters = $this->Productdetailmaster->SelectList();
            $productdetailmasters =$this->Productdetailmaster->findById($pid);
	        $this->set(compact('productdetailmasters','pid'));
	    }
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null,$pid = null) {
	    $pid = $this->params->named['pid'];
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Productcontainer->save($this->request->data)) {
				$this->Flash->success(__('保存しました'));
				if (isset($this->request->named['page'])) {
					return $this->redirect(['controller' => $this->request->named['page'], 'action' => 'index']);
				} else {
					return $this->redirect(['action' => 'index','pid'=>$pid]);
				}
			} else {
				$this->Flash->error(__('保存できません'));
			}
		} else {
		    if($id){
    			//$options = array('conditions' => array('Productcontainer.' . $this->Productcontainer->primaryKey => $id));
    			$this->request->data = $this->Productcontainer->findById($id);
		    }
		    $productdetailmasters = $this->Productdetailmaster->SelectList();
		    $this->set(compact('productdetailmasters','pid'));
		}
	}

	public function delete($id = null) {
        $pid = $this->params->named['pid'];
	    $this->Productcontainer->id = $id;
	    if ($this->request->is('get')){
	        throw new MethodNotAllowedException();
	    }else{
	        $this->request->data['Productcontainer']['delflg'] = 1;
	        $this->Productcontainer->id = $id;
	        if ($this->Productcontainer->save($this->request->data,false,array('delflg'))){
	            $this->Flash->success(__('削除しました'));
	        }else{
	            $this->Flash->error(__('失敗しました'));
	        }
	    }
	    return $this->redirect(array('action' => 'index','pid'=>$pid));
	}

}
