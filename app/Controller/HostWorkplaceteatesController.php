<?php
App::uses('AppController', 'Controller');
/**
 * HostWorkplaceteate Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class HostWorkplaceteatesController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('Workplace','Workplaceteate','Branchmaster');

    public function beforeFilter(){
    	parent::beforeFilter();
    	//$this->set('auth',$this->Auth);

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
	public function index($id = null) {
	    if($id){
	        $this->Session->write('BackData1.wpid',$id);
	    }
        $workplaces = $this->Workplace->findById($id);
	    $workplaceteates = $this->Workplaceteate->find('all',[
            'conditions'=>[
                'workplaces_id'=>$id,
            ]
	    ]);

	    $this->set(compact('workplaces','workplaceteates'));
	}

	public function add() {
	    $this->edit();
	    $this->render('edit');
	}

	public function edit($id = null) {
	    if ($this->request->is(array('post', 'put'))) {
	        $this->request->data['Workplaceteate']['workplaces_id'] = $this->Session->read('BackData1.wpid');
	        if ($this->Workplaceteate->save($this->request->data)) {
	            $this->Flash->success('手当を保存しました');
	            return $this->redirect(['action' => 'index',$this->Session->read('BackData1.wpid')]);
	        } else {
	            $this->Flash->error('編集に失敗しました。');
	        }
	    }else{
	        if (!$this->request->data) {
	            $post = $this->Workplaceteate->findById($id);
	            $this->request->data = $post;
	        }
	    }
	    if($this->Session->read('BackData1.wpid')){
	        $wpid = $this->Session->read('BackData1.wpid');
	    }

	    $workplaces = $this->Workplace->find('first',[
	           'fields'=>[
	               'Workplace.id',
	               'Workplace.workplacename'
	           ],
	           'conditions'=>[
	               'Workplace.id'=>$wpid
	           ]
	        ]);
	    $this->set('workplaces',$workplaces);

	    $branchmasters = $this->Branchmaster->SelectBranchList($this->kaisyas_id);
	    $this->set('branchmasters',$branchmasters);


	}

	public function delete($id = null) {
		if ($this->request->is('get')){
			throw new MethodNotAllowedException();
		}else{
			if ($this->Workplaceteate->delete($id)){
				$this->Flash->success(__('削除しました'));
			}else{
				$this->Flash->error(__('失敗しました'));
			}
		}

		return $this->redirect(array('action' => 'index',$this->Session->read('BackData1.wpid')));
	}


}
